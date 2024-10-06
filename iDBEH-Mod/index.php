<?php ob_start();
@set_time_limit(30);
@ignore_user_abort(true); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title> iDB Download List </title>
<base href="http://idb.berlios.de/ftp/" />
<meta http-equiv="content-language" content="en-US">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-15">
<meta name="Generator" content="EditPlus">
<meta name="Author" content="Cool Dude 2k">
<meta name="Keywords" content="iDB Download List">
<meta name="Description" content="iDB Download List">
<meta name="ROBOTS" content="Index, FOLLOW">
<meta name="revisit-after" content="1 days">
<meta name="GOOGLEBOT" content="Index, FOLLOW">
<meta name="resource-type" content="document">
<meta name="distribution" content="global">
<link rel="icon" href="favicon.ico" type="image/icon">
<link rel="shortcut icon" href="favicon.ico" type="image/icon">
<style type="text/css">
img { border: 0; padding: 0 2px; vertical-align: text-bottom; }
td  { font-family: monospace; padding: 2px 3px; text-align: right; vertical-align: bottom; white-space: pre; }
td:first-child { text-align: left; padding: 2px 10px 2px 3px; }
table { border: 0; }
a.symlink { font-style: italic; }
</style>
</head>

<body>
<?php
$dayconv = array('second' => 1, 'minute' => 60, 'hour' => 3600, 'day' => 86400, 'week' => 604800, 'month' => 2630880, 'year' => 31570560, 'decade' => 15705600);
$twohour = $dayconv['hour'] * 0;
function size_readable($size, $retstring = null)
{
    // adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
    $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    if ($retstring === null) {
        $retstring = '%01.2f %s';
    }
    $lastsizestring = end($sizes);
    foreach ($sizes as $sizestring) {
        if ($size < 1024) {
            break;
        }
        if ($sizestring != $lastsizestring) {
            $size /= 1024;
        }
    } //$size = round($size, 0);
    if ($sizestring == $sizes[0]) {
        $retstring = '%01d %s';
    } // Bytes aren't normally fractional
    return sprintf($retstring, $size, $sizestring);
}
function GMTimeChange($format, $timestamp, $offset, $minoffset = null, $dst = null)
{
    $TCHour = date("H", $timestamp);
    $TCMinute = date("i", $timestamp);
    $TCSecond = date("s", $timestamp);
    $TCMonth = date("n", $timestamp);
    $TCDay = date("d", $timestamp);
    $TCYear = date("Y", $timestamp);
    unset($dstake);
    $dstake = null;
    if (!is_numeric($minoffset)) {
        $minoffset = "00";
    }
    $ts_array = explode(":", $offset);
    if (count($ts_array) != 2) {
        if (!isset($ts_array[0])) {
            $ts_array[0] = "0";
        }
        if (!isset($ts_array[1])) {
            $ts_array[1] = "00";
        }
        $offset = $ts_array[0].":".$ts_array[1];
    }
    if (!is_numeric($ts_array[0])) {
        $ts_array[0] = "0";
    }
    if ($ts_array[0] > 12) {
        $ts_array[0] = "12";
        $offset = $ts_array[0].":".$ts_array[1];
    }
    if ($ts_array[0] < -12) {
        $ts_array[0] = "-12";
        $offset = $ts_array[0].":".$ts_array[1];
    }
    if (!is_numeric($ts_array[1])) {
        $ts_array[1] = "00";
    }
    if ($ts_array[1] > 59) {
        $ts_array[1] = "59";
        $offset = $ts_array[0].":".$ts_array[1];
    }
    if ($ts_array[1] < 0) {
        $ts_array[1] = "00";
        $offset = $ts_array[0].":".$ts_array[1];
    }
    $tsa = array("offset" => $offset, "hour" => $ts_array[0], "minute" => $ts_array[1]);
    //$tsa['minute'] = $tsa['minute'] + $minoffset;
    if ($dst != "on" && $dst != "off") {
        $dst = "off";
    }
    if ($dst == "on") {
        if ($dstake != "done") {
            $dstake = "done";
            $tsa['hour'] = $tsa['hour'] + 1;
        }
    }
    $TCHour = $TCHour + $tsa['hour'];
    $TCMinute = $TCMinute + $tsa['minute'];
    return date($format, mktime($TCHour, $TCMinute, $TCSecond, $TCMonth, $TCDay, $TCYear));
}
function SeverOffSet()
{
    $TestHour1 = date("H");
    $TestHour2 = gmdate("H");
    $TestHour3 = $TestHour1 - $TestHour2;
    return $TestHour3;
}
$Settings['qstr'] = "&";
$Settings['charset'] = "iso-8859-15";
function change_title($new_title, $use_gzip = "off", $gzip_type = "gzip")
{
    global $Settings;
    if ($gzip_type != "gzip") {
        if ($gzip_type != "deflate") {
            $gzip_type = "gzip";
        }
    }
    $output = @ob_get_clean();
    $output = preg_replace("/<title>(.*?)<\/title>/i", "<title>".$new_title."</title>", $output);
    /* Change Some PHP Settings Fix the &PHPSESSID to &amp;PHPSESSID */
    $SessName = @session_name();
    $output = preg_replace("/&PHPSESSID/", "&amp;PHPSESSID", $output);
    $qstrcode = htmlentities($Settings['qstr'], ENT_QUOTES, $Settings['charset']);
    $output = str_replace($Settings['qstr'].$SessName, $qstrcode.$SessName, $output);
    if ($use_gzip != "on") {
        echo $output;
    }
    if ($use_gzip == "on") {
        if ($gzip_type == "gzip") {
            $goutput = gzencode($output);
        }
        if ($gzip_type == "deflate") {
            $goutput = gzcompress($output);
        }
        echo $goutput;
    }
}
if (!isset($_GET['dst'])) {
    $_GET['dst'] = "on";
}
if ($_GET['dst'] != "on" &&
    $_GET['dst'] != "off") {
    $_GET['dst'] = "off";
}
if (!isset($_GET['offset'])) {
    $_GET['offset'] = "-6";
}
if (!is_numeric($_GET['offset'])) {
    $_GET['offset'] = "0";
}
if ($_GET['offset'] >= 12) {
    $_GET['offset'] = "12";
}
if ($_GET['offset'] <= -12) {
    $_GET['offset'] = "-12";
}
if (!isset($_GET['minoffset'])) {
    $_GET['minoffset'] = "00";
}
if (!is_numeric($_GET['minoffset'])) {
    $_GET['minoffset'] = "00";
}
if ($_GET['minoffset'] >= 59) {
    $_GET['minoffset'] = "59";
}
if ($_GET['minoffset'] <= 0) {
    $_GET['minoffset'] = "00";
}
$TimeOffSet = $_GET['offset'].":".$_GET['minoffset'];
if (!isset($_GET['act'])) {
    $_GET['act'] = "view";
}
if (!isset($_GET['file'])) {
    $_GET['act'] = "view";
    $_GET['file'] = null;
}
if (!isset($_GET['dir'])) {
    $_GET['dir'] = null;
}
if (!isset($_GET['dir'])) {
    if ($_GET['dir'] != "converters" && $_GET['dir'] != "etc" &&
        $_GET['dir'] != "nighty-ver" && $_GET['dir'] != "smileys" &&
        $_GET['dir'] != "themes" && $_GET['dir'] != null &&
        $_GET['dir'] != "CPlusPlus" && $_GET['dir'] != "KolibriOS") {
        $_GET['dir'] = "nighty-ver";
    }
}
if (isset($_GET['dir'])) {
    if ($_GET['dir'] != "converters" && $_GET['dir'] != "etc" &&
        $_GET['dir'] != "nighty-ver" && $_GET['dir'] != "smileys" &&
        $_GET['dir'] != "themes" && $_GET['dir'] != null &&
        $_GET['dir'] != "CPlusPlus" && $_GET['dir'] != "KolibriOS") {
        $_GET['dir'] = "nighty-ver";
    }
}
if (isset($_GET['dir']) && isset($_GET['subdir'])) {
    if ($_GET['dir'] == "etc") {
        if ($_GET['subdir'] != "encrypter" && $_GET['subdir'] != "imageshack" &&
            $_GET['subdir'] != "iPack" && $_GET['subdir'] != "rss" &&
            $_GET['subdir'] != "search" && $_GET['subdir'] != "YouTube") {
            $_GET['subdir'] = null;
        }
    }
    if ($_GET['dir'] == "CPlusPlus") {
        if ($_GET['subdir'] != "HideJpeg") {
            $_GET['subdir'] = null;
        }
    }
    if ($_GET['dir'] == "KolibriOS") {
        if ($_GET['subdir'] != "Programs") {
            $_GET['subdir'] = null;
        }
    }
    if ($_GET['dir'] == "themes") {
        if ($_GET['subdir'] != "Gray" && $_GET['subdir'] != "iDB" &&
            $_GET['subdir'] != "IPB" && $_GET['subdir'] != "Pink" &&
            $_GET['subdir'] != "TFBB") {
            $_GET['subdir'] = null;
        }
    }
}
if (!isset($_GET['subdir'])) {
    $_GET['subdir'] = null;
}
$ftptdir = "ftp://ftp.berlios.de/pub/idb/";
if ($_GET['dir'] != null) {
    $ftptdir = "ftp://ftp.berlios.de/pub/idb/".$_GET['dir']."/";
}
if ($_GET['subdir'] != null) {
    $ftptdir = "ftp://ftp.berlios.de/pub/idb/".$_GET['dir']."/".$_GET['subdir']."/";
}
if ($_GET['act'] == "view" ||
    $_GET['act'] == "list") {
    $chdone = false;
    $ftpid = ftp_connect("ftp.berlios.de");
    $result = ftp_login($ftpid, "anonymous", "anonymous");
    if ($_GET['dir'] == "etc" && $_GET['subdir'] != null && $chdone != true) {
        $chdone = true;
        ftp_chdir($ftpid, "/pub/idb/".$_GET['dir']."/".$_GET['subdir']."/");
    }
    if ($_GET['dir'] == "CPlusPlus" && $_GET['subdir'] != null && $chdone != true) {
        $chdone = true;
        ftp_chdir($ftpid, "/pub/idb/".$_GET['dir']."/".$_GET['subdir']."/");
    }
    if ($_GET['dir'] == "KolibriOS" && $_GET['subdir'] != null && $chdone != true) {
        $chdone = true;
        ftp_chdir($ftpid, "/pub/idb/".$_GET['dir']."/".$_GET['subdir']."/");
    }
    if ($_GET['dir'] == "themes" && $_GET['subdir'] != null && $chdone != true) {
        $chdone = true;
        ftp_chdir($ftpid, "/pub/idb/".$_GET['dir']."/".$_GET['subdir']."/");
    }
    if ($_GET['subdir'] == null && $_GET['dir'] != null && $chdone != true) {
        $chdone = true;
        ftp_chdir($ftpid, "/pub/idb/".$_GET['dir']."/");
    }
    if ($_GET['subdir'] == null && $_GET['dir'] == null && $chdone != true) {
        $chdone = true;
        ftp_chdir($ftpid, "/pub/idb/");
    }
    $flist = ftp_nlist($ftpid, ".");
    $num = count($flist);
    $i = 0;
    $testfile = null;
    ?>
<h1>Index of <?php echo $ftptdir; ?></h1>
<hr /><table>
<tbody>
<?php if ($_GET['dir'] != null && $_GET['subdir'] != null) { ?>
<tr><td colspan="3"><a href="/ftp/?dir=<?php echo $_GET['dir']; ?>">Up to higher level directory</a></td></tr>
<?php } if ($_GET['dir'] != null && $_GET['subdir'] == null) {  ?>
<tr><td colspan="3"><a href="/ftp/">Up to higher level directory</a></td></tr>
<?php }
while ($i < $num) {
    if ($_GET['dir'] == null) {
        if (ftp_size($ftpid, $flist[$i]) == '-1') {
            ?>
<tr>
 <td style="text-align: left;"><a href="/ftp/?dir=<?php echo $flist[$i]; ?>"><img src="dir.gif" alt="Directory: " /><?php echo $flist[$i]; ?></a></td>
 <td></td>
 <td><?php echo GMTimeChange("n/j/Y", ftp_mdtm($ftpid, $flist[$i]) - $twohour, $TimeOffSet, null, $_GET['dst']); ?></td>
 <td><?php echo GMTimeChange("g:i:s A", ftp_mdtm($ftpid, $flist[$i]) - $twohour, $TimeOffSet, null, $_GET['dst']); ?></td>
</tr>
<?php }
        }
    if ($_GET['dir'] != null) {
        if (ftp_size($ftpid, $flist[$i]) == '-1') {
            ?>
<tr>
 <td style="text-align: left;"><a href="/ftp/?dir=<?php echo $_GET['dir']; ?>&amp;subdir=<?php echo $flist[$i]; ?>"><img src="dir.gif" alt="Directory: " /><?php echo $flist[$i]; ?></a></td>
 <td></td>
 <td><?php echo GMTimeChange("n/j/Y", ftp_mdtm($ftpid, $flist[$i]) - $twohour, $TimeOffSet, null, $_GET['dst']); ?></td>
 <td><?php echo GMTimeChange("g:i:s A", ftp_mdtm($ftpid, $flist[$i]) - $twohour, $TimeOffSet, null, $_GET['dst']); ?></td>
</tr>
<?php } else {
    if ($_GET['subdir'] == null) {
        ?>
<tr>
 <td style="text-align: left;"><a href="/ftp/?dir=<?php echo $flist[$i]; ?>"><img src="file.gif" alt="File: " /><?php echo $flist[$i]; ?></a></td>
 <td><?php echo size_readable(ftp_size($ftpid, $flist[$i])); ?></td>
 <td><?php echo GMTimeChange("n/j/Y", ftp_mdtm($ftpid, $flist[$i]) - $twohour, $TimeOffSet, null, $_GET['dst']); ?></td>
 <td><?php echo GMTimeChange("g:i:s A", ftp_mdtm($ftpid, $flist[$i]) - $twohour, $TimeOffSet, null, $_GET['dst']); ?></td>
</tr>
<?php }
    if ($_GET['subdir'] != null) {
        ?>
<tr>
 <td style="text-align: left;"><a href="ftp://ftp.berlios.de/pub/idb/<?php echo $_GET['dir']; ?>/<?php echo $_GET['subdir']; ?>/<?php echo $flist[$i]; ?>"><img src="file.gif" alt="File: " /><?php echo $flist[$i]; ?></a></td>
 <td><?php echo size_readable(ftp_size($ftpid, $flist[$i])); ?></td>
 <td><?php echo GMTimeChange("n/j/Y", ftp_mdtm($ftpid, $flist[$i]) - $twohour, $TimeOffSet, null, $_GET['dst']); ?></td>
 <td><?php echo GMTimeChange("g:i:s A", ftp_mdtm($ftpid, $flist[$i]) - $twohour, $TimeOffSet, null, $_GET['dst']); ?></td>
</tr>
<?php }
    }
    }
    ++$i;
}
    ftp_close($ftpid);
}
?>
</tbody></table><hr />
<form method="get" action="">
<select id="offset" name="offset" class="TextBox"><?php
$tsa_mem = explode(":", $TimeOffSet);
$TimeZoneArray = array("offset" => $Settings['DefaultTimeZone'], "hour" => $tsa_mem[0], "minute" => $tsa_mem[1]);
$plusi = 1;
$minusi = 12;
$plusnum = 13;
$minusnum = 0;
while ($minusi > $minusnum) {
    if ($TimeZoneArray['hour'] == -$minusi) {
        echo "<option selected=\"selected\" value=\"-".$minusi."\">GMT - ".$minusi." hours</option>\n";
    }
    if ($TimeZoneArray['hour'] != -$minusi) {
        echo "<option value=\"-".$minusi."\">GMT - ".$minusi." hours</option>\n";
    }
    --$minusi;
}
if ($TimeZoneArray['hour'] == 0) { ?>
<option selected="selected" value="0">GMT +/- 0 hours</option>
<?php } if ($TimeZoneArray['hour'] != 0) { ?>
<option value="0">GMT +/- 0 hours</option>
<?php }
while ($plusi < $plusnum) {
    if ($TimeZoneArray['hour'] == $plusi) {
        echo "<option selected=\"selected\" value=\"".$plusi."\">GMT + ".$plusi." hours</option>\n";
    }
    if ($TimeZoneArray['hour'] != $plusi) {
        echo "<option value=\"".$plusi."\">GMT + ".$plusi." hours</option>\n";
    }
    ++$plusi;
}
?></select>
<select id="minoffset" name="minoffset" class="TextBox"><?php
$mini = 0;
$minnum = 60;
while ($mini < $minnum) {
    if (strlen($mini) == 2) {
        $showmin = $mini;
    }
    if (strlen($mini) == 1) {
        $showmin = "0".$mini;
    }
    if ($mini == $TimeZoneArray['minute']) {
        echo "\n<option selected=\"selected\" value=\"".$showmin."\">".$showmin." minutes</option>\n";
    }
    if ($mini != $TimeZoneArray['minute']) {
        echo "<option value=\"".$showmin."\">".$showmin." minutes</option>\n";
    }
    ++$mini;
}
?></select>
<select id="dst" name="dst" class="TextBox"><?php echo "\n" ?>
<?php if ($_GET['dst'] == "off" && $_GET['dst'] != "on") { ?>
<option selected="selected" value="off">DST/ST off</option><?php echo "\n" ?><option value="on">DST/ST on</option>
<?php } if ($_GET['dst'] == "on") { ?>
<option selected="selected" value="on">DST/ST on</option><?php echo "\n" ?><option value="off">DST/ST off</option>
<?php } echo "\n" ?></select>
<input type="hidden" name="act" value="<?php echo $_GET['act']; ?>" style="display: none;" />
<input type="hidden" name="dir" value="<?php echo $_GET['dir']; ?>" style="display: none;" />
<input type="submit" value="set" />
</form>
<div class="webmap" style="text-align: center;">
<a href="http://www2.clustrmaps.com/counter/maps.php?url=http://idb.berlios.de/" id="clustrMapsLink"><img src="http://www2.clustrmaps.com/counter/index2.php?url=http://idb.berlios.de/" style="border:1px solid;" alt="Locations of visitors to this page" title="Locations of visitors to this page" id="clustrMapsImg" onError="this.onError=null; this.src='http://clustrmaps.com/images/clustrmaps-back-soon.jpg'; document.getElementById('clustrMapsLink').href='http://clustrmaps.com'" />
</a>&nbsp;
<a href="http://developer.berlios.de/" title="BerliOS Developer Logo" onclick="window.open(this.href);return false;">
<img src="http://developer.berlios.de/bslogo.php?group_id=6135" alt="BerliOS Developer Logo" title="BerliOS Developer Logo" style="border: 0px; height: 32px; width: 124px;" /></a>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
<script type="text/javascript">
_uacct = "UA-1754608-1";
urchinTracker();
</script></div>
</body>
</html>
<?php
change_title("Index of ".$ftptdir, "off", "gzip");
?>