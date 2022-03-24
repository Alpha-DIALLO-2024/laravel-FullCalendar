<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>


        <!-- Bootstrap CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

        <link data-require="fullcalendar@*" data-semver="2.3.0" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.0/fullcalendar.min.css" />

        <!-- Jquery Ui Css -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">

        <!-- Fullcalendar Css -->
        <link rel="stylesheet" href="{{asset('css/fullcalendar.css')}}">

        <style>
            #dialog{
                display: none;
            }
        </style>



    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/home') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Home</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif



        <div class="container mt-3">
            <div class="mt-4 p-5 bg-primary text-white rounded">
                    <h1 class="text-center">Full Calendar avec Laravel</h1>
            </div>
            <div class="m-3">
                <button class="btn btn-danger" id="addEventButton">Add Event</button>
            </div>
            <div id="calendar"></div>

        </div>

        </div>

        <!-- day click dialog -->

        <div id="dialog">
            <div id="dialog-body">
                <form id="dayClick" method="post" action="{{route('eventStore')}}">
                    @csrf
                    <div class="form-group">
                        <label>Event Title</label>
                        <input type="text" id="title" class="form-control" name="title" placeholder="Event title">
                    </div>

                    <div class="form-group">
                        <label>Start Date/Time</label>
                        <input type="text" class="form-control" id="start" name="start" placeholder="start date & time">
                    </div>

                    <div class="form-group">
                        <label>End Date/Time</label>
                        <input type="text" class="form-control" id="end" name="end" placeholder="end date & time">
                    </div>

                    <div class="form-group">
                        <label>All Day</label>
                        <input type="checkbox" value="1" name="allDay" checked>All Day
                        <input type="checkbox" value="0" name="allDay"> Partial
                    </div>

                    <div class="form-group">
                        <label>Backgroud Color</label>
                        <input type="color" id="color" class="form-control" name="color">
                    </div>

                    <div class="form-group">
                        <label>Text Color</label>
                        <input type="color" id="texColor" class="form-control" name="textColor">
                    </div>

                    <input type="hidden" id="eventId" name="event_id">

                    <div class="form-group">
                        <button type="submit" class="btn btn-success" id="update" name="update">Add Event</button>
                        <button class="btn btn-danger" id="delete" name="delete">Delete</button>
                    </div>

                </form>

            </div>

        </div>




        <script src="{{asset('js/jquery.min.js')}}"></script>

        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>


        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

        <!-- Fullcalendar Js  -->
        <link rel="stylesheet" href="{{asset('js/fullcalendar.js')}}">


        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>

        <script type="text/javascript">
            // Code goes here
            jQuery(document).ready(function() {

                function convert(str) {
                    const d = new Date(str);
                    let month = '' + (d.getMonth() + 1);
                    let day = '' + d.getDate();
                    let year = d.getFullYear();
                    if (month.length < 2) month = '0' + month;
                    if (day.length < 2) day = '0' + day;
                    let hour = '' + d.getUTCHours();
                    let minutes = '' + d.getMinutes();
                    let seconds = '' + d.getSeconds();
                    if (hour.length < 2) hour = '0' + hour;
                    if (minutes.length < 2) minutes = '0' + minutes;
                    if (seconds.length < 2) seconds = '0' + seconds;
                    return [year, month, day].join('-')+' '+[hour, minutes, seconds].join(':');
                };

                jQuery('#addEventButton').on('click', function () {
                    jQuery('#dialog').dialog({
                        title: 'Add Event',
                        width: 600,
                        height: 700,
                        modal: true,
                        show:{effect:'clip', duration:350},
                        hide: {effect:'clip', duration:250},
                    })
                });

                let calendar = jQuery('#calendar').fullCalendar({
                    selectable: true,
                    height: 650,
                    showNonCurrentDates: false,
                    editable: true,
                    defaultView: 'month',
                    yearColumns: 3,
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'year,month,basicWeek,basicDay'

                    },

                   // plugins: [ googleCalendarPlugin ],
                    //googleCalendarApiKey: 'AIzaSyDcX4WAZHQ4bRieIlDuEMIWZ5mWoTK0fqk',
                    //events: {
                      //  googleCalendarId: 'alfapellel@gmail.com',
                       // className: 'gcal-event' // an option!
                   /// },

                    events:"{{route('allEvent')}}",
                    dayClick:function (date, event, veew) {

                        jQuery('#start').val(convert(date));
                        jQuery('#dialog').dialog({
                            title: 'Add Event',
                            width: 600,
                            height: 700,
                            modal: true,
                            show:{effect:'clip', duration:350},
                            hide: {effect:'clip', duration:250},
                        })
                    },
                    select:function (start, end){
                        jQuery('#start').val(convert(start));
                        jQuery('#end').val(convert(end));
                        jQuery('#dialog').dialog({
                            title: 'Add Event',
                            width: 600,
                            height: 700,
                            modal: true,
                            show:{effect:'clip', duration:350},
                            hide: {effect:'clip', duration:250},
                        })
                    },

                    eventClick: function (event) {
                        jQuery('#title').val(event.title);
                        jQuery('#start').val(convert(event.start));
                        jQuery('#end').val(convert(event.end));
                        jQuery('#color').val(event.color);
                        jQuery('#texColor').val(event.textColor);
                        jQuery('#eventId').val(event.id);
                        jQuery('#update').html('Update');
                        jQuery('#delete').val('Delete');
                        jQuery('#dialog').dialog({
                            title: 'Edit Event',
                            width: 600,
                            height: 700,
                            modal: true,
                            show:{effect:'clip', duration:350},
                            hide: {effect:'clip', duration:250},
                        });
                    }
                })
            });
        </script>

        @include('sweetalert::alert')



    </body>
</html>
