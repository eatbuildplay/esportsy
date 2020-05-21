<?php

namespace Esportsy;

class Series {

  public $id = 0;
  public $seriesId = 0;
  public $title;
  public $start;
  public $isOver = 0;
  public $live = 0;
  public $tournamentId = 0;
  public $tournamentTitle;
  public $gameId = 0;
  public $gameTitle;
  public $gameLogo;
  public $teamA;
  public $teamB;
  public $streams;

  public function create() {

    $params = [
      'post_type'   => 'series',
      'post_title'  => $this->title,
      'post_status' => 'publish'
    ];
    $postId = wp_insert_post( $params );

    $this->id = $postId;
    return $postId;

  }

  public function update() {

    $params = [
      'ID' => $this->id,
      'post_title'  => $this->title
    ];



    $postId = wp_update_post( $params );

    $this->id = $postId;
    return $postId;

  }

  public function save() {

    if( $this->id > 0 || $this->exists() ) {



      $this->id = $this->update();
    } else {
      $this->id = $this->create();
      if( !$this->id ) {
        return false;
      }
    }

    update_post_meta( $this->id, 'series_id', $this->seriesId );
    update_post_meta( $this->id, 'title', $this->seriesId );
    update_post_meta( $this->id, 'start', $this->start );
    update_post_meta( $this->id, 'is_over', $this->isOver );
    update_post_meta( $this->id, 'tournament_id', $this->tournamentId );
    update_post_meta( $this->id, 'tournament_title', $this->tournamentTitle );
    update_post_meta( $this->id, 'game_id', $this->gameId );
    update_post_meta( $this->id, 'game_title', $this->gameTitle );
    update_post_meta( $this->id, 'game_logo', $this->gameLogo );
    update_post_meta( $this->id, 'team_a', $this->teamA );
    update_post_meta( $this->id, 'team_b', $this->teamB );

  }

  public function exists() {
    $posts = get_posts([
      'post_type' => 'series',
      'meta_query' => [
        [
          'key'   => 'series_id',
          'value' => $this->seriesId
        ]
      ]
    ]);
    if( !empty( $posts )) {
      $this->id = $posts[0]->ID;
      return true;
    }
    return false;
  }

  public static function fetch( $games = [], $schedule = 'upcoming', $limit = 50 ) {

    $query = [
      'post_type' => 'Series',
      'posts_per_page' => $limit,
      'meta_query' => []
    ];

    if( is_array( $games )) {
      $query['meta_query'][] = [
        'key'     => 'game_id',
        'value'   => $games,
        'compare' => 'IN',
      ];
    }

    if( $schedule == 'upcoming' ) {

      $query['meta_query'][] = [
        'key'     => 'is_over',
        'value'   => 0,
      ];
      $query['order'] = 'ASC';
      $query['orderby'] = 'meta_value';
      $query['meta_key'] = 'start';

    } elseif( $schedule == 'results' ) {

      $query['meta_query'][] = [
        'key'     => 'is_over',
        'value'   => 1,
      ];
      $query['order'] = 'DESC';
      $query['orderby'] = 'meta_value';
      $query['meta_key'] = 'start';

    }

    $seriesPosts = get_posts( $query );

    $seriesList = [];
    foreach( $seriesPosts as $seriesPost ) {
      $seriesList[] = self::loadFromPost( $seriesPost );
    }

    return $seriesList;

  }

  public static function loadFromPost( $seriesPost ) {
    $series = new Series;
    $fields = get_post_meta( $seriesPost->ID );
    $series->id = $seriesPost->ID;
    $series->title = $seriesPost->post_title;
    $series->start = get_post_meta( $seriesPost->ID, 'start', 1 );
    $series->isOver = get_post_meta( $seriesPost->ID, 'is_over', 1 );
    $series->seriesId = get_post_meta( $seriesPost->ID, 'series_id', 1 );
    $series->gameId = get_post_meta( $seriesPost->ID, 'game_id', 1 );
    $series->tournamentTitle = get_post_meta( $seriesPost->ID, 'tournament_title', 1 );
    $series->gameLogo = get_post_meta( $seriesPost->ID, 'game_logo', 1 );
    $series->gameTitle = get_post_meta( $seriesPost->ID, 'game_title', 1 );
    $series->teamA = get_post_meta( $seriesPost->ID, 'team_a', 1 );
    $series->teamB = get_post_meta( $seriesPost->ID, 'team_b', 1 );

    // flag live
    $now = new \DateTime;
    $seriesStart = \DateTime::createFromFormat('Y-m-d H:i:s', $series->start);

    if( $now >= $seriesStart && !$series->isOver ) {
      $series->live = 1;
    }

    return $series;
  }

  /*
   * Stream handling methods
   */
  public function loadStreams( $casters ) {

    if( empty( $casters )) {
      $this->streams = false;
      return;
    }
    $this->streams = new \stdClass;
    $this->streams->casters = [];

    foreach( $casters as $caster ) {

      // filter out not Twitch
      if( !isset($caster->stream->platform) || $caster->stream->platform->name != 'Twitch' ) {
        continue;
      }

      // add to streams->casters
      $this->streams->casters[] = $caster;

    }

  }

  public function hasStreams() {
    if( is_null( $this->streams ) || !$this->streams ) {
      return false;
    }
    return true;
  }

  public function renderStream() {

    // match over no stream
    if( $this->isOver ):
      print '<h3>This match is over, no live streams are available.</h3>';
      return;
    endif;

    // match upcoming no stream
    $now = new \DateTime;
    $seriesStart = \DateTime::createFromFormat('Y-m-d H:i:s', $this->start);

    if( $now <= $seriesStart ) {
      print '<h3>This match has not started yet please visit again at the start time, <span class="datetime">' . $this->start . '</span>.</h3>';
      return;
    }

    // match has no streams
    if( !$this->hasStreams() ):
      print '<h3>This match has no streams available.</h3>';
      return;
    endif;

    // finally we get to show stream!
    print '<ul>';
    foreach( $this->streams->casters as $caster ):
      print '<li class="series-caster" data-caster="' . $caster->name . '">';
      print '<img src="' . $caster->country->images->default . '" />';
      print '<h2>' . $caster->name . '</h2>';
      print '</li>';
    endforeach;
    print '</ul>';

    $selectedCaster = $this->streams->casters[0];
    print '<iframe
      src="https://player.twitch.tv/?channel=' . $selectedCaster->name . '"
      height="720"
      width="1280"
      frameborder="0"
      scrolling="no"
      allowfullscreen="true"
      id="cast">
    </iframe>';

  }

  /*
   * Render methods
   */
  public function renderGameLogo() {
    if( isset( $this->gameLogo )) {
      print '<img class="game-logo" src="' . $this->gameLogo . '" />';
    }
  }

}
