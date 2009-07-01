<?php

    include ('bloc/search.php');


//        mail404();
        log404('404.php');


    /////////////////////////
    // PAGE INFOS
    /////////////////////////

    $tpl->assignVar (array (
            'PAGE_TITLE'          => 'Map Factory - 404 not Found',
            'PAGE_DESCRIPTION'    => 'Download custom Maps for your favorit FPS (First Person Shooters) games, and submit your own maps.',
            'PAGE_KEYWORDS'       => implode(', ', $keywordList)
    ));

