<?php get_header(); ?>

<div class="all-body">
<div class="all-body-in">

<!--▼パン屑ナビ-->
<?php the_breadcrumbs(); ?>
<!--▲パン屑ナビ-->

	<!--▼メインコンテンツ-->
	<main>
	<div class="top-conts">

		<section class="section-border">
			<div class="section-bor-lab">

			<h1 class="section-title"><?php _e( 'Sorry, but you are looking for something that isn&#8217;t here.', 'wmc' ); ?></h1>
			<div class="contents">
			<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'wmc' ); ?></p>
			<p><?php get_search_form(); ?></p>
			</div>

			</div><!--section-in-->
		</section><!--記事-->

		<div class="float-area">
		<?php do_shortcode('[newpost rows=5, social=1 show_date="default"]'); ?>
		</div>

		<!-- 最新記事一覧 -->
		<div class="home">
			<div class="top-conts" style="width: 100%; float: none;">
				<div class="top-404-title">最新記事一覧</div>

				<?php
					// 検索条件
					$args = array('posts_per_page' => 4);

					// 記事取得
					$posts = get_posts( $args );

					// ループの開始
					foreach ( $posts as $post ):

						// 投稿一覧
						get_template_part('cont');

					endforeach;

					// 直前のクエリを復元する
					wp_reset_postdata();
				 ?>
			</div>
		</div>
		<!-- 最新記事一覧 -->
				
	</div><!--top-conts-->

	</main>
	<!--▲メインコンテンツ-->

	<?php get_sidebar(); ?>

</div>
</div>

<?php get_footer(); ?>