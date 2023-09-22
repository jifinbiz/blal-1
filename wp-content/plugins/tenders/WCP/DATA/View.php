<?php
 

class WCP_Tenders_View {

	public static function build_html() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/list_data.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	public static function active_tender_view() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/active_tender_view.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}

	public static function archived_tender_view() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/archived_tender_view.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}

	public static function tender_download_view() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/tender_download_view.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	
}