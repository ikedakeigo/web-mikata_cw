<?php get_header(); ?>

<div class="all-body">
<div class="all-body-in">



<?php if (!is_front_page()) the_breadcrumbs(); ?>
	
	<!--▼メインコンテンツ-->
	<main>
	<div class="top-conts">
<?php if (is_front_page() && the_wmc('social_top_view') == "y") { ?>
	<div class="float-area">
		<?php get_template_part('social-button2') ?>
	</div>
<?php }

	// 最新情報
	if (is_front_page() && the_wmc('new_info') == "y") {
		$the_page = pageNumber();
		if ($the_page['now_page'] <= 1) echo "<section class=\"section-border\">\n<div class=\"section-in \">\n".do_shortcode('[newpost rows=5, social=1 show_date="default"]')."\n</div>\n</section>\n";
	}

	while (have_posts()) : the_post(); ?>

		<!--記事-->
		<article id="post-<?php the_ID(); ?>" <?php post_class('section-border'); ?>>
			<div class="section-in">

			<header class="article-header">
<?php if (is_front_page()) { ?>
				<h2 class="section-title"><?php echo esc_attr(get_the_title(get_the_ID())); ?></h2>
<?php } else { ?>
				<h1 class="section-title"><?php h1_wmc(); ?></h1>
<?php } ?>
			</header>

			<div class="article-body">
			<?php
			 the_content();

			wp_link_pages( array(
				'before'      => '<div class="link-pages"><span class="link-pages-cap">'. __('Pages','wmc').':</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
			?>
			</div><!--article-body-->

			<?php if (the_wmc('social_page_view') == "y") get_template_part('social-button2'); ?>

			<?php if(get_the_tags()){ ?>
			<div class="post-tag">
			<p>タグ：<?php the_tags('', ', '); ?></p>
			</div>
			<?php } ?>

			<?php relation_wmc(); ?>

			</div><!--section-in-->
		</article><!--記事-->

<?php endwhile; ?>

	</div><!--top-conts-->
	</main>
	<!--▲メインコンテンツ-->

	<?php get_sidebar(); ?>

</div>
</div>

<?php get_footer(); ?>