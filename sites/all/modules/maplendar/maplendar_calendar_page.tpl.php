<?php

// You can use the $account variable to get the user information
// $account->uid is the user id

?>
<html>
	<head>
		<script src="jquery.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.css"/>
		<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
		<!--<script src="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.js"></script>
		
		<script src="calendarXMLScript.js"></script>-->
		
		<script>
			$(document).ready(function()
			{
				$("#t").click(function() {
					$("#t").dialog();
				});
			});
		</script>
		
		<style>
			#goBack{width:100%;}
			#addHour{height: 30px; width:35px; text-align: right;}
			#addMin{height: 30px; width:35px;}
			#AMPM{height: 30px; width: 35px; text-transform: uppercase;}
		</style>
	</head>
	<body>
		<div id="main" data-role="page">
			
			<div data-role="header"></div>
			<div data-role="content">
				<div id="t"><p>test</p></div>
				<ul data-role="listview"></ul>
				<br />
				<h3>Choose Date</h3>
				<input id="chosenDate" type="date" data-role="datebox" data-options='{"mode": "calbox", "useTodayButton": true "dateformat":"MM/DD/YYYY"}'>
				<button id="grabSchedule">Grab Schedule</button>
				<a href="#addEvent" data-role="button" data-rel="dialog" data-transition="pop">Add Event</a>
				
			</div>
			
			<div data-role="footer">
				<a id="goBack" data-rel="back" data-role="button" data-icon="arrow-l">Back</a>
			</div>
		</div>
		
		<div id="addEvent" data-role="page">
		
		<div data-role="header"></div>
		
		<div data-role="content">
			
			<input id="addDate" type="date" data-role="datebox" data-option='{"mode": "calbox", "useTodayButton": true, "dateformat":"MM/DD/YYYY"}'>
			
			<div data-role="fieldcontain">
				<label for="name">Time:</label>
				<input id="addHour" type="text" value="1" maxlength="2"/>:
				<input id="addMin" type="text" value="00" maxlength="2"/>
				<input id="AMPM" type="text" value="AM" maxlength="2" readonly="readonly"/>
				<br />
				<br />
				<label for="name">Event:</label>
				<input type="text" value="" />
			</div>
			
		</div>
		
		<div data-role="footer">
			<a id="goBack" data-rel="back" data-role="button" data-icon="arrow-l">Back</a>
		</div>
		
	</div>
		
	</body>
</html>