// カウント数を表示するエリアを管理画面に追加
(function($) {
	$(function() {
		if ($("#title")[0]) {
			$("#titlewrap").after('<div class="title_count_area"><strong class="title_count">タイトル文字数：</strong><span id="title_count">'+getTitleCount()+'文字</span></div>');

			$("#title").keyup(function() {
				$("#title_count").html(getTitleCount()+'文字');
			});
		}
	});
	
	// タイトルに入っている文字数を取得
	function getTitleCount() {
		if ($("#title")[0]) return $("#title").val().length;
	}	
})(jQuery);