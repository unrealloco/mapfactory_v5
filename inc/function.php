<?php

    ////////////////////////////////////////////
    //	DEBUG
    ////////////////////////////////////////////

    function print_array ($array){
            echo '<pre>';
            print_r ($array);
            echo '</pre>';
    }

    function log404 ($source){
        $file = fopen('log/404', 'a');
        fwrite($file, date('c') . ' - ' . $_SERVER['REMOTE_ADDR'] . ' - ON: ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . ' - FROM: ' . $_SERVER['HTTP_REFERER'] . " - IN: " . $source . " \n");
        fclose($file);
    }

    function mail404 (){
        $headers = "MIME-Version: 1.0\n";
        $headers .= "content-type: text/html; charset=iso-8859-1\n";
        $headers .= "From: Admin <" . ADMIN_EMAIL . ">\n";

        mail (ADMIN_EMAIL, 'MapFactory - 404', 'on page: ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '<br />from page: ' . $_SERVER['HTTP_REFERER'], $headers);
    }



    ////////////////////////////////////////////
    //	UTILITIES
    ////////////////////////////////////////////

    function isOK (&$var){
            if (isset ($var)){
                    if (!empty ($var)){
                            return true;
                    }
            }
            return false;
    }

    function checkMail($email){
            $atom = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]';
            $domain = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';
            return eregi("^$atom+(\\.$atom+)*@($domain?\\.)+$domain\$", $email);
    }

    function microtime_float (){
        return (float) array_sum (explode (' ', microtime ()));
    }

    function microtime_string (){
        return (string) str_replace ('.', '', microtime_float ());
    }

    function xmlFormat ($text){
            return str_replace (array ('&', "'", '"', '>', '<'), array ('&amp;', '&apos;', '&quot;', '&gt;', '&lt;'), $text);
    }

    function array_ksupp (&$array, $keySup){
            $newArray = array ();
            $r = false;

            while (list ($key, $item) = each ($array)){
                    if ($key != $keySup){
                            $newArray[$key] = $item;
                    }else{
                            $r = true;
                    }
            }

            $array = $newArray;

            return $r;
    }

    function getLikeList ($q){
            $q = urldecode ($q);

            // replace spimple quotes by double quotes
            $q = str_replace ("'", '"', str_replace ('/\\', '', $q));

            // delet multiple quotes
            $q =  preg_replace ('#("+)#is', '"', $q);

            // delet spaces near cotes
            $q = preg_replace ('#(\s*"\s*)#is', '"', $q);

            // get out sentences (don't keep quotes)
            preg_match_all ('#"(.+)"#isU', $q, $sentences);

            // delet sentences (and quotes)
            $q =  preg_replace ('#(".+")#isU', ' ', $q);

            // delet multiple spaces
            $q =  preg_replace ('#(\s+)#is', ' ', $q);

            // get words
            $words = explode (' ', trim ($q));

            $searched_list = '';

            if (count ($sentences[1]) != 0){
                    $searched_list .= '%'.implode('%', $sentences[1]).'%';
            }

            if (!empty ($words[0])){
                    $searched_list .= '%'.implode('%', $words).'%';
            }

            return $searched_list;
    }

    function cutText ($text, $max){
            if (strlen ($text) >= $max){
                    $text = substr ($text, 0, $max);
                    $text = substr ($text, 0, strrpos ($text, " "))."...";
            }

            return $text;
    }

    function removeHTML ($text){
            $text = str_replace ('</p>', ' ', $text);
            $text = preg_replace ('#(<[^>]*>)#isU', '', $text);

            return $text;
    }

    function isRobotAgent (){
        $botlist = array(
            "msnbot",
            "Teoma",
            "alexa",
            "froogle",
            "inktomi",
            "looksmart",
            "URL_Spider_SQL",
            "Firefly",
            "NationalDirectory",
            "Ask Jeeves",
            "TECNOSEEK",
            "InfoSeek",
            "WebFindBot",
            "girafabot",
            "crawler",
            "www.galaxy.com",
            "Googlebot",
            "Scooter",
            "Slurp",
            "appie",
            "FAST",
            "WebBug",
            "Spade",
            "ZyBorg",
            "rabaz");

        $agent = $_SERVER['HTTP_USER_AGENT'];

        foreach ($botlist as $bot){
            if (stripos($agent, $bot) !== false){
                return true;
            }
        }

        return false;
    }



    ////////////////////////////////////////////
    //	CLEAN UP TEXT
    ////////////////////////////////////////////

    function makeGuid ($text){
            $text = strtolower ($text);
            // enleve tous les accents
            $text = strtr($text, "àáâãäåòóôõöøèéêëçìíîïùúûüÿñ", "aaaaaaooooooeeeeciiiiuuuuyn");
            // remplace tous ce qui n'est pas lettre ou chifre pas par un tir?
            $text = preg_replace ('([^a-z0-9\-])', '-', $text);
            // remplace les tiré mustiples par un tiré
            $text = preg_replace ('(-+)', '-', $text);
            // efface les underscore et les tirés en fin de chaine
            $text = ereg_replace('(-*$)', '', $text);
            // efface les underscore et les tirés eno.ok début de chaine
            $text = ereg_replace('(^-*)', '', $text);

            return $text;
    }

    function removeSpecialChar ($text){
            return strtr ($text, "àáâãäåòóôõöøèéêëçìíîïùúûüÿñ", "aaaaaaooooooeeeeciiiiuuuuyn");
    }




    ////////////////////////////////////////////
    //	DATES
    ////////////////////////////////////////////

    function timeWarp ($time){
            if ($time > time ()){
                    $diff = $time - time ();
            }else{
                    $diff = time () - $time;
            }

            if ($diff < 60){
                    $unit = 'second';
                    $n = $diff;
            }else
            if ($diff < 3600){
                    $unit = 'minute';
                    $n = round ($diff / 60);
            }else
            if ($diff < 86400){
                    $unit = 'hour';
                    $n = round ($diff / 3600);
            }else
            if ($diff < 604800){
                    $unit = 'day';
                    $n = round ($diff / 86400);
            }else
            if ($diff < 1814400){
                    $unit = 'week';
                    $n = round ($diff / 604800);
            }else{
                    return date ('d/m/Y', $time);
            }

            if ($n > 1){
                    $s = 's';
            }else{
                    $s = '';
            }

            if ($time > time ()){
                    return 'in '.$n.' '.$unit.$s;
            }else{
                    return $n.' '.$unit.$s.' ago';
            }
    }




    ////////////////////////////////////////////
    //	IMAGES
    ////////////////////////////////////////////

    function redimage ($src, $dest, $dw=false, $dh=false, $stamp = false){
            // detect file type (could be a lot better)
            if (is_array ($src)){
                    $type_src = strtoupper (substr ($src['name'], -3));
                    $src = $src['tmp_name'];
            }else{
                    $type_src = strtoupper (substr ($src, -3));
            }

            $type_dest = strtoupper (substr ($dest, -3));

            // read source image
            switch ($type_src){
                    case 'JPG' : $src_img = ImageCreateFromJpeg ($src);
                            break;
                    case 'PEG' : $src_img = ImageCreateFromJpeg ($src);
                            break;
                    case 'GIF' : $src_img = ImageCreateFromGif ($src);
                            break;
                    case 'PNG' : $src_img = imageCreateFromPng ($src);
                            break;
                    case 'BMP' : $src_img = imageCreatefromWBmp ($src);
                            break;
            }

            // get it's info
            $size = GetImageSize ($src);
            $sw = $size[0];
            $sh = $size[1];

/*
            // get it's info
            $size = GetImageSize ($src);
            $fw = $size[0];
            $fh = $size[1];

            // ROGNE the picture from the top left pixel's color
            $rogne_color = imagecolorat ($src_img, 0, 0);
            $rogne_point = array ($fh, 0, 0, $fw);

            for ($x = 0; $x < $fw; $x ++){
                    for ($y = 0; $y < $fh; $y ++){
                            if (imagecolorat ($src_img, $x, $y) != $rogne_color){
                                    $rogne_point[0] = ($rogne_point[0] > $y)?$y:$rogne_point[0];
                                    $rogne_point[1] = ($rogne_point[1] < $x)?$x:$rogne_point[1];
                                    $rogne_point[2] = ($rogne_point[2] < $y)?$y:$rogne_point[2];
                                    $rogne_point[3] = ($rogne_point[3] > $x)?$x:$rogne_point[3];
                            }
                    }
            }

            $sw = $rogne_point[1] - $rogne_point[3];
            $sh = $rogne_point[2] - $rogne_point[0];

            $rogne_img = ImageCreateTrueColor ($sw, $sh);

            ImageCopyResampled ($rogne_img, $src_img, 0, 0, $rogne_point[3], $rogne_point[0], $fw, $fh, $fw, $fh);

            $src_img = $rogne_img;
*/

            // do not redim the pic
            if ($dw == false && $dh == false){
                    $dest_img = ImageCreateTrueColor ($sw, $sh);

                    ImageCopyResampled ($dest_img, $src_img, 0, 0, 0, 0, $sw, $sh, $sw, $sh);
            }else
            // redim the pix with dest W as max Side
            if ($dw != 0 && $dh == false){
                    if ($sw == $sh){
                            $dh = $dw;
                    }else
                    if ($sw > $sh){
                            $dh = round (($dw / $sw) * $sh);
                    }else{
                            $dh = $dw;
                            $dw = round (($dh / $sh) * $sw);
                    }

                    $dest_img = ImageCreateTrueColor ($dw, $dh);

                    ImageCopyResampled ($dest_img, $src_img, 0, 0, 0, 0, $dw, $dh, $sw, $sh);
            }else
            // redim the pic according to dest W or dest H
            if ($dw == 0 || $dh == 0){
                    if ($dw == 0){
                            $dw = round (($dh / $sh) * $sw);
                    }else
                    if ($dh == 0){
                            $dh = round (($dw / $sw) * $sh);
                    }

                    $dest_img = ImageCreateTrueColor ($dw, $dh);

                    ImageCopyResampled ($dest_img, $src_img, 0, 0, 0, 0, $dw, $dh, $sw, $sh);
            }else
            // redim the pic and crop it according to dest W and dest H
            {
                    if ($sw / $sh < $dw / $dh){
                            $tw = $sw;
                            $th = round (($sw / $dw) * $dh);

                            $x = 0;
                            $y = round (($sh - $th) / 2);

                            $temp_img = ImageCreateTrueColor ($tw, $th);
                            $dest_img = ImageCreateTrueColor ($dw, $dh);

                            ImageCopyResampled ($temp_img, $src_img, 0, 0, $x, $y, $sw, $sh, $sw, $sh);
                            ImageCopyResampled ($dest_img, $temp_img, 0, 0, 0, 0, $dw, $dh, $tw, $th);

                            ImageDestroy ($temp_img);
                    }else{
                            $tw = $sw;
                            $th = round ($sw * ($dh / $dw));

                            $x = 0;
                            $y = round (($th - $sh) / 2);

                            $temp_img = ImageCreateTrueColor ($tw, $th);
                            $dest_img = ImageCreateTrueColor ($dw, $dh);

                            imagefill ($temp_img, 0, 0, imagecolorallocate ($dest_img, 0, 0, 0));

                            ImageCopyResampled ($temp_img, $src_img, $x, $y, 0, 0, $sw, $sh, $sw, $sh);
                            ImageCopyResampled ($dest_img, $temp_img, 0, 0, 0, 0, $dw, $dh, $tw, $th);

                            ImageDestroy ($temp_img);
                    }
            }

            if ($stamp != false){
                    // detect file type (could be a lot better)
                    $type_stamp = strtoupper (substr ($stamp, -3));

                    // read  stamp
                    switch ($type_stamp){
                            case 'JPG' : $stamp_img = ImageCreateFromJpeg ($stamp);
                                    break;
                            case 'PEG' : $stamp_img = ImageCreateFromJpeg ($stamp);
                                    break;
                            case 'GIF' : $stamp_img = ImageCreateFromGif ($stamp);
                                    break;
                            case 'PNG' : $stamp_img = imageCreateFromPng ($stamp);
                                    break;
                            case 'BMP' : $stamp_img = imageCreatefromWBmp ($stamp);
                                    break;
                    }

                    // get it's info
                    $size = GetImageSize ($stamp);
                    $stw = $size[0];
                    $sth = $size[1];

                    $sx = $dw - $stw;
                    $sy = $dh - $sth;

                    imagecolortransparent ($stamp_img, imageColorAllocate ($stamp_img, 0, 0, 0));

                    imagecopy ($dest_img, $stamp_img, $sx, $sy, 0, 0, $stw, $sth);
            }

            // free destination
            if (file_exists ($dest_img)){
                    unlink ($dest_img);
            }

            // save dest image
            switch ($type_dest){
                    case 'JPG' : imageJpeg ($dest_img, $dest, 90);
                            break;
                    case 'PEG' : imageJpeg ($dest_img, $dest, 90);
                            break;
                    case 'GIF' : imageGif ($dest_img, $dest, 90);
                            break;
                    case 'PNG' : imagePng ($dest_img, $dest, 90);
                            break;
                    case 'BMP' : imageWBmp ($dest_img, $dest, 90);
                            break;
            }

            // free memory
            imageDestroy ($src_img);
            ImageDestroy ($dest_img);
    }

?>
