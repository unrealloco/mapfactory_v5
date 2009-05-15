<?php

    /////////////////////////
    // COMMENT
    /////////////////////////

    $rs = $db->select('SELECT id, date, name, message
        FROM map_comment
        WHERE map_id = ' . $_GET['map'] . ' AND status = 1 AND parent_id = 0
        ORDER BY date DESC',
        $_GET['p'] * COMMENT_PER_PAGE, COMMENT_PER_PAGE
    );

    $tpl->assignVar('commentFormDisplay', 'block');
    $tpl->assignVar('commentFormClass', 'on');

    if ($rs['total'] != 0)
    {
        $tpl->assignVar('commentDisplay', 'block');
        $tpl->assignVar('commentClass', 'on');

        $idList = array();
    	foreach ($rs['result'] as $item)
    	{
    	   $idList[] = $item['id'];
        }

        $rs_response = $db->select('SELECT date, parent_id, name, message
    		FROM map_comment
    		WHERE parent_id IN (' . implode(',', $idList) . ') AND status = 1
    		ORDER BY date ASC'
    	);

        $n = 0;

        foreach ($rs['result'] as $comment)
    	{
            $message = nl2br($comment['message']);
            $message = preg_replace('/([\t\r\n\v\f]+)/is', '', $message);
            $message = preg_replace('/((?:<br \/>){2,})/is', '<br /><br />', $message);

            $tpl->assignLoopVar('comment', array
            (
                'time'       => timeWarp($comment['date']),
                'name'       => $comment['name'],
                'message'    => $message,
                'id'         => $comment['id']
            ));

            foreach ($rs_response['result'] as $response)
            {
                if ($response['parent_id'] == $comment['id'])
                {
                    $message = nl2br($response['message']);
                    $message = preg_replace('/([\t\r\n\v\f]+)/is', '', $message);
                    $message = preg_replace('/((?:<br \/>){2,})/is', '<br /><br />', $message);

                    $tpl->assignLoopVar('comment.response', array
                    (
                            'time'       => timeWarp($response['date']),
                            'name'       => $response['name'],
                            'message'    => $message
                    ));
                }
            }

            $n ++;
            if ($n > COMMENT_PER_PAGE)
            {
                break;
            }
    	}
    }
    else
    {
        $tpl->assignVar('commentDisplay', 'none');
        $tpl->assignVar('commentClass', 'off');
    }

    /////////////////////////
    // PAGINATION
    /////////////////////////

    $pageTotal = ceil($rs['total'] / COMMENT_PER_PAGE);
    $n = 1;

    for ($p = 0; $p < $pageTotal; $p ++)
    {
        if ($p > 2 && $p < $_GET['p'] - 4)
        {
            $p = $_GET['p'] - 4;
            $tpl->assignSection('pagination_space' . $n);
            $n ++;
        }

        if ($p < $pageTotal - 3 && $p > $_GET['p'] + 4)
        {
            $p = $pageTotal - 3;
            $tpl->assignSection('pagination_space' . $n);
            $n ++;
        }

        $tpl->assignLoopVar('pagination_' . $n, array
        (
            'n'      => $p + 1,
            'link'   => ($p == $_GET['p']) ? '' : $p,
            'class'  => ($p == $_GET['p']) ? 'on' : 'off'
        ));
    }

    if ($pageTotal > 1)
    {
        $tpl->assignSection('pagination');

        $tpl->assignVar(array
        (
            'pagination_next' => $_GET['p'] + 1,
            'pagination_prev' => $_GET['p'] - 1
        ));

        if ($_GET['p'] > 0)
        {
            $tpl->assignSection('pagination_prev');
        }

        if ($_GET['p'] < $pageTotal - 1)
        {
            $tpl->assignSection('pagination_next');
        }
    }


    /////////////////////////
    // RESULT INFOS
    /////////////////////////

    $to = ($_GET['p'] * COMMENT_PER_PAGE) + COMMENT_PER_PAGE;

    $tpl->assignVar(array
    (
        'result_from'     => number_format(($_GET['p'] * COMMENT_PER_PAGE) + 1, 0, '', ','),
        'result_to'       => number_format(($to > $rs['total']) ? $rs['total'] : $to, 0, '', ','),
        'result_total'    => number_format($rs['total'], 0, '', ',')
    ));

