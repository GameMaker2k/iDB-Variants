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

    $FileInfo: members.php - Last Update: 04/12/2008 SVN 160 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "members.php" || $File3Name == "/members.php") {
    require('index.php');
    exit();
}
if ($_GET['act'] == "list") {
    $orderlist = null;
    $orderlist = "order by `ID` asc";
    if (!isset($_GET['orderby'])) {
        $_GET['orderby'] = null;
    }
    if (!isset($_GET['sorttype'])) {
        $_GET['sorttype'] = null;
    }
    if (!isset($_GET['ordertype'])) {
        $_GET['ordertype'] = null;
    }
    if (!isset($_GET['orderby'])) {
        $_GET['orderby'] = null;
    }
    if (!isset($_GET['sortby'])) {
        $_GET['sortby'] = null;
    }
    if (!isset($_GET['gid'])) {
        $_GET['gid'] = null;
    }
    if (!isset($_GET['groupid'])) {
        $_GET['groupid'] = null;
    }
    if ($_GET['orderby'] == null) {
        if ($_GET['sortby'] != null) {
            $_GET['orderby'] = $_GET['sortby'];
        }
    }
    if ($_GET['orderby'] == null) {
        $_GET['orderby'] = "joined";
    }
    if ($_GET['orderby'] != null) {
        if ($_GET['orderby'] == "id") {
            $orderlist = "order by `ID`";
        }
        if ($_GET['orderby'] == "name") {
            $orderlist = "order by `Name`";
        }
        if ($_GET['orderby'] == "joined") {
            $orderlist = "order by `Joined`";
        }
        if ($_GET['orderby'] == "active") {
            $orderlist = "order by `LastActive`";
        }
        if ($_GET['orderby'] == "posts") {
            $orderlist = "order by `PostCount`";
        }
        if ($_GET['orderby'] == "offset") {
            $orderlist = "order by `TimeZone`";
        }
    }
    if ($_GET['ordertype'] == null) {
        if ($_GET['sorttype'] != null) {
            $_GET['ordertype'] = $_GET['sorttype'];
        }
    }
    if ($_GET['ordertype'] == null) {
        $_GET['ordertype'] = "asc";
    }
    if ($_GET['ordertype'] != null) {
        if ($_GET['ordertype'] == "ascending") {
            $orderlist .= " asc";
        }
        if ($_GET['ordertype'] == "descending") {
            $orderlist .= " desc";
        }
        if ($_GET['ordertype'] == "asc") {
            $orderlist .= " asc";
        }
        if ($_GET['ordertype'] == "desc") {
            $orderlist .= " desc";
        }
    }
    if (!is_numeric($_GET['gid'])) {
        $_GET['gid'] = null;
    }
    if ($_GET['gid'] != null && $_GET['groupid'] == null) {
        $_GET['groupid'] = $_GET['gid'];
    }
    if (!is_numeric($_GET['groupid'])) {
        $_GET['groupid'] = null;
    }
    $ggquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `Name`='%s' LIMIT 1", array($Settings['GuestGroup']));
    $ggresult = mysql_query($ggquery);
    $GGroup = mysql_result($ggresult, 0, "id");
    @mysql_free_result($ggresult);
    //Get SQL LIMIT Number
    $nums = $_GET['page'] * $Settings['max_memlist'];
    $PageLimit = $nums - $Settings['max_memlist'];
    if ($PageLimit < 0) {
        $PageLimit = 0;
    }
    $i = 0;
    if ($_GET['groupid'] == null) {
        $query = query("SELECT SQL_CALC_FOUND_ROWS * FROM `".$Settings['sqltable']."members` WHERE `GroupID`<>%i ".$orderlist." LIMIT %i,%i", array($GGroup,$PageLimit,$Settings['max_memlist']));
    }
    if ($_GET['groupid'] != null) {
        $query = query("SELECT SQL_CALC_FOUND_ROWS * FROM `".$Settings['sqltable']."members` WHERE `GroupID`=%i AND `GroupID`<>%i ".$orderlist." LIMIT %i,%i", array($_GET['groupid'],$GGroup,$PageLimit,$Settings['max_memlist']));
    }
    $rnquery = query("SELECT FOUND_ROWS();", array(null));
    $result = mysql_query($query);
    $rnresult = mysql_query($rnquery);
    $NumberMembers = mysql_result($rnresult, 0);
    @mysql_free_result($rnresult);
    if ($NumberMembers == null) {
        $NumberMembers = 0;
    }
    $num = $NumberMembers;
    //Start MemberList Page Code
    if (!isset($Settings['max_memlist'])) {
        $Settings['max_memlist'] = 10;
    }
    if ($_GET['page'] == null) {
        $_GET['page'] = 1;
    }
    if ($_GET['page'] <= 0) {
        $_GET['page'] = 1;
    }
    $nums = $_GET['page'] * $Settings['max_memlist'];
    if ($nums > $num) {
        $nums = $num;
    }
    $numz = $nums - $Settings['max_memlist'];
    if ($numz <= 0) {
        $numz = 0;
    }
    //$i=$numz;
    if ($nums < $num) {
        $nextpage = $_GET['page'] + 1;
    }
    if ($nums >= $num) {
        $nextpage = $_GET['page'];
    }
    if ($numz >= $Settings['max_memlist']) {
        $backpage = $_GET['page'] - 1;
    }
    if ($_GET['page'] <= 1) {
        $backpage = 1;
    }
    $pnum = $num;
    $l = 1;
    $Pages = null;
    while ($pnum > 0) {
        if ($pnum >= $Settings['max_memlist']) {
            $pnum = $pnum - $Settings['max_memlist'];
            $Pages[$l] = $l;
            ++$l;
        }
        if ($pnum < $Settings['max_memlist'] && $pnum > 0) {
            $pnum = $pnum - $pnum;
            $Pages[$l] = $l;
            ++$l;
        }
    }
    $nums = $_GET['page'] * $Settings['max_memlist'];
    //End MemberList Page Code
    $num = mysql_num_rows($result);
    //List Page Number Code Start
    $pagenum = count($Pages);
    if ($_GET['page'] > $pagenum) {
        $_GET['page'] = $pagenum;
    }
    $pagei = 0;
    $pstring = "<div class=\"PageList\">Pages: ";
    if ($_GET['page'] < 4) {
        $Pagez[0] = null;
    }
    if ($_GET['page'] >= 4) {
        $Pagez[0] = "First";
    }
    if ($_GET['page'] >= 3) {
        $Pagez[1] = $_GET['page'] - 2;
    }
    if ($_GET['page'] < 3) {
        $Pagez[1] = null;
    }
    if ($_GET['page'] >= 2) {
        $Pagez[2] = $_GET['page'] - 1;
    }
    if ($_GET['page'] < 2) {
        $Pagez[2] = null;
    }
    $Pagez[3] = $_GET['page'];
    if ($_GET['page'] < $pagenum) {
        $Pagez[4] = $_GET['page'] + 1;
    }
    if ($_GET['page'] >= $pagenum) {
        $Pagez[4] = null;
    }
    $pagenext = $_GET['page'] + 1;
    if ($pagenext < $pagenum) {
        $Pagez[5] = $_GET['page'] + 2;
    }
    if ($pagenext >= $pagenum) {
        $Pagez[5] = null;
    }
    if ($_GET['page'] < $pagenum) {
        $Pagez[6] = "Last";
    }
    if ($_GET['page'] >= $pagenum) {
        $Pagez[6] = null;
    }
    $pagenumi = count($Pagez);
    if ($NumberMembers == 0) {
        $pagenumi = 0;
        $pstring = $pstring."<a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">1</a> ";
    }
    while ($pagei < $pagenumi) {
        if ($Pagez[$pagei] != null &&
           $Pagez[$pagei] != "First" &&
           $Pagez[$pagei] != "Last") {
            $pstring = $pstring."<a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=".$Pagez[$pagei], $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">".$Pagez[$pagei]."</a> ";
        }
        if ($Pagez[$pagei] == "First") {
            $pstring = $pstring."<a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">&lt; First</a> ... ";
        }
        if ($Pagez[$pagei] == "Last") {
            $pstring = $pstring."... <a href=\"".url_maker($exfile['member'], $Settings['file_ext'], "act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=".$pagenum, $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'])."\">Last &gt;</a> ";
        }
        ++$pagei;
    } $pstring = $pstring."</div>";
    echo $pstring;
    //List Page Number Code end
    ?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1" colspan="7"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=list&orderby=".$_GET['orderby']."&ordertype=".$_GET['ordertype']."&page=".$_GET['page'], $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>">Member List</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="Member" class="TableRow2">
<th class="TableRow2" style="width: 5%;">ID</th>
<th class="TableRow2" style="width: 28%;">Name</th>
<th class="TableRow2" style="width: 10%;">Group</th>
<th class="TableRow2" style="width: 10%;">Posts</th>
<th class="TableRow2" style="width: 20%;">Joined</th>
<th class="TableRow2" style="width: 20%;">Last Active</th>
<th class="TableRow2" style="width: 7%;">Website</th>
</tr>
<?php
while ($i < $num) {
    $MemList['ID'] = mysql_result($result, $i, "id");
    $MemList['Name'] = mysql_result($result, $i, "Name");
    $MemList['Email'] = mysql_result($result, $i, "Email");
    $MemList['GroupID'] = mysql_result($result, $i, "GroupID");
    $MemList['WarnLevel'] = mysql_result($result, $i, "WarnLevel");
    $MemList['Interests'] = mysql_result($result, $i, "Interests");
    $MemList['Title'] = mysql_result($result, $i, "Title");
    $MemList['Joined'] = mysql_result($result, $i, "Joined");
    $MemList['Joined'] = GMTimeChange("F j Y, g:i a", $MemList['Joined'], $_SESSION['UserTimeZone'], 0, $_SESSION['UserDST']);
    $MemList['LastActive'] = mysql_result($result, $i, "LastActive");
    $MemList['LastActive'] = GMTimeChange("F j Y, g:i a", $MemList['LastActive'], $_SESSION['UserTimeZone'], 0, $_SESSION['UserDST']);
    $MemList['Website'] = mysql_result($result, $i, "Website");
    $MemList['Gender'] = mysql_result($result, $i, "Gender");
    $MemList['PostCount'] = mysql_result($result, $i, "PostCount");
    $MemList['TimeZone'] = mysql_result($result, $i, "TimeZone");
    $MemList['DST'] = mysql_result($result, $i, "DST");
    $MemList['IP'] = mysql_result($result, $i, "IP");
    $gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($MemList['GroupID']));
    $gresult = mysql_query($gquery);
    $MemList['Group'] = mysql_result($gresult, 0, "Name");
    @mysql_free_result($gresult);
    $membertitle = " ".$ThemeSet['TitleDivider']." Member List";
    if ($MemList['Group'] != $Settings['GuestGroup']) {
        ?>
<tr class="TableRow3" id="Member<?php echo $MemList['ID']; ?>">
<td class="TableRow3" style="text-align: center;"><?php echo $MemList['ID']; ?></td>
<td class="TableRow3">&nbsp;<a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=view&id=".$MemList['ID'], $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>"><?php echo $MemList['Name']; ?></a></td>
<td class="TableRow3" style="text-align: center;"><a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=list&gid=".$MemList['GroupID']."&page=".$_GET['page'], $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>"><?php echo $MemList['Group']; ?></a></td>
<td class="TableRow3" style="text-align: center;"><?php echo $MemList['PostCount']; ?></td>
<td class="TableRow3" style="text-align: center;"><?php echo $MemList['Joined']; ?></td>
<td class="TableRow3" style="text-align: center;"><?php echo $MemList['LastActive']; ?></td>
<td class="TableRow3" style="text-align: center;"><a href="<?php echo $MemList['Website']; ?>" onclick="window.open(this.href);return false;">Website</a></td>
</tr>
<?php }
    ++$i;
} @mysql_free_result($result);
    ?>
<tr id="MemEnd" class="TableRow4">
<td class="TableRow4" colspan="7">&nbsp;</td>
</tr>
</table></div>
<?php }
if ($_GET['act'] == "view") {
    $query = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i LIMIT 1", array($_GET['id']));
    $result = mysql_query($query);
    $num = mysql_num_rows($result);
    $i = 0;
    if ($num == 0 || $_GET['id'] == "-1") {
        redirect("location", $basedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
        ob_clean();
        @header("Content-Type: text/plain; charset=".$Settings['charset']);
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        @mysql_close();
        die();
    }
    $ViewMem['ID'] = mysql_result($result, $i, "id");
    $ViewMem['Name'] = mysql_result($result, $i, "Name");
    $ViewMem['Signature'] = mysql_result($result, $i, "Signature");
    $ViewMem['Avatar'] = mysql_result($result, $i, "Avatar");
    $ViewMem['AvatarSize'] = mysql_result($result, $i, "AvatarSize");
    $ViewMem['Email'] = mysql_result($result, $i, "Email");
    $ViewMem['GroupID'] = mysql_result($result, $i, "GroupID");
    $ViewMem['WarnLevel'] = mysql_result($result, $i, "WarnLevel");
    $ViewMem['Interests'] = mysql_result($result, $i, "Interests");
    $ViewMem['Title'] = mysql_result($result, $i, "Title");
    $ViewMem['Joined'] = mysql_result($result, $i, "Joined");
    $ViewMem['Joined'] = GMTimeChange("M j Y, g:i a", $ViewMem['Joined'], $_SESSION['UserTimeZone'], 0, $_SESSION['UserDST']);
    $ViewMem['LastActive'] = mysql_result($result, $i, "LastActive");
    $ViewMem['LastActive'] = GMTimeChange("M j Y, g:i a", $ViewMem['LastActive'], $_SESSION['UserTimeZone'], 0, $_SESSION['UserDST']);
    $ViewMem['Website'] = mysql_result($result, $i, "Website");
    $ViewMem['Gender'] = mysql_result($result, $i, "Gender");
    $ViewMem['PostCount'] = mysql_result($result, $i, "PostCount");
    $ViewMem['TimeZone'] = mysql_result($result, $i, "TimeZone");
    $ViewMem['DST'] = mysql_result($result, $i, "DST");
    $ViewMem['IP'] = mysql_result($result, $i, "IP");
    $gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($ViewMem['GroupID']));
    $gresult = mysql_query($gquery);
    $ViewMem['Group'] = mysql_result($gresult, 0, "Name");
    @mysql_free_result($gresult);
    $membertitle = " ".$ThemeSet['TitleDivider']." ".$ViewMem['Name'];
    if ($ViewMem['Avatar'] == "http://" || $ViewMem['Avatar'] == null ||
        strtolower($ViewMem['Avatar']) == "noavatar") {
        $ViewMem['Avatar'] = $ThemeSet['NoAvatar'];
        $ViewMem['AvatarSize'] = $ThemeSet['NoAvatarSize'];
    }
    $AvatarSize1 = explode("x", $ViewMem['AvatarSize']);
    $AvatarSize1W = $AvatarSize1[0];
    $AvatarSize1H = $AvatarSize1[1];
    $ViewMem['Signature'] = text2icons($ViewMem['Signature'], $Settings['sqltable']);
    if ($_GET['view'] == null) {
        $_GET['view'] = "profile";
    }
    if ($_GET['view'] != "profile" && $_GET['view'] != "avatar" &&
        $_GET['view'] != "website" && $_GET['view'] != "homepage") {
        $_GET['view'] = "profile";
    }
    if ($_GET['view'] == "avatar") {
        @session_write_close();
        @header("Location: ".$ViewMem['Avatar']);
    }
    if ($_GET['view'] == "website" || $_GET['view'] == "homepage") {
        if ($ViewMem['Website'] != "http://" && $ViewMem['Website'] != null) {
            @session_write_close();
            @header("Location: ".$ViewMem['Website']);
        }
        if ($ViewMem['Website'] == "http://" || $ViewMem['Website'] == null ||
        strtolower($ViewMem['Avatar']) == "noavatar") {
            @session_write_close();
            @header("Location: ".$BoardURL."index.php?act=view");
        }
    }
    ?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1" colspan="2"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=view&id=".$_GET['id'], $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>">Viewing Profile</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="Member" class="TableRow2">
<th class="TableRow2" style="width: 50%;">Avatar</th>
<th class="TableRow2" style="width: 50%;">User Info</th>
</tr>
<tr class="TableRow3" id="MemberProfile">
<td class="TableRow3">
<?php  /* Avatar Table Thanks For SeanJ's Help at http://seanj.jcink.com/ */  ?>
 <table class="AvatarTable" style="width: 100%; height: 100px; text-align: center;">
	<tr class="AvatarRow" style="width: 100px; height: 100px;">
		<td class="AvatarRow" style="width: 100%; height: 100%; text-align: center; vertical-align: middle;">
		<img src="<?php echo $ViewMem['Avatar']; ?>" alt="<?php echo $ViewMem['Name']; ?>'s Avatar" title="<?php echo $ViewMem['Name']; ?>'s Avatar" style="border: 0px; width: <?php echo $AvatarSize1W; ?>px; height: <?php echo $AvatarSize1H; ?>px;" />
		</td>
	</tr>
 </table>
<div style="text-align: center;">
Name: <?php echo $ViewMem['Name']; ?><br />
Title: <?php echo $ViewMem['Title']; ?></div>
</td>
<td class="TableRow3">
&nbsp;User Name: <?php echo $ViewMem['Name']; ?><br />
&nbsp;User Title: <?php echo $ViewMem['Title']; ?><br />
&nbsp;User Group: <?php echo $ViewMem['Group']; ?><br />
&nbsp;User Joined: <?php echo $ViewMem['Joined']; ?><br />
&nbsp;Last Active: <?php echo $ViewMem['LastActive']; ?><br />
&nbsp;User Time: <?php echo GMTimeGet("M j Y, g:i a", $ViewMem['TimeZone'], 0, $ViewMem['DST']); ?><br />
&nbsp;User Website: <a href="<?php echo $ViewMem['Website']; ?>" onclick="window.open(this.href);return false;">Website</a><br />
&nbsp;Post Count: <?php echo $ViewMem['PostCount']; ?><br />
&nbsp;Interests: <?php echo $ViewMem['Interests']; ?><br />
</td>
</tr>
<tr class="TableRow4">
<td class="TableRow4" colspan="2">&nbsp;</td>
</tr>
</table></div>
<?php } @mysql_free_result($result);
if ($_GET['act'] == "logout") {
    @session_unset();
    if ($cookieDomain == null) {
        @setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir);
        @setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir);
        @setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir);
        @setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir);
    }
    if ($cookieDomain != null) {
        if ($cookieSecure == true) {
            @setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
            @setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
            @setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
            @setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
        }
        if ($cookieSecure == false) {
            @setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
            @setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
            @setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
            @setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
        }
    }
    unset($_COOKIE[session_name()]);
    $_SESSION = array();
    @session_unset();
    @session_destroy();
    @redirect("location", $basedir.url_maker($exfile['member'], $Settings['file_ext'], "act=login", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'], false));
    ob_clean();
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    @mysql_close();
    die();
}
if ($_GET['act'] == "login") {
    if ($_SESSION['UserID'] != 0 && $_SESSION['UserID'] != null) {
        redirect("location", $basedir.url_maker($exfile['member'], $Settings['file_ext'], "act=logout", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'], false));
        ob_clean();
        @header("Content-Type: text/plain; charset=".$Settings['charset']);
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        @mysql_close();
        die();
    }
    if ($_SESSION['UserID'] == 0 || $_SESSION['UserID'] == null) {
        $membertitle = " ".$ThemeSet['TitleDivider']." Login";
        ?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=login", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>">Log in</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">&nbsp;Inert your login info: </th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<form style="display: inline;" method="post" action="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=login_now", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="username">Enter UserName: </label></td>
	<td style="width: 70%;"><input maxlength="24" class="TextBox" id="username" type="text" name="username" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="userpass">Enter Password: </label></td>
	<td style="width: 70%;"><input maxlength="30" class="TextBox" id="userpass" type="password" name="userpass" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" title="Store userinfo as a cookie so you dont need to login again." for="storecookie">Store as cookie?</label></td>
	<td style="width: 70%;"><select id="storecookie" name="storecookie" class="TextBox">
<option value="true">Yes</option>
<option value="false">No</option>
</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="loginmember" style="display: none;" />
<input class="Button" type="submit" value="Log in" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table></div>
<?php }
    } if ($_POST['act'] == "loginmember" && $_GET['act'] == "login_now") {
        if ($_SESSION['UserID'] != 0 && $_SESSION['UserID'] != null) {
            redirect("location", $basedir.url_maker($exfile['member'], $Settings['file_ext'], "act=logout", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'], false));
            ob_clean();
            @header("Content-Type: text/plain; charset=".$Settings['charset']);
            gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
            @mysql_close();
            die();
        }
        if ($_SESSION['UserID'] == 0 || $_SESSION['UserID'] == null) {
            $membertitle = " ".$ThemeSet['TitleDivider']." Login";
            $REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
            $URL['REFERER'] = $REFERERurl['host'];
            $URL['HOST'] = $_SERVER["SERVER_NAME"];
            $REFERERurl = null;
            ?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1">
<span style="float: left;">&nbsp;<a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=login", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>">Log in</a></span>
<span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">&nbsp;Login Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<table style="width: 100%; height: 25%; text-align: center;">
<?php
if (pre_strlen($_POST['userpass']) >= "30") {
    $Error = "Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your password is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($_POST['username']) >= "24") {
    $Error = "Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your user name is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($Settings['TestReferer'] == true) {
    if ($URL['HOST'] != $URL['REFERER']) {
        $Error = "Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&nbsp;</td>
</tr>
<?php }
    } $BanError = null;
            if ($Error == "Yes") {
                @redirect("refresh", $basedir.url_maker($exfile['member'], $Settings['file_ext'], "act=login", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'], false), "4");
            }
            if ($Error != "Yes") {
                $YourName = stripcslashes(htmlspecialchars($_POST['username'], ENT_QUOTES, $Settings['charset']));
                //$YourName = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $YourName);
                $YourName = @remove_spaces($YourName);
                $passtype = "ODFH";
                $querylog = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `Name`='%s' LIMIT 1", array($YourName));
                $resultlog = mysql_query($querylog);
                $numlog = mysql_num_rows($resultlog);
                if ($numlog >= 1) {
                    $i = 0;
                    $YourPassTry = mysql_result($resultlog, $i, "Password");
                    $HashType = mysql_result($resultlog, $i, "HashType");
                    $JoinedPass = mysql_result($resultlog, $i, "Joined");
                    $HashSalt = mysql_result($resultlog, $i, "Salt");
                    $UpdateHash = false;
                    if ($HashType == "ODFH") {
                        $YourPassword = sha1(md5($_POST['userpass']));
                    }
                    if ($HashType == "DF4H") {
                        $YourPassword = b64e_hmac($_POST['userpass'], $JoinedPass, $HashSalt, "sha1");
                    }
                    if ($HashType == "iDBH" || $UpdateHash != true) {
                        $YourPassword = b64e_hmac($_POST['userpass'], $JoinedPass, $HashSalt, "sha1");
                    }
                    if ($YourPassword == $YourPassTry) {
                        $passright = false;
                    }
                    if ($YourPassword == $YourPassTry) {
                        $passright = true;
                        $YourIDM = mysql_result($resultlog, $i, "id");
                        $YourNameM = mysql_result($resultlog, $i, "Name");
                        $YourPassM = mysql_result($resultlog, $i, "Password");
                        $PostCount = mysql_result($resultlog, $i, "PostCount");
                        $YourGroupM = mysql_result($resultlog, $i, "GroupID");
                        $YourBanTime = mysql_result($resultlog, $i, "BanTime");
                        $CGMTime = GMTimeStamp();
                        if ($YourBanTime != 0 && $YourBanTime != null) {
                            if ($YourBanTime >= $CGMTime) {
                                $BanError = "yes";
                            }
                        }
                        $gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($YourGroupM));
                        $gresult = mysql_query($gquery);
                        $YourGroupM = mysql_result($gresult, 0, "Name");
                        @mysql_free_result($gresult);
                        $YourTimeZoneM = mysql_result($resultlog, $i, "TimeZone");
                        $YourDSTM = mysql_result($resultlog, $i, "DST");
                        $JoinedDate = mysql_result($resultlog, $i, "Joined");
                        $UseTheme = mysql_result($resultlog, $i, "UseTheme");
                        $NewHashSalt = salt_hmac();
                        $NewPassword = b64e_hmac($_POST['userpass'], $JoinedPass, $NewHashSalt, "sha1");
                        $NewDay = GMTimeStamp();
                        $NewIP = $_SERVER['REMOTE_ADDR'];
                        if ($BanError != "yes") {
                            $queryup = query("UPDATE `".$Settings['sqltable']."members` SET `Password`='%s',`HashType`='iDBH',`LastActive`=%i,`IP`='%s',`Salt`='%s' WHERE `id`=%i", array($NewPassword,$NewDay,$NewIP,$NewHashSalt,$YourIDM));
                            mysql_query($queryup);
                            @mysql_free_result($resultlog);
                            @mysql_free_result($queryup);
                            //session_regenerate_id();
                            $_SESSION['Theme'] = $UseTheme;
                            $_SESSION['MemberName'] = $YourNameM;
                            $_SESSION['UserID'] = $YourIDM;
                            $_SESSION['UserTimeZone'] = $YourTimeZoneM;
                            $_SESSION['UserGroup'] = $YourGroupM;
                            $_SESSION['UserDST'] = $YourDSTM;
                            $_SESSION['UserPass'] = $NewPassword;
                            $_SESSION['DBName'] = $Settings['sqldb'];
                            if ($_POST['storecookie'] == true) {
                                if ($cookieDomain == null) {
                                    @setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir);
                                    @setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir);
                                    @setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir);
                                }
                                if ($cookieDomain != null) {
                                    if ($cookieSecure == true) {
                                        @setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
                                        @setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
                                        @setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
                                    }
                                    if ($cookieSecure == false) {
                                        @setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir, $cookieDomain);
                                        @setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir, $cookieDomain);
                                        @setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir, $cookieDomain);
                                    }
                                }
                            }
                        }
                    }
                } if ($numlog <= 0) {
                    //echo "Password was not right or user not found!! <_< ";
                } ?>
<?php if ($passright == true && $BanError != "yes") {
                    @redirect("refresh", $basedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false), "3"); ?>
<tr>
	<td><span class="TableMessage">
	<br />Welcome to the Board <?php echo $_SESSION['MemberName']; ?>. ^_^<br />
	Click <a href="<?php echo url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']); ?>">here</a> to continue to board.<br />&nbsp;
	</span><br /></td>
</tr>
<?php } if ($passright == false || $BanError == "yes") { ?>
<tr>
	<td><span class="TableMessage">
	<br />Password was not right or user not found or user is banned!! &lt;_&lt;<br />
	Click <a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=login", $Settings['qstr'], $Settings['qsep'], $exqstr['member'], $prexqstr['member']); ?>">here</a> to try again.<br />&nbsp;
	</span><br /></td>
</tr>
<?php }
} ?>
</table>
</td></tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table></div>
<?php }
        } if ($_GET['act'] == "signup") {
            $membertitle = " ".$ThemeSet['TitleDivider']." Signing up";
            if ($_SESSION['UserID'] != 0 && $_SESSION['UserID'] != null) {
                redirect("location", $basedir.url_maker($exfile['member'], $Settings['file_ext'], "act=logout", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'], false));
                ob_clean();
                @header("Content-Type: text/plain; charset=".$Settings['charset']);
                gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
                @mysql_close();
                die();
            }
            if ($_SESSION['UserID'] == 0 || $_SESSION['UserID'] == null) {
                ?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=signup", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>">Register</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">&nbsp;Inert your user info: </th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<form style="display: inline;" method="post" action="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=makemember", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 30%;"><label class="TextBoxLabel" for="Name">Insert a UserName:</label></td>
	<td style="width: 70%;"><input maxlength="24" type="text" class="TextBox" name="Name" size="20" id="Name" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="Password">Insert a Password:</label></td>
	<td style="width: 70%;"><input maxlength="30" type="password" class="TextBox" name="Password" size="20" id="Password" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="RePassword">ReInsert a Password:</label></td>
	<td style="width: 70%;"><input maxlength="30" type="password" class="TextBox" name="RePassword" size="20" id="RePassword" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="Email">Insert Your Email:</label></td>
	<td style="width: 70%;"><input type="text" class="TextBox" name="Email" size="20" id="Email" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="YourOffSet">Your TimeZone:</label></td>
	<td style="width: 70%;"><select id="YourOffSet" name="YourOffSet" class="TextBox"><?php
                $tsa_mem = explode(":", $Settings['DefaultTimeZone']);
                $TimeZoneArray = array("offset" => $Settings['DefaultTimeZone'], "hour" => $tsa_mem[0], "minute" => $tsa_mem[1]);
                $plusi = 1;
                $minusi = 12;
                $plusnum = 13;
                $minusnum = 0;
                while ($minusi > $minusnum) {
                    if ($TimeZoneArray['hour'] == -$minusi) {
                        echo "<option selected=\"selected\" value=\"-".$minusi."\">GMT - ".$minusi.":00 hours</option>\n";
                    }
                    if ($TimeZoneArray['hour'] != -$minusi) {
                        echo "<option value=\"-".$minusi."\">GMT - ".$minusi.":00 hours</option>\n";
                    }
                    --$minusi;
                }
                if ($TimeZoneArray['hour'] == 0) { ?>
<option selected="selected" value="0">GMT +/- 0:00 hours</option>
<?php } if ($TimeZoneArray['hour'] != 0) { ?>
<option value="0">GMT +/- 0:00 hours</option>
<?php }
while ($plusi < $plusnum) {
    if ($TimeZoneArray['hour'] == $plusi) {
        echo "<option selected=\"selected\" value=\"".$plusi."\">GMT + ".$plusi.":00 hours</option>\n";
    }
    if ($TimeZoneArray['hour'] != $plusi) {
        echo "<option value=\"".$plusi."\">GMT + ".$plusi.":00 hours</option>\n";
    }
    ++$plusi;
}
                ?></select></td>
</tr><tr>
	<td style="width: 50%;"><label class="TextBoxLabel" for="MinOffSet">Minute OffSet:</label></td>
	<td style="width: 50%;"><select id="MinOffSet" name="MinOffSet" class="TextBox"><?php
                $mini = 0;
                $minnum = 60;
                while ($mini < $minnum) {
                    if (pre_strlen($mini) == 2) {
                        $showmin = $mini;
                    }
                    if (pre_strlen($mini) == 1) {
                        $showmin = "0".$mini;
                    }
                    if ($mini == $TimeZoneArray['minute']) {
                        echo "\n<option selected=\"selected\" value=\"".$showmin."\">0:".$showmin." minutes</option>\n";
                    }
                    if ($mini != $TimeZoneArray['minute']) {
                        echo "<option value=\"".$showmin."\">0:".$showmin." minutes</option>\n";
                    }
                    ++$mini;
                }
                ?></select></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="DST">Is <span title="Daylight Savings Time">DST</span> / <span title="Summer Time">ST</span> on or off:</label></td>
	<td style="width: 70%;"><select id="DST" name="DST" class="TextBox"><?php echo "\n" ?>
<?php if ($Settings['DefaultDST'] == "off" || $Settings['DefaultDST'] != "on") { ?>
<option selected="selected" value="off">off</option><?php echo "\n" ?><option value="on">on</option>
<?php } if ($Settings['DefaultDST'] == "on") { ?>
<option selected="selected" value="on">on</option><?php echo "\n" ?><option value="off">off</option>
<?php } echo "\n" ?></select></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="YourGender">Your Gender:</label></td>
	<td style="width: 70%;"><select id="YourGender" name="YourGender" class="TextBox">
<option value="Male">Male</option>
<option value="Female">Female</option>
<option value="Unknow">Unknow</option>
</select></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="Website">Insert your Website:</label></td>
	<td style="width: 70%;"><input type="text" class="TextBox" name="Website" size="20" value="http://" id="Website" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" for="Avatar">Insert a URL for Avatar:</label></td>
	<td style="width: 70%;"><input type="text" class="TextBox" name="Avatar" size="20" value="http://" id="Avatar" /></td>
</tr><tr>
	<td style="width: 30%;"><label class="TextBoxLabel" title="Store userinfo as a cookie so you dont need to login again." for="storecookie">Store as cookie?</label></td>
	<td style="width: 70%;"><select id="storecookie" name="storecookie" class="TextBox">
<option value="true">Yes</option>
<option value="false">No</option>
</select></td>
</tr>
</table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<label class="TextBoxLabel" for="TOSBox">TOS - Please read fully and check 'I agree' box ONLY if you agree to terms</label><br />
<textarea rows="10" cols="58" id="TOSBox" name="TOSBox" class="TextBox" readonly="readonly" accesskey="T"><?php
                    echo file_get_contents("TOS");	?></textarea><br />
<input type="checkbox" class="TextBox" name="TOS" value="Agree" id="TOS" /><label class="TextBoxLabel" for="TOS">I Agree</label><br/>
<input type="hidden" style="display: none;" name="act" value="makemembers" />
<input type="submit" class="Button" value="Sign UP" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table></div>
<?php }
        } if ($_GET['act'] == "makemember") {
            if ($_POST['act'] == "makemembers") {
                if ($_SESSION['UserID'] != 0 && $_SESSION['UserID'] != null) {
                    redirect("location", $basedir.url_maker($exfile['member'], $Settings['file_ext'], "act=logout", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'], false));
                    ob_clean();
                    @header("Content-Type: text/plain; charset=".$Settings['charset']);
                    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
                    @mysql_close();
                    die();
                }
                if ($_SESSION['UserID'] == 0 || $_SESSION['UserID'] == null) {
                    $membertitle = " ".$ThemeSet['TitleDivider']." Signing up";
                    $REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
                    $URL['REFERER'] = $REFERERurl['host'];
                    $URL['HOST'] = $_SERVER["SERVER_NAME"];
                    $REFERERurl = null;
                    if (!isset($_POST['username'])) {
                        $_POST['username'] = null;
                    }
                    if (!isset($_POST['TOS'])) {
                        $_POST['TOS'] = null;
                    }
                    ?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: right;">&nbsp;</span>
&nbsp;<a href="<?php echo url_maker($exfile['messenger'], $Settings['file_ext'], "act=signup", $Settings['qstr'], $Settings['qsep'], $prexqstr['messenger'], $exqstr['messenger']); ?>">Register</a></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">&nbsp;Signup Message: </th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<table style="width: 100%; height: 25%; text-align: center;">
<?php if (pre_strlen($_POST['Password']) >= "30") {
    $Error = "Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your password is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if (pre_strlen($_POST['username']) >= "24") {
    $Error = "Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your user name is too big.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['Password'] != $_POST['RePassword']) {
    $Error = "Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Your passwords did not match.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($Settings['TestReferer'] == true) {
    if ($URL['HOST'] != $URL['REFERER']) {
        $Error = "Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />Sorry the referering url dose not match our host name.<br />
	</span>&nbsp;</td>
</tr>
<?php }
    }
                    $Name = stripcslashes(htmlspecialchars($_POST['Name'], ENT_QUOTES, $Settings['charset']));
                    //$Name = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $Name);
                    $Name = @remove_spaces($Name);
                    $lonewolfqy = query("SELECT * FROM `".$Settings['sqltable']."restrictedwords` WHERE `RestrictedUserName`='yes'", array(null));
                    $lonewolfrt = mysql_query($lonewolfqy);
                    $lonewolfnm = mysql_num_rows($lonewolfrt);
                    $lonewolfs = 0;
                    $RMatches = null;
                    while ($lonewolfs < $lonewolfnm) {
                        $RWord = mysql_result($lonewolfrt, $lonewolfs, "Word");
                        $RCaseInsensitive = mysql_result($lonewolfrt, $lonewolfs, "CaseInsensitive");
                        if ($RCaseInsensitive == "on") {
                            $RCaseInsensitive = "yes";
                        }
                        if ($RCaseInsensitive == "off") {
                            $RCaseInsensitive = "no";
                        }
                        if ($RCaseInsensitive != "yes" || $RCaseInsensitive != "no") {
                            $RCaseInsensitive = "no";
                        }
                        $RWholeWord = mysql_result($lonewolfrt, $lonewolfs, "WholeWord");
                        if ($RWholeWord == "on") {
                            $RWholeWord = "yes";
                        }
                        if ($RWholeWord == "off") {
                            $RWholeWord = "no";
                        }
                        if ($RWholeWord != "yes" || $RWholeWord != "no") {
                            $RWholeWord = "no";
                        }
                        $RWord = preg_quote($RWord, "/");
                        if ($RCaseInsensitive != "yes" && $RWholeWord == "yes") {
                            $RMatches = preg_match("/\b(".$RWord.")\b/", $Name);
                            if ($RMatches == true) {
                                break 1;
                            }
                        }
                        if ($RCaseInsensitive == "yes" && $RWholeWord == "yes") {
                            $RMatches = preg_match("/\b(".$RWord.")\b/i", $Name);
                            if ($RMatches == true) {
                                break 1;
                            }
                        }
                        if ($RCaseInsensitive != "yes" && $RWholeWord != "yes") {
                            $RMatches = preg_match("/".$RWord."/", $Name);
                            if ($RMatches == true) {
                                break 1;
                            }
                        }
                        if ($RCaseInsensitive == "yes" && $RWholeWord != "yes") {
                            $RMatches = preg_match("/".$RWord."/i", $Name);
                            if ($RMatches == true) {
                                break 1;
                            }
                        }
                        ++$lonewolfs;
                    } @mysql_free_result($lonewolfrt);
                    $sql_email_check = mysql_query(query("SELECT `Email` FROM `".$Settings['sqltable']."members` WHERE `Email`='%s'", array($_POST['Email'])));
                    $sql_username_check = mysql_query(query("SELECT `Name` FROM `".$Settings['sqltable']."members` WHERE `Name`='%s'", array($Name)));
                    $email_check = mysql_num_rows($sql_email_check);
                    $username_check = mysql_num_rows($sql_username_check);
                    @mysql_free_result($sql_email_check);
                    @mysql_free_result($sql_username_check);
                    if ($_POST['TOS'] != "Agree") {
                        $Error = "Yes";  ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to  agree to the tos.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['Name'] == null) {
    $Error = "Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a name.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['Name'] == "ShowMe") {
    $Error = "Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a name.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['Password'] == null) {
    $Error = "Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a password.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($_POST['Email'] == null) {
    $Error = "Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />You need to enter a email.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($email_check > 0) {
    $Error = "Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />Email address is already used.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($username_check > 0) {
    $Error = "Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />User Name is already used.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($RMatches == true) {
    $Error = "Yes"; ?>
<tr>
	<td><span class="TableMessage">
	<br />This User Name is restricted to use.<br />
	</span>&nbsp;</td>
</tr>
<?php } if ($Error == "Yes") {
    @redirect("refresh", $basedir.url_maker($exfile['member'], $Settings['file_ext'], "act=signup", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'], false), "4"); ?>
<tr>
	<td><span class="TableMessage">
	<br />Click <a href="<?php echo url_maker($exfile['member'], $Settings['file_ext'], "act=signup", $Settings['qstr'], $Settings['qsep'], $exqstr['member'], $prexqstr['member']); ?>">here</a> to try again.<br />&nbsp;
	</span><br /></td>
</tr>
<?php } if ($Error != "Yes") {
    $_POST['UserIP'] = $_SERVER['REMOTE_ADDR'];
    $_POST['Group'] = $Settings['MemberGroup'];
    $_POST['Joined'] = GMTimeStamp();
    $_POST['LastActive'] = GMTimeStamp();
    $_POST['Signature'] = "";
    $_POST['Interests'] = "";
    $_POST['Title'] = "";
    $_POST['PostCount'] = "0";
    if ($Settings['AdminValidate'] == true || $Settings['AdminValidate'] != false) {
        $ValidateStats = "no";
        $yourgroup = $Settings['ValidateGroup'];
    }
    if ($Settings['AdminValidate'] == false) {
        $ValidateStats = "yes";
        $yourgroup = $Settings['MemberGroup'];
    }
    $HashSalt = salt_hmac();
    $NewPassword = b64e_hmac($_POST['Password'], $_POST['Joined'], $HashSalt, "sha1");
    $_GET['YourPost'] = $_POST['Signature'];
    //require( './'.$SettDir['misc'].'HTMLTags.php');
    $_GET['YourPost'] = htmlspecialchars($_GET['YourPost'], ENT_QUOTES, $Settings['charset']);
    //$_GET['YourPost'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_GET['YourPost']);
    $NewSignature = $_GET['YourPost'];
    $_GET['YourPost'] = preg_replace("/\t+/", " ", $_GET['YourPost']);
    $_GET['YourPost'] = preg_replace("/\s\s+/", " ", $_GET['YourPost']);
    $_GET['YourPost'] = remove_bad_entities($_GET['YourPost']);
    $Avatar = stripcslashes(htmlspecialchars($_POST['Avatar'], ENT_QUOTES, $Settings['charset']));
    //$Avatar = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $Avatar);
    $Avatar = @remove_spaces($Avatar);
    $Website = stripcslashes(htmlspecialchars($_POST['Website'], ENT_QUOTES, $Settings['charset']));
    //$Website = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $Website);
    $Website = @remove_spaces($Website);
    $gquerys = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `Name`='%s' LIMIT 1", array($yourgroup));
    $gresults = mysql_query($gquerys);
    $yourgroup = mysql_result($gresults, 0, "id");
    @mysql_free_result($gresults);
    $yourid = getnextid($Settings['sqltable'], "members");
    $_POST['Interests'] = @remove_spaces($_POST['Interests']);
    $_POST['Title'] = @remove_spaces($_POST['Title']);
    $_POST['Email'] = @remove_spaces($_POST['Email']);
    if (!is_numeric($_POST['YourOffSet'])) {
        $_POST['YourOffSet'] = "0";
    }
    if ($_POST['YourOffSet'] > 12) {
        $_POST['YourOffSet'] = "12";
    }
    if ($_POST['YourOffSet'] < -12) {
        $_POST['YourOffSet'] = "-12";
    }
    if (!is_numeric($_POST['MinOffSet'])) {
        $_POST['MinOffSet'] = "00";
    }
    if ($_POST['MinOffSet'] > 59) {
        $_POST['MinOffSet'] = "59";
    }
    if ($_POST['MinOffSet'] < 0) {
        $_POST['MinOffSet'] = "00";
    }
    $_POST['YourOffSet'] = $_POST['YourOffSet'].":".$_POST['MinOffSet'];
    $query = query("INSERT INTO `".$Settings['sqltable']."members` VALUES (".$yourid.",'%s','%s','%s','%s','%s','%s',%i,'%s','%s',%i,%i,'0','0','0','0','%s','%s','%s','%s','%s','%s',%i,'%s','%s','%s','%s','%s')", array($Name,$NewPassword,"iDBH",$_POST['Email'],$yourgroup,$ValidateStats,"0",$_POST['Interests'],$_POST['Title'],$_POST['Joined'],$_POST['LastActive'],$NewSignature,'Your Notes',$Avatar,"100x100",$Website,$_POST['YourGender'],$_POST['PostCount'],$_POST['YourOffSet'],$_POST['DST'],$Settings['DefaultTheme'],$_POST['UserIP'],$HashSalt));
    mysql_query($query);
    $querylogr = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `Name`='%s' AND `Password`='%s' LIMIT 1", array($Name,$NewPassword));
    $resultlogr = mysql_query($querylogr);
    $numlogr = mysql_num_rows($resultlogr);
    if ($numlogr >= 1) {
        $ir = 0;
        $YourIDMr = mysql_result($resultlogr, $ir, "id");
        $YourNameMr = mysql_result($resultlogr, $ir, "Name");
        $YourGroupMr = mysql_result($resultlogr, $ir, "GroupID");
        $gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($YourGroupMr));
        $gresult = mysql_query($gquery);
        $YourGroupMr = mysql_result($gresult, 0, "Name");
        @mysql_free_result($gresult);
        $YourTimeZoneMr = mysql_result($resultlogr, $ir, "TimeZone");
        $YourDSTMr = mysql_result($resultlogr, $ir, "DST");
    }
    @mysql_free_result($resultlogr);
    @session_regenerate_id(true);
    $_SESSION['Loggedin'] = true;
    $_SESSION['MemberName'] = $YourNameMr;
    $_SESSION['UserID'] = $YourIDMr;
    $_SESSION['UserTimeZone'] = $YourTimeZoneMr;
    $_SESSION['UserDST'] = $YourDSTMr;
    $_SESSION['UserGroup'] = $YourGroupMr;
    $_SESSION['UserPass'] = $NewPassword;
    $_SESSION['DBName'] = $Settings['sqldb'];
    if ($_POST['storecookie'] == true) {
        if ($cookieDomain == null) {
            @setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir);
            @setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir);
            @setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir);
        }
        if ($cookieDomain != null) {
            if ($cookieSecure == true) {
                @setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
                @setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
                @setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir, $cookieDomain, 1);
            }
            if ($cookieSecure == false) {
                @setcookie("MemberName", $YourNameM, time() + (7 * 86400), $cbasedir, $cookieDomain);
                @setcookie("UserID", $YourIDM, time() + (7 * 86400), $cbasedir, $cookieDomain);
                @setcookie("SessPass", $NewPassword, time() + (7 * 86400), $cbasedir, $cookieDomain);
            }
        }
    }
    /*
    $SendPMtoID=$_SESSION['UserID'];
    $YourPMID = 1;
    $PMTitle = "Welcome ".$Name.".";
    $YourMessage = "Hello ".$Name.". Welcome to ".$Settings['board_name'].". I hope you enjoy your stay here. ^_^ ";
    $_POST['YourDate'] = $_POST['Joined'];
    $query = query("INSERT INTO `".$Settings['sqltable']."messenger` VALUES (null,%i,%i,'%s','%s','%s','%s',0)", array($YourPMID,$SendPMtoID,'',$PMTitle,$YourMessage,$_POST['YourDate']));
    //mysql_query($query);
    @redirect("refresh",$basedir.url_maker($exfile['index'],$Settings['file_ext'],"act=view",$Settings['qstr'],$Settings['qsep'],$prexqstr['index'],$exqstr['index'],FALSE),"3");
    */
    ?>
<tr>
	<td><span class="TableMessage">
	<br />Welcome to the Board <?php echo $_SESSION['MemberName']; ?>. ^_^<br />
	Click <a href="<?php echo url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']); ?>">here</a> to continue to board.<?php echo "\n";
    if ($Settings['AdminValidate'] == true || $Settings['AdminValidate'] != false) {
        echo "<br />The admin has to validate your account befoure you can post.\n";
        echo "<br />The admin has been notified of your registration.\n";
    } ?>
	<br />&nbsp;
	</span><br /></td>
</tr>
<?php } ?>
</table>
</td></tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table></div>
<?php }
                }
        } ?>
<div>&nbsp;</div>