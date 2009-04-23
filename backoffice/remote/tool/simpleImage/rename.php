<?php

	require_once '../../../../inc/conf.php';
	
	$db->update('UPDATE ' . $_POST['table'] . ' SET name="' . $_POST['name'] . '" WHERE id=' . $_POST['id']);
