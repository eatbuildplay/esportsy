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
        <?php

          $supportedGames = [
            'Dota 2',
            'LoL',
            'CS:GO'
          ];
          foreach( $calendarData->games as $game ):

            if( !in_array($game->title, $supportedGames) ) {
              continue;
            }

          ?>
          <li>
            <img src="<?php print $game->images->square; ?>" />
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </header>

  <section>

    <article>
      <h5>Wednesday, April 22</h5>
    </article>

    <article>
      <h5>Thursday, April 23</h5>
    </article>

  </section>

</div>



<style>

.espy-calendar {

}

header {
  background: #454850;
}
.header-left {
  width: 20%;
  min-width: 120px;
  float: left;
}
.header-right {
  width: 80%;
  min-width: 200px;
  float: left;
  text-align: right;
}

ul.top-menu {
  padding: 0;
  margin: 0;
  list-style: none;
}

ul.top-menu li {
  display: inline-block;
  margin: 0 40px 0 0;
  text-transform: uppercase;
  font-size: 14px;
  font-family: verdana, sans-serif;
  cursor: pointer;
}

.filter-menu {
  padding: 0;
  margin: 0;
  list-style: none;
}

.filter-menu li {
  display: inline-block;
  margin: 0 20px 0 0;
  text-transform: uppercase;
  font-size: 14px;
  font-family: verdana, sans-serif;
  cursor: pointer;
}

.filter-menu li img {
  width: 70px;
}


section {
  background: #F0F1F5;
}

</style>

<script>

var calendar = {

}

</script>
