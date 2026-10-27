# VaraStudy Vault

VaraStudy Vault is an independent classic WordPress theme for organizing college study resources.

## Features

- Subject, study-material, and question-paper archive templates
- Resource cards and breadcrumb navigation
- Semester and subject-based resource navigation
- Primary and footer menu locations
- Responsive layout and keyboard-visible focus styles
- Support for displaying resources using WordPress custom post types and Advanced Custom Fields (ACF)

## Requirements

- WordPress 6.0 or later
- PHP 7.4 or later
- Custom post types registered with these slugs: `subjects`, `study-materials`, and `question-papers`
- Advanced Custom Fields (ACF), with the fields listed below

This theme does **not** register custom post types or ACF fields. Keep your existing CPT UI (or equivalent registration setup) and ACF configuration active.

## Expected ACF fields

`semester`, `subject_code`, `subject_description`, `subject`, `resource_category`, `unit_lesson`, `theory_resource_type`, `lab_resource_type`, `skill_resource_type`, `pdf_file`, `resource_link`, `exam_type`, `paper_status`, `academic_year`, `question_paper_pdf`.

The `subject` relationship/post-object field must return a value format supported by the theme. Check the field's return format in ACF and test resource-to-subject navigation on a development site.

## Installation

1. Back up your site and test the theme on a development or staging site first.
2. In WordPress, open **Appearance → Themes → Add New → Upload Theme** and upload the theme ZIP from the project's Releases section, or install the theme folder from this repository.
3. Activate **VaraStudy Vault**.
4. Keep the required custom post type and ACF configurations enabled.
5. Open **Settings → Permalinks** and click **Save Changes** once.
6. Assign your menus under **Appearance → Menus** or the site editor, depending on your WordPress setup.
7. Create the About, Contact, and Privacy Policy pages if needed, and review the links in `footer.php`.

## Development notes

- Theme stylesheet: `style.css`
- Theme setup and resource helpers: `functions.php`
- Navigation script: `assets/js/navigation.js`
- CSS classes retain the `cv-` prefix for internal consistency.
- Theme name: VaraStudy Vault
- Text domain: `varastudy-vault`

This is an initial release and has not been verified against every WordPress, CPT UI, or ACF configuration. Test archive filters, relationship fields, menus, and resource links before using it on a production site.

## Images and branding

The theme does not bundle stock photography or user-specific branding images. Site owners can configure their own site icon, logo, images, and study-resource content using WordPress and the theme's supported features. Users are responsible for ensuring they have permission to use the content they upload.


## Screenshots

### Homepage
![VaraStudy Vault homepage](screenshots/homepage.png)

### Study Materials
![Study Materials archive with resource filters](screenshots/study-materials.png)


## License

VaraStudy Vault is distributed under the **GNU General Public License, version 2 or later (GPL-2.0-or-later)**.

See the [`LICENSE`](LICENSE) file for the complete license text.
