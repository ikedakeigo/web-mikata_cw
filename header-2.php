<!DOCTYPE html>
<html lang="ja" class="<?php echo getPageLayout($post->ID); ?>" itemscope itemtype="https://schema.org/<?php echo getMicroCodeType(); ?>">
<head prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb#">

<title><?php title_wmc(); ?></title>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if (the_wmc('mobile_layout') == "y") { ?><meta name="viewport" content="width=device-width, initial-scale=1.0"><?php } ?>

<?php if (the_wmc('view_meta') == "y") { ?>
<meta name="keywords" content="<?php keyword_wmc(); ?>">
<meta name="description" content="<?php description_wmc(); ?>">
<?php }

wp_enqueue_script('jquery');
if (get_option('blog_public') != false) echo getIndexFollow();
canonical_wmc();
pageRelNext();
wp_head();

facebook_wmc();
tw_cards_wmc();
microdata_wmc();

if (function_exists("get_site_icon_url") && get_site_icon_url() == "") { ?>
<link rel="shortcut icon" type="image/x-icon" href="<?php bloginfo('template_url'); ?>/favicon.ico">
<link rel="apple-touch-icon" href="<?php bloginfo('template_url'); ?>/images/apple-touch-icon.png">
<link rel="apple-touch-icon-precomposed" href="<?php bloginfo('template_url'); ?>/images/apple-touch-icon.png">
<link rel="icon" href="<?php bloginfo('template_url'); ?>/images/apple-touch-icon.png">
<?php } ?>
<!--[if lt IE 9]><script src="<?php bloginfo('template_url'); ?>/js/html5.js"></script><![endif]-->
<?php echo do_shortcode(the_wmc('meta_text'))."\n";
if (is_single() || is_page()) echo get_post_meta( $post->ID, 'page_tags', true)."\n";
?>


</head>
<?php
$gnav = ((get_globalmenu_wmc('top_menu') == "") || ((is_front_page() || is_home() || is_singular()) && get_post_meta($post->ID, 'menu_view', true) == "n")) ? "no-gn" : "";	// メニューを表示しない場合は、classにno-gnを設定する

// ランディングページで画像をフルサイズで表示する
if (is_singular(LP_DIR) && get_post_meta( $post->ID, 'fullscreen_view', true) == "y") {
	$gnav .= ($gnav != "") ? " lp" : "lp"; ?>
	<body <?php body_class($gnav); ?>>
	<?php echo do_shortcode(the_wmc('body_text'))."\n"; ?>	
	<div class="container">
	<header id="top" class="site-header full-screen"<?php if (get_post_meta( $post->ID, 'header_image', true) != "") { ?> style="background-image: url(<?php echo get_post_meta( $post->ID, 'header_image', true); ?>)"<?php } ?>>
		<div class="site-header-in">
			<div class="site-header-conts">
			<div class="head_btn1"></div>
				<h1 class="site-title"><?php echo (get_post_meta($post->ID, 'page_h1', true)) ? esc_html(get_post_meta($post->ID, 'page_h1', true)) : get_h1_wmc(); ?></h1>
				
			<div class="head_btn1"></div>
				<?php echo get_post_meta($post->ID, 'catch_text', true) ? "<p class=\"lp-catch\">".esc_html(get_post_meta($post->ID, 'catch_text', true))."</p>" : ""; ?>
				<p><a href="#main"><img src="<?php bloginfo('template_url'); ?>/images/common/icon-arw-full-screen.png" alt="メインへ" width="48" height="48"></a></p>
			</div>
		</div>
	</header>
<?php
	if (strpos($gnav, "no-gn") === false) { ?>
	<!--▼グローバルナビ-->
	<nav class="global-nav">
		<div class="global-nav-in">
			<div class="global-nav-panel"></div>
			<ul id="menu">
			<?php echo get_globalmenu_wmc('top_menu'); ?>
			</ul>
		</div>
	</nav>
	<!--▲グローバルナビ-->
<?php }

// それ以外の場合
} else { ?>
	<body <?php body_class($gnav); ?>>
	<?php echo do_shortcode(the_wmc('body_text'))."\n"; ?>
	<div class="container">
		<header id="top" class="site-header <?php if (is_singular(LP_DIR)) { echo 'normal-screen'; } ?>">	
		<div class="site-header-in">
			<div class="site-header-conts">
<?php if (is_singular(LP_DIR)) {
				echo '<h1 class="site-title">';
				echo get_h1_wmc();
				echo "</h1>\n";
				echo (get_post_meta($post->ID, 'catch_text', true)) ? "<p class=\"lp-catch\">".esc_html(get_post_meta($post->ID, 'catch_text', true))."</p>\n" : ""; ?>
<?php } else if (is_front_page()) { ?>
				<h1 class="site-title"><a href="<?php bloginfo('url'); ?>"><?php echo (the_wmc('site_logo') != "") ? "<img src=\"".the_wmc('site_logo')."\" alt=\"".esc_html(get_bloginfo('name'))."\" />": esc_html(get_bloginfo('name'));  ?></a></h1>
<?php } else { ?>
				<p class="site-title"><a href="<?php bloginfo('url'); ?>"><?php echo (the_wmc('site_logo') != "") ? "<img src=\"".the_wmc('site_logo')."\" alt=\"".get_bloginfo('name')."\" />": get_bloginfo('name');  ?></a></p>
<?php } ?>
			</div>
		</div>
<?php

	if ($gnav == "") {	?>
		<!--▼グローバルナビ-->
		<nav class="global-nav">
			<div class="global-nav-in">
				<div class="global-nav-panel"><span class="btn-global-nav icon-gn-menu">メニュー</span></div>
				<ul id="menu">
				<?php echo get_globalmenu_wmc('top_menu'); ?>
				</ul>
			</div>
		</nav>
		<!--▲グローバルナビ-->
	<?php }
	
	if (is_front_page() && (!isset($_GET['post_type']) || $_GET['post_type'] == "")) { ?>
		<div class="main-image">
<?php	$mainimage = the_wmc("mainimage");
		if (!empty($mainimage)) { 
			if (the_wmc("mainimage_posision") == "image") { ?>
				<div class="main-image-in<?php if (the_wmc('mainimage_wide') == "y") { ?> wide<?php } ?>">
				<img  class="header-image" src="<?php echo esc_url( $mainimage ); ?>" alt="<?php echo esc_html(the_wmc("mainimage_alt")); ?>" />
				</div>
<?php } else { ?>
				<div class="main-image-in-text<?php if (the_wmc('mainimage_wide') == "y") { ?> wide<?php } ?>" style="background-image: url(<?php echo esc_url( $mainimage ); ?>);">
					<div class="main-image-in-text-cont">
					<?php if (the_wmc("main_catchcopy") != "") { ?><p class="main-copy"><?php echo esc_html(the_wmc("main_catchcopy")); ?></p><?php } ?>
					
					<?php if (the_wmc("sub_catchcopy") != "") { ?><p class="sub-copy"><?php echo esc_html(the_wmc("sub_catchcopy")); ?></p><?php } ?>
					
					<?php if (the_wmc("free_catchcopy") != "") { echo "<div class=\"main-image-in-text-box\">\n".the_wmc("free_catchcopy")."\n</div>\n"; } ?>
					
					</div>
				</div>
<?php }
		} else if (the_wmc("mainimage_posision") != "image") { ?>
				<div class="main-image-in-text<?php if (the_wmc('mainimage_wide') == "y") { ?> wide<?php } ?>" style="background-color: #<?php echo the_wmc('mainimage_bg_color'); ?>;">
					<div class="main-image-in-text-cont">
					<?php if (the_wmc("main_catchcopy") != "") { ?><p class="main-copy"><?php echo esc_html(the_wmc("main_catchcopy")); ?></p><?php } ?>
					
					<?php if (the_wmc("sub_catchcopy") != "") { ?><p class="sub-copy"><?php echo esc_html(the_wmc("sub_catchcopy")); ?></p><?php } ?>
					
					<?php if (the_wmc("free_catchcopy") != "") { echo "<div class=\"main-image-in-text-box\">\n".the_wmc("free_catchcopy")."\n</div>\n"; } ?>
					</div>
				</div>
<?php } ?>
		</div>

<?php } ?>
	</header>
<?php
}
?>
