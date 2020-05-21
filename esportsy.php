<?php

/**
 *
 * Plugin Name: eSportsy
 * Plugin URI: https://esportsy.com/
 * Description: Custom plugin to power eSportsy.com.
 * Version: 1.2.0
 * Author: Casey Milne, Eat/Build/Play
 * Author URI: https://eatbuildplay.com/
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 */

namespace Esportsy;

define( 'ESPORTSY_PATH', plugin_dir_path( __FILE__ ) );
define( 'ESPORTSY_URL', plugin_dir_url( __FILE__ ) );
define( 'ESPORTSY_VERSION', '1.2.0' );

class plugin {

  public function __construct() {

    require_once( ESPORTSY_PATH . 'src/Template.php' );
    require_once( ESPORTSY_PATH . 'src/PostType.php' );
    require_once( ESPORTSY_PATH . 'src/log/Log.php' );
    require_once( ESPORTSY_PATH . 'src/Shortcode.php' );
    require_once( ESPORTSY_PATH . 'src/AbiosApi.php' );
    require_once( ESPORTSY_PATH . 'src/SeriesImport.php' );
    require_once( ESPORTSY_PATH . 'src/SeriesImportToday.php' );
    require_once( ESPORTSY_PATH . 'src/models/Game.php' );
    require_once( ESPORTSY_PATH . 'src/models/Series.php' );
    require_once( ESPORTSY_PATH . 'src/sync/Sync.php' );
    require_once( ESPORTSY_PATH . 'src/sync/SyncInstance.php' );

    // CPT
    require_once( ESPORTSY_PATH . 'src/cpt/LogPostType.php' );
    require_once( ESPORTSY_PATH . 'src/cpt/SyncInstancePostType.php' );
    require_once( ESPORTSY_PATH . 'src/cpt/GamePostType.php' );
    require_once( ESPORTSY_PATH . 'src/cpt/SeriesPostType.php' );

    require_once( ESPORTSY_PATH . 'src/shortcodes/ShortcodeSeriesSingle.php' );
    new ShortcodeSeriesSingle();

    require_once( ESPORTSY_PATH . 'src/shortcodes/ShortcodeCalendar.php' );
    new ShortcodeCalendar();

    require_once( ESPORTSY_PATH . 'src/shortcodes/ShortcodeCalendarHome.php' );
    new ShortcodeCalendarHome();

    // do init at init
    add_action('init', [$this, 'init']);

  }

  public function init() {

    // enqueue scripts
    add_action('wp_enqueue_scripts', [$this, 'scripts']);

    // schedule cron
    // add_action( 'espy_series_import_cron', [$this, 'seriesImportCron']);
    if ( !wp_next_scheduled( 'espy_series_import_cron' ) ) {
      wp_schedule_event( time(), 'everyfiveminutes', 'espy_series_import_cron' );
    }

    // add_action( 'espy_series_import_today_cron', [$this, 'seriesImportTodayCron']);
    if ( !wp_next_scheduled( 'espy_series_import_today_cron' ) ) {
      wp_schedule_event( time(), 'everytwominutes', 'espy_series_import_today_cron' );
    }

    // register CPT's
    $pt = new LogPostType;
    $pt->register();

    $pt = new SyncInstancePostType;
    $pt->register();

    $pt = new GamePostType;
    $pt->register();

    $pt = new SeriesPostType;
    $pt->register();

  }

  public function seriesImportCron() {

    $importer = new SeriesImport;
    $importer->run();

  }

  public function seriesImportTodayCron() {

    $importer = new SeriesImportToday;
    $importer->run();

  }

  public function scripts() {

    // main css
    wp_enqueue_style(
      'esportsy-main-css',
      ESPORTSY_URL . 'assets/esportsy.css',
      array(),
      '1.0.0'
    );

    // moment js
    wp_enqueue_script(
      'moment-js',
      ESPORTSY_URL . 'assets/moment.min.js',
      array( 'jquery' ),
      '2.25.3',
      true
    );

    // moment locales js
    wp_enqueue_script(
      'moment-locales-js',
      ESPORTSY_URL . 'assets/moment-with-locales.min.js',
      array( 'jquery' ),
      '2.25.3',
      true
    );

    // moment tz
    wp_enqueue_script(
      'moment-tz-js',
      ESPORTSY_URL . 'assets/moment-tz.min.js',
      array( 'jquery' ),
      '0.5.31',
      true
    );

    // main js
    wp_enqueue_script(
      'esportsy-main-js',
      ESPORTSY_URL . 'assets/esportsy.js',
      array(
        'jquery',
        'moment-js',
        'moment-locales-js',
        'moment-tz-js'
      ),
      ESPORTSY_VERSION,
      true
    );

    // localize ajax url
    $localizedData = [
      'ajaxurl' => admin_url( 'admin-ajax.php' ),
      'seriesBaseUrl' => site_url('series')
    ];
    wp_localize_script( 'esportsy-main-js', 'espy', $localizedData );

  }

 }

new plugin();
