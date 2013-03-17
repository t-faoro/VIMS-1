$(function(){
	var uName = $('#existingName');
	
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
					console.log(msg);
				});
				$(this).dialog('close');
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		},
		close: function(){
			uName.val('');
		}
	});
	
	$('#joinUser')
		.button()
		.click(function(){
		$('#join-form').dialog('open');
		});
});