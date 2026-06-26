<?php
/**
 * Theme Documentation
 *
 * Provides a documentation reader for theme README.md files
 */

// Prevent direct access
if (!defined('ABSPATH')) {
	exit;
}

class Theme_Documentation {
	private $theme_path;
	private $theme_name;
	private $docs_found = [];
	
	public function __construct() {
		$this->theme_path = get_template_directory();
		$theme_data = wp_get_theme();
		$this->theme_name = $theme_data->get('Name');
		
		// Initialize the plugin
		add_action('admin_menu', [$this, 'add_documentation_menu']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
		
		// Scan for documentation files
		$this->scan_documentation_files();
	}
	
	/**
	 * Scan theme directory for README.md files
	 */
	public function scan_documentation_files() {
		// Add main theme README
		if (file_exists($this->theme_path . '/THEME.md')) {
			$this->docs_found['theme'] = [
				'path' => $this->theme_path . '/THEME.md',
				'title' => 'Documentation',
				'url' => admin_url('admin.php?page=theme-documentation'),
				'type' => 'theme'
			];
		}
		
		// Scan blocks directory if it exists
		$blocks_path = $this->theme_path . '/blocks';
		if (is_dir($blocks_path)) {
			$block_dirs = glob($blocks_path . '/**/*', GLOB_ONLYDIR);
			
			foreach ($block_dirs as $block_dir) {
				$block_name = basename($block_dir);
				$readme_path = $block_dir . '/README.md';

				if (file_exists($readme_path)) {
					$this->docs_found[$block_name] = [
						'path' => $readme_path,
						'title' => $this->format_block_name($block_name) . ' Block',
						'url' => admin_url('admin.php?page=theme-documentation&doc=' . $block_name),
						'type' => 'block'
					];
				}
			}
		}
	}
	
	/**
	 * Format block name for display
	 */
	private function format_block_name($name) {
		return ucwords(str_replace(['-', '_'], ' ', $name));
	}
	
	/**
	 * Add documentation menu to admin
	 */
	public function add_documentation_menu() {
		add_menu_page(
			$this->theme_name . ' Documentation',
			'Theme Docs',
			'edit_posts',
			'theme-documentation',
			[$this, 'render_documentation_page'],
			'dashicons-book-alt',
			100
		);
	}
	
	/**
	 * Enqueue necessary assets
	 */
	public function enqueue_assets($hook) {
		if ($hook !== 'toplevel_page_theme-documentation') {
			return;
		}
		
		// Enqueue highlight.js for code syntax highlighting
		wp_enqueue_style(
			'highlightjs-style',
			'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/github.min.css'
		);
		
		wp_enqueue_script(
			'highlightjs',
			'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js',
			[],
			'11.7.0',
			true
		);
		
		// Add custom styles
		wp_enqueue_style(
			'theme-documentation-style',
			get_template_directory_uri() . '/assets/css/documentation.css',
			[],
			'1.0.0'
		);
		
		// Add custom script
		wp_add_inline_script('highlightjs', 'document.addEventListener("DOMContentLoaded", function() { hljs.highlightAll(); });');
		
		wp_add_inline_style('theme-documentation-style', '
			.theme-docs-container {
				display: flex;
				margin: 20px 0;
			}
			.theme-docs-sidebar {
				width: 200px;
				padding-right: 20px;
				border-right: 1px solid #ddd;
			}
			.theme-docs-content {
				flex: 1;
				padding: 0 20px;
				max-width: 900px;
			}
			.theme-docs-sidebar ul {
				list-style: none;
				margin: 0;
				padding: 0;
			}
			.theme-docs-sidebar li {
				margin-bottom: 10px;
			}
			.theme-docs-sidebar a {
				text-decoration: none;
				display: block;
				padding: 5px 10px;
				border-radius: 4px;
			}
			.theme-docs-sidebar a.active {
				background: #f0f0f0;
				font-weight: bold;
			}
			.theme-docs-content img {
				max-width: 100%;
				height: auto;
			}
			.theme-docs-content pre {
				background: #f5f5f5;
				padding: 15px;
				border-radius: 5px;
				overflow: auto;
			}
			.theme-docs-content code {
				font-family: monospace;
				font-size: 14px;
			}
			.theme-docs-content h1:first-child {
				margin-top: 0;
			}
			.theme-docs-breadcrumb {
				margin-bottom: 20px;
				padding-bottom: 10px;
				border-bottom: 1px solid #eee;
			}
		');
	}
	
	/**
	 * Render documentation page
	 */
	public function render_documentation_page() {
		// Get current doc to display
		$current_doc = isset($_GET['doc']) ? sanitize_text_field($_GET['doc']) : 'theme';
		
		// Default to theme doc if requested doc doesn't exist
		if (!isset($this->docs_found[$current_doc])) {
			$current_doc = 'theme';
		}
		
		if( ! empty( $this->docs_found[$current_doc] ) ) {
			$doc_data = $this->docs_found[$current_doc];
			$doc_content = file_exists($doc_data['path']) ? file_get_contents($doc_data['path']) : 'Documentation not found.';
		}
		
		// Convert Markdown to HTML
		if (!class_exists('Parsedown')) {
			require_once get_template_directory() . '/vendor/parsedown/Parsedown.php';
		}
		
		$parsedown = new Parsedown();
		$html_content = ! empty( $doc_content ) ? $parsedown->text($doc_content) : '';
		
		// Render the page
		echo '<div class="wrap">';
		echo '<h1>Your Website Documentation</h1>';
		echo '<div class="theme-docs-breadcrumb">';
		echo '<a href="' . admin_url('admin.php?page=theme-documentation') . '">Home</a>';
		
		if ($current_doc !== 'theme') {
			echo ' &raquo; <span>' . esc_html($doc_data['title']) . '</span>';
		}
		
		echo '</div>';
		
		echo '<div class="theme-docs-container">';
		
		// Sidebar navigation
		echo '<div class="theme-docs-sidebar">';
		echo '<ul>';
		
		foreach ($this->docs_found as $doc_id => $doc) {
			$active_class = ($doc_id === $current_doc) ? 'class="active"' : '';
			echo '<li><a href="' . esc_url($doc['url']) . '" ' . $active_class . '>' . esc_html($doc['title']) . '</a></li>';
		}
		
		echo '</ul>';
		echo '</div>';
		
		// Main content
		echo '<div class="theme-docs-content">';
		echo $html_content;
		echo '</div>';
		
		echo '</div>'; // .theme-docs-container
		echo '</div>'; // .wrap
	}
}

// Initialize the plugin
new Theme_Documentation();
