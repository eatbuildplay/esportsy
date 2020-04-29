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
    require_once( ESPORTSY_PATH . 'src/models/Game.php' );
    require_once( ESPORTSY_PATH . 'src/models/Series.php' );
    require_once( ESPORTSY_PATH . 'src/models/Match.php' );
    require_once( ESPORTSY_PATH . 'src/sync/Sync.php' );
    require_once( ESPORTSY_PATH . 'src/sync/SyncRoutine.php' );
    require_once( ESPORTSY_PATH . 'src/sync/SyncInstance.php' );

    require_once( ESPORTSY_PATH . 'src/ShortcodeGamesList.php' );
    //new ShortcodeGamesList();

    require_once( ESPORTSY_PATH . 'src/ShortcodeSeriesList.php' );
    //new ShortcodeSeriesList();

    require_once( ESPORTSY_PATH . 'src/ShortcodeTournamentList.php' );
    //new ShortcodeTournamentList();

    require_once( ESPORTSY_PATH . 'src/ShortcodeTeamsList.php' );
    //new ShortcodeTeamsList();

    require_once( ESPORTSY_PATH . 'src/ShortcodeCalendar.php' );
    new ShortcodeCalendar();

    // do init at init
    add_action('init', [$this, 'init']);

  }

  public function init() {

    // enqueue scripts
    add_action('wp_enqueue_scripts', [$this, 'scripts']);

    add_action( 'espy_cron_hook', [$this, 'cron']);
    if ( !wp_next_scheduled( 'espy_cron_hook' ) ) {
      wp_schedule_event( time(), 'everyminute', 'espy_cron_hook' );
    }

    // test test test
    // $this->importSeries();

  }

  public function cron() {

    //$this->importSeries();

  }

  public function importSeries() {

    // get the series data and parse it into matches
    $api = new AbiosApi();

    $syncLast = SyncInstance::fetchLast();

    /*
    print '<pre>';
    var_dump( $syncLast );
    print '</pre>';
    die();
    */


    if( !$syncLast ) {
      $importDate = new \DateTime();
    } else {
      $importDate = \DateTime::createFromFormat( 'Y-m-d', $syncLast->dateImport );
      if( $syncLast->currentPage == $syncLast->lastPage ) {
        $importDate->modify('+1 day');
        $page = 1;
      } else {
        $page = $syncLast->currentPage +1;
      }
    }

    $beginOfDay = clone $importDate;
    $beginOfDay->modify('today');
    $endOfDay = clone $beginOfDay;
    $endOfDay->modify('tomorrow');
    $endOfDateTimestamp = $endOfDay->getTimestamp();
    $endOfDay->setTimestamp($endOfDateTimestamp - 1);
    $start = $beginOfDay->format( 'Y-m-d\TH:i:s\Z' );
    $end = $endOfDay->format( 'Y-m-d\TH:i:s\Z' );

    $seriesResponse = $api->fetchSeriesByDateRange( $start, $end, $page );

    if( $seriesResponse->code != 200 ) {
      return;
    }

    // insert each match as a post
    foreach( $seriesResponse->data as $seriesData ) {

      $series = new Series();

      $series->seriesId = $seriesData->id;
      $series->title = $seriesData->title;
      $series->gameId = $seriesData->game->id;
      $series->gameTitle = $seriesData->game->title;
      $series->gameLogo = $seriesData->game->images->square;
      $series->tournamentId = $seriesData->tournament->id;
      $series->tournamentTitle = $seriesData->tournament->title;

      $series->save();

    }

    // update tracker
    $sync = new SyncInstance();
    $sync->routine = "series_import";
    $sync->timestamp = time();
    $sync->dateImport = $beginOfDay->format('Y-m-d');
    $sync->lastPage = $seriesResponse->last_page;
    $sync->currentPage = $seriesResponse->current_page;
    $sync->save();

  }

  public function scripts() {

    // main css
    wp_enqueue_style(
      'esportsy-main-css',
      ESPORTSY_URL . 'assets/esportsy.css',
      array(),
      '1.0.0'
    );

    // main js
    wp_enqueue_script(
      'esportsy-main-js',
      ESPORTSY_URL . 'assets/esportsy.js',
      array( 'jquery' ),
      '1.0.0',
      true
    );

    // localize ajax url
    $localizedData = [
      'ajaxurl' => admin_url( 'admin-ajax.php' )
    ];
    wp_localize_script( 'esportsy-main-js', 'espy', $localizedData );

  }

 }

new plugin();
