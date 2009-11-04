<?php

    require_once '../../inc/conf.php';

/*     header('Content-Type: text/xml'); */
    header('Content-Type: application/json; charset=utf-8');
    header("Cache-Control: no-cache");

    set_time_limit(30);
    ini_set("memory_limit",'8M');

    $rs = $db->select('SELECT * FROM '.$_POST['type'].' ORDER BY '.$_POST['orderBy'].' '.$_POST['sortOrder']);

/*
    $doc = new DomDocument('1.0');
    $root = $doc->appendChild($doc->createElement('root'));
    foreach ($rs['result'] as $row){
        $item = $root->appendChild($doc->createElement('item'));
        foreach ($row as $name => $value){
            $item->appendChild($doc->createElement($name))->appendChild($doc->createTextNode($value));
        }
    }

    //$doc->formatOutput = true;
    echo $doc->saveXML();
*/

    echo json_encode($rs['result']);
