/**
 * @author Ben
 */
$(document).ready(function()
{
  
  $("p").text('some text');
  
  $.get('customCalendar.xml', function(d){
  	$('body').append('<h1> Recommended Web Dev Books </h1>');
  	$('body').append('<div>');
  	$(d).find('date').each(function() {
  		$('div').append('Be bold!');
		var $date = $(this);
		var time = $date.find("time").text();
		var evnt = $date.find("event").text();
		
		var html = '<p>Time: ' + time + '</p>';
		html += '<p>Event: ' + evnt + '</p>';
		$('div').append($(html));
		$('div').append('new row');
	  
	$('body').append('</div>')
  	});
  });
});