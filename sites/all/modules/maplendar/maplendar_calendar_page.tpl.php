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
<h2><?php print $account->field_full_name[LANGUAGE_NONE][0]["value"]; ?>
<br />
<span id="dateHeader"></span></h2>
<h3>Choose Date</h3>
<input id="chosenDate" type="date" data-role="datebox" data-options='{"mode": "calbox", "useTodayButton": true "dateformat":"MM/DD/YYYY"}'>
<button id="grabSchedule">Grab Schedule</button>
<br />
<ul id="calendar_list" data-role="listview"></ul>
<br />
<a href="/user/<?php print $account->uid; ?>/calendar/add" data-role="button">Add Event</a>