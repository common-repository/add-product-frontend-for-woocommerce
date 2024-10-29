<?php
namespace AddProductFrontendForWooCommerce;

if(!is_admin())
	return;

global $pagenow;

if($pagenow != "plugins.php")
	return;

if(defined('APFFW_DEACTIVATE_FEEDBACK_FORM_INCLUDED'))
	return;
define('APFFW_DEACTIVATE_FEEDBACK_FORM_INCLUDED', true);

add_action('admin_enqueue_scripts', function() {
	
	// Enqueue scripts
	wp_enqueue_script('remodal', plugin_dir_url(__FILE__) . 'remodal.min.js');
	wp_enqueue_style('remodal', plugin_dir_url(__FILE__) . 'remodal.css');
	wp_enqueue_style('remodal-default-theme', plugin_dir_url(__FILE__) . 'remodal-default-theme.css');
	
	wp_enqueue_script('codecabin-deactivate-feedback-form', plugin_dir_url(__FILE__) . 'deactivate-feedback-form.js');
	wp_enqueue_style('codecabin-deactivate-feedback-form', plugin_dir_url(__FILE__) . 'deactivate-feedback-form.css');
	
	// Localized strings
	wp_localize_script('codecabin-deactivate-feedback-form', 'codecabin_deactivate_feedback_form_strings', array(
		'quick_feedback'			=> __('Quick Feedback', 'bytes_product_frontend'),
		'foreword'					=> __('If you would be kind enough, please tell us why you\'re deactivating?', 'bytes_product_frontend'),
		'better_plugins_name'		=> __('Please tell us which plugin?', 'bytes_product_frontend'),
		'please_tell_us'			=> __('Please tell us the reason so we can improve the plugin', 'bytes_product_frontend'),
		'do_not_attach_email'		=> __('Do not send my e-mail address with this feedback', 'bytes_product_frontend'),
		
		'brief_description'			=> __('Please give us any feedback that could help us improve', 'bytes_product_frontend'),
		
		'cancel'					=> __('Cancel', 'bytes_product_frontend'),
		'skip_and_deactivate'		=> __('Skip &amp; Deactivate', 'bytes_product_frontend'),
		'submit_and_deactivate'		=> __('Submit &amp; Deactivate', 'bytes_product_frontend'),
		'please_wait'				=> __('Please wait', 'bytes_product_frontend'),
		'thank_you'					=> __('Thank you!', 'bytes_product_frontend'),
		'deactivate_ajax_url' => admin_url( 'admin-ajax.php' ),
	));
	
	// Plugins
	$plugins = apply_filters('codecabin_deactivate_feedback_form_plugins', array());
	
	// Reasons
	$defaultReasons = array(
		'suddenly-stopped-working'	=> __('The plugin suddenly stopped working', 'bytes_product_frontend'),
		'plugin-broke-site'			=> __('The plugin broke my site', 'bytes_product_frontend'),
		'no-longer-needed'			=> __('I don\'t need this plugin any more', 'bytes_product_frontend'),
		'found-better-plugin'		=> __('I found a better plugin', 'bytes_product_frontend'),
		'temporary-deactivation'	=> __('It\'s a temporary deactivation, I\'m troubleshooting', 'bytes_product_frontend'),
		'other'						=> __('Other', 'bytes_product_frontend')
	);
	
	foreach($plugins as $plugin)
	{
		$plugin->reasons = apply_filters('codecabin_deactivate_feedback_form_reasons', $defaultReasons, $plugin);
	}
	
	// Send plugin data
	wp_localize_script('codecabin-deactivate-feedback-form', 'codecabin_deactivate_feedback_form_plugins', $plugins);
});

/**
 * Hook for adding plugins, pass an array of objects in the following format:
 *  'slug'		=> 'plugin-slug'
 *  'version'	=> 'plugin-version'
 * @return array The plugins in the format described above
 */
add_filter('codecabin_deactivate_feedback_form_plugins', function($plugins) {
	return $plugins;
});