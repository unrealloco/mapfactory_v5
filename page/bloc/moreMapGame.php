<?php

    if (!isset($gameId))
    {
        $gameId = $_POST['gameId'];
    }

    $paginationPage = (isset($_POST['page'])) ? $_POST['page'] : 0;


    /////////////////////////
    // MORE MAP GAME
    /////////////////////////

    $tpl->setCacheKey('cached/more_map_game.tpl', 'more_map_game_' . $gameId . '_' . $paginationPage);

    if ($tpl->isCached('cached/more_map_game.tpl', 60) == false)
    {
    	$rs = $db->select('SELECT
            m.id                    AS id,
            m.title                 AS title,
            m.image_id              AS image,
            m.ratting               AS ratting,
            g.guid                  as game_guid,
            t.name                  AS gametype,
            t.guid                  AS gametype_guid

            FROM              map             AS m
            JOIN              game            AS g    ON m.game_id = g.id
            JOIN              gametype        AS t    ON m.gametype_id = t.id

            WHERE     m.status = 1
            AND       m.game_id = ' . $gameId . '
            AND       m.date < '.time ().'
            AND       g.status = 1
            AND       t.status = 1

            GROUP BY m.id
            ORDER BY m.date DESC',
            $paginationPage * 5, 5
    	);

    	foreach ($rs['result'] as $key => $item)
    	{
            $tpl->assignLoopVar('moreMapGame', array
            (
                'id'             => $item['id'],
                'title'          => $item['title'],
                'gametype'       => $item['gametype'],
                'image'          => $item['image'],
                'ratting'        => round (($item['ratting'] / 5) * 80),

                'game_guid'      => $item['game_guid'],
                'map_guid'       => makeGUID($item['title']),
                'gametype_guid'  => $item['gametype_guid'],

                'class'          => ($key % 2 == 0) ? 'pair' : 'odd'
            ));
        }


        /////////////////////////
        // PAGINATION
        /////////////////////////

        $paginationPageTotal = ceil($rs['total'] / 5);

        if ($paginationPageTotal > 1)
        {
            $tpl->assignVar(array
            (
                'pagination_game_id' => $gameId,
                'pagination_game' => $game,
                'pagination_total' => ceil($rs['total'] / 5),
                'pagination_page' => $paginationPage + 1,
                'pagination_next' => $paginationPage + 1,
                'pagination_prev' => $paginationPage - 1
            ));

            if ($paginationPage > 0)
            {
                $tpl->assignSection('pagination_prev');
            }

            if ($paginationPage < $paginationPageTotal - 1)
            {
                $tpl->assignSection('pagination_next');
            }
        }
    }

