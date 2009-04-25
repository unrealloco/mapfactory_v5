<?php

    if (isset($_POST['search']))
    {
        $_POST['q'] = preg_replace('#<script[^>]*>([\\S\\s]*?)<\/script>#isU', '', $_POST['q']);
        $_POST['q'] = preg_replace('#(<\/?[^>]+>)#isU', '', $_POST['q']);
        $_POST['q'] = trim($_POST['q']);

        $url = ROOT_PATH;

        if (isset($_POST['search_game']) && $_POST['search_game'] != '0')
        {
            if (is_string($_POST['search_game']))
            {
                $url .= $_POST['search_game'] . '/';
            }
        }

        if (isset($_POST['search_gametype']) && $_POST['search_gametype'] != '0')
        {
            $url .= $_POST['search_gametype'] . '/';
        }

        if (empty($_POST['q']))
        {
            header('Location: ' . $url);
            exit();
        }
        else
        {
            header('Location: ' . $url . 'search/' . rawurlencode(utf8_decode(stripslashes($_POST['q']))));
            exit();
        }
    }

    // REDIRECT OLD IMAGES FROM V4
    else if (isset($_GET['image']))
    {
        $rs = $db->select('SELECT id FROM map_image WHERE parent_id=' . $_GET['map']);

        if ($rs['total'] >= $_GET['image'])
        {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . ROOT_PATH . 'screenshot/' . $_GET['size'] . '/' . $_GET['guid'] . '-' . $rs['result'][$_GET['image']]['id'] . '.jpg');
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

        exit();
    }

