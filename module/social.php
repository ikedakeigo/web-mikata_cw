<?php


$social = getSocialInfo();
if (!is_array($social) or count($social) <= 0) {	// 新規登録
	createData();
	$social = getSocialInfo();
}


/* ------------------------------------------
	facebook タグを出力する関数
 ------------------------------------------*/
function facebook_wmc() {


	global $social;

	if (isset($social['fb_view']) && $social['fb_view'] == "y") {

		$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';

		echo "\n<!--OGP-->\n";
		echo (is_front_page() && !get_query_var('paged')) ? "<meta property=\"og:type\" content=\"".$social['fb_type']."\" />\n" : "<meta property=\"og:type\" content=\"article\" />\n";
		echo "<meta property=\"og:url\" content=\"".get_canonical_wmc(false)."\" />\n";

		$title = (get_post_meta( get_the_ID(), 'page_ogp_title', true) != "") ? get_post_meta( get_the_ID(), 'page_ogp_title', true) : get_title_wmc();
		echo "<meta property=\"og:title\" content=\"".esc_html($title)."\" />\n";

		$description = (get_post_meta( get_the_ID(), 'page_ogp_description', true) != "") ? get_post_meta( get_the_ID(), 'page_ogp_description', true) : get_description_wmc();
		echo "<meta property=\"og:description\" content=\"".esc_html($description)."\" />\n";

		echo "<meta property=\"og:site_name\" content=\"".esc_html(get_bloginfo('name'))."\" />\n";


		$image = getSocialImage('fb_image');
		foreach ($image as $val) echo "<meta property=\"og:image\" content=\"".esc_html($val)."\" />\n";

		if (!empty($social['fb_app_id'])) echo "<meta property=\"fb:app_id\" content=\"".esc_html($social['fb_app_id'])."\" />\n";
		if (!empty($social['fb_admins'])) echo "<meta property=\"fb:admins\" content=\"".esc_html($social['fb_admins'])."\" />\n";

		echo "<meta property=\"og:locale\" content=\"".esc_html($social['fb_lang'])."\" />\n";
		echo "<!--OGP-->\n";
	}
}


/* --------------------------------------------------------
	Twitter情報表示
-------------------------------------------------------- */
function tw_cards_wmc() {

	global $social;
	$twc_list = twCardsKey();

	if (isset($social['tw_view']) && $social['fb_view'] == "y")  {
		$view = "y";

		// 対象の投稿の種類を取得
		$tw_card = get_post_meta( get_the_ID(), 'tw_card', true);
		if (empty($tw_card)) $tw_card = key($twc_list);

		if ($twc_list[$tw_card]) {

			foreach ($twc_list[$tw_card] as $key => $val) {
				if ($key != "*info*") {
					$twitter[$key] = get_post_meta( get_the_ID(), $key, true);

					if (empty($twitter[$key])) {
						switch($key) {
							case "site":
								$twitter[$key] = the_wmc("tw_screen_name");
								break;

							case "title":
								$twitter[$key] = (get_post_meta( get_the_ID(), 'page_ogp_title', true) != "") ? get_post_meta( get_the_ID(), 'page_ogp_title', true) : get_title_wmc();
								break;

							case "description":
								$twitter[$key] = (get_post_meta( get_the_ID(), 'page_ogp_description', true) != "") ? get_post_meta( get_the_ID(), 'page_ogp_description', true) : get_description_wmc();
								break;

							case "image":
							case "image0":
								$image = getSocialImage('tw_image');
								foreach ($image as $img_val) {
									$twitter[$key] = $img_val;
								}
								break;
						}
					}

					if (($val['nec'] == "y") && empty($twitter[$key])) $view = "n";
				}
			}
		}

		if ($view == "y") {
			echo "\n<!-- Twitter Cards -->\n";
			echo "<meta name=\"twitter:card\" content=\"".$tw_card."\" />\n";
			foreach ($twitter as $key => $val) if ($val != "") echo "<meta name=\"twitter:".$key."\" content=\"".esc_html($val)."\" />\n";
			echo "<!--Twitter Cards-->\n";
		}
	}
}


/* ------------------------------------------
	google+ タグを出力する関数
 ------------------------------------------*/
function microdata_wmc() {

	global $social;

	if (have_posts()) {
		echo "\n<!--microdata-->\n";

		$title = (get_post_meta( get_the_ID(), 'page_ogp_title', true) != "") ? get_post_meta( get_the_ID(), 'page_ogp_title', true) : get_title_wmc();
		echo "<meta itemprop=\"name\" content=\"".esc_html($title)."\" />\n";

		$description = (get_post_meta( get_the_ID(), 'page_ogp_description', true) != "") ? get_post_meta( get_the_ID(), 'page_ogp_description', true) : get_description_wmc();
		echo "<meta itemprop=\"description\" content=\"".esc_html($description)."\" />\n";

		$image = getSocialImage('gp_image');
		foreach ($image as $val) echo "<meta itemprop=\"image\" content=\"".esc_html($val)."\" />\n";
		echo "<!--microdata-->\n";
	}
}



//---------------------------------------------------------------------------
//	対象の投稿のソーシャル情報を取得する
//---------------------------------------------------------------------------
function getPostSocial($postid) {
	global $social;
	foreach ($social as $key => $val) {
		$posted_list[$key] = get_post_meta( $postid, $key);
	}
	return $posted_list;
}



//---------------------------------------------------------------------------
//	画像のURLを取得
//---------------------------------------------------------------------------
function getSocialImage($target="") {

	global $social;

	$image = array();
	$image_id = "";

	if ((is_front_page() && is_page()) || is_singular()) {
		$image_id = get_post_thumbnail_id(get_the_ID());
	} else if (is_home()) {
		$image_id = get_post_thumbnail_id(get_option('page_for_posts'));
	}

	if (preg_match("/^[0-9]+$/",$image_id) && ($image_id > 0)) {
		$image_data = wp_get_attachment_image_src( $image_id, 'full');
		if (isset($image_data[0]) && ($image_data[0] != "")) $image[] = $image_data[0];
	}

	$image[] = !empty($social[$target]) ? $social[$target] : $social['so_image'];

	return $image;
}



//---------------------------------------------------------------------------
//	管理画面上での個別title/descriptionの指定
//---------------------------------------------------------------------------

// if ($social['fb_view'] == "y") {
// 	add_action('admin_menu', 'add_ogp_box');
// 	add_action('save_post', 'save_ogp_string');
// }

if (isset($social['fb_view']) && $social['fb_view'] == "y") {
	add_action('admin_menu', 'add_ogp_box');
	add_action('save_post', 'save_ogp_string');
}

function add_ogp_box() {
	// ランディングページのディレクトリ名を取得
	if (!defined('LP_DIR')) define('LP_DIR', the_wmc('lp_dir'));

	add_meta_box('ogp', 'OGP・Microdata・Twitterカードの個別設定', 'ogp_setting', 'post', 'normal');
	add_meta_box('ogp', 'OGP・Microdata・Twitterカードの個別設定', 'ogp_setting', 'page', 'normal');
	add_meta_box('ogp', 'OGP・Microdata・Twitterカードの個別設定', 'ogp_setting', LP_DIR, 'normal');
}

function ogp_setting() {
	if (isset($_GET['post'])) {
		$page_ogp_title = get_post_meta( $_GET['post'], 'page_ogp_title',true);
		$page_ogp_description = get_post_meta( $_GET['post'], 'page_ogp_description', true);
	} else {
		$page_ogp_title = "";
		$page_ogp_description = "";
	}

	echo "<table>\n<tbody>\n";
	echo "<tr>\n<th>タイトル</th>\n<td class=\"wmc_ogp_title\"><input type=\"text\" name=\"page_ogp_title\" value=\"".esc_html($page_ogp_title)."\" size=\"64\" maxlength=\"64\" /></td>\n</tr>\n";
	echo "<tr>\n<th>ディスクリプション</th>\n<td class=\"wmc_ogp_description\"><input type=\"text\" name=\"page_ogp_description\" value=\"".esc_html($page_ogp_description)."\" size=\"64\" maxlength=\"64\" /></td>\n</tr>\n";
	echo "</tbody>\n</table>\n";
}

function save_ogp_string($post_id) {
	if (isset($_POST['page_ogp_title']) && isset($_POST['page_ogp_description']) ) {
		update_post_meta( $post_id, 'page_ogp_title', $_POST['page_ogp_title']);
		update_post_meta( $post_id, 'page_ogp_description', $_POST['page_ogp_description']);
	}
}



//---------------------------------------------------------------------------
//	管理画面上でのTwitterCards個別情報の指定
//---------------------------------------------------------------------------
// if ($social['tw_view'] == "y") {
// 	add_action('admin_menu', 'add_tw_box');
// 	add_action('save_post', 'save_tw_string');
// }

if (isset($social['tw_view']) && $social['tw_view'] == "y") {
	add_action('admin_menu', 'add_ogp_box');
	add_action('save_post', 'save_ogp_string');
}


function add_tw_box() {
	// ランディングページのディレクトリ名を取得
	if (!defined('LP_DIR')) define('LP_DIR', the_wmc('lp_dir'));

	add_meta_box('twc', 'Twitter Cards の個別設定', 'twc_setting', 'post', 'normal');
	add_meta_box('twc', 'Twitter Cards の個別設定', 'twc_setting', 'page', 'normal');
	add_meta_box('twc', 'Twitter Cards の個別設定', 'twc_setting', LP_DIR, 'normal');
}

function twc_setting() {

	$twc_list = twCardsKey();

	$setting_data = getPostSocial(get_the_ID());

	$images_no = 10;

	// デフォルトの値を取得
	$tw_screen_name = the_wmc('tw_screen_name');
	$tw_image = the_wmc('tw_image');

	if (isset($_GET['post'])) {
		$tw_card = get_post_meta( get_the_ID(), 'tw_card', true);
		if (empty($tw_card)) $tw_card = key($twc_list);
	} else {
		$tw_card = key($twc_list);
	}

	foreach ($twc_list[$tw_card] as $key => $val) {
		if ($key != "*info*") $tw_data[$key] = get_post_meta( get_the_ID(), $key, true);
	}

	echo "<table>\n<tbody>\n";
	foreach ($twc_list as $key => $twc_val) {
		if (isset($tw_card) && ($tw_card == $key)) {
			echo "<tr>\n<th><input type=\"radio\" name=\"tw_card\" value=\"".$key."\" id=\"".$key."\" onclick=\"ChangeTwCards('".$key."')\" checked=\"checked\"><label for=\"".$key."\">".$key."</label></th><td><label for=\"".$key."\">".$twc_val['*info*']."</label></td>\n</tr>\n";
		} else {
			echo "<tr>\n<th><input type=\"radio\" name=\"tw_card\" value=\"".$key."\" id=\"".$key."\" onclick=\"ChangeTwCards('".$key."')\"><label for=\"".$key."\">".$key."</label></th><td><label for=\"".$key."\">".$twc_val['*info*']."</label></td>\n</tr>\n";
		}
	}
	echo "</tbody>\n</table>\n";

	echo "<table>\n<tbody>\n";
	foreach ($twc_list as $key => $twc_val) {
		echo "<tr id=\"tw_".$key."\">\n<td>\n";
		echo "<table>\n";
		foreach ($twc_val as $twc_line_key => $twc_line_val) {
			if ($twc_line_key != '*info*') echo "<tr>\n<th>".$twc_line_key."</th>\n";

			if (is_array($twc_line_val)) {
				switch ($twc_line_val['type']) {
					case "text":
						echo "<td><input type=\"text\" name=\"".$key."_".$twc_line_key."\" value=\"".$tw_data[$twc_line_key]."\" size=\"60\" />";
						break;
					case "image":
						$images_no++;
						echo "<td><div id=\"wmc_img_".$images_no."\"></div>\n";
						echo "<input type=\"text\" name=\"".$key."_".$twc_line_key."\" id=\"wmc_upload_image_".$images_no."\" value=\"".$tw_data[$twc_line_key]."\" size=\"70\" />\n";
						echo "<input type=\"button\" class=\"wmc_upload_image_button\" id=\"wmc_upload_image_button_".$images_no."\" value=\"画像を設定する\" />\n";
						break;
				}
				if ($twc_line_val['nec'] == "y") echo "<span class=\"wmc_note\">※ 必須</span>";
				if ($twc_line_key != '*info*' && isset($twc_line_val['info'])) echo "<br />".$twc_line_val['info'];
			}
		}
		echo "</tr>\n</table>\n</td>\n</tr>\n";
	}
	echo "</tbody>\n</table>\n";

	echo "<script>function ChangeTwCards(sel) {\n";
	foreach ($twc_list as $key => $twc_val) {
		echo "if (sel == '".$key."') {\n";
		echo "jQuery(\"#tw_".$key."\").show();\n";
		echo "} else {\n";
		echo "jQuery(\"#tw_".$key."\").hide();\n";
		echo "}\n";
	}
	echo "}\n";

	echo "jQuery(function() {\n";
	echo "var tw_sel = jQuery(\"input[name='tw_card']:checked\").val();\n";
	foreach ($twc_list as $key => $twc_val) {
		echo "if (tw_sel == '".$key."') {\n";
		echo "jQuery(\"#tw_".$key."\").show();\n";
		echo "} else {\n";
		echo "jQuery(\"#tw_".$key."\").hide();\n";
		echo "}\n";
	}
	echo "})\n";
	echo "</script>\n";
}


function save_tw_string($post_id) {
	if (isset($_POST['tw_card'])) {
		update_post_meta( $post_id, 'tw_card', $_POST['tw_card']);
		$twc_list = twCardsKey();
		foreach ($twc_list[$_POST['tw_card']] as $key => $val) {
			if ($key != "*info*") {
				$post_key = $_POST['tw_card']."_".$key;
				if (isset($_POST[$post_key])) update_post_meta( $post_id, $key, $_POST[$post_key]);
			}
		}
	}
}






//---------------------------------------------------------------------------
//	TwitterCardsの種類と設定内容
//---------------------------------------------------------------------------
function twCardsKey() {
	$site = (the_wmc('tw_screen_name') != "") ? "空白の場合の初期値：@".the_wmc('tw_screen_name') : "例） @seokyoto";

	$tw_type = array("summary" => array("*info*"  => "通常のツイートに利用します。140文字のテキストの下に画像とテキストを入力する ",
																					 "site" => array("info" => "Twitterのアカウント名を入力します。".$site,
																													 "type" => "text",
																													 "nec" => "y"),
																					 "title" => array("info" => "Twitter Cardsのタイトルにしたい文字を入力します。空白の場合の初期値は「投稿タイトル」になります。",
																														"type" => "text",
																														"nec" => "y"),
																					 "description" => array("info" => "投稿内容の抜粋などを入力します。空白の場合の初期値は「抜粋」になります。",
																																	"type" => "text",
																																	"nec" => "y"),
																					 "image" => array("info" => "Tweetに付ける画像を指定します",
																														"type" => "image",
																														"nec" => "n")
																					),
									 "summary_large_image" =>array("*info*"  => "大きな画像を付けてツイートしたい場合に利用します",
																					 "site" => array("info" => "Twitterのアカウント名を入力します。".$site,
																														"type" => "text",
																														"nec" => "y"),
																					 "title" => array("info" => "Twitter Cardsのタイトルにしたい文字を入力します。空白の場合の初期値は「投稿タイトル」になります。",
																														"type" => "text",
																														"nec" => "y"),
																					 "description" => array("info" => "投稿内容の抜粋などを入力します。空白の場合の初期値は「抜粋」になります。",
																																	"type" => "text",
																																	"nec" => "y"),
																					 "image" => array("info" => "Tweetに付ける画像を指定します",
																														"type" => "image",
																														"nec" => "n")
																					),
									 "photo" =>array("*info*"  => "画像をメインにしたツイートをしたい場合に利用します",
																					 "site" => array("info" => "Twitterのアカウント名を入力します。@".$site,
																														"type" => "text",
																														"nec" => "y"),
																					 "title" => array("info" => "Twitter Cardsのタイトルにしたい文字を入力します。空白の場合の初期値は「投稿タイトル」になります。",
																														"type" => "text",
																														"nec" => "n"),
																					 "image" => array("info" => "Tweetに付ける画像を指定します",
																														"type" => "image",
																														"nec" => "y")
																					),
									 "gallery" =>array("*info*"  => "複数（最大4枚）の画像を付けてツイートをしたい場合に利用します",
																					 "site" => array("info" => "Twitterのアカウント名を入力します。@".$site,
																														"type" => "text",
																														"nec" => "y"),
																					 "title" => array("info" => "Twitter Cardsのタイトルにしたい文字を入力します。空白の場合の初期値は「投稿タイトル」になります。",
																														"type" => "text",
																														"nec" => "y"),
																					 "description" => array("info" => "投稿内容の抜粋などを入力します。空白の場合の初期値は「抜粋」になります。",
																														"type" => "text",
																														"nec" => "n"),
																					 "image0" => array("val" => "",
																													 "info" => "Tweetに付ける画像(1)を指定します",
																														"type" => "image",
																														"nec" => "y"),
																					 "image1" => array("val" => "",
																													 "info" => "Tweetに付ける画像(2)を指定します",
																														"type" => "image",
																														"nec" => "y"),
																					 "image2" => array("val" => "",
																													 "info" => "Tweetに付ける画像(3)を指定します",
																														"type" => "image",
																														"nec" => "y"),
																					 "image3" => array("val" => "",
																													 "info" => "Tweetに付ける画像(4)を指定します",
																														"type" => "image",
																														"nec" => "y")
																					)
							);

	return $tw_type;
}




/* --------------------------------------------------------
	テーブル情報の設定
-------------------------------------------------------- */
function createData() {

	global $social;
	global $wpdb;

	if (!is_array($social) or count($social) < 0) {	// 新規登録

		// 過去のテーブルが存在するかどうかを確認
		$before_version = get_option("wmc62_before");

		if (!empty($before_version)) {
			switch ($before_version) {
				case "6.2":
					$table_name = $wpdb->prefix."wmc_setting62";
					break;
				case "6.1":
					$table_name = $wpdb->prefix."wmc_setting61";
					break;
				case "6.0":
					$table_name = $wpdb->prefix."wmc_setting";
					break;
			}

			$old_data = $wpdb->get_results("SELECT ks_sys_cont, ks_val FROM ".$table_name." WHERE ks_group in ('Facebook','Google＋','Twitterカード') ORDER BY ks_sort");
			foreach ($old_data as $cont) {
				$list[$cont->ks_sys_cont] = $cont->ks_val;
			}
		}

		/* --------------------------------------------------------
			ソーシャルネットワークの表示制御
		-------------------------------------------------------- */
		$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('SNSの設定', 'ソーシャルボタンの表示', 'social_top_view','トップページ（サイトトップ）','n','n','check','151')";
		$results = $wpdb->query( $insert );

		$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('SNSの設定', 'ソーシャルボタンの表示', 'social_top_archive_view','トップページ（記事一覧部分）','n','n','check','152')";
		$results = $wpdb->query( $insert );

		$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('SNSの設定', 'ソーシャルボタンの表示', 'social_archive_view','一覧ページ（トップページを除く）','n','n','check','153')";
		$results = $wpdb->query( $insert );

		$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('SNSの設定', 'ソーシャルボタンの表示', 'social_post_view','投稿ページ','n','n','check','155')";
		$results = $wpdb->query( $insert );

		$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('SNSの設定', 'ソーシャルボタンの表示', 'social_page_view','固定ページ','n','n','check','157')";
		$results = $wpdb->query( $insert );


		if (isset($list['fb_ogpimage'])) {
			$image_url = $list['fb_ogpimage'];
		} else if (isset($list['gp_image'])) {
			$image_url = $list['gp_image'];
		} else if (isset($list['tw_image'])) {
			$image_url = $list['tw_image'];
		} else {
			$image_url = get_template_directory_uri().'/ogp.jpg';
		}

		$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_ext, ks_type, ks_sort) VALUES ('SNSの設定', 'so_image','共通のサムネイル画像','".$image_url."','".$image_url."','ここに設定された画像が、各ソーシャルメディアの標準画像となります。\n個別に設定をしたい場合は、それぞれの各画像を設定して下さい。','image','161')";
		$results = $wpdb->query( $insert );

			}
}



/* --------------------------------------------------------
	データベースから情報を取得
-------------------------------------------------------- */
function getSocialInfo() {
	global $wpdb;
	$res = $wpdb->get_results("SELECT ks_id, ks_sys_cont, ks_val FROM ".WMC_SET." WHERE ks_group='SNSの設定' && ks_active='y' ORDER BY ks_sort");
	if (isset($res) && count($res) > 0) {
		foreach ($res as $tw) {
			$social[$tw->ks_sys_cont] = $tw->ks_val;
		}
	}
	if (isset($social)) {
		return $social;
	} else {
		return false;
	}
}

?>
