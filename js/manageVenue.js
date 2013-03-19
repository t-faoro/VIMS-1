/**
 * manageVenue.js
 * Purpose: Assisting functions for the manageVenue and venue page
 * Contains: 
 * 	updateStatus(venue)
 * 	deleteUser(user, venue)
 * 	enableSaveButton(user)
 * 	updateUser(user, venue)
 * By: Justin Werre
 * March 19, 2013
 */

/**
 * updateStatus(venue)
 * Purpose: Issues a ajax call to update the venues active status
 * @param venue the id number of the venue to be updated
 */
function updateStatus(venue){
	var status = document.getElementById(venue).value; 
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "php/updateVenueStatus.php");
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("id="+venue+"&status="+status); 
}

/**
 * deleteUser(user, venue)
 * purpose: disables a users access to the system
 * @param user the users id number that is to be disabled
 * @param venue the venue number for which the user is to be disabled
 */
function deleteUser(user, venue){
	if(!confirm("are you sure you want to delete this user?")){
		return false;
	}
	
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "php/deleteUser.php");
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("id="+user+"&venue="+venue); 
	xmlhttp.onreadystatechange=function()
	{
		if(4 == xmlhttp.readyState){
			if(xmlhttp.responseText){
				var userRow = document.getElementById(user);
				userRow.parentNode.removeChild(userRow);
			}
		}
	};
}

/**
 * enableSaveButton(user)
 * Purpose: enables the save button for a particular user
 * @param user the id number for the user that can be saved
 */
function enableSaveButton(user){
	document.getElementById("update"+user).disabled = false;
}

/**
 * updateUser(user, venue)
 * purpose: updates the users information
 * @param user the user number of the user to be updated
 * @param venue the venue id number of the venue that the user
 * 	is authorized to acces.
 */
function updateUser(user, venue){
	//Get new data
	var data  = "id="+user;
	data += "&venue="+venue;
	data += "&name="+document.getElementById("user"+user).value;
	data += "&first="+document.getElementById("first"+user).value;
	data += "&last="+document.getElementById("last"+user).value;
	data += "&auth="+document.getElementById("auth"+user).value;
	
	//Send data to be updated
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "php/updateUser.php");
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send(data); 
	xmlhttp.onreadystatechange=function(){
		if(4 == xmlhttp.readyState){
			console.log(xmlhttp.responseText);
			document.getElementById("update"+user).disabled = true;
		}
	};
}