<?php
 

class WCP_downpdf_View {

	function build_html() {
		
		
		global $wpdb;
		
		$t = new \stdclass();
		
		ob_start();

		include(dirname(__FILE__) . "/html/list_data.php");

		$s = ob_get_contents();

		ob_end_clean();
		return $s;
	}
	
}