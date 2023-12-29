<?php get_header(); ?>

<div class="all-body">
<div class="all-body-in">
	
	<!--▼メインコンテンツ-->
	<main id="main">
	<article id="post-<?php the_ID(); ?>" <?php post_class('top-conts'); ?>>

<?php while (have_posts()) : the_post(); ?>

<?php $content = get_the_content();
if (substr_count($content, "[wide]") <= 0 && substr_count($content, "[normal]") <= 0) { ?>
<div class="section-border">
<div class="section-bor-lab">
<?php the_content(); ?>
</div>
</div>
<?php } else {
	the_content();
} ?>
<?php endwhile; ?>

	</article><!--top-conts-->
	</main>
	<!--▲メインコンテンツ-->

	<?php get_sidebar(); ?>

</div>
</div>

<?php get_footer(); ?>