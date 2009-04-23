<?php

	require_once '../../../../inc/conf.php';
	
	header("Cache-Control: no-cache");
	header("HTTP/1.0 200 OK");
	
	set_time_limit(60);
	ini_set("memory_limit",'64M');

    if ($_GET['multipleFiles'] == 0){
        include 'remove.php';
    }

	$dir = $_GET['folder'];
    $name = makeGuid(preg_replace('#(.+)\.[a-zA-Z]+$#isU', '$1', $_FILES['Filedata']['name']));
	$extention = '.'.$_GET['extention'];
    $id = $db->insert('INSERT INTO ' . $_GET['table'] . ' SET parent_id=' . $_GET['id'] . ', name="' . $name . '"');
    $dest = $dir.$id.$extention;
    
    if (file_exists (ROOT_DIR.$dest)){
		unlink (ROOT_DIR.$dest);
	}
	move_uploaded_file ($_FILES['Filedata']['tmp_name'], ROOT_DIR.$dest);
    
    echo $dest;
