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

    $FileInfo: forums.php - Last Update: 04/12/2008 SVN 160 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "forums.php" || $File3Name == "/forums.php") {
    require('index.php');
    exit();
}

// Check if we can goto admin cp
if ($_SESSION['UserGroup'] == $Settings['GuestGroup'] || $GroupInfo['HasAdminCP'] == "no") {
    redirect("location", $basedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    ob_clean();
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    @mysql_close();
    die();
}
if (!isset($_POST['update'])) {
    $_POST['update'] = null;
}
?>
<table class="Table3">
<tr style="width: 100%; vertical-align: top;">
	<td style="width: 15%; vertical-align: top;">
<?php
require($SettDir['admin'].'table.php');
?>
</td>
	<td style="width: 85%; vertical-align: top;">
<?php if ($_GET['act'] == "addforum" && $_POST['update'] != "now") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Adding new Forum";
    ?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
&nbsp;<a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addforum", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Forum Manager</a></span>
<span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Adding new Forum: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<form style="display: inline;" method="post" name="install" id="install" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addforum", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumID">Insert ID for forum:</label></td>
	<td style="width: 50%;"><input type="text" name="ForumID" class="TextBox" id="ForumID" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="OrderID">Insert order id forum:</label></td>
	<td style="width: 50%;"><input type="text" name="OrderID" class="TextBox" id="OrderID" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumCatID">Select category for forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ForumCatID" id="ForumCatID">
<?php
    $cq = query("SELECT * FROM `".$Settings['sqltable']."categories` ORDER BY `OrderID` ASC, `id` ASC", array(null));
    $cr = mysql_query($cq);
    $eu = mysql_num_rows($cr);
    $nu = 0;
    while ($nu < $eu) {
        $InCatID = mysql_result($cr, $nu, "id");
        $InCatName = mysql_result($cr, $nu, "Name");
        $EuNuMai = "Eu nu mai vreau";
        ?>
	<option value="<?php echo $InCatID; ?>"><?php echo $InCatName; ?></option>
<?php ++$nu;
    }
    @mysql_free_result($cr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumName">Insert name for forum:</label></td>
	<td style="width: 50%;"><input type="text" name="ForumName" class="TextBox" id="ForumName" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumDesc">Insert description for forum:</label></td>
	<td style="width: 50%;"><input type="text" name="ForumDesc" class="TextBox" id="ForumDesc" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ShowForum">Show forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ShowForum" id="ShowForum">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumType">Insert forum type:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ForumType" id="ForumType">
	<option selected="selected" value="forum">Forum</option>
	<option value="subforum">SubForum</option>
	<option value="redirect">Redirect</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="RedirectURL">Insert Redirect URL for redirect forum:</label></td>
	<td style="width: 50%;"><input type="text" name="RedirectURL" class="TextBox" id="RedirectURL" size="20" value="http://" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="InSubForum">In SubForum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="InSubForum" id="InSubForum">
	<option selected="selected" value="0">none</option>
<?php
    $fq = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `InSubForum`=0 ORDER BY `OrderID` ASC, `id` ASC", array(null));
    $fr = mysql_query($fq);
    $ai = mysql_num_rows($fr);
    $fi = 0;
    while ($fi < $ai) {
        $InForumID = mysql_result($fr, $fi, "id");
        $InForumName = mysql_result($fr, $fi, "Name");
        $InForumType = mysql_result($fr, $fi, "ForumType");
        $AiFiInSubForum = mysql_result($fr, $fi, "InSubForum");
        if ($InForumType != "redirect" && $AiFiInSubForum == "0") {
            ?>
	<option value="<?php echo $InForumID; ?>"><?php echo $InForumName; ?></option>
<?php } ++$fi;
    }
    @mysql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PostCountAdd">Add to post count:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="PostCountAdd" id="PostCountAdd">
	<option selected="selected" value="on">yes</option>
	<option value="off">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanHaveTopics">Allow topics in forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanHaveTopics" id="CanHaveTopics">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="addforum" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Add Forum" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if ($_POST['act'] == "addforum" && $_POST['update'] == "now" && $_GET['act'] == "addforum" &&
        $_SESSION['UserGroup'] != $Settings['GuestGroup'] && $GroupInfo['HasAdminCP'] == "yes") {
    $_POST['ForumName'] = stripcslashes(htmlspecialchars($_POST['ForumName'], ENT_QUOTES, $Settings['charset']));
    //$_POST['ForumName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['ForumName']);
    $_POST['ForumName'] = @remove_spaces($_POST['ForumName']);
    $_POST['ForumDesc'] = stripcslashes(htmlspecialchars($_POST['ForumDesc'], ENT_QUOTES, $Settings['charset']));
    //$_POST['ForumDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['ForumDesc']);
    $_POST['ForumDesc'] = @remove_spaces($_POST['ForumDesc']);
    $sql_id_check = mysql_query(query("SELECT `id` FROM `".$Settings['sqltable']."forums` WHERE `id`=%i LIMIT 1", array($_POST['ForumID'])));
    $sql_order_check = mysql_query(query("SELECT `OrderID` FROM `".$Settings['sqltable']."forums` WHERE `OrderID`=%i LIMIT 1", array($_POST['OrderID'])));
    $id_check = mysql_num_rows($sql_id_check);
    $order_check = mysql_num_rows($sql_order_check);
    @mysql_free_result($sql_id_check);
    @mysql_free_result($sql_order_check);
    $errorstr = "";
    if ($_POST['ForumName'] == null ||
        $_POST['ForumName'] == "ShowMe") {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a forum name.<br />\n";
    }
    if ($_POST['ForumDesc'] == null) {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a description.<br />\n";
    }
    if ($_POST['ForumID'] == null ||
        !is_numeric($_POST['ForumID'])) {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a forum ID.<br />\n";
    }
    if ($id_check > 0) {
        $Error = "Yes";
        $errorstr = $errorstr."This ID number is already used.<br />\n";
    }
    if ($order_check > 0) {
        $Error = "Yes";
        $errorstr = $errorstr."This order number is already used.<br />\n";
    }
    if (pre_strlen($_POST['ForumName']) >= "30") {
        $Error = "Yes";
        $errorstr = $errorstr."Your Forum Name is too big.<br />\n";
    }
    if (pre_strlen($_POST['ForumDesc']) >= "45") {
        $Error = "Yes";
        $errorstr = $errorstr."Your Forum Description is too big.<br />\n";
    }
    if ($Error != "Yes") {
        @redirect("refresh", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
        $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
        $query = query("INSERT INTO `".$Settings['sqltable']."forums` VALUES (%i,%i,%i,'%s','%s','%s',%i,'%s',0,0,'%s','%s','%s',0,0)", array($_POST['ForumID'],$_POST['ForumCatID'],$_POST['OrderID'],$_POST['ForumName'],$_POST['ShowForum'],$_POST['ForumType'],$_POST['InSubForum'],$_POST['RedirectURL'],$_POST['ForumDesc'],$_POST['PostCountAdd'],$_POST['CanHaveTopics']));
        mysql_query($query);
        ?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">Updating Settings</th>
</tr>
<tr class="TableRow3" id="ProfileUpdate">
<td class="TableRow3">
<div style="text-align: center;">
	<br />The forum was created successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr></table></div>
<?php }
    } if ($_GET['act'] == "deleteforum" && $_POST['update'] != "now") {
        $admincptitle = " ".$ThemeSet['TitleDivider']." Deleting a Forum";
        ?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
&nbsp;<a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addforum", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Forum Manager</a></span>
<span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Deleting a Forum: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<form style="display: inline;" method="post" name="install" id="install" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=deleteforum", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelForums">Delete all forums in subforum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelForums" id="DelForums">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelTopics">Delete all topics in (sub)forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelTopics" id="DelTopics">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelPermission">Delete all permission sets in (sub)forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelPermission" id="DelPermission">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelID">Delete Forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelID" id="DelID">
<?php
        $fq = query("SELECT * FROM `".$Settings['sqltable']."forums` ORDER BY `OrderID` ASC, `id` ASC", array(null));
        $fr = mysql_query($fq);
        $ai = mysql_num_rows($fr);
        $fi = 0;
        while ($fi < $ai) {
            $InForumID = mysql_result($fr, $fi, "id");
            $InForumName = mysql_result($fr, $fi, "Name");
            $InForumType = mysql_result($fr, $fi, "ForumType");
            $AiFiInSubForum = mysql_result($fr, $fi, "InSubForum");
            ?>
	<option value="<?php echo $InForumID; ?>"><?php echo $InForumName; ?></option>
<?php ++$fi;
        }
        @mysql_free_result($fr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="deleteforum" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Delete Forum" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if ($_GET['act'] == "deleteforum" && $_POST['update'] == "now" && $_GET['act'] == "deleteforum" &&
            $_SESSION['UserGroup'] != $Settings['GuestGroup'] && $GroupInfo['HasAdminCP'] == "yes") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
    $prequery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i LIMIT 1", array($_POST['DelID']));
    $preresult = mysql_query($prequery);
    $prenum = mysql_num_rows($preresult);
    $errorstr = "";
    $Error = null;
    if (!is_numeric($_POST['DelID'])) {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a forum ID.<br />\n";
    }
    if ($prenum > 0 && $Error != "Yes") {
        $dtquery = query("DELETE FROM `".$Settings['sqltable']."forums` WHERE `id`=%i", array($_POST['DelID']));
        mysql_query($dtquery);
        if ($_POST['DelForums'] == "yes") {
            $dtquery = query("DELETE FROM `".$Settings['sqltable']."topics` WHERE `ForumID`=%i", array($_POST['DelID']));
            mysql_query($dtquery);
            $dtquery = query("DELETE FROM `".$Settings['sqltable']."posts` WHERE `ForumID`=%i", array($_POST['DelID']));
            mysql_query($dtquery);
        }
        if ($_POST['DelPermission'] == "yes") {
            $dtquery = query("DELETE FROM `".$Settings['sqltable']."permissions` WHERE `ForumID`=%i", array($_POST['DelID']));
            mysql_query($dtquery);
        }
        if ($_POST['DelForums'] == "yes") {
            $apcquery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `InSubForum`=%i ORDER BY `OrderID` ASC, `id` ASC", array($_POST['DelID']));
            $apcresult = mysql_query($apcquery);
            $apcnum = mysql_num_rows($apcresult);
            $apci = 0;
            $apcl = 1;
            if ($apcnum >= 1) {
                while ($apci < $apcnum) {
                    $DelSubsForumID = mysql_result($apcresult, $apci, "id");
                    if ($_POST['DelForums'] == "yes") {
                        $dtquery = query("DELETE FROM `".$Settings['sqltable']."topics` WHERE `ForumID`=%i", array($DelSubsForumID));
                        mysql_query($dtquery);
                        $dtquery = query("DELETE FROM `".$Settings['sqltable']."posts` WHERE `ForumID`=%i", array($DelSubsForumID));
                        mysql_query($dtquery);
                    }
                    if ($_POST['DelPermission'] == "yes") {
                        $dtquery = query("DELETE FROM `".$Settings['sqltable']."permissions` WHERE `ForumID`=%i", array($DelSubsForumID));
                        mysql_query($dtquery);
                    }
                    $dtquery = query("DELETE FROM `".$Settings['sqltable']."forums` WHERE `id`=%i", array($DelSubsForumID));
                    mysql_query($dtquery);
                    ++$apci;
                }
                @mysql_free_result($apcresult);
            }
        }
        ?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">Updating Settings</th>
</tr>
<tr class="TableRow3" id="ProfileUpdate">
<td class="TableRow3">
<div style="text-align: center;">
	<br />The forum was deleted successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr></table></div>
<?php }
    } if ($_GET['act'] == "editforum" && $_POST['update'] != "now") {
        $admincptitle = " ".$ThemeSet['TitleDivider']." Editing a Forum";
        if (!isset($_POST['id'])) {
            ?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
&nbsp;<a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editforum", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Forum Manager</a></span>
<span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing a Forum: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<form style="display: inline;" method="post" name="install" id="install" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editforum", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="id">Forum to Edit:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="id" id="id">
<?php
            $fq = query("SELECT * FROM `".$Settings['sqltable']."forums` ORDER BY `OrderID` ASC, `id` ASC", array(null));
            $fr = mysql_query($fq);
            $ai = mysql_num_rows($fr);
            $fi = 0;
            while ($fi < $ai) {
                $InForumID = mysql_result($fr, $fi, "id");
                $InForumName = mysql_result($fr, $fi, "Name");
                $InForumType = mysql_result($fr, $fi, "ForumType");
                $AiFiInSubForum = mysql_result($fr, $fi, "InSubForum");
                ?>
	<option value="<?php echo $InForumID; ?>"><?php echo $InForumName; ?></option>
<?php ++$fi;
            }
            @mysql_free_result($fr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editforum" style="display: none;" />
<input type="submit" class="Button" value="Edit Forum" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if (isset($_POST['id'])) {
    $prequery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i LIMIT 1", array($_POST['id']));
    $preresult = mysql_query($prequery);
    $prenum = mysql_num_rows($preresult);
    if ($prenum == 0) {
        redirect("location", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false));
        @mysql_free_result($preresult);
        ob_clean();
        @header("Content-Type: text/plain; charset=".$Settings['charset']);
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        @mysql_close();
        die();
    }
    if ($prenum >= 1) {
        $ForumID = mysql_result($preresult, 0, "id");
        $ForumCatID = mysql_result($preresult, 0, "CategoryID");
        $ForumOrder = mysql_result($preresult, 0, "OrderID");
        $ForumName = mysql_result($preresult, 0, "Name");
        $ShowForum = mysql_result($preresult, 0, "ShowForum");
        $ForumType = mysql_result($preresult, 0, "ForumType");
        $InSubForum = mysql_result($preresult, 0, "InSubForum");
        $RedirectURL = mysql_result($preresult, 0, "RedirectURL");
        $RedirectTimes = mysql_result($preresult, 0, "Redirects");
        $NumberViews = mysql_result($preresult, 0, "NumViews");
        $ForumDescription = mysql_result($preresult, 0, "Description");
        $PostCountAdd = mysql_result($preresult, 0, "PostCountAdd");
        $CanHaveTopics = mysql_result($preresult, 0, "CanHaveTopics");
        $NumberPosts = mysql_result($preresult, 0, "NumPosts");
        $NumberTopics = mysql_result($preresult, 0, "NumTopics");
        @mysql_free_result($preresult);
        $ForumType = strtolower($ForumType);
        $CanHaveTopics = strtolower($CanHaveTopics);
        ?>
<div class="Table1Border">
<table class="Table1">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
&nbsp;<a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editforum", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">iDB Forum Manager</a></span>
<span style="float: right;">&nbsp;</span></td>
</tr>
<tr class="TableRow2">
<th class="TableRow2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing a Forum: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableRow3">
<td class="TableRow3">
<form style="display: inline;" method="post" name="install" id="install" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editforum", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumID">Insert ID for forum:</label></td>
	<td style="width: 50%;"><input type="text" name="ForumID" class="TextBox" id="ForumID" size="20" value="<?php echo $ForumID; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="OrderID">Insert order id forum:</label></td>
	<td style="width: 50%;"><input type="text" name="OrderID" class="TextBox" id="OrderID" size="20" value="<?php echo $ForumOrder; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumCatID">Select category for forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ForumCatID" id="ForumCatID">
<?php
        $cq = query("SELECT * FROM `".$Settings['sqltable']."categories` ORDER BY `OrderID` ASC, `id` ASC", array(null));
        $cr = mysql_query($cq);
        $eu = mysql_num_rows($cr);
        $nu = 0;
        while ($nu < $eu) {
            $InCatID = mysql_result($cr, $nu, "id");
            $InCatName = mysql_result($cr, $nu, "Name");
            $EuNuMai = "Eu nu mai vreau";
            if ($ForumCatID == $InCatID) {
                ?>
	<option value="<?php echo $InCatID; ?>" selected="selected"><?php echo $InCatName; ?></option>
<?php } if ($ForumCatID != $InCatID) { ?>
	<option value="<?php echo $InCatID; ?>"><?php echo $InCatName; ?></option>
<?php } ++$nu;
        }
        @mysql_free_result($cr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumName">Insert name for forum:</label></td>
	<td style="width: 50%;"><input type="text" name="ForumName" class="TextBox" id="ForumName" size="20" value="<?php echo $ForumName; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumDesc">Insert description for forum:</label></td>
	<td style="width: 50%;"><input type="text" name="ForumDesc" class="TextBox" id="ForumDesc" size="20" value="<?php echo $ForumDescription; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ShowForum">Show forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ShowForum" id="ShowForum">
	<option <?php if ($ShowForum == "yes") {
	    echo "selected=\"selected\" ";
	} ?>value="yes">yes</option>
	<option <?php if ($ShowForum == "no") {
	    echo "selected=\"selected\" ";
	} ?>value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ForumType">Insert forum type:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ForumType" id="ForumType">
	<option <?php if ($ForumType == "forum") {
	    echo "selected=\"selected\" ";
	} ?>value="forum">Forum</option>
	<option <?php if ($ForumType == "subforum") {
	    echo "selected=\"selected\" ";
	} ?>value="subforum">SubForum</option>
	<option <?php if ($ForumType == "redirect") {
	    echo "selected=\"selected\" ";
	} ?>value="redirect">Redirect</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="RedirectURL">Insert Redirect URL for redirect forum:</label></td>
	<td style="width: 50%;"><input type="text" name="RedirectURL" class="TextBox" id="RedirectURL" size="20" value="<?php echo $RedirectURL; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="InSubForum">In SubForum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="InSubForum" id="InSubForum">
	<option selected="selected" value="0">none</option>
<?php
$fq = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `InSubForum`=0 ORDER BY `OrderID` ASC, `id` ASC", array(null));
        $fr = mysql_query($fq);
        $ai = mysql_num_rows($fr);
        $fi = 0;
        while ($fi < $ai) {
            $InForumID = mysql_result($fr, $fi, "id");
            $InForumName = mysql_result($fr, $fi, "Name");
            $InForumType = mysql_result($fr, $fi, "ForumType");
            $AiFiInSubForum = mysql_result($fr, $fi, "InSubForum");
            if ($InForumType != "redirect" && $AiFiInSubForum == "0") {
                if ($InSubForum == $InForumID) {
                    ?>
	<option value="<?php echo $InForumID; ?>" selected="selected"><?php echo $InForumName; ?></option>
<?php } if ($InSubForum != $InForumID) { ?>
	<option value="<?php echo $InForumID; ?>"><?php echo $InForumName; ?></option>
<?php }
} ++$fi;
        }
        @mysql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="PostCountAdd">Add to post count:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="PostCountAdd" id="PostCountAdd">
	<option <?php if ($PostCountAdd == "on") {
	    echo "selected=\"selected\" ";
	} ?>value="on">yes</option>
	<option <?php if ($PostCountAdd == "off") {
	    echo "selected=\"selected\" ";
	} ?>value="off">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanHaveTopics">Allow topics in forum:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanHaveTopics" id="CanHaveTopics">
	<option <?php if ($CanHaveTopics == "yes") {
	    echo "selected=\"selected\" ";
	} ?>value="yes">yes</option>
	<option <?php if ($CanHaveTopics == "no") {
	    echo "selected=\"selected\" ";
	} ?>value="no">no</option>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editforum" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $ForumID; ?>" style="display: none;" />
<input type="submit" class="Button" value="Edit Forum" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr>
</table>
</div>
<?php }
}
} if ($_POST['act'] == "editforum" && $_POST['update'] == "now" && $_GET['act'] == "editforum" &&
isset($_POST['id']) && $_SESSION['UserGroup'] != $Settings['GuestGroup'] && $GroupInfo['HasAdminCP'] == "yes") {
    $_POST['ForumName'] = stripcslashes(htmlspecialchars($_POST['ForumName'], ENT_QUOTES, $Settings['charset']));
    //$_POST['ForumName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['ForumName']);
    $_POST['ForumName'] = @remove_spaces($_POST['ForumName']);
    $_POST['ForumDesc'] = stripcslashes(htmlspecialchars($_POST['ForumDesc'], ENT_QUOTES, $Settings['charset']));
    //$_POST['ForumDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['ForumDesc']);
    $_POST['ForumDesc'] = @remove_spaces($_POST['ForumDesc']);
    $prequery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i LIMIT 1", array($_POST['id']));
    $preresult = mysql_query($prequery);
    $prenum = mysql_num_rows($preresult);
    if ($prenum == 0) {
        redirect("location", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false));
        @mysql_free_result($preresult);
        ob_clean();
        @header("Content-Type: text/plain; charset=".$Settings['charset']);
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        @mysql_close();
        die();
    }
    if ($prenum >= 1) {
        $OldID = mysql_result($preresult, 0, "id");
        $OldOrder = mysql_result($preresult, 0, "OrderID");
        @mysql_free_result($preresult);
        $sql_id_check = mysql_query(query("SELECT `id` FROM `".$Settings['sqltable']."forums` WHERE `id`=%i LIMIT 1", array($_POST['ForumID'])));
        $sql_order_check = mysql_query(query("SELECT `OrderID` FROM `".$Settings['sqltable']."forums` WHERE `OrderID`=%i LIMIT 1", array($_POST['OrderID'])));
        $id_check = mysql_num_rows($sql_id_check);
        $order_check = mysql_num_rows($sql_order_check);
        @mysql_free_result($sql_id_check);
        @mysql_free_result($sql_order_check);
        if ($_POST['ForumName'] == null ||
            $_POST['ForumName'] == "ShowMe") {
            $Error = "Yes";
            $errorstr = $errorstr."You need to enter a forum name.<br />\n";
        }
        if ($_POST['ForumDesc'] == null) {
            $Error = "Yes";
            $errorstr = $errorstr."You need to enter a description.<br />\n";
        }
        if ($_POST['ForumID'] == null ||
            !is_numeric($_POST['ForumID'])) {
            $Error = "Yes";
            $errorstr = $errorstr."You need to enter a forum ID.<br />\n";
        }
        if ($id_check > 0 && $_POST['ForumID'] != $OldID) {
            $Error = "Yes";
            $errorstr = $errorstr."This ID number is already used.<br />\n";
        }
        if ($order_check > 0 && $_POST['OrderID'] != $OldOrder) {
            $Error = "Yes";
            $errorstr = $errorstr."This order number is already used.<br />\n";
        }
        if (pre_strlen($_POST['ForumName']) >= "30") {
            $Error = "Yes";
            $errorstr = $errorstr."Your Forum Name is too big.<br />\n";
        }
        if (pre_strlen($_POST['ForumDesc']) >= "45") {
            $Error = "Yes";
            $errorstr = $errorstr."Your Forum Description is too big.<br />\n";
        }
        if ($Error != "Yes") {
            @redirect("refresh", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
            $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
            $query = query("UPDATE `".$Settings['sqltable']."forums` SET `id`=%i,`CategoryID`=%i,`OrderID`=%i,`Name`='%s',`ShowForum`='%s',`ForumType`='%s',`InSubForum`=%i,`RedirectURL`='%s',`Description`='%s',`PostCountAdd`='%s',`CanHaveTopics`='%s' WHERE `id`=%i", array($_POST['ForumID'],$_POST['ForumCatID'],$_POST['OrderID'],$_POST['ForumName'],$_POST['ShowForum'],$_POST['ForumType'],$_POST['InSubForum'],$_POST['RedirectURL'],$_POST['ForumDesc'],$_POST['PostCountAdd'],$_POST['CanHaveTopics'],$_POST['id']));
            mysql_query($query);
            ?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">Updating Settings</th>
</tr>
<tr class="TableRow3" id="ProfileUpdate">
<td class="TableRow3">
<div style="text-align: center;">
	<br />The forum was edited successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr></table></div>
<?php }
        }
} if ($_GET['act'] != null && $Error == "Yes") {
    @redirect("refresh", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
    $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
    ?>
<div class="Table1Border">
<table class="Table1" style="width: 100%;">
<tr class="TableRow1">
<td class="TableRow1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr>
<tr id="ProfileTitle" class="TableRow2">
<th class="TableRow2">Updating Settings</th>
</tr>
<tr class="TableRow3" id="ProfileUpdate">
<td class="TableRow3">
<div style="text-align: center;">
	<br /><?php echo $errorstr; ?>
	Click <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">here</a> to back to admin cp.<br />&nbsp;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableRow4">
<td class="TableRow4">&nbsp;</td>
</tr></table></div>
<?php } ?>
</td></tr>
</table>
<div>&nbsp;</div>