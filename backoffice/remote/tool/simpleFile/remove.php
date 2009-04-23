<?php

	require_once '../../../../inc/conf.php';
	
	$rs = $db->select('SELECT id FROM ' . $_GET['table'] . ' WHERE parent_id=' . $_GET['id']);
echo 'SELECT id FROM ' . $_GET['table'] . ' WHERE parent_id=' . $_GET['id'].'#';
	$db->delete('DELETE FROM ' . $_GET['table'] . ' WHERE parent_id=' . $_GET['id']);
echo 'DELETE FROM ' . $_GET['table'] . ' WHERE parent_id=' . $_GET['id'].'#';
    foreach($rs['result'] as $item){    
        $file = $_GET['folder'].$item['id'].'.'.$_GET['extention'];
    echo ROOT_DIR.$file.'#';
        if (file_exists (ROOT_DIR.$file)){
    		unlink (ROOT_DIR.$file);
    	}
	}
