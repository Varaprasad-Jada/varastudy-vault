
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

            $subject_id = function_exists( 'varastudy_vault_subject_id' )
                ? varastudy_vault_subject_id( $post_id )
                : 0;

            $file = function_exists( 'get_field' )
                ? get_field( 'question_paper_pdf', $post_id )
                : false;

            $meta = function_exists( 'varastudy_vault_resource_meta' )
                ? varastudy_vault_resource_meta( $post_id )
                : array();

            /*
             * Resolve the uploaded file URL.
             * Supports ACF File fields returning an array,
             * attachment ID, or URL.
             */
            $pdf_url = '';

            if ( is_array( $file ) && ! empty( $file['url'] ) ) {
                $pdf_url = $file['url'];
            } elseif ( is_numeric( $file ) && $file ) {
                $pdf_url = wp_get_attachment_url( (int) $file );
            } elseif ( is_string( $file ) && $file ) {
                $pdf_url = $file;
            }

            /*
             * Identify PDFs so other file types are not
             * embedded incorrectly.
             */
            $is_pdf = false;

            if ( $pdf_url ) {
                $is_pdf = (bool) preg_match(
                    '/\.pdf(?:$|[?#])/i',
                    $pdf_url
                );

                if (
                    ! $is_pdf &&
                    is_array( $file ) &&
                    ! empty( $file['mime_type'] ) &&
                    'application/pdf' === $file['mime_type']
                ) {
                    $is_pdf = true;
                }
            }

            /*
             * Metadata labels.
             */
            $exam_labels = array(
                'mid_semester' => 'Mid-Semester',
                'semester_end' => 'Semester-End Exam',
            );

            $status_labels = array(
                'previous' => 'Previous Paper',
                'model'    => 'Model Paper',
                'new'      => 'Newly Added Paper',
            );
            ?>

            <p class="cv-eyebrow">Question Paper</p>

            <h1 class="cv-title"><?php the_title(); ?></h1>

            <?php if ( $subject_id ) : ?>
                <p class="cv-intro">
                    Subject:
                    <a href="<?php echo esc_url( get_permalink( $subject_id ) ); ?>">
                        <?php echo esc_html( get_the_title( $subject_id ) ); ?>
                    </a>
                </p>
            <?php endif; ?>

            <?php if ( ! empty( $meta ) ) : ?>
                <div class="cv-pills">

                    <?php foreach ( $meta as $key => $value ) : ?>

                        <?php
                        /*
                         * Normalize ACF values.
                         */
                        if ( is_array( $value ) ) {
                            $value = $value['label'] ?? $value['value'] ?? '';
                        }

                        if ( ! is_scalar( $value ) ) {
                            continue;
                        }

                        $value = trim( (string) $value );

                        if ( '' === $value ) {
                            continue;
                        }

                        /*
                         * Convert the Semester number into
                         * a readable label.
                         */
                        if ( 'semester' === $key ) {
                            if (
                                in_array(
                                    $value,
                                    array( '1', '2', '3', '4', '5', '6' ),
                                    true
                                )
                            ) {
                                $value = 'Semester ' . $value;
                            }
                        }

                        /*
                         * Format Exam Type.
                         */
                        if ( 'exam_type' === $key ) {
                            $normalized_exam = strtolower( $value );

                            $value = $exam_labels[ $normalized_exam ]
                                ?? ucwords(
                                    str_replace( '_', ' ', $value )
                                );
                        }

                        /*
                         * Format Paper Status if it is still
                         * included in the metadata function.
                         */
                        if ( 'paper_status' === $key ) {
                            $normalized_status = strtolower( $value );

                            $value = $status_labels[ $normalized_status ]
                                ?? ucwords(
                                    str_replace( '_', ' ', $value )
                                );
                        }
                        ?>

                        <span class="cv-pill">
                            <?php echo esc_html( $value ); ?>
                        </span>

                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

            <?php if ( trim( get_the_content() ) !== '' ) : ?>
                <article class="cv-content cv-lesson-content">
                    <?php the_content(); ?>
                </article>
            <?php endif; ?>

            <?php if ( $is_pdf ) : ?>

                <section
                    class="cv-pdf-section"
                    aria-labelledby="cv-pdf-heading"
                >

                    <h2 id="cv-pdf-heading">View Question Paper</h2>

                    <p class="cv-intro">
                        Read the question paper below or open it separately.
                    </p>

                    <div class="cv-pdf-viewer">
                        <iframe
                            src="<?php echo esc_url( $pdf_url ); ?>"
                            title="<?php echo esc_attr( get_the_title() . ' PDF preview' ); ?>"
                            loading="lazy"
                        ></iframe>
                    </div>

                    <p class="cv-pdf-actions">

                        <a
                            class="cv-download"
                            href="<?php echo esc_url( $pdf_url ); ?>"
                            target="_blank"
                            rel="noopener"
                        >Open PDF in New Tab</a>

                        <a
                            class="cv-download"
                            href="<?php echo esc_url( $pdf_url ); ?>"
                            download
                        >Download Question Paper</a>

                    </p>

                </section>

            <?php elseif ( $pdf_url ) : ?>

                <p>
                    <a
                        class="cv-download"
                        href="<?php echo esc_url( $pdf_url ); ?>"
                        target="_blank"
                        rel="noopener"
                    >Open / Download Question Paper</a>
                </p>

            <?php endif; ?>

            <?php if (
                ! $is_pdf &&
                ! $pdf_url &&
                trim( get_the_content() ) === ''
            ) : ?>

                <div class="cv-empty">
                    This question paper has not been uploaded yet.
                </div>

            <?php endif; ?>

        <?php endwhile; ?>

    </div>
</main>

<?php get_footer(); ?>
