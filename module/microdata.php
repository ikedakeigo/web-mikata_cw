<?php


function getMicroCodeType() {

	if (is_front_page() && is_home()) {
		$type = "Blog";
	} else if (is_front_page()) {
		$type = "WebPage";
	} else if (is_singular(LP_DIR)) {
		$type = "WebPage";
	} else if (is_page()) {
		$type = "WebPage";
	} else if (is_attachment()) {
		$type = "WebPage";
	} else if (is_singular()) {
		$type = "Article";
	} else {
		$type = "Blog";
	}
	
	return $type;
}	
?>