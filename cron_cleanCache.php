#!/usr/local/bin/php5
<?php

$fileList = scandir('cache');
$n = 0;

foreach($fileList as $file)
{
    if ($file != '.' && $file != '..')
    {
        if (filemtime('cache/' . $file) < time() - (3600 * 1))
        {
            $n ++;
            unlink('cache/' . $file);
        }
    }
}

$file = fopen('log/cache_clean', 'a');
fwrite($file, date('c') . ' - ' . 'Files deleted: ' . $n . '/' . count($fileList) . "\n");
fclose($file);

