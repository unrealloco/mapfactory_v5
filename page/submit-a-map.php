<?php

    include ('bloc/search.php');


    /////////////////////////
    // GAME AND GAMETYPE
    /////////////////////////

    $rs = $db->select('SELECT
        g.id,
        g.name

        FROM game   AS g

        WHERE     g.status=1

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

        WHERE     g.status=1

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

