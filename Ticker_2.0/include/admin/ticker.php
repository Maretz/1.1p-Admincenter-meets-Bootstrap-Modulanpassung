<?php 
#   Copyright by Topolino
defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$design = new design ( 'Admins Area', 'Admins Area', 2 );
$design->header();

function getColor(){
for ($red=0;$red<=15;$red=$red+3)
{
for ($green=0;$green<=15;$green=$green+3)
{
for ($blue=0;$blue<=15;$blue=$blue+3)
{
$red_value=dechex($red).dechex($red);
$green_value=dechex($green).dechex($green);
$blue_value=dechex($blue).dechex($blue);
@$pointer++;
$hex_values_table[$pointer]=$red_value.$green_value.$blue_value;
}
}
}
$pointer=1;
$rgb = '<table>';
for ($x=1;$x<=15;$x++)
{
$rgb .= '<tr>';
for ($y=1;$y<=15;$y++)
{
$display_value='#'.@$hex_values_table[$pointer];
if (!$display_value)$display_value="#ffffff";

$rgb .= "<td bgcolor=$display_value><a href=javascript:setcolor('$display_value') alt=$display_value title=$display_value><img border=0 src=include/images/icons/pix.gif width=10px height=10px alt=$display_value></a></td>";
$pointer++;
}
$rgb .= "</tr>";
}
$rgb .= "</table>";
return ($rgb);
}

$direction_ar = array (
  'left'  => 'von rechts',
  'right' => 'von links',
	'down'  => 'von oben',
	'up'    => 'von unten'
);

$speed_ar = array (
  '1'  => '&raquo; eins',
  '2'  => '&raquo; zwei',
	'3'  => '&raquo; drei',
	'4'  => '&raquo; vier',
  '5'  => '&raquo; f&uuml;nf',
  '6'  => '&raquo; sechs',
	'7'  => '&raquo; sieben',
	'8'  => '&raquo; acht',
  '9'  => '&raquo; neun',
	'10' => '&raquo; zehn',
);

if ( isset ($_POST['sub'])) {
  $title = escape($_POST['title'], 'string');
  $text  = escape($_POST['text'], 'string');
  $color = escape($_POST['color'], 'string');
  $speed  = escape($_POST['speed'], 'string');
  $direction  = escape($_POST['direction'], 'string');
  $width  = escape($_POST['width'], 'string');
  $height  = escape($_POST['height'], 'string');
  $size  = escape($_POST['size'], 'string');
  $frei  = escape($_POST['frei'], 'string');
  if ( empty ( $_POST['sid']) ) {
	  $pos = db_count_query("SELECT COUNT(*) as anz FROM prefix_ticker");
		db_query("INSERT INTO prefix_ticker (pos,title,text,color,speed,direction,width,height,size,frei) VALUES (".$pos.",'".$_POST['title']."','".$_POST['text']."','".$_POST['color']."','".$_POST['speed']."','".$_POST['direction']."','".$_POST['width']."','".$_POST['height']."','".$_POST['size']."','".$_POST['frei']."')");
	} else {
    $sid  = escape($_POST['sid'], 'integer');
    db_query("UPDATE prefix_ticker SET title = '".$title."', text = '".$text."', color = '".$color."', speed = '".$speed."', direction = '".$direction."', width = '".$width."', height = '".$height."', size = '".$size."', frei = '".$frei."' WHERE id = ".$sid);
	}
}
if ( $menu->get(1) == 'delete' ) {
  $id = $menu->get(2);
  $anz = db_count_query("SELECT COUNT(id) FROM prefix_ticker WHERE id = ".$id."");
	if ( $anz == 1 ) {
	  $pos = db_result(db_query("SELECT pos FROM prefix_ticker WHERE id = ".$id ),0);
    db_query("DELETE FROM prefix_ticker WHERE id = ".$id);
		db_query("UPDATE prefix_ticker SET pos = pos - 1 WHERE pos > ".$pos);
	}
}
if ( $menu->get(1) == 'u' OR $menu->get(1) == 'o' ) {
	$a = db_count_query("SELECT COUNT(*) as anz FROM prefix_ticker");
  $np = ( $menu->get(1) == 'o' ? $menu->get(3) -1 : $menu->get(3) +1 );
  $np = ( $np >= ( $a -1 ) ? ( $a - 1) : $np );
  $np = ( $np < 0 ? 0 : $np );
  db_query("UPDATE prefix_ticker SET pos = ".$menu->get(3)." WHERE pos = ".$np);
  db_query("UPDATE prefix_ticker SET pos = ".$np." WHERE id = ".$menu->get(2));
}
if ( $menu->get(1) == 'c' ) {
  $n = ( $menu->get(3) == 3 ? 4 : 3 );
  db_query("UPDATE prefix_ticker SET text = ".$n." WHERE id = ".$menu->get(2)); 
}
$tpl = new tpl ( 'ticker', 1);

  if ( $menu->get(1) != 'edit' ) {
	  $row = array(
		  'sub'       => 'Eintragen',
		  'pos'       => '',
			'title'     => '',
			'text'      => '',
			'color'     => '',
			'speed'     => '',
      'direction' => '',
			'width'     => '',
			'height'    => '',
			'size'      => '',
      'frei'      => '',      
      'sid'       => ''
	   );
	} else {
    $sid = $menu->get(2);
		$abf = 'SELECT title,text,color,speed,direction,width,height,size,frei,id as sid FROM prefix_ticker WHERE id = "'.$sid.'"';
		$erg = db_query($abf);
		$row = db_fetch_assoc($erg);
    $row['sub'] = '&Auml;ndern';
	}
  if ($row['frei'] == 1 ) { 
        $row['frei1'] = 'checked'; 
        $row['frei0'] = ''; 
        } else { 
        $row['frei1'] = ''; 
        $row['frei0'] = 'checked';
        }

  if ( $menu->getA(1) == 'f' ) {
  db_query('UPDATE `prefix_ticker` SET `frei` = IF(`frei`>0,0,1) WHERE id = "'.$menu->getE(1).'" LIMIT 1');
  }
	$row['direction'] = arlistee ( $row['direction'] , $direction_ar );		
	$row['colorpicker'] = getColor();
  $row['speed'] = arlistee ( $row['speed'] , $speed_ar );
  $tpl->set_ar_out($row,0);
  $class = 'Cnorm';

	$erg = db_query('SELECT * FROM prefix_ticker ORDER BY pos');
	while ($r = db_fetch_assoc($erg) ) {
    $class = ( $class == '' ? '' : '' );
    $text  = substr(preg_replace("/\015\012|\015|\012/", " ", htmlentities(strip_tags(stripslashes($r['text'])))),0,20);
    $sperre = $r['frei'] >= 1 ? '<i style="color:#ff0000;" class="fa fa-exclamation-triangle"></i>' : '<i style="color:#00cc00;" class="fa fa-check-circle-o"></i>';
    $sperren = $r['frei'] >= 1 ? 'Ticker jetzt freischalten' : 'Ticker jetzt sperren';
    echo '<tr><td>'.$r['title'].'</td>';
    echo '<td>'.$text.' ...</td>';
    echo '<td class="text-right"><a style="margin-right:5px;" href="?ticker-edit-'.$r['id'].'" rel="tooltip" title="&auml;ndern"><i style="color:#2D9600;" class="fa fa-pencil-square-o"></i></a>
<a style="margin-right:5px;"href="javascript:delcheck('.$r['id'].')" rel="tooltip" title="l&ouml;schen"><i style="color:#AD0000;"class="fa fa-times"></i></a>
<a style="margin-right:5px;"href="?ticker-o-'.$r['id'].'-'.$r['pos'].'" rel="tooltip" title="nach oben verschieben"><i class="fa fa-long-arrow-up"></i></a>
<a style="margin-right:5px;"href="?ticker-u-'.$r['id'].'-'.$r['pos'].'" rel="tooltip" title="nach unten verschieben"><i class="fa fa-long-arrow-down"></i></a>
<a href="?ticker-f'.$r['id'].'" rel="tooltip" title="'.$sperren.'">'.$sperre.'</a></td>';
    echo '</tr>';
	}
	$tpl->out(1);
  $abf = 'SELECT * FROM prefix_ticker ORDER BY pos LIMIT 0,1';
  $erg = db_query($abf);
while ($row = db_fetch_object($erg)) { 
  echo '<marquee direction="'.$row->direction.'" scrollamount="'.$row->speed.'" style="height:'.$row->height.'px;width:100%; color:'.$row->color.'; font-size:'.$row->size.'px;">';
  echo '<b> &raquo;<i>'.$row->title.':</i></b>&nbsp;'.$row->text.'';
  } 
  echo '</marquee>'; 
	$tpl->out(2);
$design->footer();
?>