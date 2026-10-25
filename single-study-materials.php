
<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="main-content" class="cv-page">
    <div class="cv-container">

        <?php get_template_part( 'template-parts/breadcrumbs' ); ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <?php
            $post_id = get_the_ID();

            $subject_id = varastudy_vault_subject_id( $post_id );
            $meta       = varastudy_vault_resource_meta( $post_id );

            $file = function_exists( 'get_field' )
                ? get_field( 'pdf_file', $post_id )
                : false;

            $resource_link = function_exists( 'get_field' )
                ? get_field( 'resource_link', $post_id )
                : '';

            // Optional lesson sections. These fields can be added later in ACF.
            $objectives = function_exists( 'get_field' )
                ? get_field( 'learning_objectives', $post_id )
                : '';

            $summary = function_exists( 'get_field' )
                ? get_field( 'quick_revision', $post_id )
                : '';

            $practice = function_exists( 'get_field' )
                ? get_field( 'practice_questions', $post_id )
                : '';

            // Resolve the ACF file value to a URL.
            $pdf_url = '';

            if ( is_array( $file ) && ! empty( $file['url'] ) ) {
                $pdf_url = $file['url'];
            } elseif ( is_numeric( $file ) && $file ) {
                $pdf_url = wp_get_attachment_url( (int) $file );
            } elseif ( is_string( $file ) && $file ) {
                $pdf_url = $file;
            }

            $is_pdf = $pdf_url && (
                preg_match( '/\.pdf(?:$|[?#])/i', $pdf_url )
                || (
                    is_array( $file )
                    && ! empty( $file['mime_type'] )
                    && 'application/pdf' === $file['mime_type']
                )
            );

            // Generate section links from the actual headings in the lesson.
            $content = apply_filters( 'the_content', get_the_content() );
            $has_content = trim( wp_strip_all_tags( $content ) ) !== '';
            ?>

            <article <?php post_class( 'cv-lesson' ); ?>>

                <header class="cv-lesson-header">
                    <p class="cv-eyebrow">
                        <?php
                        echo esc_html(
                            $meta['resource_category'] ?? 'Study Material'
                        );
                        ?>
                    </p>

                    <h1 class="cv-title"><?php the_title(); ?></h1>

                    <?php if ( $subject_id ) : ?>
                        <p class="cv-intro">
                            Subject:
                            <a href="<?php echo esc_url( get_permalink( $subject_id ) ); ?>">
                                <?php echo esc_html( get_the_title( $subject_id ) ); ?>
                            </a>
                        </p>
                    <?php endif; ?>

                    <?php if ( $meta ) : ?>
                        <div class="cv-pills">
                            <?php foreach ( $meta as $key => $value ) : ?>
                                <?php
                                // Avoid repeating the category in the metadata pills.
                                if ( 'resource_category' === $key ) {
                                    continue;
                                }
                                ?>
                                <span class="cv-pill">
                                    <?php echo esc_html( $value ); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </header>

                <?php if ( $objectives ) : ?>
                    <section class="cv-learning-box" aria-labelledby="cv-objectives-heading">
                        <h2 id="cv-objectives-heading">What You Will Learn</h2>
                        <?php echo wp_kses_post( wpautop( $objectives ) ); ?>
                    </section>
                <?php endif; ?>

                <?php if ( $has_content ) : ?>
                    <section class="cv-lesson-body" aria-label="Lesson notes">
                        <?php echo $content; ?>
                    </section>
                <?php endif; ?>

                <?php if ( $summary ) : ?>
                    <section class="cv-learning-box cv-revision" aria-labelledby="cv-revision-heading">
                        <h2 id="cv-revision-heading">Quick Revision</h2>
                        <?php echo wp_kses_post( wpautop( $summary ) ); ?>
                    </section>
                <?php endif; ?>

                <?php if ( $practice ) : ?>
                    <section class="cv-practice" aria-labelledby="cv-practice-heading">
                        <h2 id="cv-practice-heading">Practice Questions</h2>
                        <?php echo wp_kses_post( wpautop( $practice ) ); ?>
                    </section>
                <?php endif; ?>

                <?php if ( $is_pdf ) : ?>
                    <section class="cv-pdf-section" aria-labelledby="cv-pdf-heading">
                        <h2 id="cv-pdf-heading">Supporting PDF</h2>

                        <div class="cv-pdf-viewer">
                            <iframe
                                src="<?php echo esc_url( $pdf_url ); ?>"
                                title="<?php echo esc_attr( get_the_title() . ' PDF preview' ); ?>"
                                loading="lazy"
                            ></iframe>
                        </div>

                        <p class="cv-pdf-actions">
                            <a class="cv-download"
                               href="<?php echo esc_url( $pdf_url ); ?>"
                               target="_blank" rel="noopener">
                                Open PDF in New Tab
                            </a>

                            <a class="cv-download"
                               href="<?php echo esc_url( $pdf_url ); ?>"
                               download>
                                Download PDF
                            </a>
                        </p>
                    </section>
                <?php elseif ( $pdf_url ) : ?>
                    <p>
                        <a class="cv-download"
                           href="<?php echo esc_url( $pdf_url ); ?>"
                           target="_blank" rel="noopener">
                            Open / Download File
                        </a>
                    </p>
                <?php endif; ?>

                <?php if ( $resource_link ) : ?>
                    <p>
                        <a class="cv-download"
                           href="<?php echo esc_url( $resource_link ); ?>"
                           target="_blank" rel="noopener noreferrer">
                            Open External Resource
                        </a>
                    </p>
                <?php endif; ?>

                <?php if ( ! $has_content && ! $objectives && ! $summary && ! $practice && ! $pdf_url && ! $resource_link ) : ?>
                    <div class="cv-empty">
                        Lesson content and supporting materials will appear here when added.
                    </div>
                <?php endif; ?>

            </article>

        <?php endwhile; ?>

    </div>
</main>

<?php get_footer(); ?>
