<?php

    $tpl->clearLayout();
    $tpl->clearSection();
    $tpl->assignTemplate('template/rss.tpl');
    $tpl->setCacheKey('cached/rss.tpl', 'rss');


	$lastBuildDate = '';


    if ($tpl->isCached('cached/rss.tpl', 60) == false)
    {
    	$rs = $db->select('SELECT
            m.id                    AS id,
            m.date                  AS map_date,
            m.title                 AS title,
            m.guid                  AS guid,
            m.image_id              AS image,
            g.name                  AS game,
            g.guid                  AS game_guid,
            t.name                  AS gametype,
            t.guid                  AS gametype_guid,
            a.name                  AS author,
            a.id                    AS author_id,
            a.guid                  AS author_guid

            FROM              map             AS m
            JOIN              game            AS g    ON m.game_id = g.id
            JOIN              gametype        AS t    ON m.gametype_id = t.id
            JOIN              author          AS a    ON m.author_id = a.id

            WHERE     m.status = 1
            AND       m.date < '.time ().'
            AND       g.status = 1
            AND       t.status = 1
            AND       a.status = 1

            GROUP BY m.id
            ORDER BY m.date DESC',
            0, 32
    	);

        if ($rs['total'] != 0)
        {
            foreach ($rs['result'] as $key => $item)
            {
                if (!isOk ($lastBuildDate)){
                    $lastBuildDate = date ('r', $item['map_date']);
                }

                $description = '';
                $description .= '<ul>';
                $description .=     '<li>';
                $description .=         '<b>Game: </b> <a href="' . ROOT_PATH . $item['game_guid'] . '">' . $item['game'] . '</a>';
                $description .=     '</li>';
                $description .=     '<li>';
                $description .=         '<b>Gametype: </b> <a href="' . ROOT_PATH . $item['game_guid'] . '/' . $item['gametype_guid'] . '">' . $item['gametype'] . '</a>';
                $description .=     '</li>';
                $description .=     '<li>';
                $description .=         '<b>Author: </b> <a href="' . ROOT_PATH . 'author/' . $item['author_guid'] . '-' . $item['author_id'] . '">' . $item['author'] . '</a>';
                $description .=     '</li>';
                $description .= '</ul>';
                $description .= '<p><a href="' . ROOT_PATH . $item['game_guid'] . '/' . $item['gametype_guid'] . '/' . $item['guid'] . '-' . $item['id'] . '"><img src="' . ROOT_PATH . 'screenshot/160x120/' . $item['guid'] . '-' . $item['image'] . '.jpg" width="160px" height="120px" /></a></p>';

                $tpl->assignLoopVar('map', array
                (
                    'id'             => $item['id'],
                    'date'           => date('r', $item['map_date']),
                    'title'          => xmlFormat($item['title']),

                    'description'    => xmlFormat($description),

                    'game'           => $item['game'],
                    'gametype'       => $item['gametype'],
                    'author'         => xmlFormat($item['author']),
                    'author_id'      => $item['author_id'],

                    'map_guid'       => $item['guid'],
                    'game_guid'      => $item['game_guid'],
                    'gametype_guid'  => $item['gametype_guid'],
                    'author_guid'    => $item['author_guid'],
                ));
            }

            $tpl->assignVar (array (
                'PAGE_TITLE' => PAGE_TITLE.' - Latest maps',
                'type' => $_GET['type'],
                'lastBuildDate' => $lastBuildDate
            ));
        }
    }

    header('Content-Type: text/xml; charset=utf-8');
    header("Cache-Control: max-age=" . (3600 * 1) . ', must-revalidate');

    $tpl->display();

    exit();

