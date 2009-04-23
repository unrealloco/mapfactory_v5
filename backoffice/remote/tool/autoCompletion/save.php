<?php

	require_once '../../../../inc/conf.php';

	header('Cache-Control: no-cache');

    echo $db->insert('INSERT INTO ' . $_POST['table'] . ' SET '.$_POST['table_field'].'="' . $_POST['value'] . '"');
