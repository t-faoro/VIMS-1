/*
 * CreateUser.js
 * Purpose: creates a dialog box from which the user can create new users
 * By: Justin Werre
 */

$(function(){
	var uName = $('#uName'),
	fName = $('#fName'),
	lName = $('#lName'),
	auth = $('#auth'),
	venue = $('#venue'),
	allFields = $( [] ).add(uName).add(fName).add(lName).add(auth),
	tips = $(".validateTips");
	var created = false;

	//Turns the html form into a dialog box, and ajaxs to the server to create the user
	//account
	$('#create-form').dialog({
		autoOpen: false,
		height: 450,
		width: 350,
		modal: true,
		buttons: {
			"Create an user": function(){
				$.ajax({
					type: "POST", 	
					data: { 
						user: uName.val(),
						first: fName.val(),
						last: lName.val(),
						auth: auth.val(),
						venue: venue.val()
					},
					url: "php/createUser.php"
				}).done(function(msg){
						if('false' == msg){
								// alert('That username is taken. Please chose different username.');
								$("#uError").text("That username is taken. Please chose a different username.");
							}
							else{
								$('#users').append(msg);
								created = true;
							}
					});
					if(created)
						$(this).dialog("close");
			},
			Cancel: function() {
			$(this).dialog("close");
			}	
		},
		close: function(){
			allFields.val("");
		}
	});
	
	//Creates a button to show the form
	$("#createUser")
		.button()
		.click(function() {
			$("#create-form").dialog("open");
		});
});