<?php

    require_once '../../inc/conf.php';

    header("Cache-Control: no-cache");

    $db->delete('DELETE FROM '.$_POST['type'].' WHERE id='.$_POST['id']);
