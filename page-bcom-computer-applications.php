
<?php get_header(); ?>

<main id="main-content">

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

                <a class="cv-button"
                   href="<?php echo esc_url( add_query_arg(
                       'semester', '1',
                       get_post_type_archive_link( 'subjects' )
                   ) ); ?>">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="10.8" cy="10.8" r="6.8"></circle>
                        <path d="m16 16 5 5"></path>
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

                <div class="cv-study-desk">
                    <span></span>
                </div>
            </div>

        </div>
    </section>

    <section class="cv-section cv-semesters">
        <div class="cv-container">

            <p class="cv-eyebrow">Browse by semester</p>
            <h2>Select Your Semester</h2>
            <p class="cv-intro">
                Choose your semester to access subjects, study materials
                and question papers.
            </p>

            <div class="cv-grid cv-semester-grid">
                <?php for ( $s = 1; $s <= 6; $s++ ) : ?>
                    <a class="cv-semester-card"
                       href="<?php echo esc_url( add_query_arg(
                           'semester', $s,
                           get_post_type_archive_link( 'subjects' )
                       ) ); ?>">

                        <span class="cv-semester-icon" aria-hidden="true">
                            <svg viewBox="0 0 48 48">
                                <path d="M5 10 Q15 5 23 12 V40 Q14 33 5 36 Z"
                                      fill="none" stroke="currentColor"
                                      stroke-width="3" stroke-linejoin="round"/>
                                <path d="M43 10 Q33 5 25 12 V40 Q34 33 43 36 Z"
                                      fill="none" stroke="currentColor"
                                      stroke-width="3" stroke-linejoin="round"/>
                                <path d="M11 17 L18 19 M11 23 L18 25 M30 19 L37 17 M30 25 L37 23"
                                      fill="none" stroke="currentColor"
                                      stroke-width="2.5" stroke-linecap="round"/>
                            </svg>
                        </span>

                        <span class="cv-semester-label">
                            <strong>Semester <?php echo esc_html( $s ); ?></strong>
                            <small>Browse subjects</small>
                        </span>

                        <span class="cv-semester-arrow" aria-hidden="true">→</span>
                    </a>
                <?php endfor; ?>
            </div>

        </div>
    </section>

    <section class="cv-section cv-resources">
        <div class="cv-container">

            <p class="cv-eyebrow">What you can find</p>
            <h2>Everything You Need to Study Better</h2>

            <div class="cv-grid cv-two cv-resource-grid">

                <article class="cv-feature-card">
                    <div class="cv-feature-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48">
                            <path d="M5 10 Q15 5 23 12 V40 Q14 33 5 36 Z"
                                  fill="none" stroke="currentColor"
                                  stroke-width="3" stroke-linejoin="round"/>
                            <path d="M43 10 Q33 5 25 12 V40 Q34 33 43 36 Z"
                                  fill="none" stroke="currentColor"
                                  stroke-width="3" stroke-linejoin="round"/>
                            <path d="M11 17 L18 19 M11 23 L18 25 M30 19 L37 17 M30 25 L37 23"
                                  fill="none" stroke="currentColor"
                                  stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <div class="cv-feature-content">
                        <h3>Study Materials</h3>
                        <p>
                            Unit-wise notes, lessons, lab records, practicals
                            and skill-paper resources.
                        </p>

                        <ul class="cv-feature-list">
                            <li>Theory Notes &amp; Lessons</li>
                            <li>Lab Records &amp; Manuals</li>
                            <li>Practical Exercises</li>
                            <li>Viva Questions</li>
                            <li>Skill Paper Resources</li>
                        </ul>

                        <a class="cv-text-link"
                           href="<?php echo esc_url(
                               get_post_type_archive_link( 'study-materials' )
                           ); ?>">
                            Browse Study Materials <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>

                <article class="cv-feature-card">
                    <div class="cv-feature-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48">
                            <path d="M13 5 H30 L39 14 V42 H13 Z"
                                  fill="none" stroke="currentColor"
                                  stroke-width="3" stroke-linejoin="round"/>
                            <path d="M30 5 V14 H39 M19 22 H33 M19 28 H33 M19 34 H29"
                                  fill="none" stroke="currentColor"
                                  stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <div class="cv-feature-content">
                        <h3>Question Papers</h3>
                        <p>
                            Mid-semester, semester-end, previous and model
                            papers organized by subject.
                        </p>

                        <ul class="cv-feature-list">
                            <li>Mid-Semester Papers</li>
                            <li>Semester-End Papers</li>
                            <li>Previous Year Papers</li>
                            <li>Model Papers</li>
                            <li>Subject-wise Organization</li>
                        </ul>

                        <a class="cv-text-link"
                           href="<?php echo esc_url(
                               get_post_type_archive_link( 'question-papers' )
                           ); ?>">
                            Browse Question Papers <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>

            </div>
        </div>
    </section>

    <section class="cv-success-banner">
        <div class="cv-container cv-success-inner">
            <div class="cv-success-heading">
                <span class="cv-success-icon" aria-hidden="true">✦</span>
                <h2>Your Success<br><span>Our Priority</span></h2>
            </div>

            <p>
                Stay organized. Study smart.<br>
                Make the most of your semester.
            </p>
        </div>
    </section>

</main>

<?php get_footer(); ?>
