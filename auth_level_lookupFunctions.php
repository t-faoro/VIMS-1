<?php
// ============================================================================
/*
 * auth_level_lookupFunctions.php
 * file contains library of functions for interfacing with the table
 * 'Auth_Level_Lookup' in the 'vims' database
 * Programmed by James P. Smith March 2013
 * 
 * */
// ============================================================================
/*
 * List of functions & parameters
 * 
 * 	auth_level_lookupRead()
 * 
 * */

// ============================================================================
//								Functions
// ============================================================================
/* 
 *	auth_level_lookupRead() builds an sql statement to read the auth_level_lookup
 * 		table
 *	Parameters: none
 *	Returns:	$sql  containing sql statement STRING
 **/


function auth_level_lookupRead()
{
	// build statement
	$sql  = "SELECT * FROM auth_level_lookup";
	$sql .= " WHERE (AUT_Level > 0";
	$sql .= " AND AUT_Level < 99)";
	
	return $sql;
}
