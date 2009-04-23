#!/usr/bin/env php5
<?php

$fileList = scandir('cache');
$n = 0;

foreach($fileList as $file)
{
    if (filemtime('cache/' . $file) < time() - (3600 * 1))
    {
        $n ++;
        unlink('cache/' . $file);
    }
}

echo 'Files deleted: ' . $n . '/' . count($fileList);

