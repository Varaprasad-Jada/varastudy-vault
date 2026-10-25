<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$selected_group = isset($_GET['course_group'])
    ? sanitize_title(wp_unslash($_GET['course_group']))
    : '';

$selected_semester = isset($_GET['semester'])
    ? sanitize_text_field(wp_unslash($_GET['semester']))
    : '';

$selected_category = isset($_GET['resource_category'])
    ? sanitize_key(wp_unslash($_GET['resource_category']))
    : '';

$course_groups = get_terms(array(
    'taxonomy'   => 'course_group',
    'hide_empty' => false,
));

$archive_url = get_post_type_archive_link('study-materials');
?>

<style>
.cv-resource-filters {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    align-items: end;
    margin: 28px 0 32px;
    padding: 22px;
    background: #f6f8f2;
    border: 1px solid #e1e8dc;
    border-radius: 16px;
}

.cv-resource-filters .cv-filter-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}

.cv-resource-filters label {
    color: #244b32;
    font-size: 14px;
    font-weight: 600;
}

.cv-resource-filters select {
    width: 100%;
    min-height: 46px;
    padding: 10px 12px;
    color: #24352a;
    background: #fff;
    border: 1px solid #ccd8ca;
    border-radius: 9px;
    font: inherit;
}

.cv-resource-filters select:focus {
    outline: 2px solid #80b894;
    outline-offset: 2px;
    border-color: #287344;
}

.cv-resource-filters .cv-filter-actions {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 2px;
}

.cv-resource-filters .cv-filter-button {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    min-height: 44px;
    padding: 10px 20px;
    color: #fff;
    background: #245c38;
    border: 1px solid #245c38;
    border-radius: 9px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}

.cv-resource-filters .cv-filter-button:hover {
    background: #19472a;
}

.cv-resource-filters .cv-filter-reset {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    min-height: 44px;
    padding: 10px 16px;
    color: #245c38;
    background: transparent;
    border: 1px solid #b8cbb9;
    border-radius: 9px;
    font-weight: 600;
    text-decoration: none;
}

.cv-resource-filters .cv-filter-reset:hover {
    background: #eaf1e7;
}

@media (max-width: 760px) {
    .cv-resource-filters {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        padding: 16px;
    }

    .cv-resource-filters .cv-filter-actions {
        grid-column: 1 / -1;
    }
}

@media (max-width: 480px) {
    .cv-resource-filters {
        grid-template-columns: 1fr;
    }

    .cv-resource-filters .cv-filter-actions {
        flex-wrap: wrap;
    }

    .cv-resource-filters .cv-filter-button,
    .cv-resource-filters .cv-filter-reset {
        flex: 1;
    }
}
</style>

<main id="main-content" class="cv-page">
    <div class="cv-container">

        <?php get_template_part('template-parts/breadcrumbs'); ?>

        <p class="cv-eyebrow">Learn by topic</p>
        <h1 class="cv-title">Study Materials</h1>
        <p class="cv-intro">
            Browse notes, lessons, lab resources and skill-paper materials.
        </p>

        <form
            class="cv-resource-filters"
            method="get"
            action="<?php echo esc_url($archive_url); ?>"
        >
            <div class="cv-filter-field">
                <label for="cv-course-group">Course Group</label>
                <select id="cv-course-group" name="course_group">
                    <option value="">All course groups</option>

                    <?php
                    if (!is_wp_error($course_groups) && $course_groups) :
                        foreach ($course_groups as $group) :
                    ?>
                        <option
                            value="<?php echo esc_attr($group->slug); ?>"
                            <?php selected($selected_group, $group->slug); ?>
                        >
                            <?php echo esc_html($group->name); ?>
                        </option>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>

            <div class="cv-filter-field">
                <label for="cv-semester">Semester</label>
                <select id="cv-semester" name="semester">
                    <option value="">All semesters</option>

                    <?php for ($i = 1; $i <= 6; $i++) : ?>
                        <option
                            value="<?php echo esc_attr((string) $i); ?>"
                            <?php selected($selected_semester, (string) $i); ?>
                        >
                            Semester <?php echo esc_html((string) $i); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="cv-filter-field">
                <label for="cv-resource-category">Resource Category</label>
                <select
                    id="cv-resource-category"
                    name="resource_category"
                >
                    <option value="">All Categories</option>
                    <option
                        value="theory"
                        <?php selected($selected_category, 'theory'); ?>
                    >
                        Theory
                    </option>
                    <option
                        value="lab"
                        <?php selected($selected_category, 'lab'); ?>
                    >
                        Lab
                    </option>
                    <option
                        value="skill"
                        <?php selected($selected_category, 'skill'); ?>
                    >
                        Skill Paper
                    </option>
                </select>
            </div>

            <div class="cv-filter-actions">
                <button class="cv-filter-button" type="submit">
                    Apply Filters
                </button>

                <a
                    class="cv-filter-reset"
                    href="<?php echo esc_url($archive_url); ?>"
                >
                    Clear Filters
                </a>
            </div>
        </form>

        <?php if (have_posts()) : ?>

            <div class="cv-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/resource-card');
                endwhile;
                ?>
            </div>

            <div class="cv-pagination">
                <?php the_posts_pagination(); ?>
            </div>

        <?php else : ?>

            <div class="cv-empty">
                No study materials match these filters.
                Try selecting different options.
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
