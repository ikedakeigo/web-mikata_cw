<?php

 
//---------------------------------------------------------------------------
//	タイトルの表示する関数
//---------------------------------------------------------------------------
function title_wmc( $blogname = true, $sep = " | " ) {
	echo get_title_wmc();
}


function get_title_wmc($blogname = true, $sep = " | " ) {
	$title = "";

	if (is_front_page()) {
		$title = (trim(the_wmc('top_title')) != "") ? trim(the_wmc('top_title')) : trim(get_bloginfo('name'));

	} else if (is_home()) {

		$post_page = get_option(page_for_posts);
		if (!empty($post_page) && $post_page > 0) {
			$top_page_data = get_post($post_page);
			$title = $top_page_data->post_title;
		} else {
			if ((get_option('page_for_posts') > 0) and (get_the_ID() != get_option('page_on_front'))) {
				$title = trim(get_the_title('name'));
			} else {
				$title = trim(get_bloginfo('name'));
			}
		}

		if (is_home() && get_query_var('paged') > 1) $title = sprintf( __('Archive List for %s','wmc'),$title);

	} else if (is_singular()){

		$title = trim(get_the_title());

		$this_page = pageNumber();
		if ($this_page['now_page'] > 1) $title .= "（".$this_page['now_page']."/".$this_page['max_pages']."ページ）";


	} else if(is_category() or is_tag()){
		$title = get_archive_title_wmc("n");

	} else if(is_day()){
		$title = sprintf( __('Archive List for %s','wmc'), get_the_time(__('F j, Y','wmc')));
	} else if(is_month()){
		$title = sprintf( __('Archive List for %s','wmc'), get_the_time(__('F Y','wmc')));
	} else if(is_year()){
		$title = sprintf( __('Archive List for %s','wmc'), get_the_time(__('Y','wmc')));
	} else if(is_author()) {

		if(have_posts()):
			while(have_posts()): the_post();
				$title = get_the_author_meta('display_name').sprintf( __('Archive List for authors','wmc'));
			endwhile;
			wp_reset_query();
		endif;

	} else if(get_query_var('paged') > 1) {
		$title = sprintf( __('Archive List for blog','wmc'));
	} else if(is_search()){
		$title = sprintf( __('Search Result for %s','wmc'), get_search_query());
	} else if(is_404()){
		$title = sprintf( __('Sorry, but you are looking for something that isn&#8217;t here.','wmc'));
	} else {
		$title = wp_title('', false, 'right');
	}

	if( $title == "" ) $title = get_bloginfo('name');		

	if (is_front_page()) {
		if (get_query_var('paged') > 1) $title .= show_page_number();
	} else {
		if (get_query_var('paged') > 1) $title .= show_page_number();
		if (the_wmc('view_site_title') != "n") {
			if (get_post_meta( get_the_ID(), "title_view", true) == "y") $title .= $sep.get_bloginfo('name');
		}
	}

	wp_reset_query();

	return esc_html($title);
}
	

//---------------------------------------------------------------------------
//	抜粋文字の表示する関数
//---------------------------------------------------------------------------

function the_excerpt_wmc() {
	$excerpt = "";
	
	if (is_home() or is_front_page()) {
		if ((get_option('page_for_posts') > 0) and (get_the_ID() != get_option('page_on_front'))) {
			$page = get_page(get_option('page_for_posts'));
			$excerpt = $page->post_excerpt;
		} else {
			$excerpt = get_bloginfo('description');
		}

		if ($excerpt == "") {
			if (is_home() && get_option('page_for_posts') > 0) {
				$excerpt = get_bloginfo('name')."の「".get_bloginfo('name')."」記事一覧".show_page_number()."です";
			} else if (is_home() && get_query_var('paged') > 1 ) {		
				$excerpt = get_bloginfo('name')."の記事一覧".show_page_number()."です。";
			}
		}

	} else if (is_singular()){

		if (has_excerpt()) {
			$excerpt = trim(do_shortcode(str_replace("[conts]","",get_the_excerpt())));
		} else {
			$excerpt = "";
		}


	} else if (is_archive()) {

		 if(is_category()){
			if (category_description() != "") {
				$excerpt = do_shortcode(trim(strip_tags(category_description())));
			} else {
				$excerpt = "「".get_bloginfo('name')."」のカテゴリー「".single_cat_title("",false)."」の記事一覧".show_page_number()."です";
			}
		} else if(is_tag()) {			
			$excerpt = do_shortcode(trim(strip_tags(tag_description())));			
			if ($excerpt == "") {
				$excerpt = sprintf( __('Tag List for %1$s in %2$s','wmc'), single_tag_title('',false), get_bloginfo('name')).show_page_number();
			}			
		}
	} else {
		$excerpt = "";
	}

	return esc_html(strip_tags($excerpt));
}





//---------------------------------------------------------------------------
//	ディスクリプションの表示する関数
//---------------------------------------------------------------------------

function get_description_wmc( $blogdesc = true ){
	$desc = "";
	if (is_home() or is_front_page()) {

		if ((get_option('page_for_posts') > 0) and (get_the_ID() != get_option('page_on_front'))) {
			$page = get_page(get_option('page_for_posts'));
			$desc = $page->post_excerpt;

		} else {
			$desc = "";
			$blogdesc = false;
		}

		if (get_query_var('paged') > 1) {
			$desc = trim(get_bloginfo('name'))."のブログ記事一覧".show_page_number()."です。".get_bloginfo('description');
		}

	} else if (is_singular()){

		$desc = trim(do_shortcode(str_replace("[conts]","",get_the_excerpt())));
		
		if ($desc == "") {
			$desc = "「".trim(get_the_title())."」のページです。".get_bloginfo('description');
		}
		
		$blogdesc = false;

	} else if (is_archive()) {
		if(is_category() or is_tag()) {

			$desc = trim(strip_tags(category_description()));
			if ($desc == "") {
				$desc = sprintf( __('Archive List for %s','wmc'), single_cat_title("",false));
			}
			$blogdesc = false;

		} else if(is_day()){
			$desc = sprintf( __('Archive List for %s','wmc'), get_the_time(__('F j, Y','wmc')));
		} else if(is_month()){
			$desc = sprintf( __('Archive List for %s','wmc'), get_the_time(__('F Y','wmc')));
		} else if(is_year()){
			$desc = sprintf( __('Archive List for %s','wmc'), get_the_time(__('Y','wmc')));
		} else if(is_author()) {
			if(have_posts()):
				while(have_posts()): the_post();
					$desc = get_the_author().sprintf( __('Archive List for authors','wmc'));
				endwhile;
				wp_reset_query();
				endif;

		} elseif(is_tag()) {

			$desc = trim(strip_tags(tag_description()));
			if ($desc == "") {
				$desc = sprintf( __('Tag List for %s','wmc'), single_tag_title("",false));
			} else {
				$blogdesc = false;
			}

		} else if(isset($_GET['paged']) && !empty($_GET['paged'])) {
			$desc = sprintf( __('Archive List for blog','wmc'));
		}

		if (get_query_var('paged') > 1) {
			$desc .= show_page_number();
		}
		$blogdesc = false;


	} else if(is_search()){
		$desc = sprintf( __('Search Result for %s','wmc'), get_search_query()).show_page_number()."です。";

	} else if(is_404()){
		$desc = sprintf( __('Sorry, but you are looking for something that isn&#8217;t here.','wmc'));
		$blogdesc = false;
	} else {

	}

	if( $blogdesc == true )
	{
		$desc .= get_bloginfo('description');
	}
	else
	{
		if( $desc == "" )
		{
			$desc = get_bloginfo('description');

		}
	}
	return do_shortcode(str_replace("\n","",$desc));
}



function description_wmc( $blogdesc = true ){	
	echo esc_html(get_description_wmc());
}

//---------------------------------------------------------------------------
//	メタ・キーワードの表示する関数
//---------------------------------------------------------------------------

function keyword_wmc(){

	global $wp_query;

	$keyword = $cat = $tag = "";

	$keyword = the_wmc('keyword');
	if (substr($keyword,-1) != ",") {
		$keyword .= ",";
	}

	if (is_home() or is_front_page()) {
		if ((get_option('page_for_posts') > 0) and (get_the_ID() != get_option('page_on_front'))) {
			$id = $wp_query->post->ID;
		} else {
			$id = 0;
		}
	} else {
		$id = $wp_query->post->ID;
	}

	if ($id > 0) {
		// カテゴリー名を取得
		$cat_data = get_the_category($id);
		if( !empty( $cat_data )) {
			foreach ($cat_data as $cat_val) {
				$cat_list[] = $cat_val->cat_name;
			}
			$cat = implode(",",$cat_list).",";
		}
	
		// タグを取得
		$tags = get_the_tags($id);
		if( !empty( $tags )) {
			foreach ( $tags as $tag_val ) {
				$tag_array[] = esc_html($tag_val->name);
			}
			$tag = implode(",",$tag_array).",";
		}
	
		if(is_day()){
			$keyword .= get_the_time('Y年,n月,j日,');
		} else if(is_month()){
			$keyword .= get_the_time('Y年,n月,');
		} else if( is_year()) {
			$keyword .= get_the_time('Y年,');
		} else if( is_search() ) {
			$keyword .= get_search_query().",";
		} else if( is_singular() ) {
			$keyword .= $cat.$tag;
		} else if (is_category()) {
			$cat = get_the_category();
			$keyword .= $cat[0]->cat_name;
		} else if (is_tag()) {
			$id = key(get_the_tags());
			$tags = get_the_tags();
			$keyword .= $tags[$id]->name;
		} else if (is_home() or is_front_page()) {
			$keyword .= $cat.$tag;
		}
	}

	do {
		if (substr($keyword,0,1) == ",") {
			$keyword = substr($keyword, 1);
		}
	} while(substr($keyword,0,1) == ",");
	do {
		if (substr($keyword,-1) == ",") {
			$keyword = substr($keyword, 0, -1);
		}
	} while(substr($keyword,-1) == ",");


	wp_reset_query();

	echo esc_html(strip_tags($keyword));
	
}


//---------------------------------------------------------------------------
//	最小ページの画像
//---------------------------------------------------------------------------
function page_image_wmc($id = "") {

	if (is_singular()) {
		if (empty($id)) $id = get_the_ID();
		$image_array = wp_get_attachment_image_src(get_post_thumbnail_id($id));
		$image =  (isset($image_array[0])) ? $image_array[0] : "";
	} else {
		$image = the_wmc('mainimage');
	}

	return $image;
}

?>
