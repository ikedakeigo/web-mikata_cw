<!--▼サイトフッター-->
<footer class="site-footer">
	<div class="site-footer-in">
	<div class="site-footer-conts">
<?php	$footer = get_globalmenu_wmc('footer_menu');
if ( $footer != "") { ?>
		<ul class="site-footer-nav"><?php	echo $footer; ?></ul>
<?php }
$comment = the_wmc('footer_comment');
if ($comment != "") { ?>
		<div class="site-footer-conts-area"><?php echo do_shortcode(apply_filters( 'the_content', stripslashes(the_wmc('footer_comment')), 10 )); ?></div>
<?php } ?>
	</div>
	</div>
	<div class="copyright">
		<p><small>Copyright (C) <?php echo date("Y"); ?> <?php bloginfo('name'); ?> <span>All Rights Reserved.</span></small></p>
	</div>

</footer>
<!--▲サイトフッター-->


<!--▼ページトップ-->
<p class="page-top"><a href="#top"><img class="over" src="<?php bloginfo('template_url'); ?>/images/common/page-top_off.png" width="40" height="40" alt="ページの先頭へ"></a></p>
<!--▲ページトップ-->

<!--container-->

<?php wp_footer(); ?>
	
<?php echo do_shortcode(the_wmc('body_bottom_text'))."\n"; ?>
