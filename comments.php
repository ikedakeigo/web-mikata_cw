<?php


// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.'); ?></p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	<h2 id="comments" class="comment-form-title">
		<?php
			if ( 1 == get_comments_number() ) {
				/* translators: %s: post title */
				printf( __( 'One response to %s' ),  '&#8220;' . get_the_title() . '&#8221;' );
			} else {
				/* translators: 1: number of comments, 2: post title */
				printf( _n( '%1$s response to %2$s', '%1$s responses to %2$s', get_comments_number() ),
					number_format_i18n( get_comments_number() ),  '&#8220;' . get_the_title() . '&#8221;' );
			}
		?>
	</h2>

	<ol class="commentlist">
	<?php wp_list_comments( array(
		'style'       => 'ol',
		'format'      => 'html5'
	) ); ?>
	</ol>

<?php if (get_previous_comments_link() != "" || get_next_comments_link() != "") { ?>
	<div class="page-nav-bf">
		<div class="page-nav-prev"><?php previous_comments_link('古いコメント') ?></div>
		<div class="page-nav-next"><?php next_comments_link('新しいコメント') ?></div>
	</div>
<?php } ?>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<!--<p class="nocomments"><?php _e('Comments are closed.'); ?></p>-->

	<?php endif; ?>
<?php endif; ?>

<?php if ( comments_open() ) : ?>

<div id="respond">

<h3><?php comment_form_title( __('Leave a Reply'), __('Leave a Reply to %s' ) ); ?></h3>

<div id="cancel-comment-reply">
	<small><?php cancel_comment_reply_link() ?></small>
</div>

<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url( get_permalink() )); ?></p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( is_user_logged_in() ) : ?>

<p><?php printf(__('Logged in as <a href="%1$s">%2$s</a>.'), get_option('siteurl') . '/wp-admin/profile.php', $user_identity); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php esc_attr_e('Log out of this account'); ?>"><?php _e('Log out &raquo;'); ?></a></p>

<?php else : ?>

<div class="comment-form-author">
<p><label for="author"><small><?php _e('Name'); ?> <span class="required"><?php if ($req) _e('(required)'); ?></span></small></label></p>
<p><input type="text" name="author" id="author" class="w50" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?>></p>
</div>

<div class="comment-form-mail">
<p><label for="email"><small><?php _e('Mail (will not be published)'); ?> <span class="required"><?php if ($req) _e('(required)'); ?></span></small></label></p>
<p><input type="text" name="email" id="email" class="w50" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?>></p>
</div>

<div class="comment-form-url">
<p><label for="url"><small><?php _e('Website'); ?></small></label></p>
<p><input type="text" name="url" id="url" class="w50" value="<?php echo  esc_attr($comment_author_url); ?>" size="22" tabindex="3"></p>
</div>

<?php endif; ?>

<!--<p><small><?php printf(__('<strong>XHTML:</strong> You can use these tags: <code>%s</code>'), allowed_tags()); ?></small></p>-->

<p><textarea name="comment" id="comment" class="w90" cols="58" rows="10" tabindex="4"></textarea></p>

<p class="al-c"><button name="submit" type="submit" id="submit" class="btn btn-form01" tabindex="5" value="<?php esc_attr_e('Submit Comment'); ?>" ><span><?php esc_attr_e('Submit Comment'); ?></span></button>
<?php comment_id_fields(); ?>
</p>
<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>
