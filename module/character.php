<?php

global $wpdb;
define("WMC_CHAR",$wpdb->prefix."wmc_character");


$table_alive = $wpdb->get_results("SHOW TABLES LIKE '".WMC_CHAR."'");
if (!isset($table_alive) or count($table_alive) <= 0) {

	if ($wpdb->get_var("show tables like '".WMC_CHAR."'") != WMC_CHAR) {
		$char = defined("DB_CHARSET") ? DB_CHARSET : "utf8";
		$sql = "CREATE TABLE ".WMC_CHAR." (
						kc_id smallint(2) unsigned NOT NULL auto_increment,
						kc_name varchar(32) NOT NULL,
						kc_name_view enum('y','n') NOT NULL default 'y',
						kc_image tinytext NOT NULL,
						kc_image_style enum('square','circle') NOT NULL default 'square',
						kc_balloon enum('square','circle','none') NOT NULL default 'square',
						kc_color varchar(6) NOT NULL,
						kc_position enum('left','right') NOT NULL default 'left',
						kc_button_view enum('y','n') NOT NULL default 'n',
						kc_active enum('y','n') NOT NULL default 'y',
						PRIMARY KEY  (kc_id)
					) ENGINE=InnoDB DEFAULT CHARSET=".$char.";";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
} else {
	if ($wpdb->get_var("DESCRIBE '".WMC_CHAR."' kc_active") == false) {

		$alter = "ALTER TABLE ".WMC_CHAR." ADD kc_button_view enum('y','n') NOT NULL default 'n'";
		$results = $wpdb->query( $alter );

		$alter = "ALTER TABLE ".WMC_CHAR." ADD kc_active enum('y','n') NOT NULL default 'y'";
		$results = $wpdb->query( $alter );
	}
}


if (is_user_logged_in() and preg_match('/'.preg_quote(preg_replace('/^https?:\/\//', '',get_bloginfo('wpurl'))."/wp-admin/", '/')."/", $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])) {
	if (isset($_GET['page'])) {
		switch ($_GET['page']) {

			case "wmc_character":

				if (isset($_GET['kc_id']) && preg_match("/^[0-9]+$/", $_GET['kc_id']) && ($_GET['kc_id'] > 0)) {
					$res = getCharacter($_GET['kc_id']);

					if (isset($_POST) && (isset($_POST['reg']) || isset($_POST['reg_x']))) {
						$res = updateCharacter();
						if (is_array($res)) {
							regView($res, $_POST);
						} else if ($res === false) {
							$res = array();
							$res['db'] = "データベースの更新が出来ませんでした";
							regView($res, $_POST);
						} else {
							viewList();
						}

					} else if (isset($_POST) && (isset($_POST['del']) || isset($_POST['del_x']))) {
						delCharacter();
						viewList();

					} else if (is_array($res)) {
						regView("", $res);
					} else {
						noCharacter();
					}
				} else {
					viewList();
				}
				break;

			case "wmc_character_add":
				if (isset($_POST) && (isset($_POST['reg']) || isset($_POST['reg_x']))) {
					$res = regCharacter();
					if (is_array($res)) {
						regView($res, $_POST);
					} else {
						viewList();
					}
				} else {
						regView('', $_POST);
				}
				break;
		}
	}
}


function viewList() {
	global $wpdb;

	// リストを取得
	$sql = "SELECT * FROM ".WMC_CHAR." WHERE kc_active='y' ORDER BY kc_id";
	$res = $wpdb->get_results($sql , ARRAY_A);
	foreach ($res as $val) {
		$kc_id = $val['kc_id'];
		unset($val['kc_id']);
		$list[$kc_id] = $val;
	}

	echo "<div class=\"wrap\">";
	echo "<h2>".__('Character Setting','wmc') ."<a href=\"admin.php?page=wmc_character_add\" class=\"add-new-h2\">".__('Character Registration','wmc')."</a></h2>\n";
	if (isset($list) && count($list)> 0) {
		echo "<table class=\"wp-list-table widefat fixed striped posts\" id=\"char-list\">\n";
		echo "<thead>\n<tr>\n<th class=\"manage-column column-id\">ID</th>\n<th class=\"manage-column column-name\">".__('Name','wmc') ."</th>\n<th class=\"manage-column\">プレビュー</th>\n<th class=\"manage-column\">ショートコード</th>\n</tr>\n</thead>\n";
		foreach ($list as $kc_id => $val) {
			$position = ($val['kc_position'] == "right") ? "chat-r" : "chat-l";
			echo "<tbody>\n<tr>\n<td>".$kc_id."</td>\n<td><a href=\"admin.php?page=wmc_character&amp;kc_id=".$kc_id."\">".esc_html($val['kc_name'])."</a></td>\n<td>";
			echo do_shortcode("[char no=\"".$kc_id."\" char=\"".esc_html($val['kc_name'])."\"]テキスト[/char]");
			echo "</td>\n
			<td>[char no=\"".$kc_id."\" char=\"".$val['kc_name']."\"]テキスト[/char]</td>\n</tr></tbody>\n";
		}
		echo "</table>\n";
	}
}


function regView($error=null, $data) {

	if (isset($data['kc_color'])) {
		echo "<script>\ndefault_color='".$data['kc_color']."';\n</script>\n";
	} else {
		echo "<script>\ndefault_color='ffffff';\n</script>\n";
	}

	echo "<div>";
	echo "<div class=\"wrap\">";
	echo "<div id=\"icon-options-general\" class=\"icon32\"><br />\n</div>\n<h2>キャラの登録</h2>\n";

	echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\" autocomplete=\"off\">\n";
	echo "<div class=\"metabox-holder\">\n<div class=\"postbox\">\n";
	echo "<div class=\"inside\">\n";
	echo "<table class=\"form-table\">\n<tbody>\n";
	// 修正
	// echo "<tr>\n<th>名前</th>\n<td><input type=\"text\" name=\"kc_name\" value=\"".esc_html($data['kc_name'])."\" />";
	if (isset($error['kc_name'])) echo "<p class=\"wmc_note\">".esc_html($error['kc_name'])."</p>";
	echo "</td>\n</tr>\n";


	echo "<tr>\n<th>名前の表示</th>\n";
	if (!isset($data['kc_name_view']) || $data['kc_name_view'] == "y") {
		echo "<td><input type=\"checkbox\" name=\"kc_name_view\" value=\"y\" id=\"kc_name_view\" checked=\"checked\" />";
	} else {
		echo "<td><input type=\"checkbox\" name=\"kc_name_view\" value=\"y\" id=\"kc_name_view\" />";
	}
	echo "<label for=\"kc_name_view\">表示する</label>\n";
	echo "</td>\n</tr>\n";

// 修正
	// echo "<tr>\n<th>画像</th>\n<td><p id=\"wmc_img_1\">";
	if(isset($sameArray['kc_image'])){
		echo "<tr>\n<th>画像</th>\n<td><p id=\"wmc_img_1\">";
		if ($data['kc_image'] != "") echo "<img src=\"".esc_html($data['kc_image'])."\" />";
		echo "</p>\n";
		echo "<input id=\"wmc_upload_image_1\" type=\"text\" size=\"80\" name=\"kc_image\" value=\"". esc_html($data['kc_image']) ."\" /><br />";
	}else{
		echo "<tr>\n<th>画像</th>\n<td>";
	}

	// if ($data['kc_image'] != "") echo "<img src=\"".esc_html($data['kc_image'])."\" />";
	// echo "</p>\n";
	// echo "<input id=\"wmc_upload_image_1\" type=\"text\" size=\"80\" name=\"kc_image\" value=\"". esc_html($data['kc_image']) ."\" /><br />";
	echo "<p><input type=\"button\" class=\"wmc_upload_image_button\" id=\"upload_image_button_1\" value=\"画像を設定する\" /></p>\n";
	if (isset($error['kc_image'])) echo "<p class=\"wmc_note\">".esc_html($error['kc_image'])."</p>\n";
	echo "</td>\n</tr>\n";


	echo "<tr><th>画像の形</th>\n<td>\n";
	$image_style = array("square" => "四角", "circle" => "円形");
	if (!isset($data['kc_image_style'])) $data['kc_image_style'] = key($image_style);

	foreach ($image_style as $type => $type_val) {
		if ($data['kc_image_style'] == $type) {
			echo "<input type=\"radio\" name=\"kc_image_style\" value=\"".$type."\" id=\"image_style_".$type."\" checked=\"checked\" >";
		} else {
			echo "<input type=\"radio\" name=\"kc_image_style\" value=\"".$type."\" id=\"image_style_".$type."\" >";
		}
		echo "<label for=\"image_style_".$type."\">".$type_val."</label>\n";
	}
	if (isset($error['kc_image_style'])) echo "<p class=\"wmc_note\">".esc_html($error['kc_image_style'])."</p>";
	echo "<p>円形を選択した場合、長方形の画像だと、楕円になってしまうため、正方形画像のアップロードをお勧め致します。</p>";
	echo "</td>\n</tr>\n";


	echo "<tr><th>吹き出しの形</th>\n<td>\n";
	$balloon = array("square" => "四角", "circle" => "角丸", "none" => "表示しない");
	if (!isset($data['kc_balloon'])) $data['kc_balloon'] = key($balloon);

	foreach ($balloon as $type => $type_val) {
		if ($data['kc_balloon'] == $type) {
			echo "<input type=\"radio\" name=\"kc_balloon\" value=\"".$type."\" id=\"kc_balloon_".$type."\" checked=\"checked\" >";
		} else {
			echo "<input type=\"radio\" name=\"kc_balloon\" value=\"".$type."\" id=\"kc_balloon_".$type."\" >";
		}
		echo "<label for=\"kc_balloon_".$type."\">".$type_val."</label>\n";
	}
	if (isset($error['kc_balloon'])) echo "<p class=\"wmc_note\">".esc_html($error['kc_balloon'])."</p>";
	echo "</td>\n</tr>\n";



	echo "<tr><th>吹き出し背面の色</th>\n<td>\n";
	$color =  (isset($data['kc_color']) && $data['kc_color'] != "") ? $data['kc_color'] : "#ffffff";
	echo "<input type=\"text\" id=\"picker\" name=\"kc_color\" maxlength=\"6\" size=\"6\" value=\"".esc_html($color)."\" >\n";
	if (isset($error['kc_color'])) echo "<p class=\"wmc_note\">".esc_html($error['kc_color'])."</p>";
	echo "</td>\n</tr>\n";



	echo "<tr><th>基本ポジション</th>\n<td>\n";
	$position = array("left" => "左", "right" => "右");
	if (!isset($data['kc_position'])) $data['kc_position'] = key($position);

	foreach (	$position as $key => $val) {
		if ($data['kc_position'] == $key) {
			echo "<input type=\"radio\" name=\"kc_position\" value=\"".$key."\" id=\"".$key."\" checked=\"checked\" >\n";
		} else {
			echo "<input type=\"radio\" name=\"kc_position\" value=\"".$key."\" id=\"".$key."\" >\n";
		}
		echo "<label for=\"".$key."\">".$val."</label>\n";
	}
	if (isset($error['kc_position'])) echo "<p class=\"wmc_note\">".esc_html($error['kc_position'])."</p>";
	echo "</td>\n</tr>\n";


	echo "<tr><th>投稿用ページのボタン表示</th>\n<td>\n";
	$button = array("n" => "出力しない", "y" => "表示する");

	if (empty($data['kc_button_view'])) $data['kc_button_view'] = key($button);

	foreach ($button as $key => $val) {
		if ($key == $data['kc_button_view']) {
			echo "<input type=\"radio\" name=\"kc_button_view\" value=\"".$key."\" checked=\"checked\" id=\"".$key."\" /><label for=\"".$key."\">".$val."</label>\n";
		} else {
			echo "<input type=\"radio\" name=\"kc_button_view\" value=\"".$key."\" id=\"".$key."\" /><label for=\"".$key."\">".$val."</label>\n";
		}
	}
	echo "</td>\n</tr>\n";

	echo "</tbody>\n</table>\n";
	echo "</div>\n</div>\n</div>\n";

	echo "<p class=\"submit\"><input type=\"submit\" name=\"reg\" class=\"button button-primary button-large\" value=\"キャラを登録する\" /></p>\n";
	if (isset($data['kc_id']) && preg_match("/^[0-9]+$/", $data['kc_id']) && ($data['kc_id'] > 0)) {
		echo "<input type=\"hidden\" name=\"kc_id\" value=\"".$data['kc_id']."\" />\n";
	}
	echo "</form>\n";

	if (isset($_GET['page']) && $_GET['page'] == "wmc_character" && isset($_GET['kc_id']) && preg_match("/^[0-9]+/", $_GET['kc_id'])) {
		echo "<div class=\"char_del\">\n";
		echo "<form method=\"post\" action=\"".$_SERVER['REQUEST_URI']."\" autocomplete=\"off\">\n";
		echo "<p class=\"submit\"><button type=\"submit\" name=\"del\" class=\"char-del-button\" onclick=\"return confirm('このキャラを削除してよろしいですか。');\">キャラを削除する</button>\n";
		echo "<input type=\"hidden\" name=\"kc_id\" value=\"".$data['kc_id']."\" />\n";
		echo "</form>\n";
		echo "</div>\n";
	}

	$kc_color =  (isset($data['kc_color']) && preg_match("/^[0-9a-f]{6}$/", $data['kc_color'])) ? "#".$data['kc_color'] : $kc_color = "white";
	echo "<style type=\"text/css\">\n#picker {\nmargin:0;\n width:100px;\n outline: 1px solid #ccc;\nborder: 0;\nborder-right:20px solid ".$kc_color.";\n line-height:20px;\n}\n</style>\n";

	echo "</div>\n";

}


function regCharacter() {
	global $_POST;

	foreach ($_POST as $key => $val) {
		if ($val == "") {
			if ($key == "kc_color" && $_POST['kc_balloon'] == "none") {
				// 吹き出しを表示しない際のカラーが未入力の場合は、エラー処理を飛ばす
			} else {
				$error[$key] = "未入力です。";
			}
		}
	}

	$_POST['kc_name_view'] =  (!isset($_POST['kc_name_view'])) ? "n" : "y";

	// 修正
	// if (isset($error)) {
	// 	return $error;
	// } else {
	// 	// データベースに入れる
	// 	global $wpdb;
	// 	$wpdb->query($wpdb->prepare( "INSERT INTO ".WMC_CHAR." ( kc_name, kc_name_view, kc_image, kc_image_style, kc_balloon, kc_color, kc_position, kc_button_view ) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s )",  $_POST['kc_name'], $_POST['kc_name_view'], $_POST['kc_image'], $_POST['kc_image_style'], $_POST['kc_balloon'], $_POST['kc_color'], $_POST['kc_position'], $_POST['kc_button_view']));
	// 	unset($_POST);
	// 	if (preg_match("/^[0-9]+$/", $wpdb->insert_id)) {
	// 		return $wpdb->insert_id;
	// 	}
	// }

	if (isset($error)) {
    return $error;
} else {
    // デフォルト値の設定
    $kc_name = isset($_POST['kc_name']) ? $_POST['kc_name'] : '';
    $kc_image = isset($_POST['kc_image']) ? $_POST['kc_image'] : '';

    global $wpdb;
    $wpdb->query($wpdb->prepare(
        "INSERT INTO " . WMC_CHAR . " (kc_name, kc_name_view, kc_image, kc_image_style, kc_balloon, kc_color, kc_position, kc_button_view) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
        $kc_name, $_POST['kc_name_view'], $kc_image, $_POST['kc_image_style'], $_POST['kc_balloon'], $_POST['kc_color'], $_POST['kc_position'], $_POST['kc_button_view']
    ));
    unset($_POST);
    if (preg_match("/^[0-9]+$/", $wpdb->insert_id)) {
        return $wpdb->insert_id;
    }
}
}



function updateCharacter() {
	global $_POST;

	foreach ($_POST as $key => $val) {
		if ($val == "") {
			if ($key == "kc_color" && $_POST['kc_balloon'] == "none") {
				// 吹き出しを表示しない際のカラーが未入力の場合は、エラー処理を飛ばす
			} else {
				$error[$key] = "未入力です。";
			}
		}
	}

	$_POST['kc_name_view'] =  (!isset($_POST['kc_name_view'])) ? "n" : "y";

	if (isset($error)) {
		return $error;
	} else {
		// データベースに入れる
		global $wpdb;
		$res = $wpdb->query($wpdb->prepare( "UPDATE ".WMC_CHAR." SET kc_name=%s, kc_name_view=%s, kc_image=%s, kc_image_style=%s, kc_balloon=%s, kc_color=%s, kc_position=%s, kc_button_view=%s WHERE kc_id=%d",  $_POST['kc_name'], $_POST['kc_name_view'], $_POST['kc_image'], $_POST['kc_image_style'], $_POST['kc_balloon'], $_POST['kc_color'], $_POST['kc_position'], $_POST['kc_button_view'], $_POST['kc_id']));
		unset($_POST);
		return $res;
	}
}



function delCharacter() {
	global $_POST;
	global $wpdb;
	$res = $wpdb->query($wpdb->prepare( "UPDATE ".WMC_CHAR." SET kc_active=%s WHERE kc_id=%d",  "n", $_POST['kc_id']));
	unset($_POST);
	return false;
}




function getCharacter($kc_id="") {
	if (preg_match("/^[0-9]+$/", $kc_id) and ($kc_id > 0)) {
		global $wpdb;
		$character = $wpdb->get_results($wpdb->prepare( "SELECT * FROM ".WMC_CHAR." WHERE kc_id=%d AND kc_active='y'", $kc_id), ARRAY_A);
		if (isset($character[0])) {
			return $character[0];
		} else {
			return false;
		}
	} else {
		return false;
	}
}



function noCharacter() {

	echo "キャラは登録されていないか、削除されています。<br />";
}


?>
