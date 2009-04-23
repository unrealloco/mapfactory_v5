<?php

	require_once '../../../../inc/conf.php';

	header('Content-Type: text/xml');
	header('Cache-Control: no-cache');

    $tpl->assignTemplate('list.xml');

    $rs = $db->select('SELECT id, name FROM ' . $_POST['table'] . ' WHERE parent_id=' . $_POST['id']);

    foreach ($rs['result'] as $item){
        $tpl->assignLoopVar('item', array(
            'id' => $item['id'],
            'file' => $_POST['folder'].'original/'.$item['id'].'.jpg',
            'name' => $item['name']
        ));
    }
    
    $tpl->display();
