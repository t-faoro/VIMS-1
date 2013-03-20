<?php

class Por {
	
	private $num;
	private $id;
	private $name;
	private $involved;
	private $phone;
	private $license;
	private $notes;
	
	function Por($num, $details)
	{
		
		$this->num      = $num;
		$this->id       = $details[0];
		$this->name     = $details[1];
		$this->involved = $details[2];
		$this->phone    = $details[3];
		$this->license  = $details[4];
		$this->notes    = $details[5];
	}
	
	function drawPor(){
		$html .= "<div id='ImgField'>\n";
$html .= "		<div class='label'>Add any images you may have relating to the incident: </div><br />\n";

$html .= "<div id='ImgLines'>\n";
$html .= "	<div class='imgRec'>\n";
$html .= "		<span id='imgRec1'>\n";
$html .= "			<div class='ImgLabel'>Image 1: </div><br />\n";
$html .= "			<input type='file' name='img1' id='img1'><br />";
$html .= "			<label>Image Description: </label><br />\n";
$html .= "			<textarea name='imgDesc1'></textarea>";
$html .= "		</span>\n";
$html .= "	</div>\n"; // close imgRec
$html .= "</div>"; // close imgLines

$html .= "<input type='button' value='Add Another Image' id='addImg'>\n";
$html .= "<input type='button' value='Remove Last Image' id='removeImg'>\n";

$html .= "</div>\n"; // close imgField
$html .= "		<div id='clear'></div>\n"; // close clear
 
		
		return $html;
	}

	function chgNum($num){
		$this->num = $num;
	}
	
	function getPor()
	{
		$details[0] = $this->id;
		$details[1] = $this->name;
		$details[2] = $this->involved;
		$details[3] = $this->phone;
		$details[4] = $this->license;
		$details[5] = $this->notes;
		
		return $details;
		
	}
	
}

?>