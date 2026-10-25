
<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$selected_group = isset($_GET['course_group'])
    ? sanitize_title(wp_unslash($_GET['course_group']))
    : '';

$selected_semester = isset($_GET['semester'])
    ? sanitize_text_field(wp_unslash($_GET['semester']))
    : '';

if (
    $selected_semester !== '' &&
    !in_array($selected_semester, array('1', '2', '3', '4', '5', '6'), true)
) {
    $selected_semester = '';
}

$course_groups = get_terms(array(
    'taxonomy'   => 'course_group',
    'hide_empty' => false,
));

$subjects_url = get_post_type_archive_link('subjects');

$selected_group_name = '';

if ($selected_group !== '') {
    $selected_term = get_term_by('slug', $selected_group, 'course_group');

    if ($selected_term && !is_wp_error($selected_term)) {
        $selected_group_name = $selected_term->name;
    }
}
?>

<style>
.cv-subject-filters {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    align-items: end;
    margin: 28px 0 32px;
    padding: 22px;
    background: #f6f8f2;
    border: 1px solid #e1e8dc;
    border-radius: 16px;
}

.cv-subject-filters .cv-filter-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}

.cv-subject-filters label {
    color: #244b32;
    font-size: 14px;
    font-weight: 600;
}

.cv-subject-filters select {
    width: 100%;
    min-height: 46px;
    padding: 10px 12px;
    color: #24352a;
    background: #fff;
    border: 1px solid #ccd8ca;
    border-radius: 9px;
    font: inherit;
}

.cv-subject-filters select:focus {
    outline: 2px solid #80b894;
    outline-offset: 2px;
    border-color: #287344;
}

.cv-subject-filters .cv-filter-actions {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 2px;
}

.cv-subject-filters .cv-filter-button,
.cv-subject-filters .cv-filter-reset {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    min-height: 44px;
    padding: 10px 20px;
    border-radius: 9px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}

.cv-subject-filters .cv-filter-button {
    color: #fff;
    background: #245c38;
    border: 1px solid #245c38;
}

.cv-subject-filters .cv-filter-button:hover {
    background: #19472a;
}

.cv-subject-filters .cv-filter-reset {
    color: #245c38;
    background: transparent;
    border: 1px solid #b8cbb9;
}

.cv-subject-filters .cv-filter-reset:hover {
    background: #eaf1e7;
}

@media (max-width: 600px) {
    .cv-subject-filters {
        grid-template-columns: 1fr;
        gap: 14px;
        padding: 16px;
    }

    .cv-subject-filters .cv-filter-actions {
        grid-column: 1;
        flex-wrap: wrap;
    }

    .cv-subject-filters .cv-filter-button,
    .cv-subject-filters .cv-filter-reset {
        flex: 1;
    }
}
</style>

<main id="main-content" class="cv-page">
    <div class="cv-container">

        <?php get_template_part('template-parts/breadcrumbs'); ?>

        <p class="cv-eyebrow">Browse your course</p>

        <h1 class="cv-title">
            <?php
            if ($selected_group_name !== '' && $selected_semester !== '') {
                echo esc_html(
                    $selected_group_name . ' — Semester ' . $selected_semester . ' Subjects'
                );
            } elseif ($selected_group_name !== '') {
                echo esc_html($selected_group_name . ' Subjects');
            } elseif ($selected_semester !== '') {
                echo esc_html('Semester ' . $selected_semester . ' Subjects');
            } else {
                echo 'All Subjects';
            }
            ?>
        </h1>

        <p class="cv-intro">
            Find your subjects by course group and semester, then access their
            study materials and question papers.
        </p>

        <form
            class="cv-subject-filters"
            method="get"
            action="<?php echo esc_url($subjects_url); ?>"
        >
            <div class="cv-filter-field">
                <label for="cv-course-group">Course Group</label>

                <select id="cv-course-group" name="course_group">
                    <option value="">All course groups</option>

                    <?php
                    if (!is_wp_error($course_groups) && $course_groups) :
                        foreach ($course_groups as $group) :
                    ?>
                        <option
                            value="<?php echo esc_attr($group->slug); ?>"
                            <?php selected($selected_group, $group->slug); ?>
                        >
                            <?php echo esc_html($group->name); ?>
                        </option>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>

            <div class="cv-filter-field">
                <label for="cv-semester">Semester</label>

                <select id="cv-semester" name="semester">
                    <option value="">All semesters</option>

                    <?php for ($i = 1; $i <= 6; $i++) : ?>
                        <option
                            value="<?php echo esc_attr((string) $i); ?>"
                            <?php selected($selected_semester, (string) $i); ?>
                        >
                            Semester <?php echo esc_html((string) $i); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="cv-filter-actions">
                <button class="cv-filter-button" type="submit">
                    Apply Filters
                </button>

                <a
                    class="cv-filter-reset"
                    href="<?php echo esc_url($subjects_url); ?>"
                >
                    Clear Filters
                </a>
            </div>
        </form>

        <?php if (have_posts()) : ?>

            <div class="cv-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/subject-card');
                endwhile;
                ?>
            </div>

            <div class="cv-pagination">
                <?php the_posts_pagination(); ?>
            </div>

        <?php else : ?>

            <div class="cv-empty">
                No subjects match these filters.
                Try selecting a different course group or semester.
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
