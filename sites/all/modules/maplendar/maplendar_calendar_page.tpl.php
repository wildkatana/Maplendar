<?php

// You can use the $account variable to get the user information
// $account->uid is the user id

?>

<!--<
// This is being loaded in automatically by the code, and passed the correct xml contents - Leighton
<script src="calendarXMLScript.js"></script>
-->

<style>
	#goBack{width:100%;}
	#addHour{height: 30px; width:35px; text-align: right;}
	#addMin{height: 30px; width:35px;}
	#AMPM{height: 30px; width: 35px; text-transform: uppercase;}
</style>

<ul data-role="listview"></ul>
<br />
<h3>Choose Date</h3>
<input id="chosenDate" type="date" data-role="datebox" data-options='{"mode": "calbox", "useTodayButton": true "dateformat":"MM/DD/YYYY"}'>
<button id="grabSchedule">Grab Schedule</button>
<a href="/maplendar/<?php print $account->uid; ?>/calendar/add" data-role="button">Add Event</a>
