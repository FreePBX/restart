<?php

if (! function_exists("out")) {
	function out($text) {
		echo $text."<br />";
	}
}

if (! function_exists("outn")) {
	function outn($text) {
		echo $text;
	}
}
global $amp_conf;

$src = dirname(__FILE__).'/etc/sip_notify.conf';
$dest = $amp_conf['ASTETCDIR'].'/sip_notify.conf';

if (is_link($dest) && readlink($dest) == $src) {
  outn(_("removing symlink to $dest.."));
  if (unlink($dest)) {
    out(_("ok"));
  } else {
    out(_("FAILED"));
    out(_("You may have to remove $dest manually"));
  }
}
?>
