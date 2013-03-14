<?php
/**
* Returns a string that will make up the top-of-page HTML markup, the parameter
* $title will set the page's title at the top of the browser.
* 
* @param $title
* @return $markUp
*/
function setSiteInfo($title){		
	global $styleSheet;
	global $javaScript;
	
	$markUp  = "<html>\n";
	$markUp .= "<head>\n";
	$markUp .= '<title>'.$title.'</title>'."\n";
	$markUp .= $styleSheet;
	$markUp .= $javaScript;
	return $markUp;
}


/**
* Returns a string that will make up the body-of-page HTML markup when called
* (Will possibly put page content into a class so it can have data added to it dynamically) 
*
* @return $markUp
*/
function siteContent(){
		
	$markUp = "</head>";
	$markUp .= "<body>";
	
	return $markUp;
}


/**
* Returns a string that will pass CSS style sheet include information to the front end. This function
* will properly append to the end of the last called CSS() command and show properly justified within the 
* page source when viewed.
* 
* @param  $styleName
* @return $styleSheet
*/
function CSS($styleName){
	$styleSheet = "";

	$styleSheet .= '<link rel="stylesheet" type="text/css" href="'.CSS_PATH.$styleName.'">';
	$styleSheet .= "\n";
	
	return $styleSheet;
}


/**
* Returns a string that will pass JavaScript include information to the front end. This function
* will properly append to the end of the last called JS() command and show properly justified within the 
* page source when viewed.
* 
* @param  $scriptName
* @return $javaScript
*/
function JS($scriptName){
$javaScript = "";	

	$javaScript .= '<script type="text/javascript" src="'.JS_PATH.$scriptName.'">';
	$javaScript .= "</script>\n";
	return $javaScript;
}


/**
* Returns a string that will pass Image information to the front end in the form of a <IMG /> tag. 
* this function will accept two strings as parameters, $imgName which will be the image file name (with extenstion)
* and $alt will be the ALT value of the image to be presented.
*
* Example Use: IMG("image.png", "Example Image");
* 
* @param  $imgName
* @param  $alt
* @return $image
*/
function IMG($imgName, $alt){
$image;

	$image = '<img src="'.IMG_PATH.$imgName.'" alt="'.$alt.'" />\n';
	return $image;
}


/**
	Verifies that all necessary session variables are set.
	Session must be started before calling this function
	
	This code will verify the session is set properly and redirect
	the user to index.php to authentificate if their are any issues
	
	if(!verifyUser()) header('location:index.php');
	
	@return true if session variables are set, false otherwise
*/
function verifyUser()
{
	if(  isset($_SESSION['userName'])
		&& isset($_SESSION['userId'])
		&& isset($_SESSION['userFname'])
		&& isset($_SESSION['userLname'])
		&& isset($_SESSION['userAuth'])
		&& isset($_SESSION['venueName'])
		&& isset($_SESSION['venueId'])
	) return true;
	else return false;
}

/**
 * returns a string containing a better format of a date than what 
 * 	you get from a mysql datetime
 * @param $date	a date string
 * 
 * @return $showDate a better date string
 */
function niceDate($date)
{
	$date_ts    = strtotime($date);

	$showDate    = date('Y-m-d', $date_ts);

	
	return $showDate;
}

?>