<?php

	require_once '../../../../inc/conf.php';
	
	header("Cache-Control: no-cache");
	header("HTTP/1.0 200 OK");
	
	set_time_limit(60);
	ini_set("memory_limit",'64M');
	
	if ($_GET['multipleFiles'] == 0){
        include 'remove.php';
    }
	
	$name = makeGuid(preg_replace('#(.+)\.[a-zA-Z]+$#isU', '$1', $_FILES['Filedata']['name']));
	$id = $db->insert('INSERT INTO ' . $_GET['table'] . ' SET parent_id=' . $_GET['id'] . ', name="' . $name . '"');
	$dir = ROOT_DIR.$_GET['folder'];
    $sizeList = json_decode(stripslashes($_GET['size']));
    $extention = strtolower(preg_replace ('#.+\.([a-zA-Z]+)$#isU', '$1', $_FILES['Filedata']['name']));

	$image = $dir.'original/'.$id.'.'.$extention;
	if (file_exists($image)){
		unlink($image);
	}
	move_uploaded_file($_FILES['Filedata']['tmp_name'], $image);

	foreach ($sizeList as $size){
	    $size = explode('x', $size);
		$dest = $dir.$size[0].((isset ($size[1]))?'x'.$size[1]:'').'/'.$id.'.jpg';
		
		if (file_exists ($dest)){
			unlink($dest);
		}
		redimage($image, $dest, $size[0], (isset ($size[1]))?$size[1]:false);
	}
	
	redimage($image, ROOT_DIR.'backoffice/img/preview/'.$_GET['table'].'_'.$id.'.jpg', 80, 80);
    
	echo $id;
