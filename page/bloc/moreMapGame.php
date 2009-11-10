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
            m.guid                  AS guid,
            m.download              AS download,
            m.ratting               AS ratting,
            m.image_id              AS image,
            g.name                  AS game,
            g.guid                  AS game_guid,
            t.name                  AS gametype,
            t.guid                  AS gametype_guid,
            a.name                  AS author,
            a.id                    AS author_id,
            a.guid                  AS author_guid,
            COUNT(DISTINCT c.id)    AS comment

            FROM              map             AS m
            JOIN              game            AS g    ON m.game_id = g.id
            JOIN              gametype        AS t    ON m.gametype_id = t.id
            JOIN              author          AS a    ON m.author_id = a.id
            LEFT OUTER JOIN   map_comment     AS c    ON c.map_id=m.id AND c.status=1

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
                    'title'          => encodeCurly($item['title']),
                    'game'           => encodeCurly($item['game']),
                    'gametype'       => encodeCurly($item['gametype']),
                    'author'         => encodeCurly($item['author']),
                    'author_id'      => $item['author_id'],
                    'image'          => $item['image'],
                    'comment'        => $item['comment'],
                    'download'       => $item['download'],
                    'comment_s'      => ($item['comment'] > 1)?'s':'',
                    'download_s'     => ($item['download'] > 1)?'s':'',
                    'ratting'        => round (($item['ratting'] / 5) * 80),
                    'rattingPercent' => round (($item['ratting'] / 5) * 100),

                    'map_guid'       => $item['guid'],
                    'game_guid'      => $item['game_guid'],
                    'gametype_guid'  => $item['gametype_guid'],
                    'author_guid'    => $item['author_guid'],

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

