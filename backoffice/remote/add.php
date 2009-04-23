<?php

    require_once '../../inc/conf.php';

    header('Content-Type: text/xml');
    header("Cache-Control: no-cache");

    $id = $db->insert('INSERT INTO '.$_POST['type'].' SET ' . $_POST['previewField'] . '="'.$_POST['title'].'"' . ((isOk($_POST['guid'])) ? ', guid="' . makeGUID($_POST['title']) . '"' : ''));

    $rs = $db->select('SELECT * FROM '.$_POST['type'].' WHERE id='.$id);

    $doc = new DomDocument('1.0');
    $root = $doc->appendChild($doc->createElement('root'));
    foreach ($rs['result'] as $row){
        $item = $root->appendChild($doc->createElement('item'));
        foreach ($row as $name => $value){
            $item->appendChild($doc->createElement($name))->appendChild($doc->createTextNode($value));
        }
    }
    
    // faster but we do not retrieve default values, just table columns
    /*$rs = $db->select('SELECT COLUMN_NAME
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name="'.$_POST['type'].'"');

    $doc = new DomDocument('1.0');
    $root = $doc->appendChild($doc->createElement('root'));
    $item = $root->appendChild($doc->createElement('item'));
    foreach ($rs['result'] as $row){
        foreach ($row as $value){
            $item->appendChild($doc->createElement($value))->appendChild($doc->createTextNode(($value == 'id')?$id:''));
        }
    }*/

    //$doc->formatOutput = true;
    echo $doc->saveXML();
