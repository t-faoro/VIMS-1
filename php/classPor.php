<?php
/**
 * classPor.php
 * class for creating person of record objects
 * @author James P. Smith
 * Programmed March 2013
 */


class Por {
	
	private $num;
	private $id;
	private $name;
	private $involved;
	private $phone;
	private $license;
	private $notes;
	private $ineID;
	private $varID;
	
	/**
	 * Constructor for Por object
	 * @param $num		number to display on print out (eg. Person 1, Person 2)
	 * @param $details	an array with data to set Por object private variables
	 */
	function Por($num, $details)
	{
		
		$this->num      = $num;
		$this->id       = $details[0];
		$this->name     = $details[1];
		$this->involved = $details[2];
		$this->phone    = $details[3];
		$this->license  = $details[4];
		$this->notes    = $details[5];
		$this->ineID	= $details[6];
		$this->varID	= $details[7];
	}
	
	
	/**
	 * creates Por html
	 * @param type	used to disable data inputs in form
	 */
	function drawPor($type){
		if($type == 'disabled' ? $disabled = ' disabled ' : $disabled = null);
		$html  = "<form action='managePor.php' method='GET'>\n";
		$html .= "<div id='PorLines'>\n";
		$html .= "	<div class='porRec'>\n";
		$html .= "		<span id='PorRec" . $this->num . "'>\n";
		$html .= "		<div class='PorLabel'>Person " . $this->num . "</div><br />\n"; // close PorLabel
		$html .= "			<span>Name: </span><input " . $disabled . "type='textbox' value='" . $this->name . "' name='porName'>\n";
		$html .= " 			<span>Involvement: </span><select " . $disabled . "name='porInv'>\n";
		$html .= "											<option value='1'";
		if($this->involved == 1) $html .= " selected='selected'";
		$html .= ">Witness</option>\n";
		$html .= "											<option value='2'";
		if($this->involved == 2) $html .= " selected='selected'";
		$html .= ">Victim</option>\n";
		$html .= "											<option value='3'";
		if($this->involved == 3) $html .= " selected='selected'";
		$html .= ">Instigator</option>\n";
		$html .= "											<option value='4'";
		if($this->involved == 4) $html .= " selected='selected'";
		$html .= ">Agressor</option>\n";
		$html .= "										</select>\n";
		$html .= " 			<span>Phone: </span><input " . $disabled . "type='textbox' value='" . $this->phone . "' name='porPhone'>\n";
		$html .= " 			<span>License: </span><input  " . $disabled . "type='textbox' value='" . $this->license . "' name='porLicense'><br />\n";
		$html .= "			<span>*Notes: </span><br />\n";
		$html .= "			<textarea " . $disabled . "name='porNotes'>" . $this->notes . "</textarea>\n";
		
		
		if($disabled == null)
		{
			$html .= "<input class='floatButton' type='submit' name='action' value='Cancel'>\n";
			$html .= "<input class='floatButton' type='submit' name='action' value='Save'>\n";
		} else 
		{
			$link  = "deletePor.php?ineID=$this->ineID&varID=$this->varID&porID=$this->id";
			//$html .= "<a href='$link'><button class='floatButton'>Delete</button></a>\n";
			$html .= "<input class='floatButton' type='submit' name='action' value='Delete'>\n";
			$html .= "<input class='floatButton' type='submit' name='action' value='Update'>\n";
		}
		if($this->id != null) $html .= "<input type='hidden' name='porID' value='$this->id'>\n";
		
		$html .= "		</span>\n";
		$html .= "	</div>\n"; // close PorRec
		$html .= "</div>"; // close PorLines
		$html .= "<input type='hidden' name='ineID' value='$this->ineID'>";
		$html .= "<input type='hidden' name='varID' value='$this->varID'>";
		$html .= "</form>\n";
		
		return $html;
	}

	/**
	 * outputs all the Por object's data into an array
	 * @return details	an array containing all the private variables 
	 */
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