/**
 * CreateUser.js
 * Purpose: creates a dialog box from which the user can create new users
 * By: Justin Werre
 */

$(function(){
	var uName = $('#uName'),
	fName = $('#fName'),
	lName = $('#lName'),
	auth = $('#auth'),
	venue = $('#venueId'),
	password = $('#password'),
	allFields = $( [] ).add(uName).add(fName).add(lName).add(auth).add(password),
	tips = $(".validateTips");

	//Turns the html form into a dialog box, and ajaxs to the server to create the user
	//account
	$('#create-form').dialog({
		autoOpen: false,
		height: 500,
		width: 350,
		modal: true,
		buttons: {
			"Create an user": function(){
				$('#pError').text('');
				$('#uError').text('');
				if(7 > password.val().length)
				{
					$('#pError').text('Password must be at least 7 characters long.');
				}
				else if("" == uName.val())
				{
					$('#uError').text("User must have a user name.");
				}
				else
				{
					$.ajax({
						type: "POST", 	
						data: { 
							user: uName.val(),
							first: fName.val(),
							last: lName.val(),
							auth: auth.val(),
							venue: venue.val(),
							password: password.val()
						},
						url: "php/createUser.php"
					}).done(function(msg){
						if('false' == msg){
							$("#uError").text("That username is taken. Please chose a different username.");
						}
						else{
							$('#users').append(msg);
							$('#uError').text("");
							closeDialog();
						}
					});
				}
			},
			Cancel: function() {
			$(this).dialog("close");
			}	
		},
		close: function(){
			allFields.val("");
			$('#uError').text("");
		}
	});
	
	//Creates a button to show the form
	$("#createUser")
		.button()
		.click(function() {
			$("#create-form").dialog("open");
		});
});

function closeDialog(){
	$("create-form").dialog("close");
}