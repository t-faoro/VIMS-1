$(function(){
	var uName = $('#existingName'),
	venue = $('#venueId'),
	name = $('#existingName').val();
	
	$("#join-form").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			'Find User': function(){
				$.ajax({
					type: 'POST',
					data: {user: uName.val()},
					url: 'php/findUser.php'
				}).done(function(msg){
					if(confirm("Are you sure you want to add " + msg + "?"))
					{
						$.ajax({
							type: 'POST',
							data: {user: uName.val(),
								venue: venue.val()},
							url: 'php/addUser.php'
						}).done(function(msg){
							$('#users').append(msg);
						});
					}
				});
				$(this).dialog('close');
			},
			Cancel: function() {
				$(this).dialog('close');
				uName.val('');
			}
		}
	});
	
	$('#joinUser')
		.button()
		.click(function(){
		$('#join-form').dialog('open');
		});
});