<?php

    include('bloc/search.php');
    include('bloc/recent_activity.php');

    /////////////////////////
    // MAP LIST
    /////////////////////////

    $tpl->setCacheKey
    (
        'cached/map_list.tpl',
        'list_' . ((isOK($_GET['game'])) ? $_GET['game'] : 'ALL') .
        '_' . ((isOK($_GET['gametype'])) ? $_GET['gametype'] : 'ALL') .
        '_' . ((isOK($_GET['q'])) ? md5($_GET['q']) : 'ALL') .
        '_' . ((isOK($_GET['p'])) ? $_GET['p'] : '0') .
        '_' . ((isOK($_GET['sortby'])) ? $_GET['sortby'] : 'date')
    );

    $orderBy = 'm.date';

    if ($_GET['sortby'] == 'commented')
    {
        $orderBy = 'comment';
    }
    else if ($_GET['sortby'] == 'rated')
    {
        $orderBy = 'ratting';
    }
    else if ($_GET['sortby'] == 'downloaded')
    {
        $orderBy = 'download';
    }

    $limitTo = '';

    if ($_GET['limitto'] == 'day')
    {
        $limitTo = 'AND m.data > ' . (time() - 86400);
    }
    else if ($_GET['limitto'] == 'week')
    {
        $limitTo = 'AND m.data > ' . (time() - (86400 * 7));
    }
    else if ($_GET['limitto'] == 'month')
    {
        $limitTo = 'AND m.data > ' . (time() - (86400 * 30));
    }
    else if ($_GET['sortby'] == '3month')
    {
        $limitTo = 'AND m.data > ' . (time() - (86400 * 30 * 3));
    }

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
            ' . ((isset($_GET['game']) && is_string($_GET['game'])) ? 'AND g.guid="' . $_GET['game'] . '"' : '') . '
            ' . ((isset($_GET['gametype']) && is_string($_GET['gametype'])) ? 'AND t.guid="' . $_GET['gametype'] . '"' : '') . '
            ' . ((isOk ($_GET['q'])) ? 'AND CONCAT_WS(" ", m.title, g.name, t.name, a.name, m.description) LIKE(\'' . getLikeList ($_GET['q']) . '\')' : '' ) . '
            AND       m.date < '.time ().'
            ' . $limitTo . '
            AND       g.status = 1
            AND       t.status = 1
            AND       a.status = 1

            GROUP BY m.id
            ORDER BY ' . $orderBy . ' DESC',
            (($_GET['p'] > 0) ? $_GET['p'] - 1 : $_GET['p']) * MAP_PER_PAGE, MAP_PER_PAGE
    	);

        if ($rs['total'] != 0)
        {
            foreach ($rs['result'] as $key => $item)
            {
                $tpl->assignLoopVar('map', array
                (
                    'id'             => $item['id'],
                    'title'          => $item['title'],
                    'game'           => $item['game'],
                    'gametype'       => $item['gametype'],
                    'author'         => $item['author'],
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
        }
        else
        {
            $tpl->assignSection('noResult');
        }


    	/////////////////////////
    	// NOT RESULTS
    	/////////////////////////

    	$pageTotal = ceil($rs['total'] / MAP_PER_PAGE);
    	$link = '';
    	$link .= (isset($_GET['game']) && is_string($_GET['game'])) ? $_GET['game'] . '/' : '';
    	$link .= (isset($_GET['gametype']) && is_string($_GET['gametype'])) ? $_GET['gametype'] . '/' : '';
    	$link .= (isset($_GET['q']) && is_string($_GET['q'])) ? 'search/' . $_GET['q'] . '/' : '';
    	$n = 1;

        if ($rs['total'] == 0)
        {
            if (isOk($_GET['q']))
            {
                $tpl->assignVar(array
                (
                    'page_title'    => 'Oups ! no map found ...',
                    'search_path'   => str_replace(array('/', '-'), array(' >> ', ' '), preg_replace('#^(\/*)(.*)(\/*)$#isU', '$2', $link))
                ));

                if (isOk($_GET['game']))
                {
                    $tpl->assignSection('noResult_tip1');
                }
            }
            else
            {
                $url = ROOT_PATH;

                if ((isOK($_GET['game']) && $_GET['game'] == 'author') && (isOK($_GET['gametype']) && preg_match('#^[0-9]+-#', $_GET['gametype']) != 0))
                {
                    $info = explode('-', $_GET['gametype']);

                    $url .= 'author/' . $info[1] . '-' . $info[0];

                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: ' . $url);

                    exit();
                }

                if (isOK($_GET['game']) && preg_match('#^[0-9]+-#', $_GET['game']) != 0)
                {
                    $url .= substr($_GET['game'], strpos($_GET['game'], '-') + 1);

                    if (isOK($_GET['gametype']) && preg_match('#^[0-9]+-#', $_GET['gametype']) != 0)
                    {
                        $url .= '/' . substr($_GET['gametype'], strpos($_GET['gametype'], '-') + 1);
                    }

                    if (isOK($_GET['q']))
                    {
                        $url .= '/search/' . $_GET['q'];
                    }

                    if (isOK($_GET['p']))
                    {
                        $url .= '/' . $_GET['p'];
                    }

                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: ' . $url);

                    exit();
                }

                header("HTTP/1.0 404 Not Found");

//                mail404();
                log404('map_list.php');

                $tpl->clearLayout();
                $tpl->clearSection();
                $tpl->assignVar('PAGE_TITLE', 'Map Factory - 404 not Found');
                $tpl->assignTemplate('template/bloc/header.tpl');
                $tpl->assignTemplate('template/404.tpl');
                $tpl->assignTemplate('template/bloc/footer.tpl');
                $tpl->display();

                exit();
            }
        }




    	/////////////////////////
    	// PAGINATION
    	/////////////////////////

    	if ($_GET['p'] > 0)
    	{
    	   $_GET['p'] --;
        }

        $currentParameters = '?';
        $currentParameters .= ((isOK($_GET['sortby'])) ? 'sortby=' . $_GET['sortby'] . '&' : '');
        $currentParameters .= ((isOK($_GET['limitto'])) ? 'limitto=' . $_GET['limitto'] . '&' : '');
        $currentParameters = ((strlen($currentParameters) == 1) ? '' : substr($currentParameters, 0, -1));

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
                'link'   => $link . (($p == 0) ? '' : $p + 1) . $currentParameters,
                'class'  => ($p == $_GET['p']) ? 'on' : 'off'
            ));
    	}

    	if ($pageTotal > 1)
    	{
            $tpl->assignSection('pagination');

            $tpl->assignVar(array
            (
                'pagination_next' => $link . ($_GET['p'] + 2) . $currentParameters,
                'pagination_prev' => $link . (($_GET['p'] == 1) ? '' : $_GET['p']) . $currentParameters
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
    	// SORT BY / LIMIT TO
    	/////////////////////////

        $tpl->assignSection('sortBy');
//        $tpl->assignSection('limitTo');

        $currentURI = '';

        if (isOK($_GET['game']))
        {
            $currentURI .= $_GET['game'] . '/';

            if (isOK($_GET['gametype']))
            {
                $currentURI .= $_GET['gametype'] . '/';
            }
        }

        if (isOK($_GET['q']))
        {
            $currentURI .= 'search/' . $_GET['q'] . '/';
        }

        $sortByParameter .= ((isOK($_GET['sortby'])) ? 'sortby=' . $_GET['sortby'] . '&' : '');
        $limitToParameter .= ((isOK($_GET['limitto'])) ? '&limitto=' . $_GET['limitto'] : '');

        $tpl->assignVar(array
        (
            'currentURI' => substr($currentURI, 0, -1),

            'sortBy_active_' . ((isOK($_GET['sortby'])) ? $_GET['sortby'] : 'date') => 'on',
            'sortBy_url_none' => ((isOk($limitToParameter)) ? '?limitto=' . $_GET['limitto'] : ''),
            'sortByParameter' => $sortByParameter,

            'limitTo_active_' . ((isOK($_GET['limitto'])) ? $_GET['limitto'] : 'none') => 'on',
            'limitTo_url_none' => ((isOk($sortByParameter)) ? '?sortby=' . $_GET['sortby'] : ''),
            'limitToParameter' => $limitToParameter
        ));




    	/////////////////////////
    	// RESULT INFOS
    	/////////////////////////

    	$tpl->assignSection('result_info');

    	$to = ($_GET['p'] * MAP_PER_PAGE) + MAP_PER_PAGE + 1;

    	$tpl->assignVar(array
    	(
            'result_from'     => number_format(($_GET['p'] * MAP_PER_PAGE) + 1, 0, '', ','),
            'result_to'       => number_format(($to > $rs['total']) ? $rs['total'] : $to, 0, '', ','),
            'result_total'    => number_format($rs['total'], 0, '', ',')
    	));
    }

    $tpl->assignVar(array
    (
        'search_query'  => utf8_encode(htmlspecialchars(rawurldecode(stripslashes($_GET['q']))))
    ));


    /////////////////////////
    // PAGE INFOS
    /////////////////////////

    $pageKeyword = implode(', ', $keywordList);

    if (!isOK($_GET['q']) && !isOK($_GET['game']) && !isOK($_GET['gametype']))
    {
        $inPageTitle = 'Latest maps';
        $pageTitle = 'Map Factory - the game map database';
        $pageDescriptiom = 'Download custom Maps for your favorit FPS (First Person Shooters) games, and submit your own maps.';
    }
    else
    {
        $pageTitle = '';

        if (isOK($_GET['q']))
        {
            $pageTitle .= 'Results for "' . utf8_encode(htmlspecialchars(rawurldecode(stripslashes($_GET['q'])))) . '"';
            $pageDescriptiom = 'Search results for ' . utf8_encode(htmlspecialchars(rawurldecode(stripslashes($_GET['q']))));
        }
        else
        {
            $pageDescriptiom = 'Download a large selection of the best';
        }

        if (isOK($_GET['game']))
        {
            $rs = $db->select('SELECT name FROM game WHERE guid="' . $_GET['game'] . '"');

            $pageTitle .= ((isOK($_GET['q'])) ? ' in ' : '') . $rs['result'][0]['name'];
            $pageDescriptiom .= ((isOK($_GET['q'])) ? ' in ' : ' ') . $rs['result'][0]['name'];
            $pageKeyword = $rs['result'][0]['name'] . ', ' . $pageKeyword;
        }

        if (isOK($_GET['gametype']))
        {
            $rs = $db->select('SELECT name FROM gametype WHERE guid="' . $_GET['gametype'] . '"');

            $pageTitle .= ' ' . $rs['result'][0]['name'];
            $pageDescriptiom .= ' ' . $rs['result'][0]['name'];
            $pageKeyword = $rs['result'][0]['name'] . ', ' . $pageKeyword;
        }

        $pageTitle .= ' maps';
        $pageDescriptiom .= ' maps';
        $inPageTitle = $pageTitle;
    }

    $tpl->assignVar (array (
            'page_title'          => $inPageTitle,
            'PAGE_TITLE'          => $pageTitle,
            'PAGE_DESCRIPTION'    => $pageDescriptiom,
            'PAGE_KEYWORDS'       => $pageKeyword
    ));

