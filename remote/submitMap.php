<?php

    require '../inc/conf.php';

    header('Content-Type: application/json; charset=utf-8');
	header('Cache-Control: no-cache');

    if (!isOk($_POST['game']) || !isOk($_POST['gametype']) || !isOk($_POST['author']) || !isOk($_POST['title']))
    {
        echo '0';
        exit();
    }


//    // RETRIEVE GAME ID
//
//    $rs = $db->select('SELECT id FROM game WHERE name="' . $_POST['game'] . '"');
//
//    if ($rs['total'] != 0)
//    {
//        $game_id = $rs['result'][0]['id'];
//    }
//    else
//    {
//        $game_id = $db->insert('INSERT INTO game SET name="' . $_POST['game'] . '", guid="' . makeGuid($_POST['game']) . '"');
//        $data['game_id'] = $game_id;
//    }
//
//
//    // RETRIEVE GAMETYPE ID
//
//    $rs = $db->select('SELECT id FROM gametype WHERE name="' . $_POST['gametype'] . '"');
//
//    if ($rs['total'] != 0)
//    {
//        $gametype_id = $rs['result'][0]['id'];
//    }
//    else
//    {
//        $gametype_id = $db->insert('INSERT INTO gametype SET name="' . $_POST['gametype'] . '", guid="' . makeGuid($_POST['gametype']) . '"');
//        $data['gametype_id'] = $gametype_id;
//    }


    $game_id = $data['game_id'] = $_POST['game'];
    $gametype_id = $data['gametype_id'] = $_POST['gametype'];


    // RETRIEVE HAUTHOR ID

    $rs = $db->select('SELECT id FROM author WHERE name="' . $_POST['author'] . '"');

    if ($rs['total'] != 0)
    {
        $author_id = $rs['result'][0]['id'];
    }
    else
    {
        $author_id = $db->insert('INSERT INTO author SET name="' . $_POST['author'] . '"');
        $data['author_id'] = $author_id;
    }


    // DESCRIPTION

    if (isOk($_POST['description']))
    {
        $description = ereg_replace("[\n\r]", "\t", $description);
        $description = ereg_replace("\t\t+", "\n", $description);
        $description = '<p>' . str_replace('<br /><br />', '</p><p>', nl2br(trim($_POST['description']))) . '</p>';
    }
    else
    {
        $description = '';
    }


    // INSERT

    $id = $db->insert('
        INSERT INTO map SET
        date            = "' . time() . '",
        date_created    = "' . time() . '",
        title           = "' . $_POST['title'] . '",
        guid            = "' . makeGUID($_POST['title']) . '",
        description     = "' . $description . '",
        game_id         = "' . $game_id . '",
        gametype_id     = "' . $gametype_id . '",
        author_id       = "' . $author_id . '",
        mode_id         = "0",
        image_id        = "0"
    ');

    $data['map_id'] = $id;

    echo json_encode($data);

