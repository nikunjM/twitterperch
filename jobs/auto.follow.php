<?php

/**
 * Twitter Perch - Auto Follow by Keyword on Twitter
 *
 * Copyright (c) 2009 Iarfhlaith Kelly (webstrong.ie)
 * Licensed under GPL (http://www.gnu.org/licenses/gpl.html) license.
 *
 * Date: 2009-02-17
 */

// Initialise Packages
require_once('../inc/initialise.php');
require_once('XML/RSS.php');
require_once('HTTP/Request.php');

// Initialise Defaults
$response	  = false;
$responseCode = false;
$responseBody = false;

// Start Twitter Perch
$tp = new twitterPerch;

$list = $tp->loadList();

foreach($list as $search)
{
	$q = urlencode($search['keyword']);
	
	$rss =& new XML_RSS("http://search.twitter.com/search.rss?q=".$q."&rpp=20&since_id=".$search['latestStatus']);
	$rss->parse();
	
	foreach ($rss->getItems() as $item)
	{
		// Get username from Link
		$tempname = substr($item['link'], 19 );
		$position = strpos($tempname    , '/');
		$username = substr($tempname    , 0, $position);
		
		// Get status from Link
		$start  = strrpos($item['link'], '/statuses/');
		$status =  substr($item['link'], ($start+10));
		
		// Follow User
		$req = new HTTP_Request('http://twitter.com/friendships/create/'.$username.'.xml');
		
		$req->setMethod(HTTP_REQUEST_METHOD_POST);
		$req->setBasicAuth($search['username'], $search['password']);
	
		$response     = $req->sendRequest();
		$responseCode = $req->getResponseCode();
		$responseBody = addslashes($req->getResponseBody());
		
		// Do something with the response if you want to...
	}
}

?>
