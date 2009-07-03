<?php

    require '../inc/conf.php';

    set_time_limit(30);
	ini_set("memory_limit",'64M');

    if (isOk($_POST['map_id']))
    {
        $db->delete('DELETE FROM map WHERE id=' . $_POST['map_id']);
        $db->delete('DELETE FROM map_image WHERE parent_id=' . $_POST['map_id']);
        $db->delete('DELETE FROM map_file WHERE parent_id=' . $_POST['map_id']);
    }

//    if (isOk($_POST['game_id']))
//    {
//        $rs = $db->select('SELECT id FROM map WHERE game_id = ' . $_POST['game_id']);
//
//        if ($rs['total'] == 0)
//        {
//            $db->delete('DELETE FROM game WHERE id=' . $_POST['game_id']);
//        }
//    }

//    if (isOk($_POST['gametype_id']))
//    {
//        $rs = $db->select('SELECT id FROM map WHERE gametype_id = ' . $_POST['gametype_id']);
//
//        if ($rs['total'] == 0)
//        {
//            $db->delete('DELETE FROM gametype WHERE id=' . $_POST['gametype_id']);
//        }
//    }

    if (isOk($_POST['author_id']))
    {
        $rs = $db->select('SELECT id FROM map WHERE author_id = ' . $_POST['author_id']);

        if ($rs['total'] == 0)
        {
            $db->delete('DELETE FROM author WHERE id=' . $_POST['author_id']);
        }
    }

    if (isOk($_POST['file_id']))
    {
        $file = 'media/map/' . $_POST['file_id'] . '.zip';

        if (file_exists(ROOT_DIR.$file)){
    		unlink(ROOT_DIR.$file);
    	}
    }

    if (isOk($_POST['screenshot']))
    {
        $screenshotList = explode(',', $_POST['screenshot']);

        foreach ($screenshotList as $id)
        {
            $dir = ROOT_DIR . 'media/image/screenshot/';

            $image = $dir.'original/' . $id . '.jpg';
            if (file_exists($image))
            {
                unlink($image);
            }

            $image = $dir.'original/' . $id . '.png';
            if (file_exists($image))
            {
                unlink($image);
            }

            $image = $dir.'original/' . $id . '.gif';
            if (file_exists($image))
            {
                unlink($image);
            }

            foreach ($IMAGE_SIZE as $size){
                $size = explode('x', $size);
                $dest = $dir.$size[0].((isset ($size[1]))?'x'.$size[1]:'').'/'.$id.'.jpg';

                if (file_exists ($dest))
                {
                    unlink($dest);
                }
            }

            $preview = ROOT_DIR.'backoffice/img/preview/map_image_'.$id.'.jpg';
            if (file_exists($preview))
            {
                unlink($preview);
            }
        }
    }

