<?php

foreach($games as $game) {
  print '<div>';
  print '<div>' . '<img src="' . $game->images->rectangle . '" />' . '</div>';
  print '<div>' . '<img src="' . $game->images->circle . '" />' . '</div>';
  print '<div>' . '<img src="' . $game->images->square . '" />' . '</div>';
  print '<h5>' . $game->id . '</h5>';
  print '<h3>' . $game->title . '</h3>';
  print '<h1>' . $game->long_title . '</h1>';
  print '<h6>Default match type: ' . $game->default_match_type . '</h6>';
  print '<h6>Color: ' . $game->color . '</h6>';
  print '</div>';
  print '<hr />';
}
