<?php

class TLPteam
{
    public $options;

	function __construct(){

        $this->options = array(
            'settings' => 'tlp_team_settings',
            'version'  => '1.3',
            'feature_img_size' => 'team-thumb',
            'installed_version' => 'tlp_team_installed_version'
        );

        $this->post_type = 'team';
        $settings = get_option($this->options['settings']);
        $this->post_type_slug = isset($settings['slug']) ? ($settings['slug'] ? sanitize_title_with_dashes($settings['slug']) : 'team' ) : 'team';
        $this->incPath       = dirname( __FILE__ );
        $this->functionsPath    = $this->incPath . '/functions/';
        $this->classesPath		= $this->incPath . '/classes/';
        $this->widgetsPath		= $this->incPath . '/widgets/';
        $this->viewsPath		= $this->incPath . '/views/';

        $this->assetsUrl        = TLP_TEAM_PLUGIN_URL  . '/assets/';
        $this->TPLloadClass( $this->classesPath );

        $this->defaultSettings = array(
            'primary_color' => '#0367bf',
            'feature_img' => array(
                'width' => 400,
                'height'=> 400
            ),
            'slug' => 'team',
            'link_detail_page' => 'yes',
            'custom_css' => null
        );


        register_activation_hook(TLP_TEAM_PLUGIN_ACTIVE_FILE_NAME, array($this, 'activate'));
        register_deactivation_hook(TLP_TEAM_PLUGIN_ACTIVE_FILE_NAME, array($this, 'deactivate'));

	}
    public function activate() {
        flush_rewrite_rules();
        $this->insertDefaultData();
    }

    public function deactivate() {
        flush_rewrite_rules();
    }


	function TPLloadClass($dir){
		if (!file_exists($dir)) return;

            $classes = array();

            foreach (scandir($dir) as $item) {
                if( preg_match( "/.php$/i" , $item ) ) {
                    require_once( $dir . $item );
                    $className = str_replace( ".php", "", $item );
                    $classes[] = new $className;
                }
            }

            if($classes){
            	foreach( $classes as $class )
            	    $this->objects[] = $class;
            }
	}

    function loadWidget($dir){
        if (!file_exists($dir)) return;
        foreach (scandir($dir) as $item) {
            if( preg_match( "/.php$/i" , $item ) ) {
                require_once( $dir . $item );
                $class = str_replace( ".php", "", $item );

                if (method_exists($class, 'register_widget')) {
                    $caller = new $class;
                    $caller->register_widget();
                }
                else {
                    register_widget($class);
                }
            }
        }
    }


	 function render( $viewName, $args = array()){
        global $TLPteam;

        $viewPath = $TLPteam->viewsPath . $viewName . '.php';
        if( !file_exists( $viewPath ) ) return;

        if( $args ) extract($args);
        $pageReturn = include $viewPath;
        if( $pageReturn AND $pageReturn <> 1 )
            return $pageReturn;
        if( @$html ) return $html;
    }


	/**
     * Dynamicaly call any  method from models class
     * by pluginFramework instance
     */
    function __call( $name, $args ){
        if( !is_array($this->objects) ) return;
        foreach($this->objects as $object){
            if(method_exists($object, $name)){
                $count = count($args);
                if($count == 0)
                    return $object->$name();
                elseif($count == 1)
                    return $object->$name($args[0]);
                elseif($count == 2)
                    return $object->$name($args[0], $args[1]);
                elseif($count == 3)
                    return $object->$name($args[0], $args[1], $args[2]);
                elseif($count == 4)
                    return $object->$name($args[0], $args[1], $args[2], $args[3]);
                elseif($count == 5)
                    return $object->$name($args[0], $args[1], $args[2], $args[3], $args[4]);
                elseif($count == 6)
                    return $object->$name($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
            }
        }
    }

    private function insertDefaultData()
    {
        global $TLPteam;
        update_option($TLPteam->options['installed_version'],$TLPteam->options['version']);
        if(!get_option($TLPteam->options['settings'])){
            update_option( $TLPteam->options['settings'], $TLPteam->defaultSettings);
        }
    }

}

global $TLPteam;
if( !is_object( $TLPteam ) ) $TLPteam = new TLPteam;
