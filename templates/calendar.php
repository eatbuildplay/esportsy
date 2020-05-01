<?php

/*
print '<pre>';
var_dump( $calendarData->matches[0]->rosters );
print '</pre>';
*/

?>


<div class="espy-calendar">

  <header>

    <div class="header-left">
      <ul class="top-menu">
        <li>Upcoming</li>
        <li>Results</li>
      </ul>
    </div>

    <div class="header-right">
      <ul class="filter-menu">
        <?php foreach( $calendarData->games as $game ): ?>
          <li class="selected" data-game-id="<?php print $game->abiosId; ?>">
            <img src="<?php print $game->imageSquare; ?>" />
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </header>

  <section id="calendar-canvas"></section>

</div>
