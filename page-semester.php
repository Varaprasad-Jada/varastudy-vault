
<?php
/**
 * Template Name: VaraStudy Vault Semester
 * Description: Displays subjects for a selected course group and semester.
 */

get_header();

/*
 * Get Course Group and Semester from the URL.
 *
 * Example:
 * /bcom-computer-applications/semester1/
 */
$course_slug = get_query_var('pagename');
$semester    = get_query_var('varastudyvault_semester');

$valid_semesters = array('1', '2', '3', '4', '5', '6');

$course = get_term_by(
    'slug',
    $course_slug,
    'course_group'
);

$valid_course = $course && !is_wp_error($course);

$valid_semester = in_array(
    (string) $semester,
    $valid_semesters,
    true
);

$subjects = array();

if ($valid_course && $valid_semester) {
    $subjects = varastudyvault_get_filtered_subject_ids(
        $course_slug,
        (string) $semester
    );
}
?>

<main id="main-content" class="cv-page">

    <div class="cv-container">

        <?php get_template_part('template-parts/breadcrumbs'); ?>

        <?php if (!$valid_course || !$valid_semester) : ?>

            <header class="cv-section-header">
                <h1 class="cv-title">Semester Not Found</h1>

                <p>
                    The requested course or semester could not be found.
                </p>
            </header>

        <?php else : ?>

            <!-- SEMESTER HEADER -->
            <header class="cv-section-header">

                <p class="cv-eyebrow">
                    <?php echo esc_html($course->name); ?>
                </p>

                <h1 class="cv-title">
                    Semester <?php echo esc_html($semester); ?>
                </h1>

                <p class="cv-section-description">
                    Browse the subjects available for this semester.
                </p>

            </header>

            <!-- SUBJECT LIST -->
            <?php if (!empty($subjects)) : ?>

                <div class="cv-grid cv-subject-grid">

                    <?php foreach ($subjects as $subject_id) : ?>

                        <?php
                        $post = get_post($subject_id);

                        if (!$post) {
                            continue;
                        }

                        setup_postdata($post);

                        get_template_part(
                            'template-parts/subject-card'
                        );
                        ?>

                    <?php endforeach; ?>

                    <?php wp_reset_postdata(); ?>

                </div>

            <?php else : ?>

                <div class="cv-empty-state">
                    <p>
                        No subjects have been added for this semester yet.
                    </p>
                </div>

            <?php endif; ?>

        <?php endif; ?>

    </div>

</main>

<?php get_footer(); ?>
