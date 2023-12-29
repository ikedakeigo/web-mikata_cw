<?php get_header(); ?>

<div class="main-body">
<div class="main-body-in">

<!--▼パン屑ナビ-->
<?php the_breadcrumbs(); ?>
<!--▲パン屑ナビ-->

	<!--▼メインコンテンツ-->
	<main>
	<div class="top-conts">
	
		<h1 class="archive-title"><?php archive_title_wmc(); ?></h1>

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

	<?php get_sidebar(); ?>

</div>
</div>