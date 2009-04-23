<?php

    require '../inc/conf.php';

    $tpl->setCacheKey('../template/cached/more_map_game.tpl', 'more_map_game_' . $_POST['gameId'] . '_' . $_POST['page']);

    if ($tpl->isCached('../template/cached/more_map_game.tpl', 60) == false)
    {
        require '../page/bloc/moreMapGame.php';
    }

    $tpl->assignTemplate('../template/cached/more_map_game.tpl', 60);

    $tpl->display();

