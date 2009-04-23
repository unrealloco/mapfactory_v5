<?php

    /////////////////////////
	// SEARCH BAR
	/////////////////////////

    $game = isset($_GET['game']) ? $_GET['game'] : '';

    $rs = $db->select('SELECT
        g.name,
        g.guid

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
        $tpl->assignLoopVar('game', array (
            'name' => $item['name'],
            'guid' => $item['guid'],
            'selected' => ($item['guid'] == $game) ? ' selected="selected"' : ''
        ));
    }

    if (isset($_GET['game']))
    {
        $gametype = isset($_GET['gametype']) ? $_GET['gametype'] : '';

        $tpl->assignSection('search_gametype');

        $rs = $db->select('SELECT t.name, t.guid
            FROM gametype   AS t
            JOIN map        AS m ON m.gametype_id = t.id
            JOIN game       AS g ON m.game_id = g.id
            WHERE g.guid = "' . $game . '" AND t.status = 1
            GROUP BY t.id
            ORDER BY t.name');

        foreach($rs['result'] as $item)
        {
            $tpl->assignLoopVar('gametype', array (
                'name' => $item['name'],
                'guid' => $item['guid'],
                'selected' => ($item['guid'] == $gametype) ? ' selected="selected"' : ''
            ));
        }
    }


    /////////////////////////
	// KEYWORDS
	/////////////////////////

    $rs = $db->select('SELECT name FROM game WHERE status=1 UNION SELECT name FROM gametype WHERE status=1');

    $keywordList = array();

    foreach ($rs['result'] as $word)
    {
        $keywordList[] = $word['name'];
    }
