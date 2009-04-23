<?php

	require_once '../../../../inc/conf.php';
	
	if ($_POST['selected_image_id'] == $_POST['image_id']){
	   	$db->update('UPDATE ' . $_POST['type'] . ' SET ' . $_POST['field'] . '=0 WHERE id=' . $_POST['id']);
	}
	
	$db->delete('DELETE FROM ' . $_POST['table'] . ' WHERE id=' . $_POST['image_id']);

	$dir = ROOT_DIR.$_POST['folder'];
    $sizeList = json_decode(stripslashes($_POST['size']));

	$image = array(
	    ROOT_DIR.'backoffice/img/preview/'.$_POST['table'].'_'.$_POST['image_id'].'.jpg',
	    $dir.'original/'.$_POST['image_id'].'.jpg',
	    $dir.'original/'.$_POST['image_id'].'.jpeg',
	    $dir.'original/'.$_POST['image_id'].'.png',
	    $dir.'original/'.$_POST['image_id'].'.gif'
	);
	
	foreach ($image as $file){
    	if (file_exists ($file)){
    		unlink ($file);
    	}
    }

	foreach ($sizeList as $size){
	    $size = explode('x', $size);
		$dest = $dir.$size[0].((isset ($size[1]))?'x'.$size[1]:'').'/'.$_POST['image_id'].'.jpg';

		if (file_exists ($dest)){
			unlink ($dest);
		}
	}
