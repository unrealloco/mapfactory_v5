<?php

    include '../inc/conf.php';

    header("Cache-Control: no-cache");

    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $headers = "MIME-Version: 1.0\n";
    $headers .= "content-type: text/html; charset=iso-8859-1\n";
    $headers .= "From: ".$name." <".$email.">\n";
    $content = '<b>'.$name.' <'.$email.'></b> sent this message from Map-Factory.org<br><br>'.nl2br (str_replace ("\'", "'", $message));

    mail (ADMIN_EMAIL, 'MapFactory - '.$subject, $content, $headers);

