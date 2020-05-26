(function($) {

  /*
   * Series
   */
  var series = {

    init: function() {

      series.streamChange();

      // localize time header
      seriesTimeEl = $('.series-single header .datetime');
      if( seriesTimeEl.length ) {
        var seriesTime = seriesTimeEl.text();
        console.log( seriesTime )
        var startUtc = moment.utc( seriesTime ).toDate();
        var local = moment(startUtc).local().format('YYYY-MM-DD h:mmA');
        seriesTimeEl.text( local );
      }

      // localize time streams
      if( seriesTimeEl.length ) {
        var seriesTimeEl = $('.series-single .series-streams .datetime');
        var seriesTime = seriesTimeEl.text()
        var startUtc = moment.utc( seriesTime ).toDate();
        var local = moment(startUtc).local().format('YYYY-MM-DD h:mmA');
        seriesTimeEl.text( local );
      }

      // bet click
      series.betClick();

    },

    betClick: function() {

      $(document).on('click','.series-odds-list li', function() {
        var betUrl = $(this).data('bet-url');
        betUrl = betUrl.replace('{affiliate_tag}', '7483');
        console.log(betUrl)
        window.location.href = betUrl;
      });

    },

    streamChange: function() {
      $(document).on('click','li.series-caster', function() {
        console.log('change stream...');
        var caster = $(this).data('caster');
        $('iframe#cast').attr('src', 'https://player.twitch.tv/?channel=' + caster)
      })
    }

  }

  series.init();

  /*
   * Calendar
   */
  var calendar = {

    canvas: $('#calendar-canvas'),

    init: function() {

      $(document).on('click', '.calendar-series', function() {

        window.location.href = $(this).data('permalink');

      });

      $('.filter-menu li').on('click', function() {

        // test if in show all mode with no filtering
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
        if( !$gamesSelected.length ) {
          $('.filter-menu li').addClass('selected')
        }

        calendar.draw();

      })

      /*
       * Schedule filter click handler
       */
      $('.top-menu li').on('click', function() {

        // set selected
        var $menuBtn = $(this);
        $('.top-menu li').removeClass('selected')
        $menuBtn.addClass('selected')

        calendar.draw();

      })

      /* init load */
      var calendarEl = $('.espy-calendar');
      if( calendarEl.length ) {
        calendar.draw();
      }

    },

    draw: function() {

      // determine if homepage shortcode
      var calendarEl = $('.espy-calendar');
      if( calendarEl.hasClass('espy-calendar-home')) {
        var shortcode = 'home';
      } else {
        var shortcode = 'default';
      }

      // show skeleton
      $loaderEl = $('#calendar-canvas');
      $loaderEl.avnSkeleton({
        header: {
          selector: '> h5',
          lines: 1,
          icon: true,
          loader: true
        },
        main: {
          selector: '> div',
          paragraphs: 1,
          lines: 2
        }
      });

      // setup games filter
      var gameFilter = [];
      var $gamesSelected = $('.filter-menu li.selected');
      $gamesSelected.each( function( index ) {
        gameFilter.push( $(this).data('game-id') )
      });

      // setup schedule filter
      var scheduleFilter = $('.top-menu li.selected').data('schedule');

      var params = {
        filters: {
          games: gameFilter,
          schedule: scheduleFilter
        },
        shortcode: shortcode
      }

      data = {
        action: 'espy_calendar_draw',
        params: params
      }
      $.post( espy.ajaxurl, data, function( response ) {

        response = JSON.parse(response);

        calendar.canvas.hide().html( response.html ).fadeIn(900);

        // show local times
        $('.calendar-series-col.col-3 .start-time').each(function( index, seriesTimeDiv ) {

          var seriesTimeEl = $(seriesTimeDiv);
          var seriesTime = seriesTimeEl.text()
          var startUtc = moment.utc( seriesTime ).toDate();
          var local = moment(startUtc).local().format('YYYY-MM-DD h:mmA');
          seriesTimeEl.text( local );

        });

      });

    }

  }

  calendar.init()

})( jQuery );
