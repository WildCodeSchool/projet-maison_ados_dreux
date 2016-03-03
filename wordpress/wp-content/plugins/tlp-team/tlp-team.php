<?php
/**
 * @package TLP_Team
 * @version 1.2
 */

/**
* Plugin Name: TLP Team
* Plugin URI: http://demo.radiustheme.com/wordpress/plugins/tlp-team/
* Description: Fully Responsive and Mobile Friendly Team member display plugin for wordpress with Grid and Isotope View.
* Author: RadiusTheme
* Version: 1.3
* Author URI: www.radiustheme.com
* Text Domain: tlp-team
* License: MIT License
* License URI: http://opensource.org/licenses/MIT
*/

define( 'TLP_TEAM_VERSION', '1.3' );
define( 'TLP_TEAM_TITLE', 'TLP TEAM');
define( 'TLP_TEAM_SLUG', 'tlp-team');
define( 'TLP_TEAM_PLUGIN_PATH', dirname( __FILE__ ));
define( 'TLP_TEAM_PLUGIN_ACTIVE_FILE_NAME', plugin_basename( __FILE__ ));
define( 'TLP_TEAM_PLUGIN_URL', plugins_url( '' , __FILE__ ));
define( 'TLP_TEAM_LANGUAGE_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages');
require('lib/init.php');