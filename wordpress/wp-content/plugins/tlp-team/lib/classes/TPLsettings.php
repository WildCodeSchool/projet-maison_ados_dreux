<?php

/**
*
*/
class TPLsettings
{

    function __construct()
    {
        add_action( 'init', array($this, 'tlp_pluginInit') );
        add_action( 'wp_ajax_tlpTeamSettings', array($this, 'tlpTeamSettings'));
        add_action( 'admin_menu' , array($this, 'tlp_menu_register'));
        add_filter( 'plugin_action_links_' . TLP_TEAM_PLUGIN_ACTIVE_FILE_NAME, array($this, 'tlp_team_marketing') );
    }

    function tlp_team_marketing($links){
        $links[] = '<a target="_blank" href="'. esc_url( 'http://demo.radiustheme.com/wordpress/plugins/tlp-team/' ) .'">Demo</a>';
        $links[] = '<a target="_blank" href="'. esc_url( 'https://radiustheme.com/how-to-setup-configure-tlp-team-free-version-for-wordpress/' ) .'">Documentation</a>';
        $links[] = '<a target="_blank" href="'. esc_url( 'https://radiustheme.com/tlp-team-pro-for-wordpress/' ) .'">Get Pro</a>';
        return $links;
    }

    function tlpTeamSettings(){
        global $TLPteam;

        $error = true;
        if($TLPteam->verifyNonce()){
            unset($_REQUEST['action']);
            unset($_REQUEST['tlp_nonce']);
            unset($_REQUEST['_wp_http_referer']);
            update_option( $TLPteam->options['settings'], $_REQUEST);
            flush_rewrite_rules();
            $response = array(
                    'error'=> $error,
                    'msg' => __('Settings successfully updated',TLP_TEAM_SLUG)
                );
        }else{
            $response = array(
                    'error'=> true,
                    'msg' => __('Security Error !!',TLP_TEAM_SLUG)
                );
        }
        wp_send_json( $response );
        die();

    }

    function tlp_pluginInit(){
        $this->load_plugin_textdomain();
        global $TLPteam;
        $settings = get_option($TLPteam->options['settings']);
        $width = isset($settings['feature_img']['width']) ? ($settings['feature_img']['width'] ? (int) $settings['feature_img']['width'] : 400) : 400;
        $height = isset($settings['feature_img']['height']) ? ($settings['feature_img']['height'] ? (int) $settings['feature_img']['height'] : 400) : 400;
        add_image_size( $TLPteam->options['feature_img_size'], $width, $height, true );
    }


    function tlp_menu_register() {
        $page = add_submenu_page( 'edit.php?post_type=team', __('TLP Team Settings', TLP_TEAM_SLUG), __('Settings', TLP_TEAM_SLUG), 'administrator', 'tlp_team_settings', array($this, 'tlp_team_settings') );

        add_action('admin_print_styles-' . $page, array( $this,'tlp_style'));
        add_action('admin_print_scripts-'. $page, array( $this,'tlp_script'));

    }

    function tlp_style(){
        global $TLPteam;
        wp_enqueue_style( 'tpl_css_settings', $TLPteam->assetsUrl . 'css/settings.css');
    }

    function tlp_script(){
        global $TLPteam;
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'tpl_js_settings',  $TLPteam->assetsUrl. 'js/settings.js', array('jquery','wp-color-picker'), '', true );
        $nonce = wp_create_nonce( $TLPteam->nonceText() );
        wp_localize_script( 'tpl_js_settings', 'tpl_var', array('tlp_nonce' => $nonce) );
    }

    function tlp_team_settings(){
        global $TLPteam;
        $TLPteam->render('settings');
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since 0.1.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( TLP_TEAM_SLUG, FALSE,  TLP_TEAM_LANGUAGE_PATH );
    }

}
