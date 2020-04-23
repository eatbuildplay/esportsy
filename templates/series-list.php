<?php

foreach($seriesList as $series) {
  print '<div>';
  print '<h5>' . $series->id . '</h5>';
  print '<h3>' . $series->title . '</h3>';
  print '<h3>' . $series->game->long_title . '</h3>';
  if( empty( $series->matches )) {
    print '<h3>Has 0 Matches</h3>';
  } else {
    print '<h3>Has ' . count( $series->matches ) . ' Matches</h3>';
    foreach( $series->matches as $match ) {
      print '<h4>' . $match->id . '</h4>';
    }
  }
  print '</div>';
  print '<hr />';
}
