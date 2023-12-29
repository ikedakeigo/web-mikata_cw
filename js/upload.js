jQuery(function() {
	(function($) {
		$('.wmc_upload_image_button').click(function() {
			var upload_image_button_no = $(this).attr("id");
			var id = upload_image_button_no.match(/\d+$/);
			if (id > 0) {
				formfield =$('#wmc_upload_image_'+id).attr('name');
				tb_show('', 'media-upload.php?type=image&post_id=&TB_iframe=true');

				window.original_send_to_editor = window.send_to_editor;

				window.send_to_editor = function(html) {				
					if (formfield) {
						imgurl = $('img',html).attr('src');
						$('#wmc_upload_image_'+id).val(imgurl);
						$('#wmc_img_'+id).html('<img src="'+imgurl+'" />');
						tb_remove();
					}
					window.send_to_editor = window.original_send_to_editor;
				}
				return false;
			}
		});
	})(jQuery);
})


