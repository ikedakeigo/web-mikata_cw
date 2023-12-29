<?php


function the_breadcrumbs( $separator = '', $multiple_separator = ' | ' ) {

	if (is_front_page() && !is_paged() && !isset($_GET['post_type'])) {		
		return true;
	}

	global $wp_query;

	// TOP
	setTree(get_bloginfo('url'), get_bloginfo('name'));

	$queried_object = $wp_query->get_queried_object();

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$max_page = get_max_page();

	if (is_front_page() && isset($_GET['post_type']) && $_GET['post_type'] != "") {
		$taxonomy = get_post_type_object($_GET['post_type']);
		if (isset($taxonomy->labels->singular_name)) setTree('', $taxonomy->labels->singular_name);

	} else if (is_home()) {

		$post_page = get_option("page_for_posts");
		if (!empty($post_page) && $post_page > 0) {
			$top_page_data = get_post($post_page);
			if (is_object($top_page_data) && ($top_page_data->post_parent) > 0) get_page_parents_wmc($top_page_data->post_parent);
		}

		if ($paged > 1) {	
			if (!is_front_page()) {
				if (!empty($post_page) && $post_page > 0) {
					setTree(get_page_link($post_page), get_the_title(get_option('page_for_posts')));
				} else {
					setTree(home_url(), get_the_title(get_option('page_for_posts')));
				}
			}
	
			$title = (isset($top_page_data->post_title) && ($top_page_data->post_title != "")) ? $top_page_data->post_title : get_bloginfo('name');
			setTree('', sprintf(__('View all posts in %s','wmc'), $title).show_page_number());

		} else {
			$post_type = get_query_var('post_type');
			if (!empty($post_type)) {
				setTree(get_page_link($post_page), get_the_title(get_option('page_for_posts')));
			} else {
				setTree('', get_the_title(get_option('page_for_posts')));
			}
		}
	} else {
		if (is_page()) {
			$post_page = get_post(get_the_ID());
			if (is_object($post_page) && $post_page->post_parent > 0) get_page_parents_wmc($top_page_data->post_parent, "n");
		} else {
			$post_page = get_option("page_for_posts");
			if (!empty($post_page) && $post_page > 0) {
				$top_page_data = get_post($post_page);
				if (is_object($top_page_data) && ($top_page_data->post_parent) > 0) get_page_parents_wmc($top_page_data->post_parent);	
				setTree(get_page_link($post_page), get_the_title($post_page));
			}
		}
	}
	
	if (is_attachment()) {
		
		setTree('', get_the_title());
	
	} else if (is_page()) {

		setTree('', get_the_title());

	} else if( is_single()) {


		if (is_page()) {

		} else if (is_singular('post')) {

			$categories = get_the_category();

			foreach ( $categories as $category ) {
				if ($category->parent) {
					$parent = get_category_parents($category->parent, true, "");
					preg_match_all('/href="(.+?)">(.+?)<\/a>/', $parent, $cat_dirs, PREG_SET_ORDER);
					if (is_array($cat_dirs) && count($cat_dirs) > 0) {
						foreach ($cat_dirs as $links) {
							setTree($links[1], $links[2]);
						}
					}
				}
	
				setTree(get_category_link($category->term_id), $category->name);
	
				global $page, $paged;		
				if ($page > 1) {
					setTree(get_permalink(), get_the_title());
					setTree('', $page."ページ目");
				} else {
					setTree('', get_the_title());
				}
				break;
			}
		} else {
			$taxonomy = $wp_query->get_queried_object();
			if (isset($taxonomy->post_type)) {
				$taxonomy_category = get_post_type_object(get_post_type());
				if (isset($taxonomy_category->label)) {
					$taxonomy_category_url = (get_post_type_archive_link($taxonomy_category->name) != "") ? get_post_type_archive_link($taxonomy_category->name) : site_url() .'/?post_type='.$taxonomy_category->name;
					setTree($taxonomy_category_url, $taxonomy_category->label);
				}
				setTree('',$taxonomy->post_title);
			}
		}

	} else if( is_search()) {
		
		setTree('',sprintf(__('Search Result for %s','wmc'), esc_html(get_search_query())).show_page_number());
		
	} else if( is_404()) {

		setTree('', __('Sorry, but you are looking for something that isn&#8217;t here.','wmc'));

	} else if (is_category()) {
		if ($queried_object->category_parent) {
			$parent = get_category_parents($queried_object->category_parent, true, "");
			preg_match_all('/href="(.+?)">(.+?)<\/a>/', $parent, $cat_dirs, PREG_SET_ORDER);
			if (is_array($cat_dirs) && count($cat_dirs) > 0) {
				foreach ($cat_dirs as $links) {
					setTree($links[1], $links[2]);
				}
			}
		}
		if ($paged > 1) {
			setTree(get_category_link($queried_object->cat_ID), single_cat_title("",false));
			setTree('', sprintf( __('Archive List for %s','wmc'), single_cat_title("",false)).show_page_number());
		} else {
			setTree('', single_cat_title("",false));
		}

	} else if(is_year()) {
		if ($paged > 1) {
			setTree(get_year_link(date("Y",get_post_time())), sprintf( __('Archive List for %s','wmc'), get_the_time(__('Y','wmc'))));
			setTree('', get_archive_title_wmc());
		} else {
			setTree('', sprintf( __('Archive List for %s','wmc'), get_the_time(__('Y','wmc'))));
		}

	} else if(is_month()) {

		setTree(get_year_link(date("Y",get_post_time())), sprintf( __('Archive List for %s','wmc'), get_the_time(__('Y','wmc'))));

		if ($paged > 1) {
			setTree(get_year_link(date("Y/m",get_post_time())), sprintf( __('Archive List for %s','wmc'), get_the_time(__('F Y','wmc'))));
			setTree('', get_archive_title_wmc());
		} else {
			setTree('', sprintf( __('Archive List for %s','wmc'), get_the_time(__('F Y','wmc'))));
		}

	} else if(is_day()) {

		setTree(get_year_link(date("Y",get_post_time())), sprintf( __('Archive List for %s','wmc'), get_the_time(__('Y','wmc'))));
		setTree(get_year_link(date("Y/m",get_post_time())), sprintf( __('Archive List for %s','wmc'), get_the_time(__('F Y','wmc'))));

		if ($paged > 1) {
			setTree(get_year_link(date("Y/m/d",get_post_time())), sprintf( __('Archive List for %s','wmc'), get_the_time(__('F j, Y','wmc'))));
			setTree('', get_archive_title_wmc());
		} else {
			setTree('', sprintf( __('Archive List for %s','wmc'), get_the_time(__('F j, Y','wmc'))));
		}

	} else if( is_author() ) {
			setTree('', get_the_author().sprintf( __('Archive List for authors','wmc')).show_page_number());

	} else if (is_tag()) {
		if ($paged > 1) {
			setTree(get_tag_link($queried_object->term_id), single_cat_title("",false));
			setTree('', sprintf( __('Archive List for %s','wmc'), single_cat_title("",false)).show_page_number());
		} else {
			setTree('', single_cat_title("",false));
		}

	} else {
		$term = single_term_title('', false);
		if (!empty($term)) {
			setTree('', $term);
		} else {
			$post_type = get_query_var('post_type');
			if (!empty($post_type)) {
				$object = get_post_type_object($post_type);
				if (isset($object->labels->name) && !empty($object->labels->name)) {
					setTree('', $object->labels->name);
				}
			}
		}
	}
	wp_reset_query();

	// 生成された配列から、microdataを生成
	$breadcrumbs = "";
	global $tree;
	foreach ($tree as $position => $val) {
		if ($val['href'] != "") {
			if ($position === 1){
				$breadcrumbs .= "<li class=\"bcl-first\" itemprop=\"itemListElement\" itemscope itemtype=\"http://schema.org/ListItem\">\n";
				$breadcrumbs .= "	<a itemprop=\"item\" href=\"".$val['href']."\"><span itemprop=\"name\">".esc_html($val['name'])."</span> TOP</a>\n";
			} else {
				$breadcrumbs .= "<li itemprop=\"itemListElement\" itemscope itemtype=\"http://schema.org/ListItem\">\n";
				$breadcrumbs .= "	<a itemprop=\"item\" href=\"".$val['href']."\"><span itemprop=\"name\">".esc_html($val['name'])."</span></a>\n";
			}
	    $breadcrumbs .= "	<meta itemprop=\"position\" content=\"".$position."\" />\n";
			$breadcrumbs .= "</li>\n";
		} else {
			$breadcrumbs .= "<li class=\"bcl-last\">".esc_html($val['name'])."</li>\n";
		}
	}

	echo "<nav class=\"breadcrumbs\">\n";
	echo "<ol class=\"breadcrumbs-in\" itemscope itemtype=\"http://schema.org/BreadcrumbList\">\n";
	echo $breadcrumbs;
	echo "</ol>\n";
	echo "</nav>\n";
}



//---------------------------------------------------------------------------
//	ページの上位の取得
//---------------------------------------------------------------------------
function get_page_parents_wmc($page, $reg = "y") {
	$page_data = get_post($page);
	if (is_object($page_data) && ($page_data->post_parent) > 0) get_page_parents_wmc($page_data->post_parent);
	if ($reg == "y") setTree(get_page_link($page), get_the_title($page));
}




//---------------------------------------------------------------------------
//	URLの配列を生成する関数
//---------------------------------------------------------------------------
function setTree($href = "", $name = "") {

	global $tree;
	if (!is_array($tree)) $tree = array();
	$position = count($tree) + 1;
	if (preg_match("/^[0-9]+$/", $position) && $position > 0 && $name != "") {
		$tree[$position]['href'] = $href;
		$tree[$position]['name'] = $name;
	}

	return $tree;
}


//---------------------------------------------------------------------------
//	ページの番号関連
//---------------------------------------------------------------------------
function meta_page_number() {  
	global $wp_query;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	if ($paged > 1) return "（".$paged."ページ目）";
}

function get_page_number() {
	global $wp_query;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$max_page = get_max_page(); 
	if ($max_page > 1) return $paged."ページ";
}

function show_page_number() {
	global $wp_query;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$max_page = get_max_page(); 
	return ($max_page > 1 && $paged > 1) ? "（".$paged.' / '.$max_page."ページ）" : "";
}

function get_max_page() {
	global $wp_query;
	return $wp_query->max_num_pages; 
}

?>
