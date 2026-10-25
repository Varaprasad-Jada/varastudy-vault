<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>

    <style>
        /* Header search */
        .cv-header .cv-menu {
            display: none;
        }

        .cv-header .cv-search-wrap {
            position: relative;
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            z-index: 1000;
        }

        .cv-header .cv-search-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            padding: 9px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: var(--cv-green, #075c43);
            cursor: pointer;
        }

        .cv-header .cv-search-toggle:hover,
        .cv-header .cv-search-toggle:focus-visible {
            background: #eaf4ed;
            outline: 2px solid var(--cv-green, #075c43);
            outline-offset: 2px;
        }

        .cv-header .cv-search-toggle svg,
        .cv-header .cv-header-search button svg {
            display: block;
            width: 23px;
            height: 23px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .cv-header .cv-header-search {
            display: none;
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            z-index: 1001;
            align-items: center;
            gap: 8px;
            width: min(340px, calc(100vw - 32px));
            padding: 10px;
            border: 1px solid var(--cv-border, #d5e5d9);
            border-radius: 12px;
            background: var(--cv-cream, #fbfaf5);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.14);
        }

        .cv-header .cv-search-wrap.is-open .cv-header-search {
            display: flex;
        }

        .cv-header .cv-header-search input[type="search"] {
            display: block;
            flex: 1 1 auto;
            width: 100%;
            min-width: 0;
            height: 42px;
            margin: 0;
            padding: 9px 12px;
            border: 1px solid var(--cv-border, #d5e5d9);
            border-radius: 7px;
            background: #fff;
            color: #172a22;
            font: inherit;
            opacity: 1;
            visibility: visible;
        }

        .cv-header .cv-header-search input[type="search"]:focus {
            border-color: var(--cv-green, #075c43);
            outline: 2px solid rgba(7, 92, 67, 0.15);
        }

        .cv-header .cv-header-search button[type="submit"] {
            display: inline-flex;
            flex: 0 0 42px;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            padding: 9px;
            border: 0;
            border-radius: 7px;
            background: var(--cv-green, #075c43);
            color: #fff;
            cursor: pointer;
        }

        .cv-header .cv-header-search button[type="submit"]:hover {
            background: #064a37;
        }

        /* Contributor login link */
        .cv-header .cv-login-link {
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 9px 15px;
            border: 1px solid var(--cv-green, #075c43);
            border-radius: 8px;
            background: var(--cv-green, #075c43);
            color: #fff;
            font: inherit;
            font-weight: 600;
            line-height: 1.3;
            text-decoration: none;
            white-space: nowrap;
            transition: background-color 0.2s ease,
                        color 0.2s ease,
                        border-color 0.2s ease;
        }

        .cv-header .cv-login-link:hover {
            background: #064a37;
            border-color: #064a37;
            color: #fff;
        }

        .cv-header .cv-login-link:focus-visible {
            outline: 2px solid var(--cv-green, #075c43);
            outline-offset: 3px;
        }

        @media (max-width: 800px) {
            .cv-header .cv-menu {
                display: inline-flex;
            }

            .cv-header .cv-nav .cv-search-wrap {
                flex: 0 0 auto;
            }

            .cv-header .cv-search-wrap.is-open .cv-header-search {
                position: absolute;
                top: calc(100% + 10px);
                right: 0;
                width: min(340px, calc(100vw - 24px));
            }
        }
    </style>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="screen-reader-text" href="#main-content">Skip to content</a>

<header class="cv-header">
    <div class="cv-container cv-header-inner">

        <a class="cv-brand"
           href="<?php echo esc_url( home_url( '/' ) ); ?>"
           aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ' home' ); ?>">

            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <span class="cv-mark" aria-hidden="true">
                    <svg viewBox="0 0 48 48">
                        <path d="M5 10 Q15 5 23 12 V40 Q14 33 5 36 Z"
                              fill="none" stroke="currentColor"
                              stroke-width="3" stroke-linejoin="round"/>
                        <path d="M43 10 Q33 5 25 12 V40 Q34 33 43 36 Z"
                              fill="none" stroke="currentColor"
                              stroke-width="3" stroke-linejoin="round"/>
                        <path d="M11 16 L18 18 M11 22 L18 24 M30 18 L37 16 M30 24 L37 22"
                              fill="none" stroke="currentColor"
                              stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </span>
            <?php endif; ?>

            <span class="cv-brand-copy">
                <strong class="cv-brand-name">
                    <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                </strong>
                <small><?php bloginfo( 'description' ); ?></small>
            </span>
        </a>

        <button class="cv-menu"
                type="button"
                aria-expanded="false"
                aria-controls="cv-primary-menu">
            <span class="cv-menu-label">Menu</span>
            <span class="cv-menu-icon" aria-hidden="true">
                <span></span><span></span><span></span>
            </span>
        </button>

        <nav class="cv-nav"
             id="cv-primary-menu"
             aria-label="Primary navigation">

            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'fallback_cb'    => 'varastudy-vaultault_menu_fallback',
                    'items_wrap'     => '<ul>%3$s</ul>',
                    'depth'          => 2,
                )
            );
            ?>

            <a class="cv-login-link"
               href="<?php echo esc_url( wp_login_url() ); ?>">
                Login
            </a>

            <div class="cv-search-wrap">
                <button class="cv-search-toggle"
                        type="button"
                        aria-label="Open search"
                        aria-expanded="false"
                        aria-controls="cv-header-search-form">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="10.8" cy="10.8" r="6.8"></circle>
                        <path d="m16 16 5 5"></path>
                    </svg>
                </button>

                <form class="cv-header-search"
                      id="cv-header-search-form"
                      role="search"
                      method="get"
                      action="<?php echo esc_url( home_url( '/' ) ); ?>">

                    <label class="screen-reader-text"
                           for="cv-header-search-input">
                        Search <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                    </label>

                    <input id="cv-header-search-input"
                           type="search"
                           name="s"
                           placeholder="Search resources…"
                           value="<?php echo esc_attr( get_search_query() ); ?>">

                    <button type="submit" aria-label="Submit search">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="10.8" cy="10.8" r="6.8"></circle>
                            <path d="m16 16 5 5"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </nav>
    </div>
</header>

<script>
(function () {
    function initializevarastudy-vaultSearch() {
        document.querySelectorAll('.cv-header .cv-search-wrap').forEach(function (wrap) {
            if (wrap.dataset.searchInitialized === 'true') {
                return;
            }

            var toggle = wrap.querySelector('.cv-search-toggle');
            var form = wrap.querySelector('.cv-header-search');
            var input = wrap.querySelector('input[type="search"]');

            if (!toggle || !form || !input) {
                return;
            }

            wrap.dataset.searchInitialized = 'true';

            function openSearch() {
                wrap.classList.add('is-open');
                toggle.setAttribute('aria-expanded', 'true');
                toggle.setAttribute('aria-label', 'Close search');

                window.setTimeout(function () {
                    input.focus();
                }, 0);
            }

            function closeSearch(returnFocus) {
                wrap.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.setAttribute('aria-label', 'Open search');

                if (returnFocus) {
                    toggle.focus();
                }
            }

            toggle.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                if (wrap.classList.contains('is-open')) {
                    closeSearch(false);
                } else {
                    openSearch();
                }
            });

            form.addEventListener('click', function (event) {
                event.stopPropagation();
            });

            document.addEventListener('click', function (event) {
                if (!wrap.contains(event.target)) {
                    closeSearch(false);
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && wrap.classList.contains('is-open')) {
                    closeSearch(true);
                }
            });

            form.addEventListener('submit', function () {
                if (!input.value.trim()) {
                    input.focus();
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializevarastudy-vaultSearch);
    } else {
        initializevarastudy-vaultSearch();
    }
})();
</script>