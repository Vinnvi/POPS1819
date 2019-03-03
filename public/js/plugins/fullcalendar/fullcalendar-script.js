
  $(document).ready(function() {
    /* initialize the external events
    -----------------------------------------------------------------*/
    $('#external-events .fc-event').each(function() {

      // store data so the calendar knows to render an event upon drop
      $(this).data('event', {
        title: $.trim($(this).text()), // use the element's text as the event title
        stick: true, // maintain when user navigates (see docs on the renderEvent method)
        color: '#00bcd4'
      });

      // make the event draggable using jQuery UI
      $(this).draggable({
        zIndex: 999,
        revert: true,      // will cause the event to go back to its
        revertDuration: 0  //  original position after the drag
      });

    });


    /* initialize the calendar
    -----------------------------------------------------------------*/
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,basicWeek,basicDay'
      },
      eventRender: function (event, element) {
          var start = moment(event.start);
          var end = moment(event.end);
          while( start.format('YYYY-MM-DD') != end.format('YYYY-MM-DD') ){
              var checkDay = new Date(start.format('YYYY-MM-DD'));
              var dataToFind = start.format('YYYY-MM-DD');
              if(checkDay.getDay() == 0 || checkDay.getDay() == 6){
                $("td[data-date='"+dataToFind+"']").addClass('hideWeekend');
                // $(element).css("display", "none");
              }
              else{
                $("td[data-date='"+dataToFind+"']").addClass('dayConge');
              }
              // if(event.title = "Conge"){
              //   $(element).css("display", "none");
              // }
              start.add(1, 'd');
          }

      },
      defaultDate: $('#calendar').fullCalendar('today'),
      editable: true,
      droppable: true, // this allows things to be dropped onto the calendar
      eventLimit: true, // allow "more" link when too many events
      views: {
        month: {
          eventLimit: 2 // adjust to 6 only for agendaWeek/agendaDay
        }
      },
      businessHours: {
        // days of week. an array of zero-based day of week integers (0=Sunday)
        dow: [ 1, 2, 3, 4, 5 ], // Monday - Thursday
        start: '10:00', // a start time (10am in this example)
        end: '18:00', // an end time (6pm in this example)
      }
    });




    // congesService.forEach(function (conge){
    //   {% if(conge.id_collabo != app.user.id) %}
    // }
    // end for

  });
