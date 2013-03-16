$(function(){
	var uName = $('#uName'),
	fName = $('#fName'),
	lName = $('#lName'),
	auth = $('#auth'),
	venue = $('#venue'),
	allFields = $( [] ).add(uName).add(fName).add(lName).add(auth),
	tips = $(".validateTips");
	
	$('#create-form').dialog({
		autoOpen: false,
		height: 400,
		width: 350,
		modal: true,
		buttons: {
			"Create an user": function(){
				$.ajax({
					type: "POST", 	
					data: { user: uName.val(),
						first: fName.val(),
						last: lName.val(),
						auth: auth.val(),
						venue: venue.val()
					},
					url: "php/createUser.php"
				}).done(function(msg){
						$('#users').append(msg);
						$(this).dialog("close"); 
					});
			},
			Cancel: function() {
			$(this).dialog("close");
			}	
		},
		close: function(){
			allFields.val("");
		}
	});
	
	$("#createUser")
		.button()
		.click(function() {
			$("#create-form").dialog("open");
		});
});