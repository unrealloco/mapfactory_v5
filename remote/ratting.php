<?php

    require '../inc/conf.php';

    $rs = $db->select('SELECT id FROM map_activity WHERE map_id=' . $_POST['mapId'] . ' AND date=' . (time() - (time() % 86400)) . ' AND type="vote"');

    if ($rs['total'] == 0)
    {
       $db->insert('INSERT INTO map_activity SET map_id=' . $_POST['mapId'] . ', date=' . (time() - (time() % 86400)) . ', total=1, type="vote"');
    }
    else
    {
        $db->update('UPDATE map_activity SET total=total+1 WHERE id=' . $rs['result'][0]['id']);
    }

    if (!isOK($_SESSION['ratting']))
    {
        $_SESSION['ratting'] = '';
    }

    $_SESSION['ratting'] .= '.' . $_POST['mapId'] . '-' . $_POST['n'];

    $rs = $db->select('SELECT * FROM map_ratting WHERE map_id=' . $_POST['mapId']);

    if ($rs['total'] == 0)
    {
        echo $_POST['s'];
        $db->insert('INSERT INTO map_ratting SET map_id=' . $_POST['mapId'] . ', point_' . $_POST['n'] . '=' . $_POST['s'] . ', hint_' . $_POST['n'] . '=1');
    }
    else
    {
        $db->update('UPDATE map_ratting SET point_' . $_POST['n'] . '=point_' . $_POST['n'] . '+' . $_POST['s'] . ', hint_' . $_POST['n'] . '=hint_' . $_POST['n'] . '+1 WHERE map_id=' . $_POST['mapId']);

        echo round(($rs['result'][0]['point_' . $_POST['n']] + $_POST['s']) / ($rs['result'][0]['hint_' . $_POST['n']] + 1));
    }

    $avgHint = 0;
    $avgPoint = 0;

    for ($i = 0; $i < 5; $i ++)
    {
        $avgHint += $rs['result'][0]['hint_' . $i];
        $avgPoint += $rs['result'][0]['point_' . $i];
    }

    $db->update('UPDATE map SET ratting = ' . (($avgHint == 0) ? 0 : ($avgPoint / $avgHint)) . ' WHERE id=' . $_POST['mapId']);

