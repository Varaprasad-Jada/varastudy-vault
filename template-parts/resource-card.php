
<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$post_id   = get_the_ID();
$post_type = get_post_type( $post_id );

$meta = function_exists( 'varastudy_vault_resource_meta' )
    ? varastudy_vault_resource_meta( $post_id )
    : array();

$normalize = static function ( $value ) {
    if ( is_array( $value ) ) {
        $value = $value['label'] ?? $value['value'] ?? '';
    }

    if ( is_object( $value ) ) {
        return '';
    }

    return is_scalar( $value )
        ? strtolower( trim( (string) $value ) )
        : '';
};

$labels = array(
    'theory'        => 'Theory',
    'lab'            => 'Lab',
    'skill'          => 'Skill Paper',
    'notes'          => 'Notes',
    'lesson_notes'   => 'Lesson Notes',
    'pdf'            => 'PDF Material',
    'lab_manual'     => 'Lab Manual',
    'viva'           => 'Viva Questions',
    'practical'      => 'Practical Exercise',
    'other'          => 'Other',
    'mid_semester'   => 'Mid-Semester',
    'semester_end'   => 'Semester-End Exam',
    'previous'       => 'Previous Paper',
    'model'          => 'Model Paper',
    'new'            => 'Newly Added Paper',
);

$display = static function ( $value ) use ( $normalize, $labels ) {
    $value = $normalize( $value );

    if ( preg_match( '/^[1-6]$/', $value ) ) {
        return 'Semester ' . $value;
    }

    return $labels[ $value ] ?? ucwords( str_replace( '_', ' ', $value ) );
};

/*
 * Get the linked Subject.
 */
$subject_id = function_exists( 'varastudy_vault_subject_id' )
    ? varastudy_vault_subject_id( $post_id )
    : 0;

$subject_title = $subject_id ? get_the_title( $subject_id ) : '';
$subject_url   = $subject_id ? get_permalink( $subject_id ) : '';

/*
 * Semester source:
 * Question Papers: their own ACF semester field.
 * Study Materials: the linked Subject's semester.
 */
if ( 'question-papers' === $post_type ) {
    $semester = function_exists( 'get_field' )
        ? get_field( 'semester', $post_id )
        : '';
} else {
    $semester = $subject_id && function_exists( 'get_field' )
        ? get_field( 'semester', $subject_id )
        : '';
}

$course_groups = $subject_id
    ? get_the_terms( $subject_id, 'course_group' )
    : array();

if ( is_wp_error( $course_groups ) || ! is_array( $course_groups ) ) {
    $course_groups = array();
}

$unit = function_exists( 'get_field' )
    ? get_field( 'unit_lesson', $post_id )
    : '';
?>

<article <?php post_class( 'cv-card cv-resource-card', $post_id ); ?>>

    <?php if ( 'question-papers' === $post_type ) : ?>

        <p class="cv-eyebrow">Question Paper</p>

        <h3>
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>

        <div class="cv-pills">

            <?php
            /*
             * Display the Question Paper's own Semester.
             */
            if ( is_array( $semester ) ) {
                $semester = $semester['value'] ?? $semester['label'] ?? '';
            }

            if ( is_scalar( $semester ) && trim( (string) $semester ) !== '' ) :
                ?>
                <span class="cv-pill">
                    <?php echo esc_html( $display( $semester ) ); ?>
                </span>
            <?php endif; ?>

            <?php
            /*
             * Display Exam Type and Academic Year.
             * Paper Status has been removed.
             */
            foreach ( array( 'exam_type', 'academic_year' ) as $key ) :
                $value = function_exists( 'get_field' )
                    ? get_field( $key, $post_id )
                    : '';

                if ( is_array( $value ) ) {
                    $value = $value['label'] ?? $value['value'] ?? '';
                }

                if ( is_scalar( $value ) && trim( (string) $value ) !== '' ) :
                    ?>
                    <span class="cv-pill">
                        <?php echo esc_html( $display( $value ) ); ?>
                    </span>
                    <?php
                endif;
            endforeach;
            ?>

        </div>

    <?php else : ?>

        <?php
        $category = $normalize( $meta['resource_category'] ?? '' );

        $category_labels = array(
            'theory' => 'Theory',
            'lab'    => 'Lab',
            'skill'  => 'Skill Paper',
        );

        $type_keys = array(
            'theory' => 'theory_resource_type',
            'lab'    => 'lab_resource_type',
            'skill'  => 'skill_resource_type',
        );

        $type_key = $type_keys[ $category ] ?? '';
        $type     = $type_key ? ( $meta[ $type_key ] ?? '' ) : '';
        ?>

        <p class="cv-eyebrow">
    <?php echo esc_html( $category_labels[ $category ] ?? 'Study Resource' ); ?>
</p>

<?php if ( $subject_title ) : ?>
    <p class="cv-resource-subject">
        <a href="<?php echo esc_url( $subject_url ); ?>">
            <?php echo esc_html( $subject_title ); ?>
        </a>
    </p>
<?php endif; ?>

<h3>
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>

        <?php if ( $unit || $type ) : ?>
            <div class="cv-pills">

                <?php if ( $unit ) : ?>
                    <span class="cv-pill">
                        <?php echo esc_html( $unit ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( $type ) : ?>
                    <span class="cv-pill">
                        <?php echo esc_html( $display( $type ) ); ?>
                    </span>
                <?php endif; ?>

            </div>
        <?php endif; ?>

    <?php endif; ?>

    <?php if ( has_excerpt( $post_id ) ) : ?>
        <p><?php echo esc_html( get_the_excerpt( $post_id ) ); ?></p>
    <?php endif; ?>

    <p class="cv-resource-action">
        <a href="<?php the_permalink(); ?>">View resource →</a>
    </p>

</article>
