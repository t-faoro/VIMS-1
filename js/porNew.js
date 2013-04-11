// jquery.printpage.js
// -------------------
// Adds a print page icon and onclick event handler
// to a <span class="print">...</span> tag
// @author: ian oxley [ijoxley [at] gmail dot com]
// @date: 2010-02-22
//
/*
(function(jQuery) {
    jQuery.fn.printPage = function() {
       return this.each(function() {
            // Wrap each element in a <a href="#">...</a> tag
            var $current = jQuery(this);
            $current.wrapInner('<a href="#"></a>');
            
            jQuery('span.print > a').click(function() {
                window.print();
                return false;
            });
       });
    }
})(jQuery);
*/

/*
//=============================================================================
$(document).ready(function(){

	$('span.print').printPage();
	document.getElementById("addButton").onclick = addbutton;
 
    var counter = 2;
	
	(function addButton()) {
		alert('It works!');
     }    
 
     $("#removeButton").click(function () {
		if(counter==1){
			  alert("No more fields to remove");
			  return false;
		   }   
		
			counter--;
			$("#PorRec" + counter).remove();
		
     });
//=============================================================================	 
	 var ImgCounter = 2;
 
    $("#addImg").click(function () {
		$('#ImgLines > div:last').append('<span id="imgRec' + ImgCounter + '">' +
			'<div class="ImgLabel">Image ' + ImgCounter +': </div><br />' +
			'<input type="file" name="img' + ImgCounter +'" id="img' + ImgCounter +'"><br />' +
			'<textarea name="imgDesc' + ImgCounter + '"></textarea>' +
		'</span>'
			);
		ImgCounter++;
     });
 
     $("#removeImg").click(function () {
		if(ImgCounter==1){
			  alert("No more fields to remove");
			  return false;
		   }   
		
			ImgCounter--;
			$("#imgRec" + ImgCounter).remove();
		
     });
 
  });

  
//=============================================================================


window.onload = InitAll;

var counter = 2;

function InitAll(){
	document.getElementById("addButton").onclick = addButton;
	document.getElementById("removeButton").onclick = removeButton;
}

function addButton(){
	$('#PorLines > div:last').append('<span id="PorRec' + counter + '">' +
			'<div class="PorLabel">Person ' + counter + '</div><br />' +
			'<label>Name: </label><input type="textbox" name="porName' + counter + '"> ' +
			'<label>Involvement: </label><select name="porInv' + counter + '">' +
			'<option value="1">Witness</option> ' +
			'<option value="2">Victim</option> ' +
			'<option value="3">Instigator</option> ' +
			'<option value="4">Agressor</option></select> ' +
			'<label>Phone: </label><input type="textbox" name="porPhone' + counter + '">' +
			'<label>License: </label><input type="textbox" name="porLicense' + counter + '"><br />' +
			'<label>Notes: </label><br />' +
			'<textarea name="porNotes' + counter + '"></textarea>' +
			'</span>'
	);
	
	counter++;
}

function removeButton(){
		if(counter==1){
			  alert("No more fields to remove");
			  return false;
		   }   
		
			counter--;
			$("#PorRec" + counter).remove();
	}
*/