<?php

    include ('bloc/search.php');


    /////////////////////////
    // GAME AND GAMETYPE
    /////////////////////////

    $rs = $db->select('SELECT
        g.id,
        g.name

        FROM game   AS g
        JOIN map    AS m ON m.game_id=g.id

        WHERE     g.status=1
        AND       m.status=1
        AND       m.date < '.time ().'

        GROUP BY g.id
        ORDER BY g.name
    ');

    foreach($rs['result'] as $item)
    {
        $tpl->assignLoopVar('submit_game', array (
            'id' => $item['id'],
            'name' => $item['name']
        ));
    }

    $rs = $db->select('SELECT
        g.id,
        g.name

        FROM gametype   AS g
        JOIN map        AS m ON m.gametype_id=g.id

        WHERE     g.status=1
        AND       m.status=1
        AND       m.date < '.time ().'

        GROUP BY g.id
        ORDER BY g.name
    ');

    foreach($rs['result'] as $item)
    {
        $tpl->assignLoopVar('submit_gametype', array (
            'id' => $item['id'],
            'name' => $item['name']
        ));
    }


    /////////////////////////
    // PAGE INFOS
    /////////////////////////

    $tpl->assignVar (array (
        'PAGE_TITLE'          => 'Submit a map to Map Factory',
        'PAGE_DESCRIPTION'    => 'Download custom Maps for your favorit FPS (First Person Shooters) games, and submit your own maps.',
        'PAGE_KEYWORDS'       => implode(', ', $keywordList)
    ));

