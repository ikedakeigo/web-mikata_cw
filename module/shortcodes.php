<?php


//---------------------------------------------------------------------------
//	新着情報の表示
//---------------------------------------------------------------------------
function newposts_wmc_schotcode($atts) {	
	extract(shortcode_atts(array('target' => "new", 'num_of_posts' => 5, 'social_counts' => 1, 'excerpt' => 0, 'show_date' => "default", "catid" => 0), $atts));
	$news_data = newposts_wmc($target, $num_of_posts, $social_counts, $excerpt, $show_date, $catid);
	if (!empty($news_data)) return do_shortcode($news_data);
}
add_shortcode('newpost', 'newposts_wmc_schotcode');


//---------------------------------------------------------------------------
//	「この投稿を先頭に固定表示」になっている投稿のリストを表示
//---------------------------------------------------------------------------
function sticky_wmc_schotcode($atts) {
	extract(shortcode_atts(array('target' => "sticky", 'num_of_posts' => 3, 'social_counts' => 1, 'excerpt' => 0, 'show_date' => "default", "catid" => 0), $atts));
	$sticky_data = newposts_wmc($target, $num_of_posts, $social_counts, $excerpt, $show_date, $catid);

	if (!empty($sticky_data)) {
		$res_data = "<section class=\"section-wrap\">\n<div class=\"section-in \">".$sticky_data."\n";
		$res_data .= "</div>\n</section>\n";
		return do_shortcode($res_data);
	}
}
add_shortcode('sticky', 'sticky_wmc_schotcode');


//---------------------------------------------------------------------------
//	ワイド表示のエリア表示
//---------------------------------------------------------------------------
function wide_area( $atts, $content = null ) {

	$content = preg_replace("/^<\/p>\n/", "", $content);
	$content = preg_replace("/<p>$/us", "", $content);
	$content = preg_replace("/<br \/>\n$/us", "", $content);

	$class = (isset($atts['class'])) ? "section-in ".$atts['class'] : "section-in";

	$other_param = "";

	if (is_array($atts) && count($atts) > 0) {
		foreach ($atts as $key => $val) {
			if ($key != "class") $other_param = ' '.$key.'="'.$val.'"';
		}
	}

	return do_shortcode("<div class=\"section-wrap wide\">\n<div class=\"".$class."\"".$other_param.">\n".$content."\n</div>\n</div>\n");
}

add_shortcode('wide','wide_area');


//---------------------------------------------------------------------------
//	ノーマル表示のエリア表示
//---------------------------------------------------------------------------
function normal_area( $atts, $content = null ) {

	$content = preg_replace("/^<\/p>\n/", "", $content);
	$content = preg_replace("/<p>$/us", "", $content);
	$content = preg_replace("/<br \/>\n$/us", "", $content);

	$class = (isset($atts['class'])) ? "section-in ".$atts['class'] : "section-in";

	$other_param = "";
	if (is_array($atts) && count($atts) > 0) {
		foreach ($atts as $key => $val) {
			if ($key != "class") $other_param = ' '.$key.'="'.$val.'"';
		}
	}

	return do_shortcode("<div class=\"section-wrap\">\n<div class=\"".$class."\"".$other_param.">\n".$content."\n</div>\n</div>\n");
}

add_shortcode('normal','normal_area');


//---------------------------------------------------------------------------
//	キャラクタ表示用ショートコード設定
//---------------------------------------------------------------------------
function sc_character( $atts, $content = null ) {

	$image_style_array = array("square" => "四角", "circle" => "丸");
	$balloon_array = array("square" => "四角", "circle" => "角丸", "none" => "表示しない");
	$position_array = array("chat-l" => "left", "chat-r" => "right");
	
	extract( shortcode_atts( array('no' => '', 'position' => '', 'name' => '', 'name_view' => '', 'style' => '', 'balloon' => '', 'color' => ''), $atts ) );

	global $wpdb;
	if (preg_match("/^[0-9]+$/", $no) and ($no > 0)) {
		$character = $wpdb->get_results($wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."wmc_character WHERE kc_id=%d", $no), ARRAY_A);

		if (isset($character[0])) {
			if (empty($name_view) || !preg_match("/^[y|n]$/", $name_view)) $name_view = $character[0]['kc_name_view'];
			if (empty($name)) $name = $character[0]['kc_name'];
			if (!isset($image_style_array[$style]) || empty($style)) $style = $character[0]['kc_image_style'];
			if (!isset($balloon_array[$balloon]) || empty($balloon)) $balloon = $character[0]['kc_balloon'];
			if (!isset($position_array[$position]) || empty($position)) $position = array_search($character[0]['kc_position'], $position_array);
			$kc_color = (!empty($color) && preg_match("/^#[0-9a-f]{6}$/", $color)) ? substr($color,1) : $character[0]['kc_color'];

			$res  = "<div class=\"".$position."\">\n";
			$res .= "<div class=\"talker\"><b><img class=\"".$style."\" src=\"".esc_html($character[0]['kc_image'])."\" alt=\"".esc_html($name)."\" />";
			if ($name_view == "y") $res .= esc_html($name);
			$res .= "</b></div>\n";
		}
	} elseif (preg_match("/([0-9]+),*/", $no)) {
		$character = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wmc_character WHERE kc_id IN (".$no.") ORDER BY field(kc_id,".$no.")", ARRAY_A);

		$rows = count($character);
		if ($rows > 0) {
			if (!isset($image_style_array[$style]) || empty($style)) $style = $character[0]['kc_image_style'];
			if (!isset($balloon_array[$balloon]) || empty($balloon)) $balloon = $character[0]['kc_balloon'];
			if (!isset($position_array[$position]) || empty($position)) $position = array_search($character[0]['kc_position'], $position_array);
			$kc_color = (!empty($color) && preg_match("/^#[0-9a-f]{6}$/", $color)) ? substr($color,1) : $character[0]['kc_color'];

			$res  = "<div class=\"".$position." together\">\n";
			$res .= "<div class=\"talker\">\n";

			foreach ($character as $no => $val) {
				$res .= "<b><img class=\"".$style."\" alt=\"".esc_html($val['kc_name'])."\" src=\"".esc_html($val['kc_image'])."\">";
				if ($name_view == "y") $res .= esc_html($val['kc_name']);
				$res .= "</b>\n";
			}
			$res .= "</div>\n";
		}
	}

	$res .= "<div class=\"bubble-wrap\">\n";

	switch ($balloon) {
		case "circle":
			$res .= "<div class=\"bubble rc8\" style=\"background-color:#".esc_html($kc_color). "\">";
			$res .= "<div class=\"bubble-in\" style=\"border-color:#".esc_html($kc_color)."\">";
			$res .= "<p>".ent2ncr($content)."</p>\n";
			$res .= "</div>\n</div>\n";
			break;

		case "square":
			$res .= "<div class=\"bubble\" style=\"background-color:#".esc_html($kc_color). "\">";
			$res .= "<div class=\"bubble-in\" style=\"border-color:#".esc_html($kc_color)."\">";
			$res .= "<p>".ent2ncr($content)."</p>\n";
			$res .= "</div>\n</div>\n";
			break;

		default:
			$res .= "<p>".ent2ncr($content)."</p>\n";
			break;
	}
	$res .= "</div>\n</div>\n";

	return do_shortcode($res);
}
add_shortcode('char', 'sc_character');


//---------------------------------------------------------------------------
//	<br />が出力されないように、そこに書かれた内容をそのまま出力する
//---------------------------------------------------------------------------
function script_direct( $atts, $content = null ) {
	return do_shortcode(preg_replace('/<br[[:space:]]*\/?[[:space:]]*>/i', "\n", $content));
}

add_shortcode('script','script_direct');


//---------------------------------------------------------------------------
//	投稿での改行　　[br] または [br num="x"] x は数字を入れる
//---------------------------------------------------------------------------
function sc_brs_func( $atts, $content = null ) {
	extract( shortcode_atts( array('num' => '5',), $atts ));
	$out = "";
	for ($i=0;$i<$num;$i++) {
		$out .= "<br />";
	}
	return do_shortcode($out);
}

add_shortcode( 'br', 'sc_brs_func' );


//---------------------------------------------------------------------------
//	共通コンテンツの表示
//---------------------------------------------------------------------------
function get_common_contents ($atts) {
	extract( shortcode_atts( array('id' => $comm_post_id), $atts));
	$content = get_post($id, "ARRAY_A");
	if (isset( $content['post_content']) && $content['post_status'] == "publish") {

		return do_shortcode(apply_filters( 'the_content', $content['post_content']));		// 標準の改行処理を行います。
//		return  do_shortcode($content['post_content']);	// 本文に加工を加えるプラグイン（見出し機能など）が正常に動かない場合は、この行を有効にして下さい。

	} else {
		return "";
	}
}
add_shortcode('cc', 'get_common_contents');


//---------------------------------------------------------------------------
//	リンクリスト表示用ショートコード設定
//---------------------------------------------------------------------------
function link_list() {
	return do_shortcode("<dl class='dl-style02'>".wp_list_bookmarks('between=<dd>&title_li=&categorize=0&orderby=id&show_description=1&before=<dt>&after=</dd></dt>&echo=0')."</dl>");
}

add_shortcode('link_list','link_list');
?>