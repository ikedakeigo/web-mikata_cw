<?php
/*
Plugin Name: gatespace add extra fields for category
Plugin URI: https://github.com/gatespace/gs-add-extra-fields-for-category
Description: カテゴリーにテキスト色と背景色のカスタムフィールドを追加します。入力はカラーピッカーで行えます。また、その場で配色を確認できます。
Version: 0.1
Author: gatespace
Author URI: http://gatespace.wordpress.com/
License: GPL2

参照：http://www.webopixel.net/wordpress/436.html
http://ja.forums.wordpress.org/topic/5015
*/


/*
 * custom fields add category edit form
 */

function gs_add_extra_fields_for_category_edit_form_fields( $term ) {
	$term_id   = $term->term_id;
	$term_name = ( !empty( $term->name ) ) ? $term->name : __("Name");
	$term_meta = get_option( "term_$term_id" );
	$term_meta['textcolor'] = ( !empty( $term_meta['textcolor'] ) ) ? $term_meta['textcolor'] : "#FFF";
	$term_meta['bgcolor']   = ( !empty( $term_meta['bgcolor'] ) ) ? $term_meta['bgcolor'] : "#666";
?>
<tr>
	<th><label for="textcolor"><?php _e( "Text Color" ); ?></label></th>
	<td><input type="text" name="term_meta[textcolor]" id="textcolor" class="regular-text colordata" value="<?php echo esc_attr( $term_meta['textcolor'] ); ?>" /></td>
</tr>
<tr>
	<th><label for="bgcolor"><?php _e( "Background Color" ); ?></label></th>
	<td><input type="text" name="term_meta[bgcolor]" id="bgcolor" class="regular-text colordata" value="<?php echo esc_attr( $term_meta['bgcolor'] ); ?>" /><br>
	<div id="picker" style="float :left;"></div>
	<div id="color-sample-bgcolor" style="float: left; margin-left: 20px; margin-top: 10px; padding: 10px; background-color: <?php echo esc_attr( $term_meta['bgcolor'] ); ?>; color: <?php echo esc_attr( $term_meta['textcolor'] ); ?>"><?php echo esc_html($term_name); ?></div></td>
</tr>
<?php
	// nonce
	echo '<input type="hidden" name="gs_add_extra_fields_for_category" id="gs_add_extra_fields_for_category" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
}

add_action( 'category_edit_form_fields', 'gs_add_extra_fields_for_category_edit_form_fields' );
/*
 * custom fields add category add form
 */
function gs_add_extra_fields_for_category_add_form_fields( $term ) {
	if (isset($term->term_id)) {
		$term_id   = $term->term_id;
		$term_name = ( !empty( $term->name ) ) ? $term->name : __("Name");
		$term_meta = get_option( "term_$term_id" );
	}
	$term_meta['textcolor'] = ( !empty( $term_meta['textcolor'] ) ) ? $term_meta['textcolor'] : "#FFF";
	$term_meta['bgcolor']   = ( !empty( $term_meta['bgcolor'] ) ) ? $term_meta['bgcolor'] : "#666";
?>
<div class="form-field" style="margin-bottom:230px;">
	<label for="textcolor"><?php _e( "Text Color" ); ?></label>
	<input type="text" name="term_meta[textcolor]" id="textcolor" class="regular-text colordata" value="<?php echo esc_attr( $term_meta['textcolor'] ); ?>" /><br>
	<label for="bgcolor"><?php _e( "Background Color" ); ?></label>
	<input type="text" name="term_meta[bgcolor]" id="bgcolor" class="regular-text colordata" value="<?php echo esc_attr( $term_meta['bgcolor'] ); ?>" /><br>
	<div id="picker" style="float :left;"></div>
	<div id="color-sample-bgcolor" style="float: left; margin-left: 20px; margin-top: 10px; padding: 10px; background-color: <?php echo esc_attr( $term_meta['bgcolor'] ); ?>; color: <?php echo esc_attr( $term_meta['textcolor'] ); ?>">カテゴリー名</div>
</div>
<br style="clear: both;">
<?php
	// nonce
	echo '<input type="hidden" name="gs_add_extra_fields_for_category" id="gs_add_extra_fields_for_category" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
}
add_action( 'category_add_form_fields', 'gs_add_extra_fields_for_category_add_form_fields' );
/*
 * add color picker css
 */
function sample_load_color_picker_style() {
	wp_enqueue_style( 'farbtastic' );
}
add_action('admin_print_styles-edit-tags.php', 'sample_load_color_picker_style');

/*
 * add color picker script
 */
function my_admin_print_scripts() {
	wp_enqueue_script( 'farbtastic' );
}
add_action('admin_print_scripts-edit-tags.php', 'my_admin_print_scripts');


function my_admin_print_scriptshoge() {
?>
<script type="text/javascript">
/* <![CDATA[ */
(function($){

	function fntextcolor(color) {
		$('#textcolor').val(color);
		$('#color-sample-bgcolor').css({color: color});
	}
	function fnbgcolor(color) {
		$('#bgcolor').val(color);
		$('#color-sample-bgcolor').css({backgroundColor: color});
	}

	$(document).ready(function() {

		var f = $.farbtastic('#picker');

		$('#textcolor')
			.each( function () {
				f.linkTo( function(color){
					fntextcolor(color);
				});
			})
			.focus( function () {
				f.linkTo( function(color){
					fntextcolor(color);
				});
			});
		$('#bgcolor')
			.each( function () {
				f.linkTo( function(color){
					fnbgcolor(color);
				});
			})
			.focus( function () {
				f.linkTo( function(color){
					fnbgcolor(color);
				});
			});


		$('.colordata').keyup(function() {
			var _hex = $(this).val();
			var hex = _hex;
			if ( hex[0] != '#' )
				hex = '#' + hex;
			hex = hex.replace(/[^#a-fA-F0-9]+/, '');
			if ( hex != _hex )
				$(this).val(hex);
			if ( hex.length == 4 || hex.length == 7 )
				pickColor( hex );
		});
	});
})(jQuery);
/* ]]> */
</script>
<?php
}
add_action("admin_head-edit-tags.php", 'my_admin_print_scriptshoge');


/*
 * save custom fields data
 */
function save_extra_category_fileds( $term_id ) {
	// wp_verify_nonce
	if (isset($_POST['gs_add_extra_fields_for_category']) and !wp_verify_nonce( $_POST['gs_add_extra_fields_for_category'], plugin_basename(__FILE__) ))
		return $term_id;

	// Check capabilities
	if ( !current_user_can( 'manage_categories', $term_id ) )
		return $term_id;

	// Save data
	if ( isset( $_POST['term_meta'] ) ) {
		$term_meta = get_option( "tax_$term_id" );
		$term_keys = array_keys( $_POST['term_meta'] );
			foreach ( $term_keys as $key ) {
				if ( isset( $_POST['term_meta'][$key] ) ) {
					$term_meta[$key] = $_POST['term_meta'][$key];
				}
			}
		update_option( "term_$term_id", $term_meta );
	}
}
add_action ( 'created_term', 'save_extra_category_fileds');
add_action ( 'edited_term', 'save_extra_category_fileds');
?>