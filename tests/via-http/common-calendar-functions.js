

$(function() {
	$('.rundiz-calendar').on('click', '.event', function() {
		alert('Editing event id '+$(this).data('eventId')+"\n"
			+'You may add event/appointment form functional here.'
		);
	});
});