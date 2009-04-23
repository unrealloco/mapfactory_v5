<?php

	require_once '../../../../inc/conf.php';

	header('Content-Type: text/xml');
	header('Cache-Control: no-cache');

    $tpl->assignTemplate('search.xml');

    $rs = $db->select('SELECT id, '.$_POST['table_field'].' AS value FROM ' . $_POST['table'] . ' WHERE '.$_POST['table_field'].' LIKE "' . $_POST['value'] . '%"', 0, 12);

    foreach ($rs['result'] as $item){
        $tpl->assignLoopVar('item', array(
            'id' => $item['id'],
            'value' => $item['value']
        ));
    }
    
    $tpl->display();
