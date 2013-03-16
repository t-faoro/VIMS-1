$(function(){
	var uName = $('#uName'),
	fName = $('#fName'),
	lName = $('#lName'),
	allFields = $( [] ).add(uName).add(fName).add(lName),
	tips = $(".validateTips");
	
	$('#dialog-form').dialog({
		autoOpen: false,
		height: 350,
		width: 350,
		modal: true,
		buttons: {
			"Create an user": function(){
				console.log('test');
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
			$("#dialog-form").dialog("open");
		});
});