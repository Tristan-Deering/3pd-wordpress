<?php

namespace WPGMZA\Integration;

/**
 * This module integrates the plugin with the Gutenberg editor
 */
class Gutenberg extends \WPGMZA\Factory
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{
		global $wpgmza;
		
		add_action('enqueue_block_assets', array(
			$this,
			'onEnqueueBlockAssets'
		));
		
		add_action('init', array(
			$this,
			'onInit'
		));
		
		if(function_exists('register_block_type'))
			register_block_type('gutenberg-wpgmza/block', array(
				'render_callback' => array(
					$this,
					'onRender'
				)
			));
	}
	
	/**
	 * Enqueues assets to be used with the Gutenberg editor
	 */
	public function onEnqueueBlockAssets()
	{
		global $wpgmza;
		
		if(!is_admin())
			return;
		
		$wpgmza->loadScripts();
		
		wp_enqueue_style(
			'wpgmza-gutenberg-integration', 
			plugin_dir_url(WPGMZA_FILE) . 'css/gutenberg.css', 
			'', 
			WPGMZA_VERSION
		);
	}
	
	/**
	 * Called on the WordPress init action. This function strips out JS module generated by the babel compiler, for browser compatibility.
	 */
	public function onInit()
	{
		global $wpgmza;
		
		if(!$wpgmza->isInDeveloperMode())
			return;
		
		// NB: Commented out, false positives were causing this file to be wiped
		
		// Strip out JS module code for browser compatibility
		/*$filename = plugin_dir_path(WPGMZA_FILE) . 'js/v8/3rd-party-integration/gutenberg/dist/gutenberg.js';
		
		$contents = file_get_contents($filename);
		
		$contents = preg_replace('/Object\.defineProperty\(exports.+?;/s', '', $contents);
		
		$contents = preg_replace('/exports\.default = /', '', $contents);
		
		if(empty($contents))
			throw new \Exception('Gutenberg module would be blank');
		
		file_put_contents($filename, $contents);*/
	}
	
	/**
	 * Called to render the plugins Gutenberg block front end.
	 * @param mixed[] $atts An array of attributes passed in from the editor.
	 */
	public function onRender($attr)
	{
		extract($attr);
		
		return '[wpgmza id="1"]';
	}
}