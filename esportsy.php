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
    require_once( ESPORTSY_PATH . 'src/models/Match.php' );
    require_once( ESPORTSY_PATH . 'src/sync/Sync.php' );

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

    $matches = Match::fetch();
    $gameId = 2;
    foreach( $matches as $match ) {
      $match->gameId = $gameId;
      $match->matchId = $match->title;
      $match->save();
      if( $gameId == 2 ) {
        $gameId = 3;
      } else {
        $gameId = 2;
      }
    }

    // enqueue scripts
    add_action('wp_enqueue_scripts', [$this, 'scripts']);

    add_action( 'espy_cron_hook', [$this, 'cron']);
    if ( ! wp_next_scheduled( 'espy_cron_hook' ) ) {
      wp_schedule_event( time(), 'everyminute', 'espy_cron_hook' );
    }

  }

  public function cron() {

    $this->importMatches();

  }

  public function importMatches() {

    return;

    // get the series data and parse it into matches
    $api = new AbiosApi();
    $matches = $api->fetchMatches();

    // insert each match as a post
    foreach( $matches as $matchData ) {

      $match = new Match();
      $match->matchId = $matchData->id;

      $match->gameId = $matchData->game->id;
      $match->gameTitle = $matchData->game->title;
      $match->gameLogo = $matchData->game->images->square;

      $match->tournamentId = $matchData->tournament->id;
      $match->tournamentTitle = $matchData->tournament->title;

      $match->seriesId = $matchData->series->id;
      $match->seriesTitle = $matchData->series->title;

      $match->create();

    }

    // update tracker ()

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
