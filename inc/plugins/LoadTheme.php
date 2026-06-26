<?php

namespace SMS\Theme;

use Composer\Script\Event;

class LoadTheme {
	private string $scss_file = 'assets/sass/base/_variables.scss';
	private string $theme_json_file = 'theme.json';
	private string $scss_content;
	private array $color_palette = [];
	private array $spacing_scale = [];
	private string $base_path;

	public function __construct() {
		// Set base path to the project root (where composer.json is located)
		$this->base_path = getcwd();
		
		// Ensure paths are absolute
		$this->scss_file = $this->base_path . '/' . $this->scss_file;
		$this->theme_json_file = $this->base_path . '/' . $this->theme_json_file;
	}

	public static function run(Event $event): int {
		try {
			$converter = new self();
			return $converter->convert();
		} catch (\Exception $e) {
			echo "\nError: " . $e->getMessage() . "\n";
			return 1;
		}
	}

	public function convert(): int {
		try {
			echo "\nConverting SCSS variables to theme.json...\n";
			echo "Base path: {$this->base_path}\n";
			echo "SCSS file: {$this->scss_file}\n";
			echo "Theme JSON: {$this->theme_json_file}\n";
			
			// Read the SCSS file
			$this->read_scss_file();
			
			// Extract variables from each section
			$this->extract_color_variables();
			$this->extract_spacing_variables();
			
			// Update theme.json
			$this->update_theme_json();
			
			echo "\nSuccess! Theme.json updated at: {$this->theme_json_file}\n";
			
			return 0;
		} catch (\Exception $e) {
			echo "\nError converting SCSS to JSON: {$e->getMessage()}\n";
			return 1;
		}
	}

	private function read_scss_file(): void {
		if (!file_exists($this->scss_file)) {
			throw new \RuntimeException("SCSS file not found: {$this->scss_file}\n" .
				"Current directory: " . getcwd());
		}
		
		$this->scss_content = file_get_contents($this->scss_file);
		
		if ($this->scss_content === false) {
			throw new \RuntimeException("Failed to read SCSS file: {$this->scss_file}");
		}
		
		echo "Successfully read SCSS file ({$this->scss_file}) - " . strlen($this->scss_content) . " bytes\n";
	}

	private function extract_section_content(string $section_name): string {
		$pattern = '/\/\*\s*start:' . preg_quote($section_name, '/') . '\s*\*\/(.*?)\/\*\s*end:' . preg_quote($section_name, '/') . '\s*\*\//s';
		if (preg_match($pattern, $this->scss_content, $matches)) {
			return trim($matches[1]);
		}
		return '';
	}

	private function extract_color_variables(): void {
		// Get the content between the color section comments
		$section_content = $this->extract_section_content('colours');
		if (empty($section_content)) {
			$section_content = $this->extract_section_content('colors'); // Try alternate spelling
		}
		
		if (empty($section_content)) {
			// If no section markers found, use the whole file (backwards compatibility)
			$section_content = $this->scss_content;
			echo "No color section markers found, scanning entire file for color variables\n";
		} else {
			echo "Found color section in SCSS\n";
		}
		
		// Pattern for HEX color definitions: $variable-name: #hex;
		$hex_pattern = '/\$([\w-]+)\s*:\s*(#[A-Fa-f0-9]{3,8})\s*;/';
		
		// Extract HEX colors
		if (preg_match_all($hex_pattern, $section_content, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				$var_name = $match[1];
				$hex_color = $match[2];
				
				// Skip variables with -rgb suffix - they're typically duplicates for rgba() usage
				if (strpos($var_name, '-rgb') !== false) {
					continue;
				}
				
				// Format the name for the palette
				$name = $this->format_variable_name($var_name);
				
				$this->color_palette[] = [
					'name' => $name,
					'slug' => $var_name,
					'color' => $hex_color
				];
			}
			
			echo "Found " . count($this->color_palette) . " color variables\n";
		} else {
			echo "No color variables matched the pattern in the SCSS file\n";
		}
		
		// Sort the palette by name
		usort($this->color_palette, function($a, $b) {
			return strcmp($a['name'], $b['name']);
		});
	}

	private function extract_spacing_variables(): void {
		// Get the content between the spacing section comments
		$section_content = $this->extract_section_content('spacing');
		
		if (empty($section_content)) {
			echo "No spacing section found in SCSS file\n";
			return;
		}
		
		echo "Found spacing section in SCSS\n";
		
		// Pattern for spacing definitions: $spacer-name: 20px;
		$spacing_pattern = '/\$([\w-]+)\s*:\s*(\d+(?:\.\d+)?)(px|rem|em|%)\s*;/';
		
		// Extract spacing values
		if (preg_match_all($spacing_pattern, $section_content, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				$var_name = $match[1];
				$value = $match[2];
				$unit = $match[3];
				
				// Add to spacing scale
				$this->spacing_scale[$var_name] = $value . $unit;
			}
			
			echo "Found " . count($this->spacing_scale) . " spacing variables\n";
		} else {
			echo "No spacing variables matched the pattern in the SCSS file\n";
		}
	}

	private function format_variable_name(string $name): string {
		return ucwords(str_replace(['-', '_'], ' ', $name));
	}

	private function update_theme_json(): void {
		// Create a new theme.json if it doesn't exist
		if (!file_exists($this->theme_json_file)) {
			echo "Creating new theme.json file...\n";
			$theme_json = [
				'$schema' => 'https://schemas.wp.org/trunk/theme.json',
				'version' => 3,
				'settings' => [
					'appearanceTools' => true,
					'color' => [
						'link' => true,
						'defaultGradients' => false,
						'defaultPalette' => false,
						'palette' => []
					],
					'spacing' => [
						'units' => ['px', 'em', 'rem', '%'],
						'customSpacingSize' => true
					]
				]
			];
		} else {
			echo "Updating existing theme.json file...\n";
			// Read existing theme.json
			$theme_json_content = file_get_contents($this->theme_json_file);
			$theme_json = json_decode($theme_json_content, true);
			
			if (json_last_error() !== JSON_ERROR_NONE) {
				throw new \RuntimeException("Invalid JSON format in theme.json file.");
			}
			
			// Ensure the necessary structure exists
			if (!isset($theme_json['settings'])) {
				$theme_json['settings'] = [];
			}
			
			// Set up color section if needed
			if (!isset($theme_json['settings']['color'])) {
				$theme_json['settings']['color'] = [];
			}
			
			if (!isset($theme_json['settings']['color']['palette'])) {
				$theme_json['settings']['color']['palette'] = [];
			}
			
			// Set up spacing section if needed
			if (!isset($theme_json['settings']['spacing'])) {
				$theme_json['settings']['spacing'] = [
					'units' => ['px', 'em', 'rem', '%'],
					'customSpacingSize' => true
				];
			}
		}
		
		// Update color palette if we have colors
		if (!empty($this->color_palette)) {
			$theme_json['settings']['color']['palette'] = $this->color_palette;
			echo "Updated color palette in theme.json\n";
		}
		
		// Update spacing scale if we have spacing values
		if (!empty($this->spacing_scale)) {
			// Add spacing values to the theme.json
			$theme_json['settings']['spacing']['spacingSizes'] = [];
			
			// Sort spacing values by their numeric value for better organization
			$sorted_spacing = $this->spacing_scale;
			uasort($sorted_spacing, function($a, $b) {
				// Extract numeric values for comparison
				$a_value = (float)preg_replace('/[^0-9.]/', '', $a);
				$b_value = (float)preg_replace('/[^0-9.]/', '', $b);
				return $a_value <=> $b_value;
			});
			
			// Add to theme.json
			$slug_prefix = 'spacing';
			$size_index = 1;
			
			foreach ($sorted_spacing as $var_name => $value) {
				$slug = $var_name;
				$name = $this->format_variable_name($var_name);
				
				$theme_json['settings']['spacing']['spacingSizes'][] = [
					'name' => $name,
					'slug' => $slug,
					'size' => $value
				];
				
				$size_index++;
			}
			
			echo "Updated spacing scale in theme.json\n";
		}
		
		// Write updated theme.json
		$this->write_file(
			$this->theme_json_file, 
			json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
		);
	}

	private function write_file(string $path, string $content): void {
		if (file_put_contents($path, $content) === false) {
			throw new \RuntimeException("Failed to write file: {$path}");
		}
	}
}
