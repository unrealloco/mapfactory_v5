<?php

    include ('bloc/search.php');


    /////////////////////////
    // MAP LIST
    /////////////////////////

    $tpl->setCacheKey
    (
        'cached/map_list.tpl', 'author_maplist_' . $_GET['author']
    );

    if ($tpl->isCached('cached/map_list.tpl', 60) == false)
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
            AND       a.id = ' . $_GET['author'] . '
            AND       m.date < '.time ().'
            AND       g.status = 1
            AND       t.status = 1
            AND       a.status = 1

            GROUP BY m.id
            ORDER BY m.date DESC'
    	);

        if ($rs['total'] != 0)
        {
            foreach ($rs['result'] as $key => $item)
            {
                $tpl->assignLoopVar('map', array
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

                    'map_guid'       => $item['guid'],
                    'game_guid'      => $item['game_guid'],
                    'gametype_guid'  => $item['gametype_guid'],
                    'author_guid'    => $item['author_guid'],

                    'class'          => ($key % 2 == 0) ? 'pair' : 'odd'
                ));

                $authorName = $item['author'];
            }

            $pageTitle = 'All maps from ' . $authorName;

            $tpl->assignVar(array
            (
                    'page_title' => $pageTitle
            ));
        }
        else
        {
            header("HTTP/1.0 404 Not Found");

            $tpl->clearLayout();
            $tpl->clearSection();
            $tpl->assignTemplate('template/bloc/header.tpl');
            $tpl->assignTemplate('template/404.tpl');
            $tpl->assignTemplate('template/bloc/footer.tpl');
            $tpl->display();

            exit();
        }
    }


    /////////////////////////
    // STATS
    /////////////////////////

    $tpl->setCacheKey
    (
        'cached/author_stat.tpl', 'author_stat_' . $_GET['author']
    );

    if ($tpl->isCached('cached/author_stat.tpl', 60) == false)
    {
        $rs = $db->select('SELECT
            SUM(DISTINCT m.download)          AS download,
            COUNT(DISTINCT c.id)              AS comment,
            COUNT(m.id)                       AS map

            FROM              map             AS m
            JOIN              game            AS g    ON m.game_id = g.id
            JOIN              gametype        AS t    ON m.gametype_id = t.id
            JOIN              author          AS a    ON m.author_id = a.id
            LEFT OUTER JOIN   map_comment     AS c    ON c.map_id=m.id AND c.status=1

            WHERE     m.status = 1
            AND       a.id = ' . $_GET['author'] . '
            AND       m.date < '.time ().'
            AND       g.status = 1
            AND       t.status = 1
            AND       a.status = 1

            GROUP BY a.id'
    	);

    	$tpl->assignVar(array
    	(
            'downloadTotal' => $rs['result'][0]['download'],
            'commentTotal'  => $rs['result'][0]['comment'],
            'mapTotal'      => $rs['result'][0]['map']
    	));
    }


    /////////////////////////
    // PAGE INFOS
    /////////////////////////

    $tpl->assignVar (array (
        'PAGE_TITLE' => $authorName.' maps',
        'PAGE_DESCRIPTION' => 'Download all the maps made by '.$authorName.' on Map Factory.',
        'PAGE_KEYWORDS' => $authorName . ', ' . implode(', ', $keywordList)
    ));

