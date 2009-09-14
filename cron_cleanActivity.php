#!/usr/local/bin/php5
<?php

include "inc/conf.php";

$db->delete('DELETE FROM map_activity WHERE date < ' . (time() - (86400 * 10)));

