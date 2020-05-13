<?php

namespace Esportsy;

class Log {

  public $id;
  public $title;

  public static function add( $message, $title = null ) {

    $log = new Log;
    if( !$title ) {
      $log->title = "Log " . time();
    } else {
      $log->title = $title;
    }

    $log->message = $message;
    $log->save();

  }

  public function save() {

    $this->create();
    update_post_meta( $this->id, 'message', $this->message );

  }

  public function create() {

    $params = [
      'post_type'   => 'log',
      'post_title'  => $this->title,
      'post_status' => 'publish'
    ];
    $postId = wp_insert_post( $params );
    $this->id = $postId;

  }

}
