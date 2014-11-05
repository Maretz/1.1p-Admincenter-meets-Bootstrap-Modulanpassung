<?php
#   Copyright by: Horfic
#   Support: ICQ 282892739

defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$design = new design ( 'Admins Area', 'Admins Area', 2 );
$design->header();
function getlink ( $akt ) {
	$linkAR = array();
	$link = '';
	$erg = db_query("SELECT DISTINCT `link` FROM `prefix_linkus`");
	while ($row = db_fetch_object($erg)) {
		$linkAr[] = $row->link;
	}
	$linkAr[] = 'http://www.oeasth.at';
	$linkAr = array_unique($linkAr);
	foreach($linkAr as $a) {
		if (trim($a) == trim($akt)) {
			$sel = ' selected';
		} else {
			$sel = '';
		}
		$link .= '<option' . $sel . '>' . $a . '</option>';
	}
	return ($link);
}
function getgroesse ( $akt ) {
	$groesseAR = array();
	$groesse = '';
	$erg = db_query("SELECT DISTINCT `groesse` FROM `prefix_linkus`");
	while ($row = db_fetch_object($erg)) {
		$groesseAr[] = $row->groesse;
	}
	$groesseAr[] = '468x60';
	$groesseAr = array_unique($groesseAr);
	foreach($groesseAr as $a) {
		if (trim($a) == trim($akt)) {
			$sel = ' selected';
		} else {
			$sel = '';
		}
		$groesse .= '<option' . $sel . '>' . $a . '</option>';
	}
	return ($groesse);
}
function icUpload () {
	$pos = escape($_POST['pos'], 'string');
	$ersteller = escape($_POST['ersteller'], 'string');	
	$url = ( empty($_POST['url']) ? '' : escape($_POST['url'], 'string') ); 
	if (!empty ($_FILES['bild']['name']) ) {
		$rtype = trim(ic_mime_type ($_FILES['bild']['tmp_name']));
        $fname = escape($_FILES['bild']['name'], 'string');
        $fende = preg_replace("/.+\.([a-zA-Z]+)$/", "\\1", $fname);
        $fende = strtolower($fende);
        if (($fende != 'jpg' 
		    AND $fende != 'JPG' 
			AND $fende != 'jpeg' 
			AND $fende != 'JPEG' 
			AND $fende != 'gif' 
			AND $fende != 'GIF' 
			AND $fende != 'png' 
			AND $fende != 'PNG' 
			AND $fende != 'bmp' 
			AND $fende != 'BMP')) {
				return ('Die Datei darf nur die Endungen: .jpg, .JPG, .jpeg, .JPEG, .gif, .GIF, .png, .PNG, .bmp oder .BMP haben.');
		}
        $fname = str_replace ('.' . $fende, '', $fname);
        $fname = preg_replace("/[^a-zA-Z0-9]/", "", $fname);
        $fname = $fname . '.' . $fende;
        if ( move_uploaded_file($_FILES['bild']['tmp_name'], 'include/images/linkus/' . $fname) ) {
			$url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('admin.php','',$_SERVER['PHP_SELF']) . 'include/images/linkus/' . $fname;
			@chmod($url, 0777);
		}
	}
	if ( $_POST['link'] == 'neu' ) {
		$_POST['link'] = $_POST['newlink'];
	}
	if ( $_POST['groesse'] == 'neu' ) {
		$_POST['groesse'] = $_POST['newgroesse'];
	}
	db_query('INSERT INTO `prefix_linkus` (`pos`,`link`,`ersteller`,`bild`,`groesse`) VALUES ( "' . $pos . '", "' . $_POST['link'] . '", "' . $ersteller . '", "' . $url . '", "' . $_POST['groesse'] . '" ) ');
	return (true);
}

$um = '';
if (isset($_REQUEST['um'])) {
	$um = $_REQUEST['um'];
}

if (!empty($_POST['sub'])) {
	if ( empty($_POST['sid']) ) {
		icUpload();
    } else {
		$sid = escape($_POST['sid'], 'integer');
		$pos = escape($_POST['pos'], 'string');
		$ersteller = escape($_POST['ersteller'], 'string');	
		$url = ( empty($_POST['url']) ? '' : escape($_POST['url'],'string') ); 	
		if (!empty ($_FILES['bild']['name']) ) {
			$rtype = trim(ic_mime_type ($_FILES['bild']['tmp_name']));
			$fname = escape($_FILES['bild']['name'],'string');
			$fende = preg_replace("/.+\.([a-zA-Z]+)$/", "\\1", $fname);
			$fende = strtolower($fende);
			if (($fende != 'jpg' 
			    AND $fende != 'JPG' 
				AND $fende != 'jpeg' 
				AND $fende != 'JPEG' 
				AND $fende != 'gif' 
				AND $fende != 'GIF' 
				AND $fende != 'png' 
				AND $fende != 'PNG' 
				AND $fende != 'bmp' 
				AND $fende != 'BMP')) {
					return ('Die Datei darf nur die Endungen: .jpg, .JPG, .jpeg, .JPEG, .gif, .GIF, .png, .PNG, .bmp oder .BMP haben.');
			}
			$fname = str_replace ('.' . $fende, '', $fname);
			$fname = preg_replace("/[^a-zA-Z0-9]/", "", $fname);
			$fname = $fname . '.' . $fende;
			if ( move_uploaded_file($_FILES['bild']['tmp_name'], 'include/images/linkus/' . $fname) ) {
				$url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('admin.php','',$_SERVER['PHP_SELF']) . 'include/images/linkus/' . $fname;
				@chmod($url, 0777);
			}
		}
		if ( $_POST['link'] == 'neu' ) {
			$_POST['link'] = $_POST['newlink'];
		}
		if ( $_POST['groesse'] == 'neu' ) {
			$_POST['groesse'] = $_POST['newgroesse'];
		}
		db_query('UPDATE `prefix_linkus` SET `pos` = "' . $pos . '", `link` = "'.$_POST['link'].'", `ersteller` = "'.$ersteller.'", `bild` = "'.$url.'", `groesse` = "'.$_POST['groesse'].'" WHERE `id` = "'.$sid.'"');
	}
}
if (!empty($_GET['delete']) ) {
	$delete = escape($_GET['delete'], 'integer');
	db_query('DELETE FROM `prefix_linkus` WHERE `id` = "' . $delete . '" LIMIT 1');
}
if ( empty($_GET['sid']) ) {
	$Fpos  = '';
	$Fersteller    = '';
	$Fbild = '';
	$Flink = '';
	$Fgroesse = '';
	$Fsid = '';
	$Fsub = 'Eintragen';
} else {
	$row = db_fetch_object(db_query("SELECT `pos`, `link`, `ersteller`, `bild`, `groesse`, `id` FROM `prefix_linkus` WHERE `id` = " . $_GET['sid']));
	$Fpos = $row->pos;
	$Fersteller = $row->ersteller;
	$Fbild = $row->bild;
	$Flink = $row->link;
	$Fgroesse = $row->groesse;
	$Fsub = '&Auml;ndern';
	$Fsid = $row->id;
}
$tpl = new tpl ( 'linkus', 1);
$ar = array (
	'groesse' => getgroesse($Fgroesse),
	'link' => getlink($Flink),
	'ersteller' => $Fersteller,
	'pos' => $Fpos,
	'url' => $Fbild,
	'bild' => $Fbild,
	'sub' => $Fsub,
	'sid' => $Fsid
);
$tpl->set_ar_out($ar, 0);
$clas = '';
$erg = db_query('SELECT * FROM `prefix_linkus` ORDER BY `id`');
while ($row = db_fetch_assoc($erg) ) {
	$clas = ($clas == '' ? '' : '' );
	$row['class'] = $clas;
	$tpl->set_ar_out($row, 1);
}
$tpl->out(2);
$design->footer();
?>