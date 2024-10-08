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

    $FileInfo: topic.php - Last Update: 01/01/2008 SVN 144 - Author: cooldude2k $
*/
require('preindex.php');
$usefileext = $Settings['file_ext'];
if ($ext == "noext" || $ext == "no ext" || $ext == "no+ext") {
    $usefileext = "";
}
$filewpath = $exfile['topic'].$usefileext.$_SERVER['PATH_INFO'];
?>

<title> <?php echo $Settings['board_name'].$idbpowertitle; ?> </title>
</head>
<body>
<?php require($SettDir['inc'].'navbar.php');
if ($_GET['act'] == null) {
    $_GET['act'] = "view";
}
if (!is_numeric($_GET['id'])) {
    $_GET['id'] = "1";
}
if ($_GET['act'] == "view" || $_GET['act'] == "create" || $_GET['act'] == "delete" ||
    $_GET['act'] == "pin" || $_GET['act'] == "unpin" ||
    $_GET['act'] == "open" || $_GET['act'] == "close") {
    require($SettDir['inc'].'replys.php');
}
if ($_GET['act'] == "edit" || $_GET['act'] == "makereply" || $_POST['act'] == "makereplies" ||
    $_GET['act'] == "editreply" || $_POST['act'] == "editreplies") {
    require($SettDir['inc'].'replys.php');
}
require($SettDir['inc'].'endpage.php');
if (!isset($TopicName)) {
    $TopicName = null;
}
?>

</body>
</html>
<?php
if ($_GET['act'] == "view") {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." ".$TopicName, $Settings['use_gzip'], $GZipEncode['Type']);
}
if ($_GET['act'] == "create") {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Replying to ".$TopicName, $Settings['use_gzip'], $GZipEncode['Type']);
}
if ($_GET['act'] == "delete") {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Deleting a Post", $Settings['use_gzip'], $GZipEncode['Type']);
}
if ($_GET['act'] == "edit") {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Editing a Post", $Settings['use_gzip'], $GZipEncode['Type']);
}
if ($_GET['act'] == "editreply" && $_POST['act'] == "editreplies") {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Editing a Post", $Settings['use_gzip'], $GZipEncode['Type']);
}
if ($_GET['act'] == "pin") {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Pinning a Topic", $Settings['use_gzip'], $GZipEncode['Type']);
}
if ($_GET['act'] == "unpin") {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Unpinning a Topic", $Settings['use_gzip'], $GZipEncode['Type']);
}
if ($_GET['act'] == "open") {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Opening a Topic", $Settings['use_gzip'], $GZipEncode['Type']);
}
if ($_GET['act'] == "close") {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Closing a Topic", $Settings['use_gzip'], $GZipEncode['Type']);
}
if ($_GET['act'] == "makereply" && $_POST['act'] == "makereplies") {
    change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." Replying to ".$TopicName, $Settings['use_gzip'], $GZipEncode['Type']);
}
?>
