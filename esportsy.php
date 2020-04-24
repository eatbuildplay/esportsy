<?php

/**
 *
 * Plugin Name: eSportsy
 * Plugin URI: https://esportsy.com/
 * Description: Custom plugin to power eSportsy.com.
 * Version: 1.0.0
 * Author: Casey Milne, Eat/Build/Play
 * Author URI: https://eatbuildplay.com/
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 */

namespace Esportsy;

define( 'ESPORTSY_PATH', plugin_dir_path( __FILE__ ) );
define( 'ESPORTSY_URL', plugin_dir_url( __FILE__ ) );
define( 'ESPORTSY_VERSION', '1.0.0' );

class plugin {

  public function __construct() {

    require_once( ESPORTSY_PATH . 'src/Template.php' );
    require_once( ESPORTSY_PATH . 'src/Shortcode.php' );
    require_once( ESPORTSY_PATH . 'src/AbiosApi.php' );

    require_once( ESPORTSY_PATH . 'src/ShortcodeGamesList.php' );
    new ShortcodeGamesList();

    require_once( ESPORTSY_PATH . 'src/ShortcodeSeriesList.php' );
    new ShortcodeSeriesList();

    require_once( ESPORTSY_PATH . 'src/ShortcodeTournamentList.php' );
    new ShortcodeTournamentList();

    require_once( ESPORTSY_PATH . 'src/ShortcodeTeamsList.php' );
    new ShortcodeTeamsList();

    require_once( ESPORTSY_PATH . 'src/ShortcodeCalendar.php' );
    new ShortcodeCalendar();

  }

 }

new plugin();
