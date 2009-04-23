<?php

	require_once '../../../../inc/conf.php';

	header('Cache-Control: no-cache');

    $rs = $db->select('SELECT '.$_POST['table_field'].' AS value FROM ' . $_POST['table'] . ' WHERE id=' . $_POST['id']);

    echo $rs['result'][0]['value'];
