<?php 
/* $Id: */
//Copyright (C) 2009 Ethan Schreoder (ethan.schroeder@schmoozecom.com)
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of version 2 of the GNU General Public
//License as published by the Free Software Foundation.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

//Both of these are used for switch on config.php

function restart_get_config($engine) {
	global $db;
	global $ext; 
	switch($engine) {
		case "asterisk":
			//generate dialplan here
			
		break;
	}
}

function restart_get_devices($grp) {
	global $db;

	$sql = "SELECT * FROM devices";
	$results = $db->getAll($sql);
	if(DB::IsError($results)) 
		$results = null;
	foreach ($results as $val)
		$tmparray[] = $val[0];
	return $tmparray;
}

function get_device_useragent($device)  {
	global $astman;
	$response = $astman->send_request('Command',array('Command'=>"sip show peer $device"));
	$astout = explode("\n",$response['data']);
	$ua = "";
	foreach($astout as $entry)  {
		if(eregi("useragent",$entry))  {
			list(,$value) = split(":",$entry);
			$ua = trim($value);
		}
	}
	if($ua)  {

		if(stristr($ua,"Aastra")) {
			return "aastra";
		}
		if(stristr($ua,"Grandstream")) {
			return "grandstream";
		}
		if(stristr($ua,"snom"))  {
			return "snom";
		}
		if(stristr($ua,"Cisco"))  {
			return "cisco";
		}
		if(stristr($ua,"Polycom"))  {
			return "polycom";
		}
	}
	return null;
}
function restart_device($device)  {
	$ua = get_device_useragent($device);
	switch($ua)  {
		case "aastra":
			sip_notify("aastra-check-cfg",$device);
			break;
		case "grandstream":
			sip_notify("grandstream-check-cfg",$device);
			break;
		case "snom":
			sip_notify("reboot-snom",$device);
			break;
		case "cisco":
			sip_notify("cisco-check-cfg",$device);
			break;
		case "polycom":
			sip_notify("polycom-check-cfg",$device);
			break;
		default:
			break;

	}
}
function sip_notify($event,$device)  {
	global $astman;

	$command = 'sip notify '.$event;
	$command .= ' '.$device;

	// Send command
	$res = $astman->Command($command);
}

?>
