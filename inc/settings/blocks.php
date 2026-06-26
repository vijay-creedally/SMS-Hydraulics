<?php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Block Manager Class
 * 
 * This class handles:
 * - Registration of custom ACF blocks
 * - Block field registration
 * - Block function loading
 * - Block overrides
 * 
 */
class SMS_Block_Manager {
	private $blocks = [];
	private $template_directory;
	private $allowed_blocks = [];

	public function __construct() {
		$this->template_directory = get_template_directory();
		$this->init();
	}

	private function init() {
		add_action('init', [$this, 'register_acf_blocks']);
		add_action('acf/init', [$this, 'register_block_fields']);
		add_action('init', [$this, 'register_block_overrides']);

		add_action( 'admin_init', function() {

			// Disable "Available to Install" block suggestions.
			remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
			remove_action( 'enqueue_block_editor_assets', 'gutenberg_enqueue_block_editor_assets_block_directory' );

		} );

		add_action( 'init', function() {
			// Disable core block patterns.
			remove_theme_support( 'core-block-patterns' );
		} );

		add_filter( 'should_load_remote_block_patterns', '__return_false' );

		// Add custom category
		add_filter('block_categories_all', function($categories) {
			// Add our custom category at the start
			array_unshift($categories, [
				'slug' => 'sms-blocks',
				'title' => 'SMS Blocks',
			]);

			// Return only our category
			return array_filter($categories, function($cat) {
				return $cat['slug'] === 'sms-blocks';
			});
		}, 10, 1);

		// Set block category for ACF blocks
		add_filter('acf/register_block_type_args', function($args) {
			$args['category'] = 'sms-blocks';
			return $args;
		}, 10, 1);

		$this->load_block_functions();
	}

	/**
	 * Get all block directories from the ACF blocks folder
	 *
	 * @return array Array of block directory paths
	 */
	private function get_blocks(): array {
		if (!empty($this->blocks)) {
			return $this->blocks;
		}

		$block_directories = [];
		$glob_path = $this->template_directory . '/blocks/acf/*';
		
		foreach (glob($glob_path, GLOB_ONLYDIR) as $dir) {
			$block_directories[] = $dir;
			// Store the block name for allowed blocks filtering
			$block_json = $dir . '/src/block.json';
			if (file_exists($block_json)) {
				$json_content = json_decode(file_get_contents($block_json), true);
				if (isset($json_content['name'])) {
					$this->allowed_blocks[] = $json_content['name'];
				}
			}
		}

		$this->blocks = $block_directories;
		return $this->blocks;
	}

	/**
	 * Register ACF blocks from block.json files
	 */
	public function register_acf_blocks() {
		$blocks = $this->get_blocks();
		
		foreach ($blocks as $block) {
			$json_path = $block . '/src/block.json';
			if (file_exists($json_path)) {
				register_block_type($json_path);
			}
		}
	}

	/**
	 * Register ACF fields for blocks
	 */
	public function register_block_fields() {
		$blocks = $this->get_blocks();
	

		foreach ($blocks as $block) {
			$fields_path = $block . '/src/fields.php';
			
			if (!file_exists($fields_path)) {
				continue;
			}
			$block_fields = require_once $fields_path;

			if ($block_fields) {
				add_action('acf/include_fields', function() use ($block_fields) {
					acf_add_local_field_group($block_fields->to_array());
				});
			}
		}
	}

	/**
	 * Load block-specific function files
	 */
	private function load_block_functions() {
		$blocks = $this->get_blocks();
		
		foreach ($blocks as $block) {
			$functions_path = $block . '/src/functions.php';
			
			if (file_exists($functions_path)) {
				require_once $functions_path;
			}
		}
	}

	/**
	 * Register block override files
	 */
	public function register_block_overrides() {
		$overrides_path = $this->template_directory . '/blocks/overrides/*';
		$overrides = glob($overrides_path);
		
		if (empty($overrides)) {
			return;
		}

		foreach ($overrides as $path) {
			if (file_exists($path)) {
				require_once $path;
			}
		}
	}
}

// Initialize the block manager
new SMS_Block_Manager();
