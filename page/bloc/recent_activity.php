<?php

    /////////////////////////
    // RECENT ACTIVITY
    /////////////////////////

    $tpl->setCacheKey('cached/latest_activity.tpl', 'latest_activity');

    if ($tpl->isCached('cached/latest_activity.tpl', 60) == false)
    {
    	$rs = $db->select('SELECT
    	    a.type                  AS type,
    	    SUM(total)              AS total,
            m.id                    AS id,
            m.title                 AS title,
            m.guid                  AS guid,
            m.image_id              AS image,
            g.name                  AS game,
            g.guid                  AS game_guid,
            t.name                  AS gametype,
            t.guid                  AS gametype_guid

            FROM              map_activity        AS a
            JOIN              map                 AS m    ON a.map_id = m.id
            JOIN              game                AS g    ON m.game_id = g.id
            JOIN              gametype            AS t    ON m.gametype_id = t.id

            WHERE     a.date BETWEEN ' . (time() - ((3600 * 24) * 3)) . ' AND ' . time() . '
            AND       m.status = 1
            AND       g.status = 1
            AND       t.status = 1

            GROUP BY a.type, map_id
            ORDER BY a.total, a.type DESC'
    	);

        $total = 0;
        $activityList = array();
        $activityTotal = array();
        $factor = array(
            'comment' => 10,
            'vote' => 4,
            'download' => 1
        );

        foreach ($rs['result'] as $key => $item)
        {
            if (array_key_exists($item['id'], $activityList) == false)
            {
                $activityList[$item['id']] = array();
            }

            $activityList[$item['id']][] = array(
                'key'   => $key,
                'type'  => $item['type'],
                'total' => $item['total']
            );

            if (array_key_exists($item['id'], $activityTotal) == false)
            {
                $activityTotal[$item['id']] = 0;
            }

            $activityTotal[$item['id']] += ($item['total'] * $factor[$item['type']]);
            $total += $item['total'];
        }

        arsort($activityTotal);

        $i = 0;

        foreach ($activityTotal as $id => $total)
        {
            $item = $rs['result'][$activityList[$id][0]['key']];

            $tpl->assignLoopVar('activity', array
    		(
                    'id'             => $item['id'],
                    'title'          => $item['title'],
                    'game'           => $item['game'],
                    'gametype'       => $item['gametype'],
                    'image'          => $item['image'],

                    'map_guid'       => $item['guid'],
                    'game_guid'      => $item['game_guid'],
                    'gametype_guid'  => $item['gametype_guid']
    		));

            foreach ($activityList[$id] as $a)
            {
                $tpl->assignLoopVar('activity.type', array
                (
                    'class'  => strtolower($a['type']),
                    'name'   => $a['type'],
                    'n'      => $a['total']
                ));
            }

            $i ++;
            if ($i == 18)
            {
                break;
            }
        }
    }

