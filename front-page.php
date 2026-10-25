
<?php get_header(); ?>

<main id="main-content" class="cv-homepage">

    <!-- HERO -->
    <section class="cv-hero">
        <div class="cv-container cv-hero-grid">

            <div class="cv-hero-copy">
                <p class="cv-eyebrow">B.Com Computer Applications</p>

                <h1>
                    Your College<br>
                    <span>Study Resources</span><br>
                    in One Place
                </h1>

                <p class="cv-hero-description">
                    Access unit-wise notes, lessons, lab records, practicals,
                    skill-paper resources and question papers — all organized
                    for your semester.
                </p>

                <a class="cv-button" href="#course-groups">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="10.8" cy="10.8" r="6.5"></circle>
                        <path d="m16 16 4.5 4.5"></path>
                    </svg>
                    Find Your Semester
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="cv-hero-visual" aria-hidden="true">
                <div class="cv-hero-note">
                    <span>Learn.</span>
                    <span>Prepare.</span>
                    <span>Grow.</span>
                    <i></i>
                </div>

                <div class="cv-book-stack">
                    <div class="cv-hero-book cv-hero-book--one">
                        <span>COMPUTER APPLICATIONS</span>
                    </div>
                    <div class="cv-hero-book cv-hero-book--two">
                        <span>PROGRAMMING</span>
                    </div>
                    <div class="cv-hero-book cv-hero-book--three">
                        <span>DATABASES</span>
                    </div>
                    <div class="cv-hero-book cv-hero-book--four">
                        <span>WEB TECHNOLOGIES</span>
                    </div>
                </div>

                <div class="cv-study-desk"><span></span></div>
            </div>

        </div>
    </section>

    <!-- COURSE / SEMESTER CARDS -->
    <section class="cv-section cv-home-courses" id="course-groups">
        <div class="cv-container">

            <p class="cv-eyebrow">Browse by semester</p>
            <h2>Select Your Semester</h2>

            <p class="cv-intro">
                Choose your semester to access subjects, study materials
                and question papers.
            </p>

            <?php
            $course_groups = get_terms(array(
                'taxonomy'   => 'course_group',
                'hide_empty' => false,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ));
            ?>

            <?php if (!is_wp_error($course_groups) && !empty($course_groups)) : ?>

                <div class="cv-grid cv-semester-grid">

                    <?php foreach ($course_groups as $course_group) : ?>

                        <?php
                        $course_url = home_url(
                            '/' . $course_group->slug . '/'
                        );

                        if ($course_group->slug === 'bcom-computer-applications') {
                            $course_url = home_url(
                                '/bcom-computer-applications/'
                            );
                        }
                        ?>

                        <a class="cv-semester-card"
                           href="<?php echo esc_url($course_url); ?>">

                            <span class="cv-semester-icon" aria-hidden="true">
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
                                    <path
                                        d="M9 16 Q15 14 19 17 M9 22 Q15 20 19 23
                                           M39 16 Q33 14 29 17 M39 22 Q33 20 29 23"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                    />
                                </svg>
                            </span>

                            <span class="cv-semester-label">
                                <strong>
                                    <?php echo esc_html($course_group->name); ?>
                                </strong>
                                <small>Browse subjects</small>
                            </span>

                            <span class="cv-semester-arrow" aria-hidden="true">→</span>

                        </a>

                    <?php endforeach; ?>

                </div>

            <?php else : ?>

                <p class="cv-empty">
                    Course groups are not available yet.
                </p>

            <?php endif; ?>

        </div>
    </section>

    <!-- RESOURCE FEATURES -->
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
                                d="M5 12 Q14 7 23 13 V38 Q14 32 5 35 Z"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M43 12 Q34 7 25 13 V38 Q34 32 43 35 Z"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M10 18 Q15 16 19 19 M10 24 Q15 22 19 25
                                   M38 18 Q33 16 29 19 M38 24 Q33 22 29 25"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
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

                        <a class="cv-text-link"
                           href="<?php echo esc_url(home_url('/study-materials/')); ?>">
                            Browse Study Materials <span aria-hidden="true">→</span>
                        </a>
                    </div>

                </article>

                <!-- QUESTION PAPERS -->
                <article class="cv-feature-card">

                    <div class="cv-feature-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" fill="none">
                            <path
                                d="M13 5 H29 L37 13 V43 H13 Z"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M29 5 V13 H37 M19 21 H31 M19 27 H31
                                   M19 33 H28"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>

                    <div class="cv-feature-content">
                        <h3>Question Papers</h3>

                        <p>
                            Mid-semester, semester-end, previous and model
                            papers organized within each subject.
                        </p>

                        <ul class="cv-feature-list">
                            <li>Mid-Semester Papers</li>
                            <li>Semester-End Papers</li>
                            <li>Previous Year Papers</li>
                            <li>Model Papers</li>
                            <li>Subject-wise Organization</li>
                        </ul>

                        <a class="cv-text-link"
                           href="<?php echo esc_url(home_url('/question-papers/')); ?>">
                            Browse Question Papers <span aria-hidden="true">→</span>
                        </a>
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
