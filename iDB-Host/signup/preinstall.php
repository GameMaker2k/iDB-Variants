<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2008 Cool Dude 2k - http://idb.berlios.de/
    Copyright 2004-2008 Game Maker 2k - http://intdb.sourceforge.net/

    $FileInfo: preinstall.php - Last Update: 01/01/2008 SVN 144 - Author: cooldude2k $
*/
@error_reporting(E_ALL ^ E_NOTICE);
@ini_set('session.use_trans_sid', false);
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "preinstall.php" || $File3Name == "/preinstall.php") {
    @header('Location: index.php');
    exit();
}

@header("Cache-Control: private, must-revalidate"); // IE 6 Fix
@header("Pragma: private, must-revalidate");
@header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
@header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
@header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
@output_reset_rewrite_vars();
if (!isset($SettDir['inc'])) {
    $SettDir['inc'] = "inc/";
}
if (!isset($SettDir['misc'])) {
    $SettDir['misc'] = "inc/misc/";
}
if (!isset($SettDir['admin'])) {
    $SettDir['admin'] = "inc/admin/";
}
if (!isset($SettDir['mod'])) {
    $SettDir['mod'] = "inc/mod/";
}
if (!isset($SettDir['themes'])) {
    $SettDir['themes'] = "themes/";
}
if (!isset($_POST['License'])) {
    $_POST['License'] = null;
}
if (file_exists($SettDir['themes']."iDB/settings.php")) {
    require($SettDir['themes']."iDB/settings.php");
}
if (!file_exists($SettDir['themes']."iDB/settings.php")) {
    require($SettDir['themes']."Gray/settings.php");
}
if ($Settings['fixbasedir'] == true) {
    if ($Settings['idburl'] != null && $Settings['idburl'] != "localhost") {
        $PathsTest = parse_url($Settings['idburl']);
        $Settings['fixbasedir'] = $PathsTest['path']."/";
        $Settings['fixbasedir'] = str_replace("//", "/", $Settings['fixbasedir']);
    }
}
if ($Settings['enable_https'] == true && $_SERVER['HTTPS'] == "on") {
    if ($Settings['idburl'] != null && $Settings['idburl'] != "localhost") {
        $HTTPsTest = parse_url($Settings['idburl']);
        if ($HTTPsTest['scheme'] == "http") {
            $Settings['idburl'] = preg_replace("/http\:\/\//i", "https://", $Settings['idburl']);
        }
    }
}
$cookieDomain = null;
$cookieSecure = false;
if ($Settings['idburl'] != null && $Settings['idburl'] != "localhost") {
    $URLsTest = parse_url($Settings['idburl']);
    $cookieDomain = $URLsTest['host'];
    if ($Settings['enable_https'] == true) {
        if ($URLsTest['scheme'] == "https") {
            $cookieSecure = true;
        }
        if ($URLsTest['scheme'] != "https") {
            $cookieSecure = false;
        }
    }
}
if (dirname($_SERVER['SCRIPT_NAME']) != "." ||
    dirname($_SERVER['SCRIPT_NAME']) != null) {
    $basedir = dirname($_SERVER['SCRIPT_NAME'])."/";
}
if ($basedir == null || $basedir == ".") {
    if (dirname($_SERVER['SCRIPT_NAME']) == "." ||
        dirname($_SERVER['SCRIPT_NAME']) == null) {
        $basedir = dirname($_SERVER['PHP_SELF'])."/";
    }
}
if ($basedir == "\/") {
    $basedir = "/";
}
$basedir = str_replace("//", "/", $basedir);
$basedir = "/";
$BaseURL = $basedir;
// Get our Host Name and Referer URL's Host Name
if (!isset($_SERVER['HTTP_REFERER'])) {
    $_SERVER['HTTP_REFERER'] = null;
}
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
if (!isset($REFERERurl['host'])) {
    $REFERERurl['host'] = null;
}
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $_SERVER["SERVER_NAME"];
$REFERERurl = null;
unset($REFERERurl);
