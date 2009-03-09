<?php
// Backup old sip_notify.conf file so FreePBX can write a new one
global $amp_conf;
$astetc = $amp_conf['ASTETCDIR'];
rename($astetc."/sip_notify.conf",$astetc."/sip_notify.conf.bak");

?>
