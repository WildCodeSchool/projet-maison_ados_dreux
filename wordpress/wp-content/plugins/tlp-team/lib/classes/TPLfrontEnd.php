<?php
if( !class_exists( 'TPLfrontEnd' ) ) :

	class TPLfrontEnd {

        function __construct(){
            add_action( 'wp_enqueue_scripts', array($this, 'tlp_front_end') );
            add_filter( 'the_content', array($this,'team_content') );
            add_action( 'wp_head', array($this, 'custom_css') );
        }

        function custom_css(){
            $html = null;
            global $TLPteam;
            $settings = get_option($TLPteam->options['settings']);
            $pc = (isset($settings['primary_color']) ? ($settings['primary_color'] ? $settings['primary_color'] : '#0367bf' ) : '#0367bf');
            $html .= "<style type='text/css'>";
            $html .= '.tlp-team .short-desc, .tlp-team .tlp-team-isotope .tlp-content, .tlp-team .button-group .selected, .tlp-team .layout1 .tlp-content, .tlp-team .tpl-social a, .tlp-team .tpl-social li a.fa {';
                $html .= 'background: '.$pc;
            $html .= '}';

            $html .= (isset($settings['custom_css']) ? ($settings['custom_css'] ? "{$settings['custom_css']}" : null) : null );

            $html .= "</style>";
             echo $html;
        }

	function tlp_front_end(){
            global $TLPteam;
            wp_enqueue_style( 'tlp-fontawsome', $TLPteam->assetsUrl .'css/font-awesome/css/font-awesome.min.css' );
            wp_enqueue_style( 'tlpstyle', $TLPteam->assetsUrl . 'css/tlpstyle.css' );
            wp_enqueue_script( 'tpl-team-isotope-js', $TLPteam->assetsUrl . 'js/isotope.pkgd.js', array('jquery'), '2.2.2', true);
            wp_enqueue_script( 'tpl-team-isotope-imageload-js', $TLPteam->assetsUrl . 'js/imagesloaded.pkgd.min.js', array('jquery'), '3.2.0', true);
            wp_enqueue_script( 'tpl-team-front-end', $TLPteam->assetsUrl . 'js/front-end.js', null, null, true);
        }

        function team_content($content){
            global $post;

            $tel = get_post_meta( $post->ID, 'telephone', true );
            $loc = get_post_meta( $post->ID, 'location', true );
            $email = get_post_meta( $post->ID, 'email', true );
            $url = get_post_meta( $post->ID, 'web_url', true );

            $html = null;
            $html .="<div class='tlp-single-details tlp-team'>";
            $html .= '<ul class="contact-info">';
                if($tel){
                    $html .="<li class='telephone'>".__('<strong>Tel:</strong>',TLP_TEAM_SLUG)." $tel</li>";
                }if($loc){
                    $html .="<li class='location'>".__('<strong>Location:</strong>',TLP_TEAM_SLUG)." $loc</li>";
                }if($email){
                    $html .="<li class='email'>".__('<strong>Email:</strong>',TLP_TEAM_SLUG)." $email</li>";
                }if($url){
                    $html .="<li class='web_url'>".__('<strong>URL:</strong>',TLP_TEAM_SLUG)."$url</li>";
                }
            $html .= "</ul>";

        $s = unserialize(get_post_meta( get_the_ID(), 'social' , true));

        $html .= '<div class="tpl-social">';
            if($s){
                foreach ($s as $id => $link) {
                        $html .= "<a class='fa fa-$id' href='{$s[$id]}' title='$id' target='_blank'><i class='fa fa-$id'></i></a>";
                }
            }
        $html .= '<br></div>';

        $html .="</div>";

            if(is_singular('team')){
                $content = $content .$html;
            }
            return $content;
        }

	}
endif;
