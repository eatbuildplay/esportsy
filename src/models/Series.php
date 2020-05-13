<?php

namespace Esportsy;

class Series {

  public $id = 0;
  public $seriesId = 0;
  public $title;
  public $start;
  public $isOver;
  public $tournamentId = 0;
  public $tournamentTitle;
  public $gameId = 0;
  public $gameTitle;
  public $gameLogo;
  public $teamA;
  public $teamB;

  public function create() {

    $params = [
      'post_type'   => 'series',
      'post_title'  => $this->title,
      'post_status' => 'publish'
    ];
    $postId = wp_insert_post( $params );

    Log::add( '$series: ' . print_r($params,1), 'After Series create() - ' . $postId );


    $this->id = $postId;
    return $postId;

  }

  public function update() {

    $params = [
      'ID' => $this->id,
      'post_title'  => $this->title
    ];



    $postId = wp_update_post( $params );

    Log::add( '$series: ' . print_r($params,1), 'After Series update()' );


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

  public static function fetch( $games = [], $schedule = 'upcoming' ) {

    $query = [
      'post_type' => 'Series',
      'posts_per_page' => 100,
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
        'key'     => 'start',
        'value'   => date('Y-m-d h:i:s'),
        'compare' => '>='
      ];
      $query['order'] = 'ASC';
      $query['orderby'] = 'meta_value';
      $query['meta_key'] = 'start';

    } elseif( $schedule == 'results' ) {

      $query['meta_query'][] = [
        'key'     => 'start',
        'value'   => date('Y-m-d h:i:s'),
        'compare' => '<='
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
    return $series;
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
