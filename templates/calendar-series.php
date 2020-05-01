<?php

// $series->seriesId;

?>

<article class="calendar-series">

  <div class="calendar-series-col col-1">
    <img src="<?php print $series->gameLogo; ?>" />
  </div>

  <div class="calendar-series-col col-2">
    <?php print $series->start; ?>
  </div>

  <div class="calendar-series-col col-3">
    <h5><?php print $series->gameTitle; ?> | <?php print $series->tournamentTitle; ?></h5>
    <h1><?php print $series->title; ?></h1>
  </div>

</article>
