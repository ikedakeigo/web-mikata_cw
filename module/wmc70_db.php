<?php

$wmc_version = "7.0.4";

if (!get_option('wmc_version') or $wmc_version != get_option('wmc_version')) {
	CreateDB();
}


//---------------------------------------------------------------------
//データベースの初期化
//---------------------------------------------------------------------
function CreateDB() {
	
	global $wpdb;
	global $wmc_version;

	$version = get_option("wmc_version");
	if (($version != "") and ($version != $wmc_version)) {
		update_option("wmc70_before", $version);
	}
	if (empty($version) or $wmc_version != get_option('wmc_version')) {
		update_option("wmc_version", $wmc_version);
	}

	$table_alive = $wpdb->get_results("SHOW TABLES LIKE '".WMC_SET."'");
	if (!isset($table_alive) or count($table_alive) <= 0) {
		
		if ($wpdb->get_var("show tables like '".WMC_SET."'") != WMC_SET) {
			$char = defined("DB_CHARSET") ? DB_CHARSET : "utf8";
			$sql = "CREATE TABLE ".WMC_SET." (
								ks_id tinyint(1) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
								ks_group VARCHAR(32) NOT NULL,
								ks_sub_group VARCHAR(16),
								ks_sys_cont VARCHAR(32) NOT NULL,
								ks_view_cont VARCHAR(64) NOT NULL,
								ks_val TEXT,
								ks_def_val TEXT,
								ks_ext TEXT,
								ks_type enum('yn','int','text','textarea','richtext','column','meta_index','meta_follow','page2_index','menu','radio','check','image','include','custom','select','color','active') NOT NULL DEFAULT 'yn',
								ks_active ENUM('y','n') NOT NULL DEFAULT 'y',
								ks_sort tinyint(1) UNSIGNED NOT NULL
							) DEFAULT CHARSET = ".$char.";";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
	
			$top_image = get_template_directory_uri()."/images/main-image.png";
			
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('テンプレの設定（サイト内共通）', 'site_logo', 'ロゴ画像', '', '', 'image','1')";
			$results = $wpdb->query( $insert );		

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('テンプレの設定（サイト内共通）', 'mobile_layout', 'レスポンシブWebデザイン', 'y', 'y', 'yn','5')";
			$results = $wpdb->query( $insert );		
	
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('テンプレの設定（サイト内共通）', 'layout','サイトの基本レイアウト','col2','col2','column','9')";
			$results = $wpdb->query( $insert );
	
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('テンプレの設定（サイト内共通）', 'view_meta', 'メタキーワード・メタディスクリプションの表示', 'y', 'y', 'check','13')";
			$results = $wpdb->query( $insert );		
	
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('テンプレの設定（サイト内共通）', 'keyword', 'サイト共通のメタキーワード<br />（カンマ区切りの文字列）', '', '', 'text','17')";
			$results = $wpdb->query( $insert );		
	
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('テンプレの設定（サイト内共通）', 'view_site_title','下層ページのタイトルでのサイト名の表示','y','y','check','21')";
			$results = $wpdb->query( $insert );
			
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('テンプレの設定（サイト内共通）', 'pv_view','投稿ごとのPV数（ページ閲覧数）の表示','n','n','check','25')";
			$results = $wpdb->query( $insert );
			
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_ext, ks_type, ks_sort) VALUES ('テンプレの設定（サイト内共通）', 'view_side', 'サブコンテンツ（サイドバー）エリアの表示', 'y', 'y', '※1カラム時のみ有効','check','29')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_ext, ks_type, ks_sort) VALUES ('テンプレの設定（サイト内共通）', 'lp_dir', 'ランディングページ ディレクトリ名', 'lp', 'lp', '※ WordPressによるサーバへの書き込み権限が必要になります。権限が無い場合、「single-（ディレクトリ名）.php」 のファイルをFTPにて設置して下さい。\n例：ディレクトリ名を「landing」にした場合 → 「single-landing.php」のファイルとなります。', 'custom','31')";
			$results = $wpdb->query( $insert );

		
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('トップページ', 'top_layout','トップページのレイアウト','def','def','column','51')";
			$results = $wpdb->query( $insert );
	
			$title = get_bloginfo('name');
	
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_ext, ks_type, ks_sort) VALUES ('トップページ', 'top_title','トップページのタイトル','".$title."','".$title."','この項目を変更されても、サイトのタイトルは変更されません。<br />トップページのタイトルのみの変更になります。','text','55')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_ext, ks_type, ks_sort) VALUES ('トップページ', 'メイン画像', 'mainimage','メイン画像','".$top_image."','".$top_image."','トップのメイン画像を非表示にするには、画像URLを削除（空白に）して保存して下さい。','image','60')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_ext, ks_type, ks_sort) VALUES ('トップページ', 'メイン画像', 'mainimage_wide','メイン画像をウインドウ（ブラウザ）幅で表示','n','n','※チェックが入っていない場合は、コンテンツ幅で表示されます。','active','61')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_ext, ks_type, ks_sort) VALUES ('トップページ', 'メイン画像', 'mainimage_posision','メイン画像の表示タイプ','image','image','\'image:タグとして表示する\' \'background:背景画像として表示する\'', 'select','63')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_ext, ks_type, ks_sort) VALUES ('トップページ', 'メイン画像', 'mainimage_alt','メイン画像の代替テキスト','image','image','※この項目は「メイン画像の表示タイプ」を「タグとして表示する」を選択されている場合だけ適用されます。', 'text','64')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_ext, ks_type, ks_sort) VALUES ('トップページ', 'メイン画像', 'main_catchcopy','メイン画像のキャッチコピー','キャッチコピー。','キャッチコピー。','※この項目は「メイン画像の表示タイプ」を「背景画像として表示する」を選択されている場合だけ適用されます。', 'text','65')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_ext, ks_type, ks_sort) VALUES ('トップページ', 'メイン画像', 'sub_catchcopy','メイン画像のサブキャッチコピー','サブキャッチコピー','サブキャッチコピー。','※この項目は「メイン画像の表示タイプ」を「背景画像として表示する」を選択されている場合だけ適用されます。', 'text','66')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_ext, ks_type, ks_sort) VALUES ('トップページ', 'メイン画像', 'free_catchcopy','メイン画像の自由記述欄','','', '※この項目は「メイン画像の表示タイプ」を「背景画像として表示する」を選択されている場合だけ適用されます。','textarea','67')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_ext, ks_type, ks_sort) VALUES ('トップページ', 'メイン画像', 'mainimage_bg_color','メイン画像エリアの背景色','ffffff','ffffff', '※この項目は「メイン画像の表示タイプ」を「背景画像として表示する」を選択されていて、背景画像がない場合だけ適用されます。','color','68')";
			$results = $wpdb->query( $insert );


			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('トップページ', 'top_menu','グローバルメニューの表示','y','y','check','71')";
			$results = $wpdb->query( $insert );
		
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('トップページ', 'view_top_side', 'サブコンテンツ（サイドバー）エリアの表示', 'def', 'def','radio','75')";
			$results = $wpdb->query( $insert );
		
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('トップページ', '最新情報', 'new_info', '最新情報の表示','n','n','check','79')";
			$results = $wpdb->query( $insert );
		
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('トップページ', '最新情報', 'new_info_rows', '最新情報の表示件数','5','5','int','83')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sub_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_ext, ks_type, ks_sort) VALUES ('トップページ', '最新情報', 'new_info_ex_cat', '表示から除外をするカテゴリ','','', '※一覧から除外をしたいカテゴリのIDを、カンマ区切りで指定します。　例）3,7,18', 'text','85')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('トップページ', 'snd_page_index','2ページ目以降のインデックス','y','y','page2_index','87')";
			$results = $wpdb->query( $insert );
		
	
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('一覧ページ', 'list_category','カテゴリーページ','def','def','column','101')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('一覧ページ', 'list_category_index','インデックス','index','index','meta_index','105')";
			$results = $wpdb->query( $insert );
		
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('一覧ページ', 'list_tag','タグページ','def','def','column','109')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('一覧ページ', 'list_tag_index','インデックス','index','index','meta_index','113')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('一覧ページ', 'list_archive','年月日ページ','def','def','column','117')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('一覧ページ', 'list_archive_index','インデックス','noindex','noindex','meta_index','121')";
			$results = $wpdb->query( $insert );
						
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('一覧ページ', 'list_author','投稿者ページ','def','def','column','125')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('一覧ページ', 'list_author_index','インデックス','noindex','noindex','meta_index','129')";
			$results = $wpdb->query( $insert );
				
			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('一覧ページ', 'list_search','検索結果ページ','def','def','column','133')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('一覧ページ', 'list_search_index','インデックス','noindex','noindex','meta_index','137')";
			$results = $wpdb->query( $insert );

			$insert = "INSERT INTO ".WMC_SET." (ks_group, ks_sys_cont, ks_view_cont, ks_val, ks_def_val, ks_type, ks_sort) VALUES ('フッター', 'footer_comment', '自由記述欄（タグ可）', '', '', 'richtext','251')";
			$results = $wpdb->query( $insert );		

			update_option("wmc_version", $wmc_version);
		}
	
	
		// 過去のテーブルが存在するかどうかを確認
		$before_version = get_option("wmc70_before");

		if (!empty($before_version)) {
			switch ($before_version) {
				case "6.0":
					$table_name = $wpdb->prefix."wmc_setting";
					break;

				default:
					$table_name = $wpdb->prefix."wmc_setting".str_replace(".","",$before_version);
					break;
			}

			$old_data = $wpdb->get_results("SELECT ks_sys_cont, ks_val FROM ".$table_name);
			foreach ( $old_data as $line) {
				$update = "UPDATE ".WMC_SET." SET ks_val='".$line->ks_val."' WHERE ks_sys_cont='".$line->ks_sys_cont."'";
				$results = @$wpdb->query( $update );
			}
		}
	}


	$pv_table_alive = $wpdb->get_results("SHOW TABLES LIKE ".$wpdb->prefix."wmc_pv");
	if (!isset($pv_table_alive) or count($pv_table_alive) <= 0) {
		
		$char = defined("DB_CHARSET") ? DB_CHARSET : "utf8";
		$pv_sql = "CREATE TABLE ".$wpdb->prefix."wmc_pv (
			`pv_dates` char(10) collate utf8_bin NOT NULL,
			`post_id` int(4) unsigned NOT NULL,
			`pv_count` int(4) unsigned NOT NULL default '0',
			PRIMARY KEY  (`pv_dates`,`post_id`)
		) ENGINE=InnoDB DEFAULT CHARSET = ".$char.";";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($pv_sql);
	}
}
?>