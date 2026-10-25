<?php
/**
 * Template Name: VaraStudy Vault Course Page
 * Description: Reusable landing page for VaraStudy Vault course groups.
 */

get_header();

$course_slug = get_post_field('post_name', get_queried_object_id());

$course_group = get_term_by(
    'slug',
    $course_slug,
    'course_group'
);

$course_name = (
    $course_group && !is_wp_error($course_group)
) ? $course_group->name : get_the_title();

$subjects_archive = get_post_type_archive_link('subjects');
$materials_archive = get_post_type_archive_link('study-materials');
$papers_archive = get_post_type_archive_link('question-papers');
?>

<main id="main-content" class="cv-course-page">

    <!-- HERO -->
    <section class="cv-hero">
        <div class="cv-container cv-hero-grid">

            <div class="cv-hero-copy">
                <p class="cv-eyebrow">
                    <?php echo esc_html($course_name); ?>
                </p>

                <h1>
                    Your College<br>
                    <span>Study Resources</span><br>
                    in One Place
                </h1>

                <p class="cv-hero-description">
                    Access unit-wise notes, lessons, lab records,
                    practicals, skill-paper resources and question papers
                    — all organized for your course and semester.
                </p>

                <a class="cv-button" href="#course-semesters">
                    <span>Find Your Semester</span>
                    <span aria-hidden="true">→</span>
                </a>
            </div>

           <div class="cv-hero-visual" aria-hidden="true">
    <?php
    $course_image_id = get_post_thumbnail_id(get_queried_object_id());

    if ($course_image_id) :
        $course_image_url = wp_get_attachment_image_url(
            $course_image_id,
            'large'
        );
    ?>

        <div class="cv-course-art">
            <img
                src="<?php echo esc_url($course_image_url); ?>"
                alt=""
                loading="eager"
                fetchpriority="high"
            >
        </div>

    <?php else : ?>

        <div class="cv-course-art cv-course-art--fallback">
            <div class="cv-hero-note">
                <span>Learn.</span>
                <span>Prepare.</span>
                <span>Grow.</span>
                <i></i>
            </div>
        </div>

    <?php endif; ?>
</div>
    </section>

    <!-- SEMESTERS -->
    <section class="cv-section cv-semesters" id="course-semesters">
        <div class="cv-container">

            <p class="cv-eyebrow">Browse by semester</p>

            <h2>Select Your Semester</h2>

            <p class="cv-intro">
                Choose your semester to access subjects, study materials
                and question papers.
            </p>

            <div class="cv-grid cv-semester-grid">

                <?php for ($semester = 1; $semester <= 6; $semester++) : ?>

                    <?php
                    $subject_args = array(
                        'post_type'      => 'subjects',
                        'post_status'    => 'publish',
                        'posts_per_page' => -1,
                        'fields'         => 'ids',
                        'meta_query'     => array(
                            array(
                                'key'     => 'semester',
                                'value'   => (string) $semester,
                                'compare' => '=',
                            ),
                        ),
                    );

                    if ($course_group && !is_wp_error($course_group)) {
                        $subject_args['tax_query'] = array(
                            array(
                                'taxonomy' => 'course_group',
                                'field'    => 'term_id',
                                'terms'    => (int) $course_group->term_id,
                            ),
                        );
                    } else {
                        $subject_args['post__in'] = array(0);
                    }

                    $subject_ids = get_posts($subject_args);
                    $subject_count = count($subject_ids);

                    $semester_url = $subjects_archive
                        ? add_query_arg(
                            array(
                                'course_group' => $course_slug,
                                'semester'     => $semester,
                            ),
                            $subjects_archive
                        )
                        : home_url('/');
                    ?>

                    <a
                        class="cv-semester-card"
                        href="<?php echo esc_url($semester_url); ?>"
                    >
                        <span class="cv-semester-icon" aria-hidden="true">
                            <svg viewBox="0 0 48 48">
                                <path
                                    d="M5 10 Q15 5 23 12 V40 Q14 33 5 36 Z"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="3"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M43 10 Q33 5 25 12 V40 Q34 33 43 36 Z"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="3"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>

                        <span class="cv-semester-label">
                            <strong>
                                Semester <?php echo esc_html($semester); ?>
                            </strong>

                            <small>
                                <?php
                                echo esc_html($subject_count);
                                echo $subject_count === 1
                                    ? ' subject'
                                    : ' subjects';
                                ?>
                            </small>
                        </span>

                        <span class="cv-semester-arrow" aria-hidden="true">
                            →
                        </span>
                    </a>

                <?php endfor; ?>

            </div>
        </div>
    </section>

    <!-- RESOURCE CATEGORIES -->
    <section class="cv-section cv-resources">
        <div class="cv-container">

            <p class="cv-eyebrow">What you can find</p>

            <h2>Everything You Need to Study Better</h2>

            <div class="cv-grid cv-two cv-resource-grid">

                <!-- STUDY MATERIALS -->
                <article class="cv-feature-card">

                    <div class="cv-feature-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" fill="none">
                            <path
                                d="M5 10 Q15 5 23 12 V40 Q14 33 5 36 Z"
                                stroke="currentColor"
                                stroke-width="3"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M43 10 Q33 5 25 12 V40 Q34 33 43 36 Z"
                                stroke="currentColor"
                                stroke-width="3"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </div>

                    <div class="cv-feature-content">
                        <h3>Study Materials</h3>

                        <p>
                            Unit-wise notes, lessons, lab records,
                            practicals and skill-paper resources.
                        </p>

                        <ul class="cv-feature-list">
                            <li>Theory Notes &amp; Lessons</li>
                            <li>Lab Records &amp; Manuals</li>
                            <li>Practical Exercises</li>
                            <li>Viva Questions</li>
                            <li>Skill Paper Resources</li>
                        </ul>

                        <?php if ($materials_archive) : ?>
                            <a
                                class="cv-text-link"
                                href="<?php echo esc_url(
                                    add_query_arg(
                                        'course_group',
                                        $course_slug,
                                        $materials_archive
                                    )
                                ); ?>"
                            >
                                Browse Study Materials
                                <span aria-hidden="true">→</span>
                            </a>
                        <?php endif; ?>
                    </div>

                </article>

                <!-- QUESTION PAPERS -->
                <article class="cv-feature-card">

                    <div class="cv-feature-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" fill="none">
                            <path
                                d="M13 5 H29 L38 14 V42 H13 Z"
                                stroke="currentColor"
                                stroke-width="3"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M29 5 V14 H38 M19 22 H32 M19 28 H32 M19 34 H29"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </div>

                    <div class="cv-feature-content">
                        <h3>Question Papers</h3>

                        <p>
                            Mid-semester, semester-end, previous and
                            model papers organized by subject.
                        </p>

                        <ul class="cv-feature-list">
                            <li>Mid-Semester Papers</li>
                            <li>Semester-End Papers</li>
                            <li>Previous Year Papers</li>
                            <li>Model Papers</li>
                            <li>Subject-wise Organization</li>
                        </ul>

                        <?php if ($papers_archive) : ?>
                            <a
                                class="cv-text-link"
                                href="<?php echo esc_url(
                                    add_query_arg(
                                        'course_group',
                                        $course_slug,
                                        $papers_archive
                                    )
                                ); ?>"
                            >
                                Browse Question Papers
                                <span aria-hidden="true">→</span>
                            </a>
                        <?php endif; ?>
                    </div>

                </article>

            </div>
        </div>
    </section>

    <!-- SUCCESS BANNER -->
    <section class="cv-success-banner">
        <div class="cv-container cv-success-inner">

            <div class="cv-success-heading">
                <span class="cv-success-icon" aria-hidden="true">
                    <svg viewBox="0 0 64 64" fill="none">
                        <path
                            d="M7 24 32 12 57 24 32 36 Z"
                            fill="currentColor"
                        />
                        <path
                            d="M17 30 V43 Q32 55 47 43 V30 L32 38 Z"
                            fill="currentColor"
                        />
                        <path
                            d="M55 25 V42"
                            stroke="currentColor"
                            stroke-width="3"
                            stroke-linecap="round"
                        />
                        <circle cx="55" cy="47" r="3" fill="currentColor"/>
                    </svg>
                </span>

                <h2>
                    Your Success<br>
                    <span>Our Priority</span>
                </h2>
            </div>

            <p>
                Stay organized. Study smart.<br>
                Make the most of your semester.
            </p>

            <div class="cv-success-note" aria-hidden="true">
                <span>Good</span>
                <span>luck!</span>
                <i></i>
            </div>

        </div>
    </section>

</main>

<?php get_footer(); ?>
