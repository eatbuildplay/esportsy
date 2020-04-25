<?php

if( !empty( $teams )) {
  foreach($teams as $team) {
    print '<div>';
    print '<h2>' . $team->id . '</h2>';
    print '</div>';
    print '<hr />';
  }
}
