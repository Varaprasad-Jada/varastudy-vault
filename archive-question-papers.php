<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="main-content" class="cv-page">
    <div class="cv-container">

        <?php get_template_part( 'template-parts/breadcrumbs' ); ?>

        <p class="cv-eyebrow">Prepare with practice</p>

        <h1 class="cv-title">Question Papers</h1>

        <p class="cv-intro">
            Browse mid-semester and semester-end question papers
            to practise and prepare for your examinations.
        </p>

        <?php if ( have_posts() ) : ?>

            <div class="cv-grid">
                <?php
                while ( have_posts() ) :
                    the_post();

                    get_template_part( 'template-parts/resource-card' );
                endwhile;
                ?>
            </div>

            <div class="cv-pagination">
                <?php
                the_posts_pagination(
                    array(
                        'mid_size'  => 2,
                        'prev_text' => '&larr; Previous',
                        'next_text' => 'Next &rarr;',
                    )
                );
                ?>
            </div>

        <?php else : ?>

            <div class="cv-empty">
                <h2>No question papers available yet</h2>
                <p>
                    Question papers will appear here when they are published.
                    Please check back later.
                </p>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
```