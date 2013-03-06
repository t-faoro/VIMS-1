<?php
// ============================================================================
/*
 * mod_varFunctions.php
 * file contains library of functions for interfacing with the table
 * 'Modification_Var' in the 'vims' database
 * Programmed by James P. Smith March 2013
 * 
 * */
// ============================================================================
/*
 * List of functions & parameters
 * 
 * 	mod_varCreate()
 * 
 * */

// ============================================================================
//								Functions
// ============================================================================
/** 
 *	mod_varCreate() builds an sql statement to create a new entry in the 
 * 		'Modification_Var' table
 *	@param $varID  contains varID [int]
 * 	@param $userID contains user ID [int]
 * 	@param $action contains string for fields changed in var [string]
 * 	@param $con	   database connection [resource]
 * 
 *	@return	$sql  containing sql statement
 */


function mod_varCreate($varID, $userID, $action, $con)
{
	$action = mysqli_real_escape_string($con, $action);
	
	// build statement
	$sql  = "INSERT INTO modification_var";
	$sql .= " (Var_VAR_ID, User_USE_ID, MOD_Action)";
	$sql .= " VALUES( " . $varID . ", " . $userID . ", '" . $action . "')";
	
	return $sql;
}
