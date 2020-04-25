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
            'CS:GO',
            'SCII',
            'OW',
            'Hearthstone',
            'FIFA'
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

    <?php
      if( !empty( $calendarData->matches )) :
        foreach( $calendarData->matches as $match ): ?>

        <article>
          <h1><?php //print $match->tournament_title; ?></h1>
          <h2><?php print $match->series_title; ?></h2>
          <h4>Match ID: <?php print $match->id; ?></h4>
          <h4>Series ID: <?php print $match->series_id; ?></h4>

        </article>

      <?php endforeach; endif; ?>

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
  padding: 20px 40px 20px 40px;
  height: 70px;
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
  width: 50px;
}


section {
  background: #F0F1F5;
  padding: 40px;
}

</style>

<script>

(function($) {

  var calendar = {

    init: function() {

      $('.filter-menu li').on('click', function() {

        console.log('filtering...')

      })

      $('.top-menu li').on('click', function() {

        console.log('switch...')

      })

    },

    draw: function() {

      var params = {

      }

      data = {
        action: 'espy-calendar-draw',
        params: params
      }
      $.post( espy.ajaxurl, data, function( response ) {

        response = JSON.PARSE(response);
        console.log( response )

      });

    }

  }

  calendar.init()

})( jQuery );



</script>
