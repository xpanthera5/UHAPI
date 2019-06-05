
<?php
	$handle = fopen("users.txt", "r");

	while ($userinfo = fscanf($handle, "%s\t%s\t%s\n")) {
	    list($name, $profession, $countrycode) = $userinfo;
	    echo $name.' '.$profession.' '.$countrycode;
	}

	fclose($handle);
