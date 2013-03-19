/**
 * joinUser.js
 * Purpose: Javascript function to join a new user to a venue
 * @param #existingName html element with a existingName id, contains user name of user
 * 	to be joined
 *@param #venueId html element with a venueId id, contains venue id of venue that the 
		user is to be joined with
	@param #join-form html div element to be turned in to dialog box
 * By: Justin Werre
 * March 19, 2013
 */

$(function(){
	var uName = $('#existingName'),
	venue = $('#venueId'),
	name = $('#existingName').val();
	
	//Convert the join-form div in to a dialog form, and ajax's the userName
	//to join the user to the venue
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
	
	//Creates onclick event to show the form
	$('#joinUser')
		.button()
		.click(function(){
		$('#join-form').dialog('open');
		});
});