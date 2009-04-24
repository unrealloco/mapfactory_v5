<?php

    require '../inc/conf.php';

	$rs = $db->select('SELECT id FROM map_activity WHERE map_id=' . $_POST['map_id'] . ' AND date=' . (time() - (time() % 86400)) . ' AND type="comment"');

	if ($rs['total'] == 0)
	{
	   $db->insert('INSERT INTO map_activity SET map_id=' . $_POST['map_id'] . ', date=' . (time() - (time() % 86400)) . ', total=1, type="comment"');
    }
    else
    {
        $db->update('UPDATE map_activity SET total=total+1 WHERE id=' . $rs['result'][0]['id']);
    }

    $db->insert
    (
        'INSERT INTO map_comment SET
        date =      ' . time() . ',
        map_id =    ' . $_POST['map_id'] . ',
        message =   "' . $_POST['message'] . '",
        name =      "' . $_POST['name'] . '",
        status =    1'
    );
