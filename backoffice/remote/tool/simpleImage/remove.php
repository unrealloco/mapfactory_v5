<?php

	require_once '../../../../inc/conf.php';
	
	$db->update('UPDATE ' . $_GET['type'] . ' SET ' . $_GET['field'] . '=0 WHERE id=' . $_GET['id']);
	
	$rs = $db->select('SELECT id FROM ' . $_GET['table'] . ' WHERE parent_id=' . $_GET['id']);
	
	$db->delete('DELETE FROM ' . $_GET['table'] . ' WHERE parent_id=' . $_GET['id']);

	$dir = ROOT_DIR.$_GET['folder'];
    $sizeList = json_decode(stripslashes($_GET['size']));

    foreach($rs['result'] as $item){    
    	$image = array(
    	    ROOT_DIR.'backoffice/img/preview/'.$_GET['table'].'_'.$item['id'].'.jpg',
    	    $dir.'original/'.$item['id'].'.jpg',
    	    $dir.'original/'.$item['id'].'.jpeg',
    	    $dir.'original/'.$item['id'].'.png',
    	    $dir.'original/'.$item['id'].'.gif'
    	);
    	
    	foreach ($image as $file){
        	if (file_exists ($file)){
        		unlink ($file);
        	}
        }
    
    	foreach ($sizeList as $size){
    	    $size = explode('x', $size);
    		$dest = $dir.$size[0].((isset ($size[1]))?'x'.$size[1]:'').'/'.$item['id'].'.jpg';
    
    		if (file_exists ($dest)){
    			unlink ($dest);
    		}
    	}
	}

	
