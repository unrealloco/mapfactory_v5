<?php

    include ('page/bloc/search.php');


    /////////////////////////
    // COMMENT
    /////////////////////////

    $rs = $db->select('SELECT
        c.id                    AS id,
        c.parent_id             AS parent_id,
        c.date                  AS date,
        c.name                  AS name,
        c.message               AS message,
        m.id                    AS map_id,
        m.title                 AS map_title,
        m.guid                  AS map_guid,
        m.image_id              AS image,
        g.guid                  AS game_guid,
        t.guid                  AS gametype_guid

                FROM      map_comment     AS c
        JOIN      map             AS m    ON m.id = c.map_id
        JOIN      game            AS g    ON m.game_id = g.id
        JOIN      gametype        AS t    ON m.gametype_id = t.id

        WHERE c.status = 1
        ORDER BY c.date DESC',
        0, 50
    );

    foreach ($rs['result'] as $comment)
    {
        $tpl->assignLoopVar('comment', array
        (
            'time'           => timeWarp($comment['date']),
            'name'           => $comment['name'],
            'message'        => $comment['message'],
            'id'             => $comment['id'],
            'class'          => ($comment['parent_id'] != 0) ? 'repply' : 'main',
            'image'          => $comment['image'],
            'map_id'         => $comment['map_id'],
            'map_title'      => $comment['map_title'],
            'map_guid'       => $comment['map_guid'],
            'game_guid'      => $comment['game_guid'],
            'gametype_guid'  => $comment['gametype_guid'],
            'author_guid'    => $comment['author_guid'],
        ));
    }


    /////////////////////////
    // RESULT INFOS
    /////////////////////////

    $to = 1;

    $tpl->assignVar(array
    (
        'result_from'     => number_format(50 + 1, 0, '', ','),
        'result_to'       => number_format(($to > $rs['total']) ? $rs['total'] : $to, 0, '', ','),
        'result_total'    => number_format($rs['total'], 0, '', ',')
    ));

