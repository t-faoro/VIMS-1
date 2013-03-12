function updateStatus(id)
{
	var status = document.getElementById(id).value; 
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "php/updateVenueStatus.php");
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("id="+id+"&status="+status); 
}

function deleteUser(id)
{
	if(!confirm("are you sure you want to delete this user?"))
	{
		return false;
	}
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "php/deleteUser.php");
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("id="+id); 
	xmlhttp.onreadystatechange=function()
	{
		if(4 == xmlhttp.readyState)
		{
			console.log(xmlhttp.responseText);
			if(xmlhttp.responseText)
			{
				console.log("fjoidsuofhpowe");
			}
		}
	};
}