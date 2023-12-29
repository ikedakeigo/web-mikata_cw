(function($) {

	$(function() {
		$("#edit-slug-box").remove();
		$("#submitdiv>h3>span").html("共通コンテンツ");
		$("#misc-publishing-actions").hide();
		$("#publish").val('保存する');
	
		$(".view").remove();
		var pub = jQuery(".publish").html();
		if (pub != null) {
			$(".publish").html(pub.replace(/公開済み/g,'登録済み'));
		}
		$('.date').each(function(){
			var txt = jQuery(this).html();
			if (txt != null) {
				$(this).html(txt.replace(/<br>公開済み/g,''));
			}
		});
	})


	$('#picker').colpick({
		layout:'hex',
		submit:0,
		colorScheme:'dark',
		onChange:function(hsb,hex,rgb,el,bySetColor) {
			$(el).css('border-color','#'+hex);
			// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
			if(!bySetColor) $(el).val(hex);
		}
	}).keyup(function(){
		$(this).colpickSetColor(this.value);
	});

})(jQuery);