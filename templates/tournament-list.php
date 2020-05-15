<?php

foreach($tournaments as $tournament) {
  print '<div>';
  print '<h5>' . $tournament->id . '</h5>';
  print '<h3>' . $tournament->title . '</h3>';

  /*
  if( empty( $series->matches )) {
    print '<h3>Has 0 Matches</h3>';
  } else {
    print '<h3>Has ' . count( $series->matches ) . ' Matches</h3>';
    foreach( $series->matches as $match ) {
      print '<h4>' . $match->id . '</h4>';
    }
  }
  */

  print '</div>';
  print '<hr />';
}
