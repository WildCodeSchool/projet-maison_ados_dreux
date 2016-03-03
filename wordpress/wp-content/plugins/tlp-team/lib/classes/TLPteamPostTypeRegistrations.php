<?php

if(!class_exists('TLPteamPostTypeRegistrations')):

	class TLPteamPostTypeRegistrations {
		public function __construct() {
			// Add the team post type and taxonomies
			add_action( 'init', array( $this, 'register' ) );
		}
		/**
		 * Initiate registrations of post type and taxonomies.
		 *
		 * @uses Portfolio_Post_Type_Registrations::register_post_type()
		 */
		public function register() {
			$this->register_post_type();
		}

		/**
		 * Register the custom post type.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		protected function register_post_type() {
			global $TLPteam;
			$team_labels = array(
			    'name'                => _x( 'TLP Team', TLP_TEAM_SLUG ),
			    'singular_name'       => _x( 'Member', TLP_TEAM_SLUG ),
			    'menu_name'           => __( 'TLP Team', TLP_TEAM_SLUG ),
			    'name_admin_bar'      => __( 'Member', TLP_TEAM_SLUG ),
			    'parent_item_colon'   => __( 'Parent Member:', TLP_TEAM_SLUG ),
			    'all_items'           => __( 'All Members', TLP_TEAM_SLUG ),
			    'add_new_item'        => __( 'Add New Member', TLP_TEAM_SLUG ),
			    'add_new'             => __( 'Add Member', TLP_TEAM_SLUG ),
			    'new_item'            => __( 'New Member', TLP_TEAM_SLUG ),
			    'edit_item'           => __( 'Edit Member', TLP_TEAM_SLUG ),
			    'update_item'         => __( 'Update Member', TLP_TEAM_SLUG ),
			    'view_item'           => __( 'View Member', TLP_TEAM_SLUG ),
			    'search_items'        => __( 'Search Member', TLP_TEAM_SLUG ),
			    'not_found'           => __( 'Not found', TLP_TEAM_SLUG ),
			    'not_found_in_trash'  => __( 'Not found in Trash', TLP_TEAM_SLUG ),
			);
			$team_args = array(
			    'label'               => __( 'TLP Team', TLP_TEAM_SLUG ),
			    'description'         => __( 'Member', TLP_TEAM_SLUG ),
			    'labels'              => $team_labels,
			    'supports'            => array( 'title', 'editor','thumbnail', 'page-attributes' ),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'rewrite'			  => array('slug' => $TLPteam->post_type_slug),
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 20,
				'menu_icon'			=> $TLPteam->assetsUrl .'images/team.png',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);
			register_post_type( $TLPteam->post_type, $team_args );
			flush_rewrite_rules();
		}
	}

endif;
