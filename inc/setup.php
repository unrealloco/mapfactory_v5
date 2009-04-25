<?php

    ////////////////////////////////////////////
    //	REDIRECTION
    ////////////////////////////////////////////

    if (isset ($_POST['q'])){
            header ('Location: '.ROOT_PATH.'recherche/'.urlencode (preg_replace ('#[./]#isU', ' ', $_POST['q'])));
    }




    ////////////////////////////////////////////
    //	HARD CONF
    ////////////////////////////////////////////

    ini_set ('session.use_trans_sid', '0'); 	//enlever le PHPSSID
    ini_set ('url_rewriter.tags', ''); 		//enlever le PHPSSID




    ////////////////////////////////////////////
    //	FUNCTIONS
    ////////////////////////////////////////////

    include ROOT_DIR.'inc/function.php';



    ////////////////////////////////////////////
    //	EXEC STATS
    ////////////////////////////////////////////

    $start_time = microtime_float ();
    $sql_time = 0;




    ////////////////////////////////////////////
    //	CLASS
    ////////////////////////////////////////////

    include ROOT_DIR.'inc/class/templateEngine.php';
    include ROOT_DIR.'inc/class/mysqlDatabase.php';

    $tpl = new templateEngine();
    $db = new mysqlDatabase();

    $tpl->cacheTimeCoef = CACHE_TIMECOEF;




    ////////////////////////////////////////////
    //	MIS
    ////////////////////////////////////////////

    session_start();

    $page = (isOk ($_GET['page']))?$_GET['page']:'homepage';

    $tpl->assignVar (array (
            'PAGE_TITLE' => PAGE_TITLE,
            'PAGE_DESCRIPTION' => PAGE_DESCRIPTION,
            'PAGE_KEYWORDS' => PAGE_KEYWORDS,
            'ROOT_PATH' => ROOT_PATH,
            'VERSION' => VERSION
    ));

