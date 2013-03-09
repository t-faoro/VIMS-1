function updateStatus(id)
{
	var status = document.getElementById(id).value; 
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "php/updateVenueStatus.php");
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("id="+id+"&status="+status); 
}