<?php
 

class WCP_Career_View {

	public static function build_html() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/list_data.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}

	public static function list_result() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/list_result.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	
	public static function career_current_recruitments() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/current_recruitments.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	public static function career_in_progress_recruitments() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/career_in_progress_recruitments.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	public static function career_close_recruitments() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/career_close_recruitments.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
}