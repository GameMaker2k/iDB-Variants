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

    $FileInfo: subcategories.php - Last Update: 03/25/2008 SVN 155 - Author: cooldude2k $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "subcategories.php" || $File3Name == "/subcategories.php") {
    require('index.php');
    exit();
}
$checkquery = query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `id`=%i LIMIT 1", array($_GET['id']));
$checkresult = mysql_query($checkquery);
$checknum = mysql_num_rows($checkresult);
if ($checknum == 0) {
    redirect("location", $basedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    @mysql_free_result($checkresult);
    ob_clean();
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    @mysql_close();
    die();
}
if ($checknum >= 1) {
    $CategoryID = mysql_result($checkresult, 0, "id");
    $CategoryName = mysql_result($checkresult, 0, "Name");
    $CategoryShow = mysql_result($checkresult, 0, "ShowCategory");
    $CategoryType = mysql_result($checkresult, 0, "CategoryType");
    $SubShowForums = mysql_result($checkresult, 0, "SubShowForums");
    $CategoryType = strtolower($CategoryType);
    $SubShowForums = strtolower($SubShowForums);
    $SCategoryName = $CategoryName;
    if (!isset($CatPermissionInfo['CanViewCategory'][$CategoryID])) {
        $CatPermissionInfo['CanViewCategory'][$CategoryID] = "no";
    }
    if ($CatPermissionInfo['CanViewCategory'][$CategoryID] == "no" ||
        $CatPermissionInfo['CanViewCategory'][$CategoryID] != "yes") {
        redirect("location", $basedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
        ob_clean();
        @header("Content-Type: text/plain; charset=".$Settings['charset']);
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        @mysql_close();
        die();
    }
    if ($CatPermissionInfo['CanViewCategory'][$CategoryID] == "yes") {
        if ($CategoryType == "category") {
            redirect("location", $basedir.url_maker($exfile['category'], $Settings['file_ext'], "act=".$_GET['act']."&id=".$_GET['id'], $Settings['qstr'], $Settings['qsep'], $prexqstr['category'], $exqstr['category'], false));
            ob_clean();
            @header("Content-Type: text/plain; charset=".$Settings['charset']);
            gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
            @mysql_close();
            die();
        }
        @mysql_free_result($checkresult);
        $prequery = query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `ShowCategory`='yes' AND `InSubCategory`=%i ORDER BY `OrderID` ASC, `id` ASC", array($_GET['id']));
        $preresult = mysql_query($prequery);
        $prenum = mysql_num_rows($preresult);
        $prei = 0;
        while ($prei < $prenum) {
            $CategoryID = mysql_result($preresult, $prei, "id");
            $CategoryName = mysql_result($preresult, $prei, "Name");
            $CategoryShow = mysql_result($preresult, $prei, "ShowCategory");
            $CategoryType = mysql_result($preresult, $prei, "CategoryType");
            $SSubShowForums = mysql_result($preresult, $prei, "SubShowForums");
            $CategoryDescription = mysql_result($preresult, $prei, "Description");
            $CategoryType = strtolower($CategoryType);
            $SubShowForums = strtolower($SubShowForums);
            if (isset($CatPermissionInfo['CanViewCategory'][$CategoryID]) &&
                $CatPermissionInfo['CanViewCategory'][$CategoryID] == "yes") {
                $query = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `ShowForum`='yes' AND `CategoryID`=%i AND `InSubForum`=0 ORDER BY `OrderID` ASC, `id` ASC", array($CategoryID));
                $result = mysql_query($query);
                $num = mysql_num_rows($result);
                $i = 0;
                if ($num >= 1) {
                    ?>
<div class="Table1Border">
<table class="Table1" id="SubCat<?php echo $CategoryID; ?>">
<tr id="SubCatStart<?php echo $CategoryID; ?>" class="TableRow1">
<td class="TableRow1" colspan="5"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon'] ?><a href="<?php echo url_maker($exfile[$CategoryType], $Settings['file_ext'], "act=view&id=".$CategoryID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$CategoryType], $exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a></span>
<?php echo "<span style=\"float: right;\">&nbsp;</span>"; ?></td>
</tr>
<tr id="ForumStatRow<?php echo $CategoryID; ?>" class="TableRow2">
<th class="TableRow2" style="width: 4%;">&nbsp;</th>
<th class="TableRow2" style="width: 58%;">Forum</th>
<th class="TableRow2" style="width: 7%;">Topics</th>
<th class="TableRow2" style="width: 7%;">Posts</th>
<th class="TableRow2" style="width: 24%;">Last Topic</th>
</tr>
<?php }
                while ($i < $num) {
                    $ForumID = mysql_result($result, $i, "id");
                    $ForumName = mysql_result($result, $i, "Name");
                    $ForumShow = mysql_result($result, $i, "ShowForum");
                    $ForumType = mysql_result($result, $i, "ForumType");
                    $ForumShowTopics = mysql_result($result, $i, "CanHaveTopics");
                    $ForumShowTopics = strtolower($ForumShowTopics);
                    $NumTopics = mysql_result($result, $i, "NumTopics");
                    $NumPosts = mysql_result($result, $i, "NumPosts");
                    $NumRedirects = mysql_result($result, $i, "Redirects");
                    $ForumDescription = mysql_result($result, $i, "Description");
                    $ForumType = strtolower($ForumType);
                    $gltf = array(null);
                    $gltf[0] = $ForumID;
                    if ($ForumType == "subforum") {
                        $apcquery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `ShowForum`='yes' AND `InSubForum`=%i ORDER BY `OrderID` ASC, `id` ASC", array($ForumID));
                        $apcresult = mysql_query($apcquery);
                        $apcnum = mysql_num_rows($apcresult);
                        $apci = 0;
                        $apcl = 1;
                        if ($apcnum >= 1) {
                            while ($apci < $apcnum) {
                                $NumsTopics = mysql_result($apcresult, $apci, "NumTopics");
                                $NumTopics = $NumsTopics + $NumTopics;
                                $NumsPosts = mysql_result($apcresult, $apci, "NumPosts");
                                $NumPosts = $NumsPosts + $NumPosts;
                                $SubsForumID = mysql_result($apcresult, $apci, "id");
                                if (isset($PermissionInfo['CanViewForum'][$SubsForumID]) &&
                                    $PermissionInfo['CanViewForum'][$SubsForumID] == "yes") {
                                    $gltf[$apcl] = $SubsForumID;
                                    ++$apcl;
                                }
                                ++$apci;
                            }
                            @mysql_free_result($apcresult);
                        }
                    }
                    if (isset($PermissionInfo['CanViewForum'][$ForumID]) &&
                        $PermissionInfo['CanViewForum'][$ForumID] == "yes") {
                        $LastTopic = null;
                        if (!isset($LastTopic)) {
                            $LastTopic = null;
                        }
                        $gltnum = count($gltf);
                        $glti = 0;
                        $OldUpdateTime = 0;
                        $UseThisFonum = null;
                        if ($ForumType == "subforum") {
                            while ($glti < $gltnum) {
                                $gltfoquery = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `CategoryID`=%i AND `ForumID`=%i ORDER BY `LastUpdate` DESC LIMIT 1", array($CategoryID,$gltf[$glti]));
                                $gltforesult = mysql_query($gltfoquery);
                                $gltfonum = mysql_num_rows($gltforesult);
                                if ($gltfonum > 0) {
                                    $NewUpdateTime = mysql_result($gltforesult, 0, "LastUpdate");
                                    if ($NewUpdateTime > $OldUpdateTime) {
                                        $UseThisFonum = $gltf[$glti];
                                        $OldUpdateTime = $NewUpdateTime;
                                    }
                                }
                                @mysql_free_result($gltforesult);
                                ++$glti;
                            }
                        }
                        if ($ForumType != "subforum" && $ForumType != "redirect") {
                            $UseThisFonum = $gltf[0];
                        }
                        if ($ForumType != "redirect") {
                            $gltquery = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `CategoryID`=%i AND `ForumID`=%i ORDER BY `LastUpdate` DESC LIMIT 1", array($CategoryID,$UseThisFonum));
                            $gltresult = mysql_query($gltquery);
                            $gltnum = mysql_num_rows($gltresult);
                            if ($gltnum > 0) {
                                $TopicID = mysql_result($gltresult, 0, "id");
                                $TopicName = mysql_result($gltresult, 0, "TopicName");
                                $NumReplys = mysql_result($gltresult, 0, "NumReply");
                                $ShowReply = $NumReplys + 1;
                                $TopicName1 = pre_substr($TopicName, 0, 20);
                                if (pre_strlen($TopicName) > 20) {
                                    $TopicName1 = $TopicName1."...";
                                    $oldtopicname = $TopicName;
                                    $TopicName = $TopicName1;
                                }
                                $UsersID = mysql_result($gltresult, 0, "UserID");
                                $GuestName = mysql_result($gltresult, 0, "GuestName");
                                $UsersName = GetUserName($UsersID, $Settings['sqltable']);
                                $UsersName1 = pre_substr($UsersName, 0, 20);
                                if ($UsersName == "Guest") {
                                    $UsersName = $GuestName;
                                    if ($UsersName == null) {
                                        $UsersName = "Guest";
                                    }
                                }
                                if (pre_strlen($UsersName) > 20) {
                                    $UsersName1 = $UsersName1."...";
                                    $oldusername = $UsersName;
                                    $UsersName = $UsersName1;
                                } $lul = null;
                                if ($UsersID != "-1") {
                                    $lul = url_maker($exfile['member'], $Settings['file_ext'], "act=view&id=".$UsersID, $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member']);
                                    $LastTopic = "User: <a href=\"".$lul."\" title=\"".$oldusername."\">".$UsersName."</a><br />\nTopic: <a href=\"".url_maker($exfile['topic'], $Settings['file_ext'], "act=view&id=".$TopicID, $Settings['qstr'], $Settings['qsep'], $prexqstr['topic'], $exqstr['topic'])."#reply".$ShowReply."\" title=\"".$oldtopicname."\">".$TopicName."</a>";
                                }
                                if ($UsersID == "-1") {
                                    $LastTopic = "User: <span title=\"".$oldusername."\">".$UsersName."</span><br />\nTopic: <a href=\"".url_maker($exfile['topic'], $Settings['file_ext'], "act=view&id=".$TopicID, $Settings['qstr'], $Settings['qsep'], $prexqstr['topic'], $exqstr['topic'])."#reply".$ShowReply."\" title=\"".$oldtopicname."\">".$TopicName."</a>";
                                }
                            }
                            if ($LastTopic == null) {
                                $LastTopic = "&nbsp;<br />&nbsp;";
                            }
                        }
                        @mysql_free_result($gltresult);
                        if ($ForumType == "redirect") {
                            $LastTopic = "Redirects: ".$NumRedirects;
                        }
                        $PreForum = $ThemeSet['ForumIcon'];
                        if ($ForumType == "forum") {
                            $PreForum = $ThemeSet['ForumIcon'];
                        }
                        if ($ForumType == "subforum") {
                            $PreForum = $ThemeSet['SubForumIcon'];
                        }
                        if ($ForumType == "redirect") {
                            $PreForum = $ThemeSet['RedirectIcon'];
                        }
                        $ExStr = "";
                        if ($ForumType != "redirect" &&
                            $ForumShowTopics != "no") {
                            $ExStr = "&page=1";
                        }
                        ?>
<tr class="TableRow3" id="Forum<?php echo $ForumID; ?>">
<td class="TableRow3"><div class="forumicon">
<?php echo $PreForum; ?></div></td>
<td class="TableRow3"><div class="forumname"><a href="<?php echo url_maker($exfile[$ForumType], $Settings['file_ext'], "act=view&id=".$ForumID.$ExStr, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?>"<?php if ($ForumType == "redirect") {
    echo " onclick=\"window.open(this.href);return false;\"";
} ?>><?php echo $ForumName; ?></a></div>
<div class="forumdescription"><?php echo $ForumDescription; ?></div></td>
<td class="TableRow3" style="text-align: center;"><?php echo $NumTopics; ?></td>
<td class="TableRow3" style="text-align: center;"><?php echo $NumPosts; ?></td>
<td class="TableRow3"><?php echo $LastTopic; ?></td>
</tr>
<?php } ++$i;
                } @mysql_free_result($result);
                if ($num >= 1) {
                    ?>
<tr id="SubCatEnd<?php echo $CategoryID; ?>" class="TableRow4">
<td class="TableRow4" colspan="5">&nbsp;</td>
</tr>
</table></div>
<div>&nbsp;</div>
<?php }
                } ++$prei;
        }
    }
    @mysql_free_result($preresult);
    $CatCheck = "skip";
    if ($SubShowForums != "yes") {
        $CategoryName = $SCategoryName;
    }
    if ($SubShowForums != "no") {
        require($SettDir['inc'].'categories.php');
    }
}
?>
