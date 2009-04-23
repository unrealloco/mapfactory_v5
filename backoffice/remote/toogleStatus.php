<?php

    require_once '../../inc/conf.php';

    header("Cache-Control: no-cache");

    $db->update('UPDATE '.$_POST['type'].' SET status=(status+1)%2 WHERE id='.$_POST['id']);

