<?php

    $rs = $db->select('SELECT id FROM map_activity WHERE map_id=' . $_GET['map'] . ' AND date=' . (time() - (time() % 86400)) . ' AND type="download"');

	if ($rs['total'] == 0)
	{
	   $db->insert('INSERT INTO map_activity SET map_id=' . $_GET['map'] . ', date=' . (time() - (time() % 86400)) . ', total=1, type="download"');
    }
    else
    {
        $db->update('UPDATE map_activity SET total=total+1 WHERE id=' . $rs['result'][0]['id']);
    }

	$rs = $db->select('
        SELECT
            m.id            AS map_id,
            m.guid          AS map_guid,
            g.guid          AS game_guid,
            t.guid          AS gametype_guid,
            f.id            AS file_id

		FROM map          AS m
		JOIN game         AS g ON g.id = m.game_id
		JOIN gametype     AS t ON t.id = m.gametype_id
		JOIN map_file     AS f on m.id = f.parent_id

		WHERE m.id="' . $_GET['map'] . '"
		AND m.status = 1
		AND g.status = 1
		AND t.status = 1
		AND m.date<'.time ().'
	');

	if ($rs['total'] != 0)
	{
		$map = $rs['result'][0];

		if (substr($_SERVER['HTTP_REFERER'], 0, strlen(ROOT_PATH)) == ROOT_PATH)
		{
			if (file_exists(ROOT_DIR.'media/map/' . $map['file_id'] . '.zip'))
			{
				if (!isset($_SESSION['downloaded']) || !in_array($_GET['guid'], $_SESSION['downloaded']))
				{
					$db->update('UPDATE map SET download=download+1 WHERE id="' . $_GET['map'] . '"');
					$_SESSION['downloaded'][] = $_GET['map'];
				}

				header('Location: ' . ROOT_PATH . 'data/' . $map['file_id'] . '/' . $map['game_guid'] . ' ' . $map['map_guid'] . '.zip');

				exit();
			}else{
				header('Location: '.ROOT_PATH.$map['game_guid'].'/'.$map['gametype_guid'].'/'.$map['map_guid'].'-'.$map['map_id']);
			}
		}else{
			header('Location: '.ROOT_PATH.$map['game_guid'].'/'.$map['gametype_guid'].'/'.$map['map_guid'].'-'.$map['map_id']);
		}
	}else{
		header('Location: '.ROOT_PATH);
	}
