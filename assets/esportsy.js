(function($) {

  var calendar = {

    canvas: $('#calendar-canvas'),

    init: function() {

      $('.filter-menu li').on('click', function() {

        console.log('filtering...')

        var showAll = false;
        var $gamesSelected = $('.filter-menu li.selected');
        var $gamesAll = $('.filter-menu li');
        if( $gamesSelected.length == $gamesAll.length ) {
          showAll = true;
        }

        // toggle selection of clicked game button
        var $gameBtn = $(this);
        if( $gameBtn.hasClass('selected')) {
          if( showAll ) {
            $gamesAll.removeClass('selected')
            $gameBtn.addClass('selected');
          } else {
            $gameBtn.removeClass('selected');
          }
        } else {
          $gameBtn.addClass('selected');
        }

        // if none selected after this choice, select all
        var $gamesSelected = $('.filter-menu li.selected');

        console.log( $gamesSelected )

        if( !$gamesSelected.length ) {
          $('.filter-menu li').addClass('selected')
        }

        calendar.draw();

      })

      $('.top-menu li').on('click', function() {

        console.log('switch...')

        calendar.draw();

      })

      /* init load */
      var calendarEl = $('.espy-calendar');
      if( calendarEl.length ) {
        calendar.draw();
      }

    },

    draw: function() {

      var gameFilter = false;

      var params = {
        filters: {}
      }

      data = {
        action: 'espy_calendar_draw',
        params: params
      }
      $.post( espy.ajaxurl, data, function( response ) {

        response = JSON.parse(response);
        console.log( response )

        calendar.canvas.html( response.html );

      });

    }

  }

  calendar.init()

})( jQuery );
