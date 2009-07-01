<?php

    include 'inc/conf.php';

    if (file_exists('page/' . $page . '.php'))
    {
    	include 'page/' . $page . '.php';
    }
    else
    {
        header("HTTP/1.0 404 Not Found");

//        mail404();
        log404('index.php');

        $tpl->clearLayout();
        $tpl->clearSection();
        $tpl->assignVar('PAGE_TITLE', 'Map Factory - 404 not Found');
        $tpl->assignTemplate('template/bloc/header.tpl');
        $tpl->assignTemplate('template/404.tpl');
        $tpl->assignTemplate('template/bloc/footer.tpl');
        $tpl->display();

        exit();
    }

    if (ereg('MSIE 6',$_SERVER['HTTP_USER_AGENT']))
    {
        $tpl->assignSection('ie6warning');
    }
    else
    {
        $tpl->assignSection('notIE6');
    }

    $tpl->assignTemplate('template/bloc/header.tpl');
    $tpl->assignTemplate('template/'.$page.'.tpl');
    $tpl->assignTemplate('template/bloc/footer.tpl');

    $tpl->display();

echo '<!-- ';
echo 'SQL : '.number_format($db->execTime, 3, ',', ' ').' sec ('.$db->nbReq.' req) | ';
echo 'PHP : '.number_format(microtime_float () - $start_time - $db->execTime, 3, ',', ' ').' sec | ';
echo 'TOTAL : '.number_format(microtime_float () - $start_time, 3, ',', ' ').' sec';
echo ' --!>';
?>
