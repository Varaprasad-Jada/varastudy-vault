
<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup.
 */
function varastudy_vault_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 260,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    register_nav_menus(array(
        'primary' => __('Primary Navigation', 'varastudy-vault'),
        'footer'  => __('Footer Navigation', 'varastudy-vault'),
    ));
}
add_action('after_setup_theme', 'varastudy_vault_setup');


/**
 * Load theme styles and navigation script.
 */
function varastudy_vault_assets() {
    wp_enqueue_style(
        'varastudy-vault-style',
        get_stylesheet_uri(),
        array(),
        '1.0.0'
    );

    wp_enqueue_script(
        'varastudy-vault-navigation',
        get_template_directory_uri() . '/assets/js/navigation.js',
        array(),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'varastudy_vault_assets');


/**
 * Fallback navigation.
 */
function varastudy_vault_menu_fallback() {
    echo '<ul>';
    echo '<li><a href="' . esc_url(home_url('/')) . '">Home</a></li>';

    $types = array(
        'subjects'        => 'Subjects',
        'study-materials' => 'Study Materials',
        'question-papers' => 'Question Papers',
    );

    foreach ($types as $type => $label) {
        $url = get_post_type_archive_link($type);

        if ($url) {
            echo '<li><a href="' . esc_url($url) . '">'
                . esc_html($label)
                . '</a></li>';
        }
    }

    echo '</ul>';
}


/**
 * Get the Subject ID linked to a resource through ACF.
 */
function varastudy_vault_subject_id($id = 0) {
    if (!function_exists('get_field')) {
        return 0;
    }

    $id = $id ? (int) $id : get_the_ID();
    $value = get_field('subject', $id);

    if (is_object($value) && isset($value->ID)) {
        return (int) $value->ID;
    }

    if (is_numeric($value)) {
        return (int) $value;
    }

    return 0;
}


/**
 * Build breadcrumb items.
 */
function varastudy_vault_breadcrumb_items() {
    $items = array(
        array(
            'label' => 'Home',
            'url'   => home_url('/'),
        ),
    );

    if (is_post_type_archive('subjects') || is_singular('subjects')) {
        $items[] = array(
            'label' => 'Subjects',
            'url'   => get_post_type_archive_link('subjects'),
        );

        if (is_singular('subjects')) {
            $semester = function_exists('get_field')
                ? get_field('semester')
                : '';

            if (is_scalar($semester) && (string) $semester !== '') {
                $semester = (string) $semester;

                $items[] = array(
                    'label' => 'Semester ' . $semester,
                    'url'   => add_query_arg(
                        'semester',
                        $semester,
                        get_post_type_archive_link('subjects')
                    ),
                );
            }

            $items[] = array(
                'label' => get_the_title(),
                'url'   => '',
            );
        }
    } elseif (is_singular(array('study-materials', 'question-papers'))) {
        $subject_id = varastudy_vault_subject_id();

        if ($subject_id) {
            $items[] = array(
                'label' => 'Subjects',
                'url'   => get_post_type_archive_link('subjects'),
            );

            $semester = function_exists('get_field')
                ? get_field('semester', $subject_id)
                : '';

            if (is_scalar($semester) && (string) $semester !== '') {
                $semester = (string) $semester;

                $items[] = array(
                    'label' => 'Semester ' . $semester,
                    'url'   => add_query_arg(
                        'semester',
                        $semester,
                        get_post_type_archive_link('subjects')
                    ),
                );
            }

            $items[] = array(
                'label' => get_the_title($subject_id),
                'url'   => get_permalink($subject_id),
            );
        }

        $type = get_post_type();
        $archive_url = get_post_type_archive_link($type);

        $items[] = array(
            'label' => $type === 'study-materials'
                ? 'Study Materials'
                : 'Question Papers',
            'url'   => $subject_id
                ? add_query_arg('subject', $subject_id, $archive_url)
                : $archive_url,
        );

        $items[] = array(
            'label' => get_the_title(),
            'url'   => '',
        );
    } elseif (
        is_post_type_archive('study-materials') ||
        is_post_type_archive('question-papers')
    ) {
        $items[] = array(
            'label' => post_type_archive_title('', false),
            'url'   => '',
        );
    } elseif (is_page() || is_single()) {
        $items[] = array(
            'label' => get_the_title(),
            'url'   => '',
        );
    } elseif (is_search()) {
        $items[] = array(
            'label' => 'Search results',
            'url'   => '',
        );
    }

    return $items;
}


/**
 * Get published Subject IDs matching Course Group and Semester.
 */
function varastudy_vault_get_filtered_subject_ids($group_slug = '', $semester = '') {
    $args = array(
        'post_type'      => 'subjects',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    );

    if ($group_slug !== '') {
        $term = get_term_by('slug', $group_slug, 'course_group');

        if (!$term || is_wp_error($term)) {
            return array();
        }

        $args['tax_query'] = array(
            array(
                'taxonomy' => 'course_group',
                'field'    => 'term_id',
                'terms'    => (int) $term->term_id,
            ),
        );
    }

    if ($semester !== '') {
        $args['meta_query'] = array(
            array(
                'key'     => 'semester',
                'value'   => (string) $semester,
                'compare' => '=',
            ),
        );
    }

    return array_map('intval', get_posts($args));
}


/**
 * Filter the Subjects archive by Course Group and Semester.
 */
function varastudy_vault_subject_archive_filter($query) {
    if (
        is_admin() ||
        !$query->is_main_query() ||
        !$query->is_post_type_archive('subjects')
    ) {
        return;
    }

    $group_slug = isset($_GET['course_group'])
        ? sanitize_title(wp_unslash($_GET['course_group']))
        : '';

    $semester = isset($_GET['semester'])
        ? sanitize_text_field(wp_unslash($_GET['semester']))
        : '';

    if (
        $semester !== '' &&
        !in_array($semester, array('1', '2', '3', '4', '5', '6'), true)
    ) {
        $query->set('post__in', array(0));
        return;
    }

    if ($group_slug !== '') {
        $term = get_term_by('slug', $group_slug, 'course_group');

        if (!$term || is_wp_error($term)) {
            $query->set('post__in', array(0));
            return;
        }

        $query->set('tax_query', array(
            array(
                'taxonomy' => 'course_group',
                'field'    => 'term_id',
                'terms'    => (int) $term->term_id,
            ),
        ));
    }

    if ($semester !== '') {
        $query->set('meta_query', array(
            array(
                'key'     => 'semester',
                'value'   => $semester,
                'compare' => '=',
            ),
        ));
    }
}
add_action('pre_get_posts', 'varastudy_vault_subject_archive_filter');


/**
 * Collect resource metadata for resource cards.
 *
 * Question Papers use their own Semester field.
 * Academic Year is retained for both resource types
 * where the field is configured.
 */
function varastudy_vault_resource_meta($id = 0) {
    if (!function_exists('get_field')) {
        return array();
    }

    $id = $id ? (int) $id : get_the_ID();
    $output = array();

    $fields = array(
        'resource_category',
        'unit_lesson',
        'theory_resource_type',
        'lab_resource_type',
        'skill_resource_type',
        'exam_type',
        'semester',
        'academic_year',
    );

    foreach ($fields as $key) {
        $value = get_field($key, $id);

        if (is_array($value)) {
            $value = $value['label'] ?? $value['value'] ?? '';
        }

        if (is_scalar($value) && (string) $value !== '') {
            $output[$key] = (string) $value;
        }
    }

    return $output;
}


/**
 * Get Course Group term IDs assigned to a Subject.
 */
function varastudy_vault_subject_course_group_ids($subject_id) {
    $terms = get_the_terms(
        (int) $subject_id,
        'course_group'
    );

    if (is_wp_error($terms) || empty($terms)) {
        return array();
    }

    return array_map(
        'intval',
        wp_list_pluck($terms, 'term_id')
    );
}


/**
 * Filter Study Materials and Question Papers.
 *
 * Supported parameters:
 * course_group, semester, resource_category,
 * subject, exam_type, academic_year.
 *
 * Course Group is queried directly on resource posts.
 *
 * Study Materials:
 * Semester is matched through the linked Subject.
 *
 * Question Papers:
 * Semester and Academic Year are matched against
 * the Question Paper's own ACF fields.
 */
function varastudy_vault_resource_course_group_filter($query) {
    if (
        is_admin() ||
        !$query->is_main_query()
    ) {
        return;
    }

    $is_study_materials = $query->is_post_type_archive('study-materials');
    $is_question_papers = $query->is_post_type_archive('question-papers');

    if (!$is_study_materials && !$is_question_papers) {
        return;
    }

    $group_slug = isset($_GET['course_group'])
        ? sanitize_title(wp_unslash($_GET['course_group']))
        : '';

    $semester = isset($_GET['semester'])
        ? sanitize_text_field(wp_unslash($_GET['semester']))
        : '';

    $category = isset($_GET['resource_category'])
        ? sanitize_key(wp_unslash($_GET['resource_category']))
        : '';

    $subject_id = isset($_GET['subject'])
        ? absint($_GET['subject'])
        : 0;

    $exam_type = isset($_GET['exam_type'])
        ? sanitize_key(wp_unslash($_GET['exam_type']))
        : '';

    $academic_year = isset($_GET['academic_year'])
        ? sanitize_text_field(wp_unslash($_GET['academic_year']))
        : '';

    /*
     * Validate Semester.
     */
    if (
        $semester !== '' &&
        !in_array($semester, array('1', '2', '3', '4', '5', '6'), true)
    ) {
        $query->set('post__in', array(0));
        return;
    }

    /*
     * Validate and apply Course Group taxonomy directly
     * to the resource post type.
     */
    if ($group_slug !== '') {
        $term = get_term_by('slug', $group_slug, 'course_group');

        if (!$term || is_wp_error($term)) {
            $query->set('post__in', array(0));
            return;
        }

        $query->set('tax_query', array(
            array(
                'taxonomy' => 'course_group',
                'field'    => 'term_id',
                'terms'    => (int) $term->term_id,
            ),
        ));
    }

    /*
     * Filter by Semester.
     *
     * Question Papers use their own Semester field.
     * Study Materials use the linked Subject's Semester.
     */
    if ($semester !== '') {
        $meta_query = (array) $query->get('meta_query');

        if ($is_question_papers) {
            $meta_query[] = array(
                'key'     => 'semester',
                'value'   => $semester,
                'compare' => '=',
            );
        } else {
            $subject_ids = varastudy_vault_get_filtered_subject_ids(
                $group_slug,
                $semester
            );

            if (empty($subject_ids)) {
                $query->set('post__in', array(0));
                return;
            }

            $meta_query[] = array(
                'key'     => 'subject',
                'value'   => array_map('strval', $subject_ids),
                'compare' => 'IN',
            );
        }

        $query->set('meta_query', $meta_query);
    }

    /*
     * Filter by a specific Subject.
     */
    if ($subject_id > 0) {
        $subject = get_post($subject_id);

        if (
            !$subject ||
            $subject->post_type !== 'subjects' ||
            $subject->post_status !== 'publish'
        ) {
            $query->set('post__in', array(0));
            return;
        }

        /*
         * Confirm the selected Subject belongs to the
         * selected Course Group, if one was provided.
         */
        if ($group_slug !== '') {
            $subject_groups = wp_get_object_terms(
                $subject_id,
                'course_group',
                array('fields' => 'slugs')
            );

            if (
                is_wp_error($subject_groups) ||
                !in_array($group_slug, $subject_groups, true)
            ) {
                $query->set('post__in', array(0));
                return;
            }
        }

        /*
         * Confirm the selected Subject's Semester matches
         * the selected Semester.
         */
        if ($semester !== '') {
            $subject_semester = function_exists('get_field')
                ? (string) get_field('semester', $subject_id)
                : '';

            if ($subject_semester !== $semester) {
                $query->set('post__in', array(0));
                return;
            }
        }

        $meta_query = (array) $query->get('meta_query');

        $meta_query[] = array(
            'key'     => 'subject',
            'value'   => (string) $subject_id,
            'compare' => '=',
        );

        $query->set('meta_query', $meta_query);
    }

    /*
     * Study Materials: Theory, Lab or Skill.
     */
    if ($is_study_materials && $category !== '') {
        if (!in_array($category, array('theory', 'lab', 'skill'), true)) {
            $query->set('post__in', array(0));
            return;
        }

        $meta_query = (array) $query->get('meta_query');

        $meta_query[] = array(
            'key'     => 'resource_category',
            'value'   => $category,
            'compare' => '=',
        );

        $query->set('meta_query', $meta_query);
    }

    /*
     * Question Papers: Exam Type.
     */
    if ($is_question_papers && $exam_type !== '') {
        if (!in_array($exam_type, array('mid_semester', 'semester_end'), true)) {
            $query->set('post__in', array(0));
            return;
        }

        $meta_query = (array) $query->get('meta_query');

        $meta_query[] = array(
            'key'     => 'exam_type',
            'value'   => $exam_type,
            'compare' => '=',
        );

        $query->set('meta_query', $meta_query);
    }

    /*
     * Question Papers: Academic Year.
     *
     * Match the selected year against the ACF field
     * stored on each Question Paper.
     */
    if ($is_question_papers && $academic_year !== '') {
        /*
         * Avoid accepting unexpected query values.
         * Supports years such as 2026-27 and 2026–27.
         */
        if (
            !preg_match(
                '/^\d{4}\s*[-–—]\s*\d{2,4}$/u',
                $academic_year
            )
        ) {
            $query->set('post__in', array(0));
            return;
        }

        $meta_query = (array) $query->get('meta_query');

        $meta_query[] = array(
            'key'     => 'academic_year',
            'value'   => $academic_year,
            'compare' => '=',
        );

        $query->set('meta_query', $meta_query);
    }
}
add_action('pre_get_posts', 'varastudy_vault_resource_course_group_filter');
