<?php

	require_once '../inc/conf.php';
	
	$tpl->assignTemplate ('templates/header.tpl');
	$tpl->assignTemplate ('templates/'.$page.'.tpl');
	$tpl->assignTemplate ('templates/footer.tpl');
	
	$tpl->display ();


//~ echo 'SQL : '.number_format ($sql_time, 3, ',', ' ').' sec | ';
//~ echo 'PHP : '.number_format(microtime_float () - $start_time - $sql_time, 3, ',', ' ').' sec | ';
//~ echo 'TOTAL : '.number_format (microtime_float () - $start_time, 3, ',', ' ').' sec';
?>