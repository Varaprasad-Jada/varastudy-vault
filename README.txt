
VaraStudy Vault — Independent Classic WordPress Theme
Version 1.0.0
Author: Vara Prasad Jada

VaraStudy Vault is an independent classic WordPress theme designed for
organizing and displaying college study resources.

REQUIREMENTS

- WordPress 6.0 or later.
- PHP 7.4 or later.
- Advanced Custom Fields (ACF).
- Custom Post Type UI (CPT UI), or an equivalent plugin.

EXPECTED CUSTOM POST TYPES

- subjects
- study-materials
- question-papers

EXPECTED TAXONOMY

- course_group

EXPECTED ACF FIELD NAMES

- semester
- subject_code
- subject_description
- subject
- resource_category
- unit_lesson
- theory_resource_type
- lab_resource_type
- skill_resource_type
- pdf_file
- resource_link
- exam_type
- paper_status
- academic_year
- question_paper_pdf

IMPORTANT

Keep CPT UI and ACF active. This theme does not register custom
post types, taxonomies, or ACF fields. Create and configure them
using the required plugins before adding content.

INSTALLATION

1. Back up your WordPress site.
2. Install the theme through Appearance > Themes > Add New > Upload Theme.
   On multisite, use Network Admin if required.
3. Activate VaraStudy Vault on the intended site.
4. Visit Settings > Permalinks and click Save Changes once.
5. Create and assign menus to Primary Navigation and Footer Navigation.
6. Create the required pages, including About Us, Contact, and Privacy Policy,
   or update the corresponding links in footer.php.

CONTENT SETUP

- Assign Subjects to the appropriate course_group taxonomy terms.
- Configure the Semester field for each Subject.
- Link Study Materials and Question Papers to their respective Subjects.
- Configure the required resource and examination fields using ACF.

TESTING

Before deployment, test the theme on a local WordPress installation.

Check:
- Subject archives and individual Subject pages.
- Study Materials archives and individual resource pages.
- Question Papers archives and individual paper pages.
- Course Group, Semester, Subject, and Resource Category filters.
- ACF relationship field storage and retrieval.
- Navigation menus, breadcrumbs, pagination, and responsive layouts.

PROJECT STATUS

Version 1.0.0 — Initial release.

This theme should be tested in the intended WordPress environment
before production deployment.
