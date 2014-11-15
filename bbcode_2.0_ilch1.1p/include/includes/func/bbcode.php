<?php
#   Copyright by Manuel Staechele
#   Support www.ilch.de

defined ('main') or die ( 'no direct access' );
//Klasse laden
require_once('include/includes/class/bbcode.php');
require_once('include/includes/bbcode_config.php');
$ILCH_HEADER_ADDITIONS .= "<link rel=\"stylesheet\" href=\"include/admin/templates/bbcode/bbcode_bstyle.css\"><link href=\"//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css\" rel=\"stylesheet\"><script type=\"text/javascript\" src=\"include/includes/js/BBCodeGlobal.js\"></script>\n<script type=\"text/javascript\">\nvar bbcodemaximagewidth = {$info['ImgMaxBreite']};\nvar bbcodemaximageheight = {$info['ImgMaxHoehe']};\n</script>";




//Farbliste erstellen
function colorliste ( $ar ) {
  $l = '';
  foreach($ar as $k => $v) {
   $l .= '<td width="10" style="background-color: '.$k.';"><a href="javascript:bbcode_code_insert(\'color\',\''.$k.'\'); hide_color();"><img src="include/images/icons/bbcode/transparent.gif" border="0" height="10" width="10" alt="'.$v.'" title="'.$v.'"></td>';
  }
  return ($l);
}

function getBBCodeButtons(){
		//> Buttons Informationen.
		$ButtonSql = db_query("SELECT *	FROM prefix_bbcode_buttons WHERE fnButtonNr='1'");
		$boolButton = db_fetch_assoc($ButtonSql);

		$cfgBBCsql = db_query("SELECT * FROM prefix_bbcode_config WHERE fnConfigNr='1'");
		$cfgInfo = db_fetch_assoc($cfgBBCsql);

		$BBCodeButtons = '<script type="text/javascript" src="include/includes/js/interface.js"></script>';
		
		//> Fett Button!
		if($boolButton['fnFormatB'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert('b','Gib hier den Text an der fett formatiert werden soll.')\"><i title=\"Fettschrift formatieren\" class=\"fa fa-bold\"></i></a> ";
		}

		//> Kursiv Button!
		if($boolButton['fnFormatI'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert('i','Gib hier den Text an der kursiv formatiert werden soll.')\"><i title=\"Kursiv formatieren\" class=\"fa fa-italic\"></i></a> ";
		}

		//> Unterschrieben Button!
		if($boolButton['fnFormatU'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert('u','Gib hier den Text an der unterstrichen formatiert werden soll.')\"><i title=\"Unterstrichen formatieren\" class=\"fa fa-underline\"></i></a> ";
		}

		//> Durchgestrichener Button!
		if($boolButton['fnFormatS'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert('s','Gib hier den Text an der durchgestrichen formatiert werden soll..')\"><i title=\"Durchgestrichen formatieren\" class=\"fa fa-strikethrough\"></i></a> ";
		}

		//> Leerzeichen?
		if($boolButton['fnFormatB'] == 1 || $boolButton['fnFormatI'] == 1 || $boolButton['fnFormatU'] == 1 || $boolButton['fnFormatS'] == 1) {
			$BBCodeButtons .= "&nbsp;";
		}

		//> Links Button!
		if($boolButton['fnFormatLeft'] == 1) {
			$BBCodeButtons .= "<a  class=\"bb bb-default bb-xs\"  href=\"javascript:bbcode_code_insert('left','0')\"><i title=\"Links ausrichten\" class=\"fa fa-align-left\"></i></a> ";
		}

		//> Zentriert Button!
		if($boolButton['fnFormatCenter'] == 1) {
			$BBCodeButtons .= "<a  class=\"bb bb-default bb-xs\"  href=\"javascript:bbcode_code_insert('center','0')\"><i title=\"Mittig ausrichten\" class=\"fa fa-align-center\"></i></a> ";
		}

		//> Rechts Button!
		if($boolButton['fnFormatRight'] == 1) {
			$BBCodeButtons .= "<a  class=\"bb bb-default bb-xs\"  href=\"javascript:bbcode_code_insert('right','0')\"><i title=\"Rechts ausrichten\" class=\"fa fa-align-right\"></i></a> ";
		}
		
		//> Block Button!
		if($boolButton['fnFormatBlock'] == 1) {
			$BBCodeButtons .= "<a  class=\"bb bb-default bb-xs\"  href=\"javascript:bbcode_code_insert('block','0')\"><i title=\"Blocksatz ausrichten\" class=\"fa fa-align-justify\"></i></a> ";
		}

		//> Leerzeichen?
		if($boolButton['fnFormatLeft'] == 1 || $boolButton['fnFormatCenter'] == 1 || $boolButton['fnFormatRight'] == 1 || $boolButton['fnFormatBlock'] == 1) {
			$BBCodeButtons .= "&nbsp;";
		}

		//> Listen Button!
		if($boolButton['fnFormatList'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert('list','Gib hier den Text ein der aufgelistet werden soll.\\nUm die liste zu beenden einfach auf Abbrechen klicken.')\"><i title=\"Liste erzeugen\" class=\"fa fa-list-ol\"></i></a> ";
		}

		//> Hervorheben Button!
		if($boolButton['fnFormatEmph'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_code_insert('emph','0')\"><i title=\"Text hervorheben\" class=\"fa fa-text-height\"></i></a> ";
		}

		//> Schriftfarbe Button!
        if($boolButton['fnFormatColor'] == 1) {
        }
		
		//> Schriftfarbeauswahlcontainer
		if($boolButton['fnFormatColor'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:hide_color();\"><i style=\"color:#0094FF;\" id=\"bbcode_color_button\" title=\"Text f&auml;rben\" class=\"fa fa-paint-brush\"></i></a> ";
			$colorar = array('#FF0000' => 'red','#FFFF00' => 'yellow','#008000' => 'green','#00FF00' => 'lime','#008080' => 'teal','#808000' => 'olive','#0000FF' => 'blue','#00FFFF' => 'aqua', '#000080' => 'navy','#800080' => 'purple','#FF00FF' => 'fuchsia','#800000' => 'maroon','#C0C0C0' => 'grey','#808080' => 'silver','#000000' => 'black','#FFFFFF' => 'white',);
			$BBCodeButtons .= '<div style="position:absolute;"><div style="display:none; position:relative; top:0px; left:80px; width:200px; z-index:100;" id="colorinput">
			<table width="100%" class="bb_head_table" border="0" cellspacing="1" cellpadding="0">
				<tr class="bb_head_color" onclick="javascript:hide_color();"><td colspan="16"><b>Farbe wählen</b></td></tr>
				<tr class="bb_head_color_in" height="15">'.colorliste($colorar).'</tr></table>
			</div></div>';
		}

		//> Schriftgröße Button!
		if($boolButton['fnFormatSize'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert_with_value('size','Gib hier den Text an, der in einer anderen Schriftgr&ouml;ße formatiert werden soll.','Gib hier die Gr&ouml;&szlig;e des textes in Pixel an. \\n Pixellimit liegt bei ".$cfgInfo['fnSizeMax']."px !!!')\"><i title=\"Textgr&ouml;&szlig;e ver&auml;ndern\" class=\"fa fa-font\"></i></a> ";
		}

		//> Leerzeichen?
		if($boolButton['fnFormatList'] == 1 || $boolButton['fnFormatEmph'] == 1 || $boolButton['fnFormatColor'] == 1 || $boolButton['fnFormatSize'] == 1) {
			$BBCodeButtons .= "&nbsp;";
		}

		//> Url Button!
		if($boolButton['fnFormatUrl'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert_with_value('url','Gib hier die Beschreibung für den Link an.','Gib hier die Adresse zu welcher verlinkt werden soll an.')\"><i title=\"Hyperlink hinzuf&uuml;gen\" class=\"fa fa-link\"></i></a> ";
		}

		//> E-Mail Button!
		if($boolButton['fnFormatEmail'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert_with_value('mail','Gib hier den namen des links an.','Gib hier die eMail - Adresse an.')\"><i title=\"E-Mail hinzuf&uuml;gen\" class=\"fa fa-envelope-o\"></i></a> ";
		}

		//> Leerzeichen?
		if($boolButton['fnFormatUrl'] == 1 || $boolButton['fnFormatEmail'] == 1) {
			$BBCodeButtons .= "&nbsp;";
		}

		//> Bild Button!
		if($boolButton['fnFormatImg'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert('img','Gib hier die Adresse des Bildes an.\\nHinweise: Die Breite und H&ouml;he des Bildes ist auf ".$cfgInfo['fnImgMaxBreite']."x".$cfgInfo['fnImgMaxHoehe']." eingeschr&auml;nkt und w&uuml;rde verkleinert dargstellt werden.\\nEs ist möglich ein Bild rechts oder links von anderen Elementen darzustellen, indem man [img=left] oder [img=right] benutzt.')\"><i title=\"Bild einf&uuml;gen\" class=\"fa fa-picture-o\"></i></a> ";
		}
		
		//> Bild hochladen!
		global $allgAr;
		if($allgAr['forum_usergallery'] == 1 && loggedin() && $boolButton['fnFormatImgUpl'] == 1 ) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:usergalleryupl();\" title=\"Bild in Usergallery hochladen und einf&uuml;gen\"><i title=\"Bild hochladen\" class=\"fa fa-upload\"></i></a> ";
		}

		//> Screenshot Button!
		if($boolButton['fnFormatScreen'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert('shot','Gib hier die Adresse des Screens an.\\nDie Breite und H&ouml;he des Bildes ist auf ".$cfgInfo['fnScreenMaxBreite']."x".$cfgInfo['fnScreenMaxHoehe']." eingeschränkt und wird verkleinert dargstellt.\\nEs ist möglich ein Screenshot rechts oder links von anderen Elementen darzustellen, indem man [shot=left] oder [shot=right] benutzt.')\"><i title=\"Screen einf&uuml;gen\" class=\"fa fa-file-image-o\"></i></a> ";
		}

		//> Leerzeichen?
		if($boolButton['fnFormatImg'] == 1 || $boolButton['fnFormatScreen'] == 1) { 
			$BBCodeButtons .= "&nbsp;";
		}

		//> Quote Button!
		if($boolButton['fnFormatQuote'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_code_insert('quote','0')\"><i title=\"Zitat einf&uuml;gen\" class=\"fa fa-quote-right\"></i></a> ";
		}

		//> Klapptext Button!
		if($boolButton['fnFormatKtext'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert_with_value('ktext','Gib hier den zu verbergenden Text ein.','Gib hier einen Titel f&uuml;r den Klapptext an.')\"><i title=\"Klappfunktion hinzuf&uuml;gen\" class=\"fa fa-clipboard\"></i></a> ";
		}

		//> Video Button!
		if($boolButton['fnFormatVideo'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert_with_value_2('video','Gib hier die Video ID vom Anbieter an.','Bitte Anbieter ausw&auml;hlen.\\nAkzeptiert werden: Google, YouTube, MyVideo und GameTrailers')\"><i style=\"color:#ff0000;\" title=\"Video einsetzen\" class=\"fa fa-youtube-play\"></i></a> ";
		}

		//> Flash Button!
		if($boolButton['fnFormatFlash'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert_with_multiple_values('flash',{tag:['Gib hier den Link zur Flashdatei an',''],width:['Gib hier die Breite für die Flashdatei an','400'],height:['Gib hier die Höhe für die Flashdatei an','300']})\"><i title=\"Flash einf&uuml;gen\" class=\"fa fa-bolt\"></i></a> ";
		}

		//> Countdown Button!
		if($boolButton['fnFormatCountdown'] == 1) {
			$BBCodeButtons .= "<a class=\"bb bb-default bb-xs\" href=\"javascript:bbcode_insert_with_value('countdown','Gib hier das Datum an wann das Ereignis beginnt.\\n Format: TT.MM.JJJJ Bsp: 24.12.".date("Y")."','Gib hier eine Zeit an, wann das Ergeinis am Ereignis- Tag beginnt.\\nFormat: Std:Min:Sek Bsp: 20:15:00')\"><i title=\"Countdown festlegen\" class=\"fa fa-clock-o\"></i></a></a> ";
		}

		//> Leerzeichen?
		if($boolButton['fnFormatQuote'] == 1|| $boolButton['fnFormatKtext'] == 1 || $boolButton['fnFormatVideo'] == 1 || $boolButton['fnFormatFlash'] == 1 || $boolButton['fnFormatCountdown'] == 1) {
			$BBCodeButtons .= "&nbsp;";
		}

		//> Code Dropdown!
    if($boolButton['fnFormatCode'] == 1 || $boolButton['fnFormatPhp'] == 1 || $boolButton['fnFormatHtml'] == 1 || $boolButton['fnFormatCss'] == 1) {
      $BBCodeButtons .= "<select onChange=\"javascript:bbcode_code_insert_codes(this.value); javascript:this.value='0';\" style=\"font-family:Verdana;font-size:10px; margin-bottom:6px; z-index:0;\" name=\"code\"><option value=\"0\">Code einf&uuml;gen</option>";
    }


    if($boolButton['fnFormatPhp'] == 1) {
      $BBCodeButtons .= "<option value=\"php\">PHP</option>";
    }

    if($boolButton['fnFormatHtml'] == 1) {
      $BBCodeButtons .= "<option value=\"html\">HTML</option>";
    }

    if($boolButton['fnFormatCss'] == 1) {
      $BBCodeButtons .= "<option value=\"css\">CSS</option>";
    }

    if($boolButton['fnFormatCode'] == 1) {
      $BBCodeButtons .= "<option value=\"code\">Sonstiger Code</option>";
    }

		if($boolButton['fnFormatCode'] == 1 || $boolButton['fnFormatPhp'] == 1 || $boolButton['fnFormatHtml'] == 1 || $boolButton['fnFormatCss'] == 1) {
			$BBCodeButtons .= "</select>";
		}

    return $BBCodeButtons;
}

function BBcode($s,$maxLength=0,$maxImgWidth=0,$maxImgHeight=0) {
  global $permitted,$info,$global_smiles_array;

  //> Smilies in array abspeichern.
	if(!isset($global_smiles_array)) {
		$erg = db_query("SELECT ent, url, emo FROM `prefix_smilies`");
		while ($row = db_fetch_object($erg) ) {
			$global_smiles_array[$row->ent] = $row->emo.'#@#-_-_-#@#'.$row->url;
		}
	}

	$bbcode = new bbcode();
	$bbcode->smileys = $global_smiles_array;
	$bbcode->permitted = $permitted;
	$bbcode->info = $info;

  if ($maxLength != 0) {
    $bbcode->info['fnWortMaxLaenge'] = $maxLength;
  }
  if ($maxImgWidth != 0) {
    $bbcode->info['fnImgMaxBreite'] = $maxImgWidth;
  }
  if ($maxImgHeight != 0) {
    $bbcode->info['fnImgMaxBreite'] = $maxImgHeight;
  }

	return $bbcode->parse($s);
}
?>
