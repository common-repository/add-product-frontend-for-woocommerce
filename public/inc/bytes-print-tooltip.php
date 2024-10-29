<?php
if(!defined('ABSPATH')){
	exit;
}
/* *** function to print wp tooltip *** */
if(!function_exists('apffw_print_tooltip')){
	function apffw_print_tooltip($text = ''){
	    echo '<span class="bt-tooltip">?
	            <span class="bt-tooltiptext">'.esc_html( $text, 'bytes_product_frontend' ).'</span>
	          </span>';
	}
}
?>