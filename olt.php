<?php

echo '<pre>';

$command = escapeshellcmd('/usr/bin/python /var/www/ilunne/boss/py/nexus.py 10.169.1.13');
$output = shell_exec($command);
	echo $output;


echo '</pre>';
?>