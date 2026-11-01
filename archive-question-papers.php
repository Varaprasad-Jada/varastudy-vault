
<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$archive_url = get_post_type_archive_link( 'question-papers' );

$selected_subject = isset( $_GET['subject'] )
    ? absint( wp_unslash( $_GET['subject'] ) )
    : 0;

$selected_year = isset( $_GET['academic_year'] )
    ? sanitize_text_field( wp_unslash( $_GET['academic_year'] ) )
    : '';

/*
 * Get published subjects for the Subject filter.
 */
$subjects = get_posts(
    array(
        'post_type'      => 'subjects',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    )
);

/*
 * Build Academic Year options from published Question Papers.
 * Supports values such as 2025, 2026, and 2027.
 */
$years  = array();
$papers = get_posts(
    array(
        'post_type'      => 'question-papers',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => array(
            array(
                'key'     => 'academic_year',
                'compare' => 'EXISTS',
            ),
        ),
    )
);

foreach ( $papers as $paper_id ) {
    if ( function_exists( 'get_field' ) ) {
        $year = get_field( 'academic_year', $paper_id );
    } else {
        $year = get_post_meta( $paper_id, 'academic_year', true );
    }

    if ( is_array( $year ) ) {
        $year = $year['value'] ?? $year['label'] ?? '';
    }

    if ( is_scalar( $year ) ) {
        $year = trim( (string) $year );

        if ( preg_match( '/^\d{4}$/', $year ) ) {
            $years[ $year ] = $year;
        }
    }
}

/* Show the newest year first. */
krsort( $years, SORT_NUMERIC );
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

        <form
            class="cv-archive-filters"
            method="get"
            action="<?php echo esc_url( $archive_url ); ?>"
        >

            <div class="cv-filter-field">
                <label for="filter-subject">Subject</label>

                <select id="filter-subject" name="subject">
                    <option value="">All subjects</option>

                    <?php foreach ( $subjects as $subject ) : ?>
                        <option
                            value="<?php echo esc_attr( $subject->ID ); ?>"
                            <?php selected( $selected_subject, $subject->ID ); ?>
                        >
                            <?php echo esc_html( $subject->post_title ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="cv-filter-field">
                <label for="filter-academic-year">Academic Year</label>

                <select id="filter-academic-year" name="academic_year">
                    <option value="">All academic years</option>

                    <?php foreach ( $years as $year ) : ?>
                        <option
                            value="<?php echo esc_attr( $year ); ?>"
                            <?php selected( $selected_year, $year ); ?>
                        >
                            <?php echo esc_html( $year ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="cv-filter-actions">
                <button type="submit">Apply filters</button>

                <?php if ( $selected_subject || $selected_year !== '' ) : ?>
                    <a href="<?php echo esc_url( $archive_url ); ?>">
                        Clear filters
                    </a>
                <?php endif; ?>
            </div>

        </form>

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
                        'add_args'  => array_filter(
                            array(
                                'subject'       => $selected_subject ?: '',
                                'academic_year' => $selected_year,
                            ),
                            static function ( $value ) {
                                return $value !== '';
                            }
                        ),
                    )
                );
                ?>
            </div>

        <?php else : ?>

            <div class="cv-empty">
                <h2>No question papers found</h2>
                <p>
                    Try another subject or academic year, or clear
                    the filters to browse all available papers.
                </p>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
