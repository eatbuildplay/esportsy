<article class="calendar-series" data-permalink="<?php print get_permalink( $series->id ); ?>">

  <div class="calendar-series-col col-1"><img src="<?php print $series->gameLogo; ?>" /></div>

  <div class="calendar-series-col col-2">
    <h5><?php print $series->gameTitle; ?> / <?php print $series->tournamentTitle; ?> / <?php print $series->title; ?></h5>
    <h1>
      <?php $series->renderTeamName('a') ?> vs. <?php print $series->renderTeamName('b') ?> &nbsp;

      <?php
        if( $series->live ) {
          print "<span class='live-flag'>LIVE NOW</span>";
        } else {
      ?>
        <span class="start-time"><?php print $series->start; ?></span>
      <?php } ?>

    </h1>
  </div>

</article>
