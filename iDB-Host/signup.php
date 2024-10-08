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
    iDB Installer made by Game Maker 2k - http://upload.idb.s1.jcink.com/

    $FileInfo: signup.php - Last Update: 02/07/2008 SVN 146 - Author: cooldude2k $
*/
@error_reporting(E_ALL ^ E_NOTICE);
if (@ini_get("register_globals")) {
    if (!isset($SettDir['misc'])) {
        $SettDir['misc'] = "inc/misc/";
    }
    require_once($SettDir['misc'].'killglobals.php');
}
require('settings.php');
if (!isset($preact['idb'])) {
    $preact['idb'] = null;
}
if (!isset($_GET['act'])) {
    $_GET['act'] = null;
}
if (!isset($_POST['act'])) {
    $_POST['act'] = null;
}
if ($_GET['act'] == null || $_GET['act'] == "view") {
    $_GET['act'] = "Part1";
}
if ($_POST['act'] == null || $_POST['act'] == "view") {
    $_POST['act'] = "Part1";
}
$_TEG = array(null);
$_TEG['part'] = preg_replace("/Part(1|2|3|4)/", "\\1", $_GET['act']);
$_GET['act'] = strtolower($_GET['act']);
if (isset($_TEG['part'])) {
    if ($_TEG['part'] <= 4 && $_TEG['part'] >= 1) {
        $_GET['act'] = "Part".$_TEG['part'];
    }
}
if ($_GET['act'] != "Part4" && $_POST['act'] != "Part4") {
    $preact['idb'] = "installing";
}
$SetupDir['setup'] = "signup/";
$ConvertDir['setup'] = $SetupDir['setup'];
$SetupDir['convert'] = null;
$ConvertDir['convert'] = $SetupDir['convert'];
$Settings['output_type'] = "html";
$Settings['html_type'] = "xhtml10";
$Settings['board_name'] = "iDB Easy Host";
if (!isset($Settings['charset'])) {
    $Settings['charset'] = "ISO-8859-15";
}
if (isset($Settings['charset'])) {
    if ($Settings['charset'] != "ISO-8859-15" && $Settings['charset'] != "ISO-8859-1" &&
        $Settings['charset'] != "UTF-8" && $Settings['charset'] != "CP866" &&
        $Settings['charset'] != "Windows-1251" && $Settings['charset'] != "Windows-1252" &&
        $Settings['charset'] != "KOI8-R" && $Settings['charset'] != "BIG5" &&
        $Settings['charset'] != "GB2312" && $Settings['charset'] != "BIG5-HKSCS" &&
        $Settings['charset'] != "Shift_JIS" && $Settings['charset'] != "EUC-JP") {
        $Settings['charset'] = "ISO-8859-15";
    }
}
if (!isset($_GET['board'])) {
    $_GET['board'] = $Settings['root_board'];
    $_GET['board'] = strtolower($_GET['board']);
    $_GET['board'] = preg_replace("/[^A-Za-z0-9_$]/", "", $_GET['board']);
    $_GET['board'] = preg_replace("/(.*?)\.\/(.*?)/", "", $_GET['board']);
    $_GET['board'] = preg_replace("/(.*?)\/(.*?)/", "", $_GET['board']);
    $_GET['board'] = preg_replace("/(.*?)\.(.*?)/", "", $_GET['board']);
}
require($SetupDir['setup'].'preinstall.php');
require_once($SettDir['inc'].'filename.php');
require_once($SettDir['inc'].'function.php');
//require($SetupDir['convert'].'info.php');
require($SettDir['inc'].'xhtml10.php');
$Error = null;
$_GET['time'] = false;
?>

<title> Signing up for iDB Easy Host </title>
</head>
<body>
<?php require($SettDir['inc'].'navbar.php'); ?>

<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
&nbsp;<a href="signup.php"> Signing up for iDB Easy Host </a></span>
<span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Inert your info: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<?php
if ($_SERVER['HTTPS'] == "on") {
    $prehost = "https://";
}
if ($_SERVER['HTTPS'] != "on") {
    $prehost = "http://";
}
$this_dir = null;
if (dirname($_SERVER['SCRIPT_NAME']) != "." ||
    dirname($_SERVER['SCRIPT_NAME']) != null) {
    $this_dir = dirname($_SERVER['SCRIPT_NAME'])."/";
}
if ($this_dir == null || $this_dir == ".") {
    if (dirname($_SERVER['SCRIPT_NAME']) == "." ||
        dirname($_SERVER['SCRIPT_NAME']) == null) {
        $this_dir = dirname($_SERVER['PHP_SELF'])."/";
    }
}
if ($this_dir == "\/") {
    $this_dir = "/";
}
$this_dir = str_replace("//", "/", $this_dir);
$idbdir = addslashes(str_replace("\\", "/", dirname(__FILE__)."/"));
function sql_list_dbs()
{
    $result = mysql_query("SHOW DATABASES;");
    while ($data = mysql_fetch_row($result)) {
        $array[] = $data[0];
    } return $array;
}
if ($_GET['act'] != "Part2" && $_POST['act'] != "Part2") {
    if ($_GET['act'] != "Part3" && $_POST['act'] != "Part3") {
        require($SetupDir['setup'].'license.php');
    }
}
if ($_GET['act'] == "Part2" && $_POST['act'] == "Part2") {
    if ($_GET['act'] != "Part3" && $_POST['act'] != "Part3") {
        require($SetupDir['setup'].'setup.php');
    }
}
if ($_GET['act'] != "Part2" && $_POST['act'] != "Part2") {
    if ($_GET['act'] == "Part3" && $_POST['act'] == "Part3") {
        require($SetupDir['setup'].'mkconfig.php');
    }
}
if ($Error == "Yes") { ?>
<br />Install Failed with errors. <a href="signup.php?act=view">Click here</a> to restart install. &lt;_&lt;
<br /><br />
</td>
</tr>
<?php } ?>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;<a href="index.php?act=ReadMe">Readme.txt</a>&nbsp;</td>
</tr>
</table>
<div>&nbsp;</div>
<?php require($SettDir['inc'].'endpage.php'); ?>
</body>
</html>
<?php fix_amp(null); ?>
