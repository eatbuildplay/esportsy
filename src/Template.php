<?php

namespace Esportsy;

class Template {

  public $data = [];
  public $path;
  public $name;

  public function __construct() {
    $this->path = 'templates/';
  }

  public function get() {
    if( is_array( $this->data ) && !empty( $this->data )) {
      extract( $this->data );
    }
    ob_start();
    require( ESPORTSY_PATH . $this->path . $this->name . '.php' );
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
  }

  public function render() {
    print $this->get();
  }

}
