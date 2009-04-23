<?php

    include ('bloc/search.php');

    /////////////////////////
	// PAGE INFOS
	/////////////////////////
	
	$tpl->assignVar (array (
		'PAGE_TITLE'          => 'Submit a map to Map Factory',
		'PAGE_DESCRIPTION'    => 'Download custom Maps for your favorit FPS (First Person Shooters) games, and submit your own maps.',
		'PAGE_KEYWORDS'       => implode(', ', $keywordList)
	));
