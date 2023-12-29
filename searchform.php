<form method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
	<div class="search-box">
		<input class="search" type="text" placeholder="サイト内を検索" value="<?php if (!empty($_GET['s'])) echo esc_attr($_GET['s']); ?>" name="s" id="s"><button id="searchsubmit" class="btn-search">検索</button>
	</div>
</form>