
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
      defaultDate: $('#calendar').fullCalendar('today'),
      editable: true,
      droppable: true, // this allows things to be dropped onto the calendar
      eventLimit: true, // allow "more" link when too many events
      businessHours: {
        // days of week. an array of zero-based day of week integers (0=Sunday)
        dow: [ 1, 2, 3, 4, 5 ], // Monday - Thursday

        start: '10:00', // a start time (10am in this example)
        end: '18:00', // an end time (6pm in this example)
      },
      events: [
        {
          title: 'All Day Event',
          start: '2015-05-01',
          color: '#9c27b0'
        },
        {
          title: 'Long Event',
          start: '2019-02-02',
          end: '2019-02-03',
          color: '#e91e63'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2015-05-09T16:00:00',
          color: '#ff1744'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2015-05-16T16:00:00',
          color: '#aa00ff'
        },
        {
          title: 'Conference',
          start: '2015-05-3',
          end: '2015-05-5',
          color: '#01579b'
        },
        {
          title: 'Meeting',
          start: '2015-05-12T10:30:00',
          end: '2015-05-12T12:30:00',
          color: '#2196f3'
        },
        {
          title: 'Lunch',
          start: '2015-05-12T12:00:00',
          color: '#ff5722'
        },
        {
          title: 'Meeting',
          start: '2015-05-12T14:30:00',
          color: '#4caf50'
        },
        {
          title: 'Happy Hour',
          start: '2015-05-12T17:30:00',
          color: '#03a9f4'
        },
        {
          title: 'Dinner',
          start: '2015-05-12T20:00:00',
          color: '#009688'
        },
        {
          title: 'Birthday Party',
          start: '2015-05-13T07:00:00',
          color: '#00bcd4'
        }
      ]
    });



    // congesService.forEach(function (conge){
    //   {% if(conge.id_collabo != app.user.id) %}
    // }
    // end for

  });
