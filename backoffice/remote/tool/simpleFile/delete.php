<?php

	require_once '../../../../inc/conf.php';
	
	$db->delete('DELETE FROM ' . $_POST['table'] . ' WHERE id=' . $_POST['id']);
    
    $file = $_POST['folder'].$_POST['id'].'.'.$_POST['extention'];

    if (file_exists (ROOT_DIR.$file)){
		unlink (ROOT_DIR.$file);
	}
