<?php

	require_once '../../../../inc/conf.php';
	
	$db->update('UPDATE ' . $_POST['type'] . ' SET ' . $_POST['field'] . '=' . $_POST['image_id'] . ' WHERE id=' . $_POST['id']);
