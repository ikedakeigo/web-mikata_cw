<article class="section-border">
	<div class="section-bor-lab">
		<header class="article-header">
			<!-- サムネイル画像 -->
			<?php
				if (get_the_post_thumbnail()) {
					echo '<div class="eye-catch">
							   <div class="eye-catch-wrapper">'
									.get_the_post_thumbnail( $post->ID,'full' ).
								"</div></div>\n";
				} else {
					echo '<div class="eye-catch">
							<div class="eye-catch-wrapper">
								<img src="/images/common/noimage_511.png" alt="" /></div></div>';
				}
			?>
		</header>
		<div class="article-body">
			<!-- 投稿日付 -->
			<p class="post-date"><time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
			
			<!-- PV数 -->
			<?php if (the_wmc('pv_view') == "y" && preg_match("/^[0-9]+$/",getViewPV(get_the_ID()))) { ?><p class="post-pv"><?php viewPV(); ?>PV</p><?php } ?>

			<!-- カテゴリ -->
			<?php {
				$site_url = site_url();
				if (!preg_match("/\/$/", $site_url)) $site_url .= "/";
					$category_data = get_category_wmc();
					if (!empty($category_data)) echo "<div class=\"post-cat\" style=\"z-index: 3; position: relative;\">\n".$category_data."\n</div>\n";
				} ?>

			<?php $post_type = get_query_var('post_type');
				if ((!empty($post_type) && the_wmc('social_archive_view') == "y") || (is_front_page() && empty($post_type) && the_wmc('social_top_archive_view') == "y") || (!is_front_page() && the_wmc('social_archive_view') == "y")) get_template_part('social-button'); ?>

			<!-- タイトル -->
			<h2 class="section-title">
				<a href="<?php the_permalink() ?>" class="stretched-link" title="<?php the_title_attribute(); ?>">
				<?php
					$title = get_the_title();
					if (32 < mb_strlen ($title)) {
						$title = esc_html(mb_substr($title, 0, 32));
/*						echo "<p style=\"font-size: 1em; font-weight: 700;\">{$title}...</p>"; */
/*						echo "{$title}..."; */
						echo "{$title}...";
					} else {
						$title = esc_html(get_the_title());
/*						echo "<p style=\"font-size: 1em; font-weight: 700;\">{$title}</p>"; */
/*						echo $title; */
						echo "<p style=\"font-size: 1em; font-weight: 700;\">{$title}</p>";
					}
				?>
				
				</a></h2>
		</div>
	</div>
</article>
