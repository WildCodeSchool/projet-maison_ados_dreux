<?php

namespace MightyDev\WordPress\Plugin;

class NoozContextualHelp extends Core
{
    protected $active_help_tab;

    public function __construct( $plugin_file )
    {
        $this->set_plugin_file( $plugin_file );
    }

    /**
     * @codeCoverageIgnore
     */
    public function register()
    {
        add_action( 'admin_head', array( $this, '_add_help' ) );
        add_action( 'admin_enqueue_scripts', array( $this, '_admin_styles_and_scripts' ), 9 );
    }

    public function _admin_styles_and_scripts()
    {
        wp_enqueue_style( 'mdnooz-highlightjs-github-gist', plugins_url( 'inc/vendor/highlightjs/styles/github-gist.css', $this->get_plugin_file() ), array(), '8.8.0' );
        wp_enqueue_script( 'mdnooz-highlightjs', plugins_url( 'inc/vendor/highlightjs/highlight.pack.min.js', $this->get_plugin_file() ), array(), '8.8.0', true );
    }

    protected function is_post_type( $post_type )
    {
        if ( isset( $_GET['post_type'] ) ) {
            return $post_type == $_GET['post_type'];
        } else {
            global $post;
            return isset( $post->post_type ) && $post_type == $post->post_type;
        }
    }

    protected function is_admin_page( $page, $tab = NULL )
    {
        global $pagenow;
        $is_page = isset( $pagenow ) && 'admin.php' == $pagenow && isset( $_GET['page'] ) && $page == $_GET['page'];
        if ( ! is_null( $tab ) ) {
            return $is_page && isset( $_GET['tab'] ) && $tab == $_GET['tab'];
        }
        return $is_page;
    }

    protected function is_post_type_list_page()
    {
        global $pagenow;
        return isset( $pagenow ) && 'edit.php' == $pagenow;
    }

    protected function is_post_type_add_page()
    {
        global $pagenow;
        return isset( $pagenow ) && 'post-new.php' == $pagenow;
    }

    protected function is_post_type_edit_page()
    {
        global $pagenow;
        return isset( $pagenow ) && 'post.php' == $pagenow;
    }

    public function is_release_list_page()
    {
        return $this->is_post_type( 'nooz_release' ) && $this->is_post_type_list_page();
    }

    public function is_release_add_page()
    {
        return $this->is_post_type( 'nooz_release' ) && $this->is_post_type_add_page();
    }

    public function is_release_edit_page()
    {
        return $this->is_post_type( 'nooz_release' ) && $this->is_post_type_edit_page();
    }

    public function is_coverage_list_page()
    {
        return $this->is_post_type( 'nooz_coverage' ) && $this->is_post_type_list_page();
    }

    public function is_coverage_add_page()
    {
        return $this->is_post_type( 'nooz_coverage' ) && $this->is_post_type_add_page();
    }

    public function is_coverage_edit_page()
    {
        return $this->is_post_type( 'nooz_coverage' ) && $this->is_post_type_edit_page();
    }

    public function is_settings_page( $tab = null )
    {
        return $this->is_admin_page( 'nooz', $tab );
    }

    public function is_plugin_page()
    {
        return
            $this->is_release_list_page()
            || $this->is_release_add_page()
            || $this->is_release_edit_page()
            || $this->is_coverage_list_page()
            || $this->is_coverage_add_page()
            || $this->is_coverage_edit_page()
            || $this->is_settings_page();
    }

    public function set_active_help_tab( $tab )
    {
        $this->active_help_tab = $tab;
    }

    public function render_active_help_tab()
    {
        // https://core.trac.wordpress.org/browser/tags/4.2.2/src/wp-admin/includes/screen.php#L862
        if ( ! empty( $this->active_help_tab ) ) {
            ?><script>
                jQuery(function($) {
                    setTimeout(function() {
                        $('#tab-link-<?php echo $this->active_help_tab; ?> a').trigger('click');
                        $('#contextual-help-wrap').addClass('nooz-contextual-help');
                    }, 500);
                });
            </script><?php
        }
    }

    public function _add_help()
    {
        if ( $this->is_plugin_page() ) {
            $screen = get_current_screen();
            $screen->add_help_tab( array(
                'id' => 'nooz-general',
                'title' => __( 'Shortcode Options', 'mdnooz' ),
                'content' => file_get_contents( __DIR__ . '/help/list-options.html' )
            ) );
            $screen->add_help_tab( array(
                'id' => 'nooz-release',
                'title' => __( 'Press Release Options', 'mdnooz' ),
                'content' => file_get_contents( __DIR__ . '/help/release-options.html' ),
            ) );
            $screen->add_help_tab( array(
                'id' => 'nooz-coverage',
                'title' => __( 'Press Coverage Options', 'mdnooz' ),
                'content' => file_get_contents( __DIR__ . '/help/coverage-options.html' )
            ) );
            $screen->add_help_tab( array(
                'id' => 'nooz-shortcodes',
                'title' => __( 'Shortcode Usage', 'mdnooz' ),
                'content' => file_get_contents( __DIR__ . '/help/shortcodes.html' )
            ) );
            $screen->add_help_tab( array(
                'id' => 'nooz-list-output',
                'title' => __( 'List Output', 'mdnooz' ),
                'content' => file_get_contents( __DIR__ . '/help/list-output.html' )
            ) );
            $screen->add_help_tab( array(
                'id' => 'nooz-release-output',
                'title' => __( 'Press Release Output', 'mdnooz' ),
                'content' => file_get_contents( __DIR__ . '/help/release-output.html' )
            ) );
            $screen->set_help_sidebar(
                sprintf( '<p><strong>%s</strong></p>', __( 'For more information:', 'mdnooz' ) ) .
                sprintf( '<p><a href="%s" target="_blank">%s</a></p>', 'https://wordpress.org/plugins/nooz/faq/', __( 'Frequently Asked Questions', 'mdnooz' ) ) .
                sprintf( '<p><a href="%s" target="_blank">%s</a></p>', 'https://wordpress.org/support/plugin/nooz/', __( 'Support Forums', 'mdnooz' ) )
            );
            // release list, coverage list
            $this->set_active_help_tab( 'nooz-shortcodes' );
            if ( $this->is_settings_page() ) {
                $this->set_active_help_tab( 'nooz-general' );
            }
            if ( $this->is_settings_page( 'release' ) || $this->is_release_add_page() || $this->is_release_edit_page() ) {
                $this->set_active_help_tab( 'nooz-release' );
            }
            if ( $this->is_settings_page( 'coverage' ) || $this->is_coverage_add_page() || $this->is_coverage_edit_page() ) {
                $this->set_active_help_tab( 'nooz-coverage' );
            }
            $this->render_active_help_tab();
        }
    }
}
