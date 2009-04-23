<?php

    require_once '../../inc/conf.php';

    header("Cache-Control: no-cache");

    $set = '';
    foreach ($_POST['data'] as $key => $value){
        $set .= $key.'="'.$value.'",';
    }
    $set = substr($set, 0, -1);

    if (isOk($_POST['guid']))
    {
        $set .= ',guid="' . makeGUID($_POST['data'][$_POST['guid']]) . '"';
    }

    $db->update('UPDATE '.$_POST['type'].' SET '.$set.' WHERE id='.$_POST['id']);
