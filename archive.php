<?php get_header(); ?>

<div class="all-body">
<div class="all-body-in">

<!--▼パン屑ナビ-->
<?php the_breadcrumbs(); ?>
<!--▲パン屑ナビ-->

	<div class="home">
		
	<!--▼メインコンテンツ-->
	<main>
	<div class="top-conts">

		<h1 style="width: 100%" class="top-h2"><?php archive_title_wmc(); ?></h1>

<?php if (the_wmc('social_archive_view') == "y") {
		echo "<div class=\"float-area\">\n";
		get_template_part('social-button2');
		echo "</div>\n";
		}

		if (is_category() or is_tag()) {
			if (is_category()) {
				$content_araay = get_post_meta( get_query_var('cat'), "content");
			} else {
				$content_araay = get_post_meta( get_query_var('tag_id'), "content");
			}
			if (isset($content_araay[0]) and ($content_araay[0] != "") and (get_query_var('paged') <= 1)) {
				echo "<section class=\"section-border\">\n<div class=\"section-bor-lab \">\n";
				echo do_shortcode(apply_filters( 'the_content', stripslashes($content_araay[0]), 10 ));
				echo "\n</div>\n</div>\n";
			}
		}
		 ?>

		<?php
			if (have_posts()) {
				while (have_posts()) : the_post();

					// 投稿一覧
					get_template_part('cont');

				endwhile;
				pager_wmc();
			} ?>

	</div><!--top-conts-->
	</main>
	<!--▲メインコンテンツ-->

	</div>
	
<?php get_sidebar(); ?>

</div>
</div>

<?php get_footer(); ?>