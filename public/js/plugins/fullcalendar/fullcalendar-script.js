
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
        right: 'month,basicWeek,basicDay, agendaWeek'
      },
      allDaySlot: false,
      minTime: "08:00:00",
      maxTime: "18:00:00",
      displayEventTime: false,
      hiddenDays: [ 0, 6 ],
      eventRender: function (event, element) {
        element.attr("title",event.description);
        var start = moment(event.start);
        var end = moment(event.end);
        var colorMyConge = '#e2b14d';
        if($("#displayColorConge").is(':checked')) {
          $(".dayConge").css("background-color", "#c12600");
          $(".maybeDayConge").css("background-color", "#f7c342");
        }
        element.data('event-id',event.id);
        while( start.format('YYYY-MM-DD') != end.format('YYYY-MM-DD') ){
            var dataToFind = start.format('YYYY-MM-DD');
            if(event._id == "myConge" && (event.statut == "validee chef" || event.statut == "validee RH")){
              $("td[data-date='"+dataToFind+"']").addClass('dayConge');
            }
            else {
              if(event._id == "myConge" && (event.statut == "En cours" || event.statut == "En attente chef")){
                $("td[data-date='"+dataToFind+"']").addClass('maybeDayConge');
              }
            }
            start.add(1, 'd');
        }
      },
      dayClick: function(date, allDay, jsEvent, view) {
        $('#calendar').fullCalendar('clientEvents', function(event) {
          // match the event date with clicked date if true render clicked date events
          if ( moment(date).format('YYYY-MM-DD') >= moment(event._start).format('YYYY-MM-DD') && moment(date).format('YYYY-MM-DD') <= moment(event._end).format('YYYY-MM-DD') ) {
            console.log("test");
            console.log(event.title);
            // if you have subarray i mean array within array then
            // console.log(event.subarray[0].yoursubarrayKey);
          }
        });
      },
      eventClick: function(calEvent, jsEvent, view) {
        console.log("test ici");
      },
      defaultDate: $('#calendar').fullCalendar('today'),
      editable: false,
      droppable: false, // this allows things to be dropped onto the calendar
      eventLimit: true, // allow "more" link when too many events
      selectable: true,
      views: {
        month: {
          eventLimit: 4 // adjust to 6 only for agendaWeek/agendaDay
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
