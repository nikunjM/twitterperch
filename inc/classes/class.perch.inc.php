<?php

/**
 * Twitter Perch - Auto Follow by Keyword on Twitter
 *
 * Copyright (c) 2009 Iarfhlaith Kelly (webstrong.ie)
 * Licensed under GPL (http://www.gnu.org/licenses/gpl.html) license.
 *
 * Date: 2009-02-17
 */

class twitterPerch
{
   /**
    * The class constructor.
	*
    */
	public function __construct(){}
	
   /**
    * Adds the user details to the list
    *
	* @access 	public
	* @param	array	$vars
	* @return 	boolean
    */
	public  function add($vars)
	{		
		$datetime = time();
		$dbh      = dbConnection::get()->handle();
		
		// Add Record
		$sql = "INSERT INTO searches
				 ( keyword
				 , username
				 , password
				 , datetime
				 , ip)
				 
				VALUES
				 ('{$vars['keyword']}'
				 ,'{$vars['username']}'
				 ,'{$vars['password']}'
				 ,'{$datetime}'
				 ,'{$_SERVER['REMOTE_ADDR']}')";
		
		$affected =& $dbh->exec($sql);
		if (PEAR::isError($affected)) return(false);
		
		return(true);
	}
	
	public  function loadList()
	{		
		$list = array();
		
		$dbh = dbConnection::get()->handle();
		
		$sql = "SELECT * FROM searches";
		
		// Execute the query
		$result =& $dbh->query($sql);
		
		while($row = $result->fetchRow())
		{
			array_push($list, $row);
		}
		
		return($list);
	}
}

?>