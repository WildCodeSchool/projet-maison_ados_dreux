<?php

namespace MightyDev\WordPress\Plugin;

use MightyDev\WordPress\AdminHelper;

class NoozCore extends Core
{
    protected $release_post_type = 'nooz_release';
    protected $coverage_post_type = 'nooz_coverage';
    protected $admin_helper;

    /**
     * @codeCoverageIgnore
     */
    public function __construct( $plugin_file )
    {
        $this->set_plugin_file( $plugin_file );
    }

    public function set_default_options()
    {
        $this->set_options( array(
            'mdnooz_coverage_default_image' => '',
            'mdnooz_coverage_target' => '_blank',
            'mdnooz_coverage_slug' => 'news/press-coverage',
            'mdnooz_release_boilerplate' => '',
            'mdnooz_release_date_format' => 'F j, Y',
            'mdnooz_release_default_image' => '',
            'mdnooz_release_ending' => '###',
            'mdnooz_release_location' => '',
            'mdnooz_release_slug' => 'news/press-releases',
            'mdnooz_shortcode_count' => 5,
            // TODO: provide a group by option, year or month ... list, group-year, group-month
            'mdnooz_shortcode_display' => 'list',
            'mdnooz_shortcode_date_format' => 'M j, Y',
            'mdnooz_shortcode_more_link' => '',
            'mdnooz_shortcode_next_link' => '',
            'mdnooz_shortcode_previous_link' => '',
            'mdnooz_shortcode_use_excerpt' => 'yes',
            // defaults to "no" for backward-compatibility
            'mdnooz_shortcode_use_more_link' => 'no',
            'mdnooz_shortcode_use_pagination' => 'no',
        ) );
    }

    public function set_admin_helper( AdminHelper $admin_helper )
    {
        $this->admin_helper = $admin_helper;
    }

    // todo: move into a helper
    public function current_admin_colors()
    {
        // see https://core.trac.wordpress.org/browser/tags/4.2.2/src/wp-admin/includes/misc.php#L597
        global $_wp_admin_css_colors;
        $current_color = get_user_option( 'admin_color' );
        if ( empty( $current_color ) || ! isset( $_wp_admin_css_colors[ $current_color ] ) ) {
            $current_color = 'fresh';
        }
        return $_wp_admin_css_colors[ $current_color ];
    }

    public function upgrade_options()
    {
        $options = get_option( 'nooz_options', array() );
        if ( ! empty( $options ) ) {
            $map = array(
                'target'             => 'mdnooz_coverage_target',
                'boilerplate'        => 'mdnooz_release_boilerplate',
                'date_format'        => 'mdnooz_release_date_format',
                'ending'             => 'mdnooz_release_ending',
                'location'           => 'mdnooz_release_location',
                'release_slug'       => 'mdnooz_release_slug',
                'shortcode_count'    => 'mdnooz_shortcode_count',
                'shortcode_display'  => 'mdnooz_shortcode_display',
            );
            foreach ( $options as $name => $value ) {
                if ( isset( $map[$name] ) ) {
                    if ( 'ending' == $name && $this->is_truthy( $value ) ) {
                        $value = '###';
                    }
                    update_option( $map[$name], $value );
                }
            }
            delete_option( 'nooz_options' );
        }
        if ( false !== get_option( 'nooz_default_pages' ) ) {
            update_option( 'mdnooz_default_pages', get_option( 'nooz_default_pages' ) );
            delete_option( 'nooz_default_pages' );
        }
    }

    public function get_post_types() {
        return array( $this->release_post_type, $this->coverage_post_type );
    }

    public function get_release_post_type()
    {
        return $this->release_post_type;
    }

    public function get_coverage_post_type()
    {
        return $this->coverage_post_type;
    }

    public function get_release_date_format()
    {
        if ( get_option( 'mdnooz_release_date_format' ) ) {
            return wp_kses_data( strip_tags( get_option( 'mdnooz_release_date_format' ) ) );
        } else {
            return $this->get_default_date_format();
        }
    }

    public function get_shortcode_date_format()
    {
        if ( get_option( 'mdnooz_shortcode_date_format' ) ) {
            return wp_kses_data( strip_tags( get_option( 'mdnooz_shortcode_date_format' ) ) );
        } else {
            return $this->get_default_date_format();
        }
    }

    protected function get_default_date_format()
    {
        return get_option( 'date_format' );
    }

    /**
     * @codeCoverageIgnore
     */
    public function register()
    {
        $this->set_default_options();
        $this->upgrade_options();
        add_action( 'init', array( $this, 'create_cpt' ) );
        $this->create_release_metabox();
        $this->create_coverage_metabox();
        $this->init_admin_menus();
        $this->init_default_pages();
        add_filter( 'the_content', array( $this, '_filter_release_content' ) );
        add_shortcode( 'nooz', array( $this, '_list_shortcode' ) );
        // nooz-release, nooz-coverage are depreciated
        add_shortcode( 'nooz-release', array( $this, '_list_shortcode' ) );
        add_shortcode( 'nooz-coverage', array( $this, '_list_shortcode' ) );
        add_action( 'admin_enqueue_scripts', array( $this, '_admin_styles_and_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, '_front_styles_and_scripts' ) );
        // run after themes have enabled supported features
        add_action( 'after_setup_theme', array( $this, '_enable_featured_image_support' ), 99 );
        // flush rewrite rules after everything has been setup
        // 'updated_option' action is run and then redirects
        add_action( 'updated_option', array( $this, '_flush_rewite_rules_on_option_update' ) );
        // "admin_init" runs after "init"
        add_action( 'admin_init', array( $this, '_flush_rewrite_rules' ) );
        add_filter( 'post_type_link', array( $this, '_coverage_permalink' ), 10, 2 );
    }

    function _coverage_permalink( $permalink, $post ) {
        if ( isset( $post->post_type ) && $this->coverage_post_type == $post->post_type ) {
            $meta = get_post_meta( $post->ID, '_nooz', TRUE );
            if( isset( $meta['link'] ) ) {
                $permalink = $meta['link'];
            }
        }
        return $permalink;
    }

    function _enable_featured_image_support() {
        // enable support if NOT already enabled by the theme
        if ( ! current_theme_supports( 'post-thumbnails' ) ) {
            add_theme_support( 'post-thumbnails', $this->get_post_types() );
        }
    }

    public function _flush_rewite_rules_on_option_update( $updated_option_name )
    {
        $option_names = array(
            'mdnooz_coverage_slug',
            'mdnooz_release_slug',
        );
        if ( in_array( $updated_option_name, $option_names ) ) {
            delete_option( 'mdnooz_flush_rewrite_rules' );
        }
    }

    public function _flush_rewrite_rules()
    {
        // this will run once on install and everytime the 'mdnooz_flush_rewrite_rules' option is deleted
        if ( false === get_option( 'mdnooz_flush_rewrite_rules' ) ) {
            flush_rewrite_rules();
            update_option( 'mdnooz_flush_rewrite_rules', true );
        }
    }

    public function _admin_styles_and_scripts()
    {
        wp_enqueue_media();
        wp_enqueue_style( 'mdnooz-admin', plugins_url( 'inc/assets/admin.css', $this->get_plugin_file() ), array(), $this->version() );
        wp_enqueue_script( 'mdnooz-admin', plugins_url( 'inc/assets/admin.js', $this->get_plugin_file() ), array( 'jquery' ), $this->version(), true );
    }

    public function _front_styles_and_scripts()
    {
        wp_enqueue_style( 'mdnooz-front', plugins_url( 'inc/assets/front.css', $this->get_plugin_file() ), array(), $this->version() );
    }

    public function init_admin_menus()
    {
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }
        add_action( 'admin_init', array ( $this, '_config_admin_menus' ) );
        add_action( 'admin_menu', array ( $this, '_create_admin_menus' ) );
    }

    public function init_default_pages()
    {
        $option = 'mdnooz_default_pages';
        $option_val = get_option( $option );
        if ( false === $option_val ) {
            if ( isset( $_GET[$option] ) ) {
                update_option( $option, $_GET[$option] );
                if ( 'publish' == $_GET[$option] ) {
                    add_action( 'admin_init', array ( $this, '_create_default_pages' ) );
                }
            } else {
                $url = admin_url( 'edit.php?post_status=publish&post_type=page&' . $option . '=' );
                $message = '<strong>Nooz:</strong> ' . sprintf( __( 'Create default press pages? Yes, <a href="%s">create pages</a>. No, <a href="%s">dismiss</a>.', 'mdnooz' ), $url . 'publish', $url . 'dismiss' );
                $notice = $this->admin_helper->create_notice( $message, 'update-nag', 'edit_pages' );
                $notice->register();
            }
        }
        return $option_val;
    }

    public function _create_default_pages()
    {
        $format = "<h2>%s</h2>\n[nooz type=\"release\"]\n<p class=\"nooz-more-link\"><a href=\"/" . get_option( 'mdnooz_release_slug' ) . "/\">%s</a></p>\n<h2>%s</h2>\n[nooz type=\"coverage\"]\n<p class=\"nooz-more-link\"><a href=\"/" . get_option( 'mdnooz_coverage_slug' ) . "/\">%s</a></p>";
        $args = array ( __( 'Press Releases', 'mdnooz' ), __( 'More Press Releases ...', 'mdnooz' ), __( 'Press Coverage', 'mdnooz' ), __( 'More Press Coverage ...', 'mdnooz' ) );
        $post_id = wp_insert_post( array (
            'post_content' => vsprintf( $format, $args ),
            'post_title' => __( 'News', 'mdnooz' ),
            'post_name' => 'news',
            'post_type' => 'page',
            'post_status' => 'publish',
        ) );
        wp_insert_post( array (
            'post_content' => '[nooz type="release" use_pagination="yes"]',
            'post_title' => __( 'Press Releases', 'mdnooz' ),
            'post_name' => 'press-releases',
            'post_type' => 'page',
            'post_parent' => $post_id,
            'post_status' => 'publish',
        ) );
        wp_insert_post( array (
            'post_content' => '[nooz type="coverage" use_pagination="yes"]',
            'post_title' => __( 'Press Coverage', 'mdnooz' ),
            'post_name' => 'press-coverage',
            'post_type' => 'page',
            'post_parent' => $post_id,
            'post_status' => 'publish',
        ) );
    }

    public function _filter_release_content( $content )
    {
        global $post;
        if ( $this->get_release_post_type() == $post->post_type ) {
            $meta = get_post_meta( $post->ID, '_' . $this->get_release_post_type(), true );
            $data = array(
                'subheadline' => isset( $meta['subheadline'] ) ? $meta['subheadline'] : null,
                'location' => get_option( 'mdnooz_release_location' ),
                'date' => get_the_date( $this->get_release_date_format() ),
                'boilerplate' => trim( wpautop( get_option( 'mdnooz_release_boilerplate' ) ) ),
                'ending' => get_option( 'mdnooz_release_ending' ),
                'content' => $content,
            );
            $output = $this->get_templating()->render( 'release-default.html', $data );
            $content = apply_filters( 'nooz_release', $output, $data );
        }
        return $content;
    }

    public function _config_admin_menus()
    {
        // TODO: _slug vars need a filter to sanitize the URLs
        $active_tab = $this->get_active_tab();
        switch( $active_tab ) {
            case 'coverage':
                register_setting( 'settings', 'mdnooz_coverage_default_image');
                register_setting( 'settings', 'mdnooz_coverage_slug' );
                register_setting( 'settings', 'mdnooz_coverage_target' );
                break;
            case 'release':
                register_setting( 'settings', 'mdnooz_release_default_image' );
                register_setting( 'settings', 'mdnooz_release_slug' );
                register_setting( 'settings', 'mdnooz_release_location' );
                register_setting( 'settings', 'mdnooz_release_date_format' );
                register_setting( 'settings', 'mdnooz_release_boilerplate' );
                register_setting( 'settings', 'mdnooz_release_ending' );
                break;
            case 'general':
                register_setting( 'settings', 'mdnooz_shortcode_more_link' );
                register_setting( 'settings', 'mdnooz_shortcode_next_link' );
                register_setting( 'settings', 'mdnooz_shortcode_previous_link' );
                register_setting( 'settings', 'mdnooz_shortcode_use_more_link' );
                register_setting( 'settings', 'mdnooz_shortcode_use_pagination' );
                register_setting( 'settings', 'mdnooz_shortcode_count' );
                register_setting( 'settings', 'mdnooz_shortcode_date_format' );
                register_setting( 'settings', 'mdnooz_shortcode_display' );
                register_setting( 'settings', 'mdnooz_shortcode_use_excerpt' );
                break;
        }
        $this->settings->register( 'settings', null, array(
            'template' => 'settings.html',
            'title' => __( 'Settings', 'mdnooz' ),
            'settings_errors' => $this->settings->get_settings_errors(),
            'settings_fields' => $this->settings->get_settings_fields( 'settings' ),
            'submit' => $this->settings->get_submit_button(),
        ) );
        $this->settings->register( 'tabs', 'settings', array(
            'template' => 'tabs.html',
            'active' => $active_tab,
        ) );
        $this->settings->register( 'general_tab', 'tabs', array(
            'id' => 'general',
            'title' => __( 'Shortcode', 'mdnooz' ),
            'description' => __( 'These options modify the default behavior of the <code>nooz</code> shortcode. You can modify each option per-shortcode.', 'mdnooz' ),
            'link' => $this->get_tab_url( 'general' ),
        ) );
        $this->settings->register( 'general_default_section', 'general_tab', array(
            'template' => 'fields.html',
        ) );
        $this->settings->register( 'shortcode_count_field', 'general_default_section', array(
            'class' => 'md-tiny-field',
            'template' => 'field-number.html',
            'label' => __( 'Display Count', 'mdnooz' ),
            'description' => __( 'The number of press releases or coverage to display.', 'mdnooz' ),
            'name' => 'mdnooz_shortcode_count',
            'value' => get_option( 'mdnooz_shortcode_count' ),
            'min' => 1,
        ) );
        $this->settings->register( 'shortcode_display_field', 'general_default_section', array(
            'class' => 'md-tiny-field',
            'template' => 'field-select.html',
            'label' => _x( 'Display Type', 'the type of display selected, group or list', 'mdnooz' ),
            'description' => __( 'How to display press releases and coverage.', 'mdnooz' ),
            'name' => 'mdnooz_shortcode_display',
            'value' => get_option( 'mdnooz_shortcode_display' ),
            'options' => array (
                array (
                    'label' => 'List',
                    'value' => 'list',
                ),
                array (
                    'label' => 'Group',
                    'value' => 'group',
                ),
            ),
        ) );
        $this->settings->register( 'shortcode_date_format_field', 'general_default_section', array(
            'template' => 'field-text.html',
            'class' => 'md-tiny-field',
            'name' => 'mdnooz_shortcode_date_format',
            'label' => __( 'Date Format', 'mdnooz' ),
            'description' => sprintf( __( 'The date appearing for each press release and coverage. Leave this blank to use the <a href="%s">default date format</a> as set in WordPress. Learn more about <a href="%s" target="_blank">formatting dates</a>.', 'mdnooz' ), admin_url( 'options-general.php' ), 'https://codex.wordpress.org/Formatting_Date_and_Time' ),
            'value' => get_option( 'mdnooz_shortcode_date_format' ),
            'placeholder' => $this->get_default_date_format(),
        ) );
        $this->settings->register( 'shortcode_use_excerpt_field', 'general_default_section', array(
            'template' => 'field-checkbox.html',
            'name' => 'mdnooz_shortcode_use_excerpt',
            'label' => __( 'Display Excerpts', 'mdnooz' ),
            'after_field' => __( 'Enable press release and coverage excerpts.', 'mdnooz' ),
            'description' => __( 'An excerpt will only be used if available for the specific press release or coverage.', 'mdnooz' ),
            'checked' => $this->is_truthy( get_option( 'mdnooz_shortcode_use_excerpt' ) ),
        ) );
        $this->settings->register( 'shortcode_use_more_link_field', 'general_default_section', array(
            'template' => 'field-checkbox.html',
            'name' => 'mdnooz_shortcode_use_more_link',
            'label' => __( 'Read More Links', 'mdnooz' ),
            'description' => __( 'Add a link to the full press release or coverage.', 'mdnooz' ),
            'after_field' => __( 'Enable a "read more" link for each list item.', 'mdnooz' ),
            'checked' => $this->is_truthy( get_option( 'mdnooz_shortcode_use_more_link' ) ),
        ) );
        $this->settings->register( 'shortcode_more_link_text_field', 'general_default_section', array(
            'template' => 'field-text.html',
            'class' => 'md-connected-field',
            'style' => 'display:none;',
            'name' => 'mdnooz_shortcode_more_link',
            'placeholder' => __( 'Read More', 'mdnooz' ),
            'description' => __( 'Default text for the read more link.', 'mdnooz' ),
            'value' => get_option( 'mdnooz_shortcode_more_link' ),
            'dependency' => array(
                'name' => 'mdnooz_shortcode_use_more_link',
                'value' => 'yes'
            ),
        ) );
        $this->settings->register( 'shortcode_use_pagination_link_field', 'general_default_section', array(
            'template' => 'field-checkbox.html',
            'name' => 'mdnooz_shortcode_use_pagination',
            'label' => __( 'Pagination Links', 'mdnooz' ),
            'after_field' => __( 'Enable pagination links.', 'mdnooz' ),
            'checked' => $this->is_truthy( get_option( 'mdnooz_shortcode_use_pagination' ) ),
            'description' => __( 'Add links to navigate between pages.', 'mdnooz' ),
        ) );
        $this->settings->register( 'shortcode_pagination_link_text_field', 'general_default_section', array(
            'style' => 'display:none;',
            'class' => 'nooz-admin-pagination-links md-connected-field',
            'template' => 'field-text.html',
            'description' => __( 'Default text for the previous and next pagination links.', 'mdnooz' ),
            'dependency' => array(
                'name' => 'mdnooz_shortcode_use_pagination',
                'value' => 'yes'
            ),
            'options' => array (
                array (
                    // label is unused, but kept for translation
                    'label' => __( 'Previous Link', 'mdnooz' ),
                    'name' => 'mdnooz_shortcode_previous_link',
                    'value' => get_option( 'mdnooz_shortcode_previous_link' ),
                    'placeholder' => __( '&laquo; Previous Page', 'mdnooz' ),
                ),
                array (
                    // label is unused, but kept for translation
                    'label' => __( 'Next Link', 'mdnooz' ),
                    'name' => 'mdnooz_shortcode_next_link',
                    'value' => get_option( 'mdnooz_shortcode_next_link' ),
                    'placeholder' => __( 'Next Page &raquo;', 'mdnooz' ),
                ),
            ),
        ) );
        $this->settings->register( 'release_tab', 'tabs', array(
            'id' => 'release',
            'title' => __( 'Press Release', 'mdnooz' ),
            'link' => $this->get_tab_url( 'release' ),
        ) );
        $this->settings->register( 'release_default_section', 'release_tab', array(
            'template' => 'fields.html',
        ) );
        $this->settings->register( 'release_slug_field', 'release_default_section', array(
            'template' => 'field-text.html',
            'id' => 'md-release-slug',
            'label' => __( 'URL', 'mdnooz' ),
            'name' => 'mdnooz_release_slug',
            'description' => sprintf( __( 'The URL structure for a press release. Your browser <a href="%s" target="_blank">cache</a> may need to be cleared if you change this.', 'mdnooz' ), 'https://codex.wordpress.org/WordPress_Optimization#Caching' ),
            'value' => get_option( 'mdnooz_release_slug' ),
            'before_field' => site_url() . '/',
            'after_field' => '/{{slug}}/',
        ) );
        $this->settings->register( 'release_default_image_field', 'release_default_section', array(
            'template' => 'field-media-manager.html',
            'class' => 'md-settings-featured-image',
            'name' => 'mdnooz_release_default_image',
            'label' => __( 'Featured Image', 'mdnooz' ),
            'description' => __( 'The default featured image to use if one is not selected when editing a press release.', 'mdnooz' ),
            'value' => get_option( 'mdnooz_release_default_image' ),
            'media' => array(
                'title' => __( 'Default Featured Image', 'mdnooz' ),
                'button' => __( 'Select', 'mdnooz' ),
                'url' => wp_get_attachment_url( get_option( 'mdnooz_release_default_image' ) ),
            ),
            'labels' => array(
                'select_button' => __( 'Select Default Featured Image', 'mdnooz' ),
                'remove_button' => __( 'Remove Default Featured Image', 'mdnooz' ),
            ),
        ) );
        $this->settings->register( 'release_location_field', 'release_default_section', array(
            'template' => 'field-text.html',
            'name' => 'mdnooz_release_location',
            'label' => _x( 'Location', 'city/state', 'mdnooz' ),
            'description' => __( 'The location precedes the press release and helps to orient the reader (e.g. San Francisco, CA)', 'mdnooz' ),
            'value' => get_option( 'mdnooz_release_location' ),
        ) );
        $this->settings->register( 'release_date_format_field', 'release_default_section', array(
            'template' => 'field-text.html',
            'class' => 'md-tiny-field',
            'name' => 'mdnooz_release_date_format',
            'label' => __( 'Date Format', 'mdnooz' ),
            'description' => sprintf( __( 'The date follows the location. Leave this blank to use the <a href="%s">default date format</a> as set in WordPress. Learn more about <a href="%s" target="_blank">formatting dates</a>.', 'mdnooz' ), admin_url( 'options-general.php' ), 'https://codex.wordpress.org/Formatting_Date_and_Time' ),
            'value' => get_option( 'mdnooz_release_date_format' ),
            'placeholder' => $this->get_default_date_format(),
        ) );
        $this->settings->register( 'release_boilerplate_field', 'release_default_section', array(
            'template' => 'field-textarea.html',
            'name' => 'mdnooz_release_boilerplate',
            'label' => _x( 'Boilerplate', 'boilerplate text/content', 'mdnooz' ),
            'description' => __( 'The boilerplate is a few sentences at the end of your press release that describes your organization. This should be used consistently on press materials and written strategically, to properly reflect your organization. Using HTML in this field is allowed.', 'mdnooz' ),
            'value' => get_option( 'mdnooz_release_boilerplate' ),
        ) );
        $this->settings->register( 'release_ending_field', 'release_default_section', array(
            'template' => 'field-text.html',
            'class' => 'md-tiny-field',
            'name' => 'mdnooz_release_ending',
            'label' => _x( 'Ending', 'an ending mark/the end', 'mdnooz' ),
            'value' => get_option( 'mdnooz_release_ending' ),
            'description' => __( 'The ending mark signifies the absolute end of the press release (e.g. ###, END, XXX, -30-).', 'mdnooz' ),
        ) );
        $this->settings->register( 'coverage_tab', 'tabs', array(
            'id' => 'coverage',
            'title' => __( 'Press Coverage', 'mdnooz' ),
            'link' => $this->get_tab_url( 'coverage' ),
        ) );
        $this->settings->register( 'coverage_default_section', 'coverage_tab', array(
            'template' => 'fields.html',
        ) );
        $this->settings->register( 'coverage_slug_field', 'coverage_default_section', array(
            'template' => 'field-text.html',
            'id' => 'md-coverage-slug',
            'label' => __( 'URL', 'mdnooz' ),
            'name' => 'mdnooz_coverage_slug',
            'description' => sprintf( __( 'The URL for the press coverage list page. Your browser <a href="%s" target="_blank">cache</a> may need to be cleared if you change this.', 'mdnooz' ), 'https://codex.wordpress.org/WordPress_Optimization#Caching' ),
            'value' => get_option( 'mdnooz_coverage_slug' ),
            'before_field' => site_url() . '/',
            'after_field' => '/',
        ) );
        $this->settings->register( 'coverage_default_image_field', 'coverage_default_section', array(
            'template' => 'field-media-manager.html',
            'class' => 'md-settings-featured-image',
            'name' => 'mdnooz_coverage_default_image',
            'label' => __( 'Featured Image', 'mdnooz' ),
            'description' => __( 'The default featured image to use if one is not selected when editing a press coverage.', 'mdnooz' ),
            'value' => get_option( 'mdnooz_coverage_default_image' ),
            'media' => array(
                'title' => __( 'Default Featured Image', 'mdnooz' ),
                'button' => __( 'Select', 'mdnooz' ),
                'url' => wp_get_attachment_url( get_option( 'mdnooz_coverage_default_image' ) ),
            ),
            'labels' => array(
                'select_button' => __( 'Select Default Featured Image', 'mdnooz' ),
                'remove_button' => __( 'Remove Default Featured Image', 'mdnooz' ),
            ),
        ) );
        $this->settings->register( 'coverage_target_field', 'coverage_default_section', array(
            'template' => 'field-text.html',
            'id' => 'md-coverage-target',
            'name' => 'mdnooz_coverage_target',
            'label' => _x( 'Link Target', 'Internet Link URL target', 'mdnooz' ),
            'value' => get_option( 'mdnooz_coverage_target' ),
            'description' => __( 'Default link target for press coverage links.', 'mdnooz' ),
        ) );
    }

    public function _create_admin_menus()
    {
        global $submenu;
        $menu_slug = 'nooz';
        $parent_menu_slug = $menu_slug;
        add_menu_page( $this->title(), $this->title(), 'edit_posts', $menu_slug, null, 'dashicons-megaphone' );
        $add_new_release_text = __( 'Add New Release', 'mdnooz' );
        add_submenu_page( $parent_menu_slug, $add_new_release_text, $add_new_release_text, 'edit_posts', 'post-new.php?post_type=' . $this->release_post_type );
        // reposition "Add New" submenu item after "All Releases" submenu item
        array_splice( $submenu[$menu_slug], 1, 0, array( array_pop( $submenu[$menu_slug] ) ) );
        $add_new_coverage_text = __( 'Add New Coverage', 'mdnooz' );
        add_submenu_page( $parent_menu_slug, $add_new_coverage_text, $add_new_coverage_text, 'edit_posts', 'post-new.php?post_type=' . $this->coverage_post_type );
        $this->admin_helper->set_menu_position( 'nooz', '99.0100' );
        $title = _x( 'Settings', 'Admin settings page', 'mdnooz' );
        add_submenu_page( $parent_menu_slug, $title, $title, 'manage_options', $menu_slug, array( $this, '_render_settings_page' ) );
    }

    /**
     * @codeCoverageIgnore
     */
    public function _render_settings_page()
    {
        $config = $this->settings->build();
        echo $this->get_templating()->render( $config['template'], $config );
    }

    public function create_cpt()
    {
        // runs on "init"
        $menu_slug = 'nooz';
        $labels = array(
            'name'               => _x( 'Press Releases', 'post type general name', 'mdnooz' ),
            'singular_name'      => _x( 'Press Release', 'post type singular name', 'mdnooz' ),
            'add_new'            => _x( 'Add New', 'press release', 'mdnooz' ),
            'add_new_item'       => __( 'Add New Press Release', 'mdnooz' ),
            'new_item'           => __( 'New Page', 'mdnooz' ),
            'edit_item'          => __( 'Edit Press Release', 'mdnooz' ),
            'view_item'          => __( 'View Press Release', 'mdnooz' ),
            'all_items'          => __( 'All Releases', 'mdnooz' ),
            'not_found'          => __( 'No press releases found.', 'mdnooz' ),
            'not_found_in_trash' => __( 'No press releases found in Trash.', 'mdnooz' ),
        );
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            // show_ui=true (default) because CPT are not editable if show_ui=false
            // https://core.trac.wordpress.org/browser/tags/4.0.1/src/wp-admin/post-new.php#L14
            // https://core.trac.wordpress.org/browser/trunk/src/wp-admin/post-new.php#L14
            'show_in_menu'       => $menu_slug,
            'show_in_admin_bar'  => true,
            'rewrite'            => array( 'slug' => get_option( 'mdnooz_release_slug' ), 'with_front' => false ),
            'supports'           => array( 'title', 'editor', 'excerpt', 'author', 'revisions', 'thumbnail' ),
        );
        register_post_type( $this->release_post_type, $args );

        $labels = array(
            'name'               => _x( 'Press Coverage', 'press coverage', 'mdnooz' ),
            'singular_name'      => _x( 'Press Coverage', 'press coverage', 'mdnooz' ),
            'add_new'            => _x( 'Add New', 'press coverage', 'mdnooz' ),
            'add_new_item'       => __( 'Add New Press Coverage', 'mdnooz' ),
            'new_item'           => __( 'New Press Coverage', 'mdnooz' ),
            'edit_item'          => __( 'Edit Coverage', 'mdnooz' ),
            'view_item'          => __( 'View Coverage', 'mdnooz' ),
            'all_items'          => __( 'All Coverage', 'mdnooz' ),
            'not_found'          => __( 'No press coverage found.', 'mdnooz' ),
            'not_found_in_trash' => __( 'No press coverage found in Trash.', 'mdnooz' ),
        );
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => true,
            'has_archive'        => true,
            'show_ui'            => true, // required because public == false
            'show_in_menu'       => $menu_slug,
            'show_in_admin_bar'  => true,
            'rewrite'            => array( 'slug' => get_option( 'mdnooz_coverage_slug' ), 'with_front' => false ),
            'supports'           => array( 'title', 'excerpt', 'author', 'revisions', 'thumbnail' ),
        );
        register_post_type( $this->coverage_post_type, $args );

        // tip: use the `rewrite_rules_array` filter to view all rewrites
        // reset rewrite rules, note that when 'has_archive' => true`, the following rules are already in place we are simply overriding
        add_rewrite_rule( get_option( 'mdnooz_release_slug' ) . '/?$', 'index.php?pagename=' . get_option( 'mdnooz_release_slug' ) . '&paged', 'top' );
        add_rewrite_rule( get_option( 'mdnooz_release_slug' ) . '/page/([0-9]{1,})/?$', 'index.php?pagename=' . get_option( 'mdnooz_release_slug' ) . '&paged=$matches[1]', 'top' );
        add_rewrite_rule( get_option( 'mdnooz_coverage_slug' ) . '/?$', 'index.php?pagename=' . get_option( 'mdnooz_coverage_slug' ) . '&paged', 'top' );
        add_rewrite_rule( get_option( 'mdnooz_coverage_slug' ) . '/page/([0-9]{1,})/?$', 'index.php?pagename=' . get_option( 'mdnooz_coverage_slug' ) . '&paged=$matches[1]', 'top' );
        // instead of returning an empty feed, return 404
        add_rewrite_rule( $this->release_post_type . '/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?error=404', 'top' );
        add_rewrite_rule( $this->release_post_type . '/(feed|rdf|rss|rss2|atom)/?$', 'index.php?error=404', 'top' );
        add_rewrite_rule( $this->coverage_post_type . '/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?error=404', 'top' );
        add_rewrite_rule( $this->coverage_post_type . '/(feed|rdf|rss|rss2|atom)/?$', 'index.php?error=404', 'top' );
    }

    public function create_release_metabox()
    {
        $options = array(
            'types' => array( $this->release_post_type ),
            'lock' => 'after_post_title',
            'hide_title' => true
        );
        $this->admin_helper->create_meta_box( '_' . $this->release_post_type, 'Subtitle', dirname( __FILE__ ) . '/templates/subheadline-meta.php', $options );
    }

    public function create_coverage_metabox()
    {
        $options = array(
            'types' => array( $this->coverage_post_type )
        );
        // TODO: consider renaming _nooz to _nooz_coverage .. needs backward-compatibility consideration
        $this->admin_helper->create_meta_box( '_nooz', 'Details', dirname( __FILE__ ) . '/templates/coverage-meta.php', $options );
    }

    public function _list_shortcode( $atts, $content = null, $tag = null )
    {
        $data = $this->get_list_shortcode_data( $atts, $content, $tag );
        $output = $this->get_templating()->render( 'list-default.html', $data );
        return apply_filters( 'nooz_shortcode', $output, $data );
    }

    public function get_list_shortcode_data( $atts, $content, $tag )
    {
        // using $tag is legacy for depreciated shortcodes flavors: nooz-coverage, nooz-release
        $post_type = isset( $atts['type'] ) ? $atts['type'] : $tag ;
        if ( stristr( $post_type, 'coverage' ) ) {
            $post_type = $this->coverage_post_type;
        } else if ( stristr( $post_type, 'release' ) || 'nooz' === $post_type ) {
            $post_type = $this->release_post_type;
        }
        $default_atts = array(
            'class' => '',
            'count' => get_option( 'mdnooz_shortcode_count' ),
            'date_format' => $this->get_shortcode_date_format(),
            'display' => get_option( 'mdnooz_shortcode_display' ), // list, group
            'featured_image' => '',
            'more_link' => get_option( 'mdnooz_shortcode_more_link' ),
            'previous_link' => get_option( 'mdnooz_shortcode_previous_link' ),
            'next_link' => get_option( 'mdnooz_shortcode_next_link' ),
            'target' => '',
            'use_more_link' => get_option( 'mdnooz_shortcode_use_more_link' ),
            'use_excerpt' => get_option( 'mdnooz_shortcode_use_excerpt' ),
            'use_pagination' => get_option( 'mdnooz_shortcode_use_pagination' ),
        );
        // filter: shortcode_atts_nooz
        // https://core.trac.wordpress.org/browser/tags/4.4/src/wp-includes/shortcodes.php#L530
        $atts = shortcode_atts( $default_atts, $atts, 'nooz' );
        if ( $this->coverage_post_type == $post_type ) {
            $atts['target'] = $atts['target'] ? $atts['target'] : get_option( 'mdnooz_coverage_target' );
        }
        $data = array(
            'type' => str_replace( 'nooz_', '', $post_type ),
            'post_type' => $post_type,
            'css_classes' => $atts['class'],
            'items' => array(),
            'groups' => array(),
        );
        do_action( 'nooz_shortcode_pre_query', $post_type );
        $q = new \WP_Query( apply_filters( 'nooz_shortcode_query_options', array(
            'post_type' => $post_type,
            'posts_per_page' => ( '*' === $atts['count'] ) ? -1 : $atts['count'],
            'paged' => get_query_var( 'paged', 1 ),
            'orderby' => 'menu_order post_date',
        ) ) );
        do_action( 'nooz_shortcode_post_query', $post_type );
        if ( $q->have_posts() ) {
            global $post;
            while ( $q->have_posts() ) {
                // TIP: useful action: the_post
                $q->the_post();
                $featured_image_url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
                // TODO: consider creating a decorator for $post object .. eg: $post_obj->get_featured_image_url()
                if ( $this->coverage_post_type == $post_type ) {
                    $meta = get_post_meta( get_the_ID(), '_nooz', true );
                    $featured_image_url = $featured_image_url ?: wp_get_attachment_url( get_option( 'mdnooz_coverage_default_image' ) );
                } else if ( $this->release_post_type == $post_type ) {
                    $featured_image_url = $featured_image_url ?: wp_get_attachment_url( get_option( 'mdnooz_release_default_image' ) );
                }
                $featured_image_url = $featured_image_url ?: $atts['featured_image'];
                $item = array_merge( (array) $post, array(
                    'title' => get_the_title( get_the_ID() ),
                    'link' => get_permalink( get_the_ID() ),
                    'source' => isset( $meta['source'] ) ? $meta['source'] : '',
                    'target' => $atts['target'],
                    'post_date_formatted' => get_the_date( $atts['date_format'], get_the_ID() ),
                    'post_thumbnail_html' => get_the_post_thumbnail( get_the_ID() ),
                    'image' => $featured_image_url,
                    'priority' => get_post_meta( get_the_ID(), '_mdnooz_post_priority', true ),
                ) );
                if ( $this->is_truthy( $atts['use_excerpt'] ) ) {
                    $item['excerpt'] = $post->post_excerpt;
                }
                if ( 'group' == $atts['display'] ) {
                    $year = mysql2date( 'Y', $post->post_date );
                    //$month = mysql2date( 'n', $post->post_date );
                    if ( ! isset( $data['groups'][$year] ) ) {
                        $data['groups'][$year] = array(
                            'title' => $year,
                            'items' => array(),
                        );
                    }
                    $data['groups'][$year]['items'][] = $item;
                } else {
                    $data['items'][] = $item;
                }
            }
            // sort in decending order
            if ( 'group' == $atts['display'] ) {
                krsort( $data['groups'], SORT_NUMERIC );
            }
        }
        if ( $this->is_truthy( $atts['use_more_link'] ) ) {
            $data['more_link'] = $atts['more_link'] ?: __( 'Read More', 'mdnooz' );
        }
        if ( $this->is_truthy( $atts['use_pagination'] ) ) {
            // TIP: useful filter: previous_posts_link_attributes
            $data['previous_posts_link_html'] = get_previous_posts_link( $atts['previous_link'] ?: __( '&laquo; Previous Page', 'mdnooz' ) );
            // determine if link should/should-not be made available, previous/next_posts functions do not check
            if ( $data['previous_posts_link_html'] ) {
                $data['previous_posts_link'] = previous_posts( false );
            }
            // TIP: useful filter: next_posts_link_attributes
            $data['next_posts_link_html'] = get_next_posts_link( $atts['next_link'] ?: __( 'Next Page &raquo;', 'mdnooz' ) , $q->max_num_pages );
            if ( $data['next_posts_link_html'] ) {
                $data['next_posts_link'] = next_posts( $q->max_num_pages, false );
            }
        }
        wp_reset_postdata();
        return $data;
    }

    // TODO: move this into base
    protected function is_truthy( $val )
    {
        $truthy = array( 'on', 'yes', 'yup', 'y', '1', 1, 'true', 'enable', 'enabled', 'ok' );
        return true === $val || in_array( strtolower( $val ), $truthy );
    }

    public function uninstall()
    {
        $this->delete_option_with_prefix( 'nooz' );
        $this->delete_option_with_prefix( 'mdnooz' );
        flush_rewrite_rules();
    }
}
