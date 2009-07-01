<?php

	include ('bloc/search.php');
	include ('bloc/commentList.php');


    /////////////////////////
    // MAP
    /////////////////////////

    $rs = $db->select('SELECT
        m.id                    AS id,
        m.title                 AS title,
        m.guid                  AS guid,
        m.download              AS download,
        m.image_id              AS image,
        m.description           AS description,
        m.game_id               AS game_id,
        g.name                  AS game,
        g.guid                  as game_guid,
        t.name                  AS gametype,
        t.guid                  AS gametype_guid,
        a.name                  AS author,
        a.id                    AS author_id,
        a.guid                  AS author_guid,
        f.id                    AS file_id,
        COUNT(DISTINCT c.id)    AS comment

        FROM              map             AS m
        JOIN              game            AS g    ON m.game_id = g.id
        JOIN              gametype        AS t    ON m.gametype_id = t.id
        JOIN              author          AS a    ON m.author_id = a.id
        JOIN              map_file        AS f    ON m.id = f.parent_id
        LEFT OUTER JOIN   map_comment     AS c    ON c.map_id=m.id AND c.status=1

        WHERE     m.id = ' . $_GET['map'] . '
        AND       m.date < '.time ().'
        AND       m.status = 1
        AND       g.status = 1
        AND       t.status = 1
        AND       a.status = 1

        GROUP BY m.id'
    );

    if (isOK($rs['result'][0]['id']))
    {
    	foreach ($rs['result'] as $item)
    	{
            $tpl->assignVar(array
            (
                'page_title'    => $item['game'] . ' - ' . $item['title']
            ));

    		$tpl->assignVar(array
    		(
                    'id'             => $item['id'],
                    'title'          => $item['title'],
                    'game'           => $item['game'],
                    'gametype'       => $item['gametype'],
                    'author'         => $item['author'],
                    'author_id'      => $item['author_id'],
                    'image'          => $item['image'],
                    'description'    => $item['description'],
                    'download'       => $item['download'],
                    'download_s'     => ($item['download'] > 1) ? 's' : '',
                    'comment'        => $item['comment'],

                    'map_guid'       => $item['guid'],
                    'game_guid'      => $item['game_guid'],
                    'gametype_guid'  => $item['gametype_guid'],
                    'author_guid'    => $item['author_guid']
    		));

            if (!empty($item['description']))
            {
                $tpl->assignSection('description');
            }

            $authorId = $item['author_id'];
            $gameId = $item['game_id'];
            $imageId = $item['image'];
            $fileId = $item['file_id'];

            $authorName = $item['author'];
            $gameName = $item['game'];
            $gametypeName = $item['gametype'];
            $mapTitle = $item['title'];
    	}

        $file = ROOT_DIR.'media/map/' . $fileId . '.zip';

        if (file_exists($file))
        {
            $tpl->assignVar('size', number_format(round(filesize($file) / 1024 / 1024)));
        }
    }
    else
    {
        header("HTTP/1.0 404 Not Found");

//        mail404();
        log404('map.php');

        $tpl->clearLayout();
        $tpl->clearSection();
        $tpl->assignVar('PAGE_TITLE', 'Map Factory - 404 not Found');
        $tpl->assignTemplate('template/bloc/header.tpl');
        $tpl->assignTemplate('template/404.tpl');
        $tpl->assignTemplate('template/bloc/footer.tpl');
        $tpl->display();

        exit();
    }


    /////////////////////////
    // IMAGES
    /////////////////////////

    $rs = $db->select('SELECT id FROM map_image WHERE parent_id=' . $_GET['map']);

    if ($rs['total'] > 1)
    {
        $tpl->assignSection('previewList');

        foreach ($rs['result'] as $item)
    	{
            $tpl->assignLoopVar('preview', array
            (
                'id'    => $item['id'],
                'class' => ($imageId == $item['id']) ? 'on' : 'off'
            ));
    	}
    }


    /////////////////////////
    // RATTING
    /////////////////////////

    $rs = $db->select('SELECT * FROM map_ratting WHERE map_id=' . $_GET['map']);

    $titles = array
    (
        'Gameplay',
        'Design',
        'Originality',
        'Ambience',
        'Framerate'
    );

    for ($n = 0; $n < 5; $n ++)
    {
        $score = 0;
        $active = 'on';

        if ($rs['total'] != 0)
        {
            if ($rs['result'][0]['hint_'.$n] != 0)
            {
                $score = round($rs['result'][0]['point_'.$n] / $rs['result'][0]['hint_'.$n]);
            }

            $active = (strpos($_SESSION['ratting'], $_GET['map'] . '-' . $n)) ? 'off' : 'on';
        }

        for ($s = 0; $s < 5; $s ++)
        {
            $stars[$s] = ($s < $score) ? 'on' : 'off';
        }

        $tpl->assignLoopVar('ratting', array
        (
            'score'     => $score,
            'active'    => $active,
            'title'     => $titles[$n],
            'star_1'    => $stars[0],
            'star_2'    => $stars[1],
            'star_3'    => $stars[2],
            'star_4'    => $stars[3],
            'star_5'    => $stars[4]
        ));
    }


    /////////////////////////
    // MORE MAP FROM
    /////////////////////////

    $tpl->setCacheKey('cached/more_map_from.tpl', 'more_map_from_' . $authorId);

    if ($tpl->isCached('cached/more_map_from.tpl', 60) == false)
    {
    	$rs = $db->select('SELECT
            m.id                    AS id,
            m.title                 AS title,
            m.image_id              AS image,
            g.name                  AS game,
            g.guid                  as game_guid,
            t.name                  AS gametype,
            t.guid                  AS gametype_guid

            FROM              map             AS m
            JOIN              game            AS g    ON m.game_id = g.id
            JOIN              gametype        AS t    ON m.gametype_id = t.id

            WHERE     m.status = 1
            AND       m.author_id = ' . $authorId . '
            AND       m.date < '.time ().'
            AND       g.status = 1
            AND       t.status = 1

            GROUP BY m.id
            ORDER BY m.date DESC'
    	);

    	foreach ($rs['result'] as $key => $item)
    	{
            $tpl->assignLoopVar('moreMapFrom', array
            (
                'id'             => $item['id'],
                'title'          => $item['title'],
                'game'           => $item['game'],
                'gametype'       => $item['gametype'],
                'image'          => $item['image'],

                'map_guid'       => makeGUID($item['title']),
                'game_guid'      => $item['game_guid'],
                'gametype_guid'  => $item['gametype_guid'],

                'class'          => ($key % 2 == 0) ? 'pair' : 'odd'
            ));
        }
    }


    /////////////////////////
    // MORE MAP GAME
    /////////////////////////

    require 'bloc/moreMapGame.php';


    /////////////////////////
    // PAGE INFOS
    /////////////////////////

    $tpl->assignVar (array (
        'PAGE_TITLE'          => $gameName.' - '.$mapTitle,
        'PAGE_DESCRIPTION'    => $mapTitle . ', a '.$gametypeName.' map for '.$gameName.', realised by '.$authorName,
        'PAGE_KEYWORDS'       => $mapTitle.', '.$gameName.', '.$gametypeName.', '.$authorName.', '.implode(', ', $keywordList)
    ));

