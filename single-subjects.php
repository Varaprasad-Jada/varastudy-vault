
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
            $subject_id = get_the_ID();

            $semester = function_exists( 'get_field' )
                ? get_field( 'semester', $subject_id )
                : '';

            $description = function_exists( 'get_field' )
                ? get_field( 'subject_description', $subject_id )
                : '';
            ?>

            <p class="cv-eyebrow">
                <?php
                echo $semester
                    ? 'Semester ' . esc_html( $semester )
                    : 'Subject';
                ?>
            </p>

            <h1 class="cv-title"><?php the_title(); ?></h1>

            <?php if ( $description ) : ?>
                <p class="cv-intro">
                    <?php echo esc_html( $description ); ?>
                </p>
            <?php endif; ?>

            <div class="cv-content">
                <?php the_content(); ?>
            </div>

            <?php
            /*
             * Fetch study materials connected to this subject.
             */
            $materials_query = new WP_Query(
                array(
                    'post_type'      => 'study-materials',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'meta_query'     => array(
                        array(
                            'key'     => 'subject',
                            'value'   => (string) $subject_id,
                            'compare' => '=',
                        ),
                    ),
                    'orderby' => 'title',
                    'order'   => 'ASC',
                )
            );

            $resource_groups = array();

            if ( $materials_query->have_posts() ) {
                foreach ( $materials_query->posts as $material ) {
                    $material_id = $material->ID;

                    $category = function_exists( 'get_field' )
                        ? get_field( 'resource_category', $material_id )
                        : '';

                    $label = function_exists( 'get_field' )
                        ? get_field( 'unit_lesson', $material_id )
                        : '';

                    // Support ACF fields returning either values or arrays.
                    if ( is_array( $category ) ) {
                        $category = $category['value'] ?? '';
                    }

                    $category = strtolower( trim( (string) $category ) );
                    $label = is_string( $label ) ? trim( $label ) : '';

                    /*
                     * Match a leading number in Unit or Experiment labels.
                     * Examples:
                     * Unit 1 - Parts of Speech
                     * Unit 2: Prose
                     * Experiment 1 - Word Processing
                     */
                    $number = 0;
                    $group_type = 'other';

                    if (
                        'lab' === $category
                        && preg_match(
                            '/^\s*Experiment\s*(\d+)/i',
                            $label,
                            $matches
                        )
                    ) {
                        $number = (int) $matches[1];
                        $group_type = 'experiment';
                    } elseif (
                        in_array( $category, array( 'theory', 'skill' ), true )
                        && preg_match(
                            '/^\s*Unit\s*(\d+)/i',
                            $label,
                            $matches
                        )
                    ) {
                        $number = (int) $matches[1];
                        $group_type = 'unit';
                    }

                    if ( 'other' === $group_type ) {
                        $group_key = 'other';
                        $group_title = 'Other Resources';
                    } elseif ( 'experiment' === $group_type ) {
                        $group_key = 'lab_experiment_' . $number;
                        $group_title = 'Experiment ' . $number;
                    } else {
                        $group_key = $category . '_unit_' . $number;

                        $category_title = 'skill' === $category
                            ? 'Skill Paper'
                            : 'Theory';

                        $group_title = $category_title . ' — Unit ' . $number;
                    }

                    if ( ! isset( $resource_groups[ $group_key ] ) ) {
                        $resource_groups[ $group_key ] = array(
                            'type'  => $group_type,
                            'category' => $category,
                            'number' => $number,
                            'title' => $group_title,
                            'posts' => array(),
                        );
                    }

                    $resource_groups[ $group_key ]['posts'][] = $material;
                }
            }

            wp_reset_postdata();

            /*
             * Display theory units first, then skill units,
             * lab experiments, and finally uncategorized resources.
             */
            uasort(
                $resource_groups,
                function ( $a, $b ) {
                    $order = array(
                        'theory'     => 1,
                        'skill'      => 2,
                        'experiment' => 3,
                        'other'      => 4,
                    );

                    $a_order = $order[
                        'experiment' === $a['type']
                            ? 'experiment'
                            : ( 'other' === $a['type'] ? 'other' : $a['category'] )
                    ] ?? 4;

                    $b_order = $order[
                        'experiment' === $b['type']
                            ? 'experiment'
                            : ( 'other' === $b['type'] ? 'other' : $b['category'] )
                    ] ?? 4;

                    if ( $a_order !== $b_order ) {
                        return $a_order <=> $b_order;
                    }

                    return $a['number'] <=> $b['number'];
                }
            );
            ?>

            <section class="cv-section cv-subject-materials">
                <p class="cv-eyebrow">Learn by unit and topic</p>
                <h2>Study Materials</h2>

                <p class="cv-intro">
                    Browse notes, lessons, lab records, practicals and
                    skill-paper resources for this subject.
                </p>

                <?php if ( ! empty( $resource_groups ) ) : ?>

                    <?php foreach ( $resource_groups as $group ) : ?>

                        <section class="cv-resource-section">
                            <h3>
                                <?php echo esc_html( $group['title'] ); ?>
                            </h3>

                            <div class="cv-grid">
                                <?php foreach ( $group['posts'] as $material ) : ?>
                                    <?php
                                    $post = $material;
                                    setup_postdata( $post );

                                    get_template_part(
                                        'template-parts/resource-card'
                                    );
                                    ?>
                                <?php endforeach; ?>
                            </div>
                        </section>

                    <?php endforeach; ?>

                    <?php wp_reset_postdata(); ?>

                <?php else : ?>

                    <div class="cv-empty">
                        Study materials will appear here when added.
                    </div>

                <?php endif; ?>
            </section>

            <?php
            /*
             * Keep question papers separate from study materials.
             */
            $papers_query = new WP_Query(
                array(
                    'post_type'      => 'question-papers',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'meta_query'     => array(
                        array(
                            'key'     => 'subject',
                            'value'   => (string) $subject_id,
                            'compare' => '=',
                        ),
                    ),
                    'orderby' => 'title',
                    'order'   => 'ASC',
                )
            );
            ?>

            <section class="cv-section cv-subject-papers">
                <p class="cv-eyebrow">Prepare for exams</p>
                <h2>Question Papers</h2>

                <p class="cv-intro">
                    Find mid-semester, semester-end, previous and model papers.
                </p>

                <?php if ( $papers_query->have_posts() ) : ?>

                    <div class="cv-grid">
                        <?php while ( $papers_query->have_posts() ) : ?>
                            <?php
                            $papers_query->the_post();
                            get_template_part( 'template-parts/resource-card' );
                            ?>
                        <?php endwhile; ?>
                    </div>

                    <?php wp_reset_postdata(); ?>

                <?php else : ?>

                    <div class="cv-empty">
                        Question papers will appear here when added.
                    </div>

                <?php endif; ?>
            </section>

        <?php endwhile; ?>

    </div>
</main>

<?php get_footer(); ?>
