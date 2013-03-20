<?php
/**
 * classIncident.php
 * class for creating incident objects
 * @author James P. Smith
 * Programmed March 2013
 */
class Incident {
	
	private $id;
	private $venueName;
	private $venueAddr;
	private $involved;
	private $venueLoc;
	private $reportDate;
	private $supervisor;
	private $event;
	private $hourMin;
	private $pm;
	private $level;
	private $police;
	private $content;
	private $damages;
	private $completed;  
	private $liason;
	private $action;
	private $varID;
	
	/**
	 * Constructor function
	 * @param details	array containing all data for setting private variables
	 */
	function Incident($details)
	{
		
		$this->id      	   = $details[0];
		$this->venueName   = $details[1];
		$this->venueAddr   = $details[2];
		$this->venueLoc    = $details[3];
		$this->reportDate  = $details[4];
		$this->supervisor  = $details[5];
		$this->event	   = $details[6];
		$this->hourMin	   = $details[7];
		$this->pm		   = $details[8];
		$this->level  	   = $details[9];
		$this->police      = $details[10];
		$this->content	   = $details[11];
		$this->damages     = $details[12];
		$this->completed   = $details[13];
		$this->liason	   = $details[14];	
		$this->action	   = $details[15];
		$this->varID	   = $details[16];
	}
	
	/**
	 * function for getting all private variables
	 * @return details	an array containing all object variables
	 */
	function getIncident()
	{
		$details[0]  = $this->id;
		$details[1]  = $this->venueName;
		$details[2]  = $this->venueAddr;
		$details[3]  = $this->venueLoc;
		$details[4]  = $this->reportDate;
		$details[5]  = $this->event;
		$details[6]  = $this->supervisor;
		$details[7]  = $this->hourMin;
		$details[8]  = $this->pm;
		$details[9]  = $this->level;
		$details[10] = $this->police;
		$details[11] = $this->content;
		$details[12] = $this->damages;
		$details[13] = $this->completed;
		$details[14] = $this->liason;
		$details[15] = $this->action;
		
		return $details;
		
	}
	
	/**
	 * function for creating html form for incident input
	 * @param IneLevels	array containing the incident level definitions for combo box
	 */
	function drawIncident($IneLevels){
		$html  = "<h4>Clubwatch | Venue Information Management System | Security Incident Report Form</h4>\n";
		$html .= "	<div  id='IneTitle'>\n";
		$html .= "		<h3>Incident Report</h3>\n";
		$html .= "	</div>\n"; // close IneTitle
		
		$html .= "		<div class='smallRightField'>\n";
		$html .= "		<div class='label'>Venue Name: </div><br />\n";
		$html .= "		<span class='tab'>" . $this->venueName . "</span>\n";
		$html .= "		</div>\n"; // close smallRightField
		
		$html .= "		<div class='smallRightField'>\n";
		$html .= "		<div class='label'>Venue Address: </div><br />\n";
		$html .= "		<span class='tab'>" . $this->venueAddr . "</span>\n";
		$html .= "		</div>\n"; // close smallRightField
		
		$html .= "		<div class='smallRightField'>\n";
		$html .= "		<div class='label'>Venue City &amp Province: </div><br />\n";
		$html .= "		<span class='tab'>" . $this->venueLoc . "</span>\n";
		$html .= "		</div>\n"; // close smallRightField
		
		$html .= "		<div class='smallRightField'>\n";
		$html .= "		<div class='label'>Report Date: </div><br />\n";
		$html .= "		<span class='tab'>" . nicedate($this->reportDate). "</span>\n";
		$html .= "		</div>\n"; // close smallRightField
		
		$html .= "		<div id='clear'></div>\n"; // close clear
		
		$html .= "		<div class='smallLeftField'>\n";
		$html .= "		<div class='label'>Event Occuring: </div><br />\n";
		$html .= "		<span class='tab'>" . $this->event . "</span>\n";
		$html .= "		</div>\n"; // close smallRightField
		
		$html .= "		<div class='smallRightField'>\n";
		$html .= "		<div class='label'>Supervisor on Shift: </div><br />\n";
		$html .= "		<span class='tab'>" . $this->supervisor . "</span>\n";
		$html .= "		</div>\n"; // close smallRightField
		
		$html .= "		<div id='clear'></div>"; // close clear
		
		$html .= "		<div id='TimeField'>\n";
		$html .= "		<div class='label'>Time Incident Occurred: </div><br />\n";
		$html .= "		<input type='text' name='time' value='" . $this->hourMin . "'>\n";
		$html .= "		<input type='radio' name='pm' value=0";
		if($this->pm == 0)$html .= " checked='checked'";
		$html .= ">am\n";
		$html .= "		<input type='radio' name='pm' value=1";
		if($this->pm == 1)$html .= " checked='checked'";
		$html .= ">pm\n";
		$html .= "		</div>\n"; // close TimeField
		
		$html .= "		<div id='SeverityField'>\n";
		$html .= "		<div class='label'>Level of Severity: </div><br />\n";
		$html .= "		<select name='severity'>\n";
		$i = 1;
		foreach ($IneLevels as $key => $value)
		{
			$html .= "			<option value=" . $i;
			if($i == $this->level) $html .= " selected='selected'";
			$html .= ">" . $value;
			$html .= "</option>\n";
			$i++;
		}
		$html .= "		</select>\n";
		$html .= "		</div>\n"; // close SeverityField
		
		$html .= "		<div id='clear'></div>"; // close clear
		
		$html .= "		<div id='PoliceField'>\n";
		$html .= "		<div class='label'>Where the police involved in this incident?</div><br />\n";
		$html .= "		<input type='radio' name='police' value=0";
		if($this->police == 0)$html .= " checked='checked'";
		$html .= ">No\n";
		$html .= "		<input type='radio' name='police' value=1";
		if($this->police == 1)$html .= " checked='checked'";
		$html .= ">Yes\n";
		$html .= "		</div>\n"; // close PoliceField
		
		$html .= "		<div id='SummaryField'>\n";
		$html .= "		<div class='label'>Provide a description of the incident</div><br />\n";
		$html .= "		<textarea name='Summary'>" . $this->content . "</textarea>\n";
		$html .= "		</div>\n"; // close SummaryField
		
		$html .= "		<div id='DamagesField'>\n";
		$html .= "		<div class='label'>Specify any damages incurred</div><br />\n";
		$html .= "		<textarea name='Damages'>" . $this->damages . "</textarea>\n";
		$html .= "		</div>\n"; // close DamagesField
		
		$html .= "		<div class='smallLeftField'>\n";
		$html .= "		<div class='label'>Form Completed by:</div><br />\n";
		$html .= "		<span class='tab'>" . $this->completed. "</span>\n";
		$html .= "		</div>\n"; // close CompletedField
		
		$html .= "		<div class='smallRightField'>\n";
		$html .= "		<div class='label'>Venue Contact: </div><br />\n";
		$html .= "		<span class='tab'>" . $this->liason . "</span>\n";
		$html .= "		</div>\n"; // close smallRightField
		
		$html .= "		<div id='clear'></div>\n"; // close clear
		
		$html .= "<input type='hidden' name='varID' value='" . $this->varID . "'>\n";
		if($this->id != null) $html .= "<input type='hidden' name='ineID' value='" . $this->id . "'>\n";
		$html .= "<input type='submit' class='bottomButton' name='action' value='Save'>\n";
		if($this->action != 'view') $html .= "<input type='submit' name='action' class='bottomButton' value='Cancel'>\n";
		if($this->action == 'view') $html .= "<input type='submit' name='action' class='bottomButton' value='Delete'>";
		
		return $html;
	}
	
}

?>