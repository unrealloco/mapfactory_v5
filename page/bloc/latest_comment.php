<?php

    /////////////////////////
    // LATEST COMMENTS
    /////////////////////////

    $tpl->setCacheKey('cached/latest_comment.tpl', 'latest_comment');

    if ($tpl->isCached('cached/latest_comment.tpl', 60) == false)
    {
        $rs = $db->select('SELECT
            c.date      AS date,
            c.name      AS name,
            c.message   AS message

            FROM  map_comment AS c
            JOIN  map         AS m ON c.map_id=m.id

            WHERE     c.status = 1
            AND       m.date < '.time ().'
            AND       m.status = 1

            ORDER BY c.date DESC',
            0, 14
        );

        foreach ($rs['result'] as $key => $item)
        {
            $tpl->assignLoopVar('comment', array
            (
                'date'       => timeWarp($item['date']),
                'name'       => $item['name'],
                'message'    => encodeCurly(cutText($item['message'], 120))
            ));
        }
    }

