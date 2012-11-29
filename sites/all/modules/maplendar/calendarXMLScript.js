(function($) {
  $(document).ready(function() {
    /**
     * @author Ben
     */

    var dateString;
    var hour;
    var timeString;
    var timesEventArray = null;
    var hourlyEventArray = null;
    var labelArray = null;
    var count = 0;

    function getHourString(hour) {
      var h = hour.toString();
      switch(h) {
        case "1" :
          timeString = "1:00 AM";
          break;
        case "2":
          timeString = "2:00 AM";
          break;
        case "3":
          timeString = "3:00 AM";
          break;
        case "4":
          timeString = "4:00 AM";
          break;
        case "5":
          timeString = "5:00 AM";
          break;
        case "6":
          timeString = "6:00 AM";
          break;
        case "7":
          timeString = "7:00 AM";
          break;
        case "8":
          timeString = "8:00 AM";
          break;
        case "9":
          timeString = "9:00 AM";
          break;
        case "10":
          timeString = "10:00 AM";
          break;
        case "11":
          timeString = "11:00 AM";
          break;
        case "12":
          timeString = "12:00 PM";
          break;
        case "13":
          timeString = "1:00 PM";
          break;
        case "14":
          timeString = "2:00 PM";
          break;
        case "15":
          timeString = "3:00 PM";
          break;
        case "16":
          timeString = "4:00 PM";
          break;
        case "17":
          timeString = "5:00 PM";
          break;
        case "18":
          timeString = "6:00 PM";
          break;
        case "19":
          timeString = "7:00 PM";
          break;
        case "20":
          timeString = "8:00 PM";
          break;
        case "21":
          timeString = "9:00 PM";
          break;
        case "22":
          timeString = "10:00 PM";
          break;
        case "23":
          timeString = "11:00 PM";
          break;
        default:
          timeString = "";
          break;
      }
    }

    function getDateSchedule(inDate) {
      var currentDateTime;
      var currentHour = 0;
      //This is for the listview hour labels
      var scheduleList = "";
      hour = "";
      dateString = "";

      if (inDate == null)
        currentDateTime = new Date();
      else {
        var correctDateFormat = inDate.toString();
        correctDateFormat = correctDateFormat.replace(/-/g, "/");

        currentDateTime = new Date(correctDateFormat);
      }
      /**
       * Leighton Notes:
       * The Drupal.settings.maplendar.events variable was defined by the maplendar_calendar_page() function
       * in maplendar.ben.inc. It stores the events for the user we are viewing the calendar for in an array
       * keyed by the start timestamp. This is used instead of the XML file. We just use a for loop below to
       * get the events from the variable. I didn't change a whole lot here to get it to use the database
       * instead of XML. Most of it is still Ben's code.
       */
      user_events = Drupal.settings.maplendar.events;
      timesEventArray = new Array();
      hourlyEventArray = new Array();
      labelArray = new Array();

      //$('div[data-role="header"]').empty();
      //$('ul[data-role="listview"]').empty();
      //$('h2').replaceWith('');

      $('ul#calendar_list').empty();

      $('body').append('<div>');

      for (var j in user_events) {
        var date = user_events[j].date_string;
        var time = user_events[j].time_string;
        var evnt = user_events[j].title;

        var currentTime = currentDateTime.getTime();

        var nodeDateTime = new Date(date + " " + time);
        var nodeTime = nodeDateTime.getTime();

        var nodeDate = nodeDateTime.getMonth() + nodeDateTime.getDate() + nodeDateTime.getYear();
        var currentDate = currentDateTime.getMonth() + currentDateTime.getDate() + currentDateTime.getYear();

        //This is for the map bubbles
        //if(nodeDateTime.getHours() <= currentDateTime.getHours() && nodeDate == currentDate)
        if (nodeDate == currentDate) {
          dateString = date;
          hour = nodeDateTime.getHours();

          getHourString(hour);
          labelArray.push('<li data-role="list-divider"><h2>' + timeString + '</h2></li>')
          if (user_events[j].link) {
            timesEventArray.push('<li><a href="' + user_events[j].link + '">' + time + ' - ' + evnt + '</a></li>');
          }
          else {
            timesEventArray.push('<li>' + time + ' - ' + evnt + '</li>');
          }
        }
      }

      if(timesEventArray.length == 0)
      {
      	timesEventArray.push('<li><h1 style="color:blue;">No events were found.</h1></li>');
      }

      //timesEventArray.sort();
      //labelArray.sort();

      var labelString = "";

      for (var i = 0; i < timesEventArray.length; i++) {
        if (labelArray[i] != labelString) {
          labelString = labelArray[i];
          $('ul#calendar_list').append(labelArray[i]);
        }

        $('ul#calendar_list').append(timesEventArray[i]);
      };

      timesEventArray = null;
      hourlyEventArray = null;
      labelArray = null;

      //Update list and end div
      $('ul#calendar_list').listview("refresh");
      $('body').append('</div>')

      //Display current hour
      getHourString(hour);
      $("#date_title").remove();
      $('#dateHeader').html(dateString);
      //$('div[data-role=content]').prepend('<h2>' + timeString + '</h2>'); This is for the popup bubble time
    }

    getDateSchedule();

    $('#grabSchedule').click(function() {
      var date = $('#chosenDate').val();
      getDateSchedule(date);
    });

    $('#eventDialog').dialog({
      autoOpen : true
    });
    $('#addEvent').click(function() {
      $('#eventDialog').dialog();
    });

    $('#addHour').keyup(function() {
      var hourStr = $('#addHour').val();

      var hourInt = $('#addHour').val().match(/\d+/);
      $('#addHour').val(hourInt);

      if (hourInt > 12)
        $('#addHour').val('12');
    });

    $('#addMin').keyup(function() {
      var minStr = $('#addMin').val();

      if (minStr.length > 2)
        $('#addMin').val($('#addMin').val().substr(0, 2));

      var minInt = $('#addMin').val().match(/\d+/);
      $('#addMin').val(minInt);

      if (minStr.length > 0 && minInt < 0)
        $('#addMin').val('0');
      else if (minStr.length > 1 && minInt > 59)
        $('#addMin').val('59');
    });

    $('#addMin').focusout(function() {
      var minInt = $('#addMin').val().match(/\d+/);
      var minStr = $('#addMin').val();

      if (minInt == 0 || minStr.length == 0)
        $('#addMin').val('00');
      else if (minStr.length == 1)
        $('#addMin').val('0' + minInt);
    });

    $('#addHour').focusout(function() {
      var hourStr = $('#addHour').val();

      if (hourStr.length == 0 || hourStr == "0" || hourStr == "00")
        $('#addHour').val('1');
    });

    $('#AMPM').click(function() {
      var amPM = $('#AMPM').val();

      if (amPM == "AM")
        $('#AMPM').val('PM');
      else
        $('#AMPM').val('AM');
    });

  });
})(jQuery);