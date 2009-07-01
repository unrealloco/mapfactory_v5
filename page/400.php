<?php

    include ('bloc/search.php');


    header("HTTP/1.1 400 Bad Request");


//    mail404();
    log404('404.php');


    /////////////////////////
    // PAGE INFOS
    /////////////////////////

    $tpl->assignVar (array (
            'PAGE_TITLE'          => 'Map Factory - 400 Bad Request',
            'PAGE_DESCRIPTION'    => 'Download custom Maps for your favorit FPS (First Person Shooters) games, and submit your own maps.',
            'PAGE_KEYWORDS'       => implode(', ', $keywordList)
    ));

