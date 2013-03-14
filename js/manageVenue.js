function updateStatus(venue)
{
	var status = document.getElementById(venue).value; 
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "php/updateVenueStatus.php");
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("id="+venue+"&status="+status); 
}

function deleteUser(user, venue)
{
	if(!confirm("are you sure you want to delete this user?"))
	{
		return false;
	}
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "php/deleteUser.php");
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("id="+user+"&venue="+venue); 
	xmlhttp.onreadystatechange=function()
	{
		if(4 == xmlhttp.readyState)
		{
			if(xmlhttp.responseText)
			{
				var userRow = document.getElementById(user);
				userRow.parentNode.removeChild(userRow);
			}
		}
	};
}

function enableSaveButton(user)
{
	document.getElementById("update"+user).disabled = false;
}

function updateUser(user, venue)
{
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
	xmlhttp.onreadystatechange=function()
	{
		if(4 == xmlhttp.readyState)
		{
			console.log(xmlhttp.responseText);
			document.getElementById("update"+user).disabled = true;
		}
	};
}