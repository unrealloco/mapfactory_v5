<?php

    require '../inc/conf.php';

    header('Cache-Control: no-cache');

    set_time_limit(30);
    ini_set("memory_limit",'64M');

    $dir = ROOT_DIR . 'media/image/screenshot/';
    $name = makeGuid(preg_replace('#(.+)\.[a-zA-Z]+$#isU', '$1', $_FILES['Filedata']['name']));
    $extention = strtolower(preg_replace ('#.+\.([a-zA-Z]+)$#isU', '$1', $_FILES['Filedata']['name']));

    $id = $db->insert('INSERT INTO map_image SET parent_id=' . $_POST['mapId'] . ', name="' . $name . '"');

    $db->update('UPDATE map SET image_id=' . $id . ' WHERE id=' . $_POST['mapId']);

    $image = $dir.'original/' . $id . '.' . $extention;
    if (file_exists($image))
    {
            unlink($image);
    }
    move_uploaded_file($_FILES['Filedata']['tmp_name'], $image);

    foreach ($IMAGE_SIZE as $size)
    {
        $size = explode('x', $size);
        $dest = $dir.$size[0].((isset ($size[1]))?'x'.$size[1]:'').'/'.$id.'.jpg';

        if (file_exists ($dest))
        {
            unlink($dest);
        }
        redimage($image, $dest, $size[0], (isset ($size[1]))?$size[1]:false);
    }

    redimage($image, ROOT_DIR.'backoffice/img/preview/map_image_'.$id.'.jpg', 80, 80);

    echo $id;

