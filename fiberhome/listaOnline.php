<?php
var_dump($_GET);

$command = '/usr/bin/python /var/www/ilunne/boss/py/descobertafh.py '.$_GET[OLT];

$output = shell_exec($command);
		
var_dump($output);
?>