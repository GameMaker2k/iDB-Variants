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

    $FileInfo: event.php - Last Update: 01/01/2008 SVN 144 - Author: cooldude2k $
*/
require('preindex.php');
$usefileext = $Settings['file_ext'];
if ($ext == "noext" || $ext == "no ext" || $ext == "no+ext") {
    $usefileext = "";
}
$filewpath = $exfile['event'].$usefileext.$_SERVER['PATH_INFO'];
?>

<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
</head>
<body>
<?php if ($_GET['act'] == null) {
    $_GET['act'] = "view";
}
if (!is_numeric($_GET['id'])) {
    $_GET['id'] = "1";
}
require($SettDir['inc'].'navbar.php');
if ($_GET['act'] == "event" || $_GET['act'] == null) {
    $_GET['act'] = "view";
}
if ($_GET['act'] == "view" || $_GET['act'] == "create" ||
    $_GET['act'] == "makeevent" || $_POST['act'] == "makeevents") {
    require($SettDir['inc'].'events.php');
}
require($SettDir['inc'].'endpage.php');
if (!isset($EventName)) {
    $EventName = null;
}
?>

</body>
</html>
<?php
if ($_GET['act'] == "view") {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." ".$EventName, $Settings['use_gzip'], $GZipEncode['Type']);
}
if ($_GET['act'] == "create" || $_GET['act'] == "makeevent" || $_POST['act'] == "makeevents") {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Making a Event", $Settings['use_gzip'], $GZipEncode['Type']);
}
?>
