<?php
$script = "
<script>
(function($) {
	$(function() {
		var ww = Number(window.parent.screen.width * 0.4);
		var wh = Number(window.parent.screen.height * 0.65);
		var wl= Number((screen.width-ww)/2);
		var wt= 45;
		$('#timestampdiv').after('<div id=\"keni_seo_link\">&nbsp;<a href=\"".get_template_directory_uri()."/module/wmc_seo_check_view.php\" class=\"popup\">チェックシート</a></div>');
		$(\".popup\").click(function(){
			window.open(this.href,\"seocheck\",\"width=\"+ww+\",height=\"+wh+\",left=\"+wl+\",top=\"+wt+\",resizable=yes,scrollbars=yes\");
			return false;
		});
	});
})(jQuery);
</script>";


echo $script;
?>