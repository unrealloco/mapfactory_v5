<?php

    require '../inc/conf.php';

    header('Cache-Control: no-cache');

    set_time_limit(30);
	ini_set("memory_limit",'64M');

    $id = $db->insert('INSERT INTO map_file SET parent_id=' . $_POST['mapId'] . ', name="submited by a visitor"');

    $dest = 'media/map/' . $id . '.zip';

    if (file_exists(ROOT_DIR.$dest)){
		unlink(ROOT_DIR.$dest);
	}
	move_uploaded_file($_FILES['Filedata']['tmp_name'], ROOT_DIR.$dest);

    $headers = "MIME-Version: 1.0\n";
	$headers .= "content-type: text/html; charset=iso-8859-1\n";
	$headers .= "From: Map Factory <".ADMIN_EMAIL.">\n";
	$content = 'Sent from '.$_SERVER['REMOTE_ADDR'];

	mail (ADMIN_EMAIL, 'MapFactory - NEW MAP', $content, $headers);

    echo $id;
