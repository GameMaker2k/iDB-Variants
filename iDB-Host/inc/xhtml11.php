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

    $FileInfo: xhtml11.php - Last Update: 02/07/2008 SVN 146 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "xhtml11.php" || $File3Name == "/xhtml11.php") {
    require('index.php');
    exit();
}
// Check to see if we serv the file as html or xhtml
// if we do xhtml we also check to see if user's browser
// can dispay if or else fallback to html
if ($Settings['output_type'] != "xhtml") {
    $Settings['output_type'] = "xhtml";
}
if ($Settings['output_type'] == "html") {
    $ccstart = "//<!--";
    $ccend = "//-->";
    @header("Content-Type: text/html; charset=".$Settings['charset']);
}
if ($Settings['output_type'] == "xhtml") {
    if (stristr($_SERVER["HTTP_ACCEPT"], "application/xhtml+xml")) {
        $ccstart = "//<![CDATA[";
        $ccend = "//]]>";
        @header("Content-Type: application/xhtml+xml; charset=".$Settings['charset']);
        xml_doc_start("1.0", $Settings['charset']);
    } else {
        if (stristr($_SERVER["HTTP_USER_AGENT"], "W3C_Validator")) {
            $ccstart = "//<![CDATA[";
            $ccend = "//]]>";
            @header("Content-Type: application/xhtml+xml; charset=".$Settings['charset']);
            xml_doc_start("1.0", $Settings['charset']);
        } else {
            $ccstart = "//<!--";
            $ccend = "//-->";
            @header("Content-Type: text/html; charset=".$Settings['charset']);
        }
    }
}
@header("Content-Script-Type: text/javascript");
if ($Settings['output_type'] != "xhtml") {
    if ($Settings['output_type'] != "html") {
        $ccstart = "//<!--";
        $ccend = "//-->";
        @header("Content-Type: text/html; charset=".$Settings['charset']);
    }
}
if ($ThemeSet['CSSType'] != "import" &&
   $ThemeSet['CSSType'] != "link" &&
   $ThemeSet['CSSType'] != "xml") {
    $ThemeSet['CSSType'] = "import";
}
if ($ThemeSet['CSSType'] == "xhtml") {
    xml_tag_make("xml-stylesheet", "type=text/css&href=".$ThemeSet['CSS']);
}
if ($ThemeSet['CSSType'] != "xhtml") {
    $ThemeSet['CSSType'] = "import";
}
if (isset($Settings['showverinfo'])) {
    $idbmisc['showverinfo'] = $Settings['showverinfo'];
}
if (!isset($Settings['showverinfo'])) {
    $idbmisc['showverinfo'] = false;
}
if ($Settings['showverinfo'] != true) {
    $iDBURL1 = "<a href=\"http://idb.berlios.de/\" title=\"".$iDB."\" onclick=\"window.open(this.href);return false;\">";
}
if ($Settings['showverinfo'] == true) {
    $iDBURL1 = "<a href=\"http://idb.berlios.de/\" title=\"".$VerInfo['iDB_Ver_Show']."\" onclick=\"window.open(this.href);return false;\">";
}
$GM2kURL = "<a href=\"signup.php\" title=\"".$GM2k."\" onclick=\"window.open(this.href);return false;\">".$GM2k."</a>";
$csryear = "2004";
$cryear = date("Y");
if ($cryear <= 2004) {
    $cryear = "2005";
}
$endpagevar = "<div class=\"copyright\">Powered by ".$iDBURL1.$RName."</a> &copy; ".$GM2kURL." ".$cryear." <br />\n".$ThemeSet['CopyRight'];
@header("Content-Language: en");
@header("Vary: Accept");
// Check if we are on a secure HTTP connection
if ($_SERVER['HTTPS'] == "on") {
    $prehost = "https://";
}
if ($_SERVER['HTTPS'] != "on") {
    $prehost = "http://";
}
// Get the board's url
if ($Settings['idburl'] == "localhost" || $Settings['idburl'] == null) {
    $BoardURL = $prehost.$_SERVER["HTTP_HOST"].$basedir;
}
if ($Settings['idburl'] != "localhost" && $Settings['idburl'] != null) {
    $BoardURL = $Settings['idburl'];
}
// HTML Document Starts, HTML meta tags and other html, head tags?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
   "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Language" content="en" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Settings['charset']; ?>" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<base href="<?php echo $BoardURL; ?>" />
<?php if ($Settings['showverinfo'] == true) { ?>
<meta name="Generator" content="<?php echo $VerInfo['iDB_Ver_Show']; ?>" />
<?php } if ($Settings['showverinfo'] != true) { ?>
<meta name="Generator" content="<?php echo $iDB; ?>" />
<?php } echo "\n"; ?>
<meta name="Author" content="<?php echo $SettInfo['Author']; ?>" />
<meta name="Keywords" content="<?php echo $SettInfo['Keywords']; ?>" />
<meta name="Description" content="<?php echo $SettInfo['Description']; ?>" />
<meta name="ROBOTS" content="Index, FOLLOW" />
<meta name="revisit-after" content="1 days" />
<meta name="GOOGLEBOT" content="Index, FOLLOW" />
<meta name="resource-type" content="document" />
<meta name="distribution" content="global" />
<?php if ($Settings['showverinfo'] == true) { ?>
<!-- generator="<?php echo $VerInfo['iDB_Ver_Show']; ?>" -->
<?php } if ($Settings['showverinfo'] != true) { ?>
<!-- generator="<?php echo $iDB; ?>" -->
<?php } echo "\n"; ?>

<script type="text/javascript" src="<?php echo url_maker($exfilejs['javascript'], $Settings['js_ext'], null, $Settings['qstr'], $Settings['qsep'], $prexqstrjs['javascript'], $exqstrjs['javascript']); ?>"></script>
<?php if ($ThemeSet['CSSType'] == "import") { ?>
<style type="text/css"><?php echo "\n@import url(\"".$ThemeSet['CSS']."\");\n"; ?></style>
<?php } if ($ThemeSet['CSSType'] == "link") { ?>
<link rel="prefetch alternate stylesheet" href="<?php echo $ThemeSet['CSS']; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $ThemeSet['CSS']; ?>" />
<?php } if ($ThemeSet['FavIcon'] != null) { ?>
<link rel="icon" href="<?php echo $ThemeSet['FavIcon']; ?>" />
<link rel="shortcut icon" href="<?php echo $ThemeSet['FavIcon']; ?>" />
<?php } ?>
