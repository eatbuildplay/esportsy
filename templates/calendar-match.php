<article>
  <img src="<?php print $match->game->images->square; ?>" />
  <h5><?php print $match->game->title; ?></h5>
  <h2><?php print $match->tournament_title; ?></h2>
  <h1><?php print $match->series_title; ?></h1>
  <h4>Start Time: <?php print $match->start; ?></h4>

  <h4>Match ID: <?php print $match->id; ?></h4>
  <h4>Series ID: <?php print $match->series_id; ?></h4>

  <h3>
    <?php print $match->rosters[0]->teams[0]->name; ?>
     vs.
    <?php print $match->rosters[1]->teams[0]->name; ?>
   </h3>

</article>
