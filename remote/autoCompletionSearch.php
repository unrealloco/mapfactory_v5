<?php

    require '../inc/conf.php';

    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-cache');

    $rs = $db->select('SELECT '.$_POST['field'].' AS value FROM ' . $_POST['table'] . ' WHERE '.$_POST['field'].' LIKE "' . $_POST['value'] . '%"', 0, 12);

    echo json_encode($rs['result']);

