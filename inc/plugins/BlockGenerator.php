<?php

namespace SMS\Theme;

use Composer\Script\Event;

class BlockGenerator {
	private string $block_name;
	private string $block_title;
	private string $block_path;
	private string $namespace = 'sms';

	public function __construct(string $block_name) {
		$this->block_name = strtolower($block_name);
		$this->block_title = $this->generate_title($this->block_name);

		// Get current path the script is running from
		$theme_root = getcwd();
		// check if the /blocks/acf directory exists in this location
		if (!file_exists($theme_root . '/blocks/acf')) {
			// if it doesn't exist, tell user this needs to be run from the theme root
			throw new \RuntimeException("blocks/acf directory not found. Please ensure you run this script in the theme directory.");
		}

		$this->block_path = $theme_root . '/blocks/acf/' . $this->block_name;
	}

	public static function run(Event $event): int {
		$args = $event->getArguments();
		
		if (empty($args)) {
			self::show_usage();
			return 1;
		}

		try {
			$generator = new self($args[0]);
			return $generator->generate();
		} catch (\Exception $e) {
			echo "\nError: " . $e->getMessage() . "\n";
			return 1;
		}
	}

	private static function show_usage(): void {
		echo "Usage: composer create-block <block-name>\n";
		echo "Example: composer create-block hero-banner\n\n";
		echo "Block will be created in: /blocks/acf/<block-name>\n";
	}

	private function generate_title(string $name): string {
		return ucwords(str_replace(['-', '_'], ' ', $name));
	}

	public function generate(): int {
		try {
			// Verify we're in the right location
			if (!file_exists(dirname($this->block_path))) {
				throw new \RuntimeException("blocks/acf directory not found in theme root. Please ensure the directory exists.");
			}

			// Create block directory structure
			$this->create_directory_structure();

			// Generate block files
			$this->generate_block_json();
			$this->generate_fields_php();
			$this->generate_template_php();
			$this->generate_functions_php();
			$this->generate_styles_css();
			$this->generate_editor_styles_css();
			$this->generate_block_js();
			$this->generate_readme();

			echo "\nBlock '{$this->block_title}' created successfully!\n";
			echo "Location: blocks/acf/{$this->block_name}\n";

			return 0;
		} catch (\Exception $e) {
			echo "\nError creating block: {$e->getMessage()}\n";
			return 1;
		}
	}

	private function generate_readme(): void {
		$content = <<<PHP
#{$this->block_name}
Description of the block and what it's meant to do goes here.

## Block Settings
- **Image**: Upload an image to be displayed in the block.

## Patterns
A pattern for this block is included in the theme. You can find it in the patterns section of the block editor: `Appearance > Design > Patterns`. The pattern is called "Patterns that include this block". It will do X, Y and Z. You can edit this pattern to change the introduction and title.
PHP;

		$this->write_file($this->block_path . '/README.md', $content);
	}

	private function create_directory_structure(): void {
		// Create block directory if it doesn't exist
		if (!file_exists($this->block_path)) {
			if (!mkdir($this->block_path, 0755, true)) {
				throw new \RuntimeException("Failed to create block directory: {$this->block_path}");
			}
		}

		// Create src directory if it doesn't exist
		$src_path = $this->block_path . '/src';
		if (!file_exists($src_path)) {
			if (!mkdir($src_path, 0755, true)) {
				throw new \RuntimeException("Failed to create src directory: {$src_path}");
			}
		}
	}

	private function generate_block_json(): void {
		$content = [
			'name' => "{$this->namespace}/{$this->block_name}",
			'title' => $this->block_title,
			'description' => "{$this->block_title} block.",
			'category' => 'sms-blocks',
			'icon' => 'admin-comments',
			'keywords' => [$this->block_name],
			'version' => '1.0.0',
			"script" => [
				"file:../build/block.js"
			],
			"style" => [
				"file:../build/style-block.css"
			],
			"editorStyle" => [
				"file:../build/block.css"
			],
			'acf' => [
				'mode' => 'preview',
				"renderTemplate" => "render.php"
			],
			'supports' => [
				'align' => false,
				'mode' => false,
				'jsx' => true,
				'multiple' => true
			]
		];

		$this->write_file(
			$this->block_path . '/src/block.json',
			json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
		);
	}

	private function generate_fields_php(): void {
		$content = <<<PHP
<?php
use Extended\ACF\Fields\Image;
use Extended\ACF\Location;

\$block_name = "{$this->block_name}";

return register_extended_field_group([
	'title' => 'Block Name',
	'key' => \$block_name,
	'fields' => [
		Image::make('Image')
			->format('id'),
	],
	'location' => [
		Location::where('block', '{$this->namespace}/{$this->block_name}'),
	],
]);
PHP;

		$this->write_file($this->block_path . '/src/fields.php', $content);
	}

	private function generate_template_php(): void {
		$content = <<<PHP
<?php
/**
 * Block Template: {$this->block_title}
 *
 * @param   array \$block The block settings and attributes.
 * @param   string \$content The block inner HTML (empty).
 * @param   bool \$is_preview True during backend preview render.
 * @param   int \$post_id The post ID the block is rendering content against.
 */

\$attrs = get_block_wrapper_attributes(['class' => 'sms-block {$this->block_name}']);
?>

<div <?= \$attrs ?>>
	<h2>{$this->block_title}</h2>
</div>
PHP;

		$this->write_file($this->block_path . '/src/render.php', $content);
	}

	private function generate_functions_php(): void {
		$content = <<<PHP
<?php
/**
 * Block Functions: {$this->block_title}
 */

// Add your custom PHP functions for this block here
PHP;

		$this->write_file($this->block_path . '/src/functions.php', $content);
	}

	private function generate_styles_css(): void {
		$content = <<<CSS
@import '../../../../assets/sass/base/variables';
@import '../../../../assets/sass/base/mixins';

.{$this->block_name} {
	/* Add your styles here */
}
CSS;

		$this->write_file($this->block_path . '/src/style.scss', $content);
	}

	private function generate_editor_styles_css(): void {
		$content = <<<CSS
@import '../../../../assets/sass/base/variables';
@import '../../../../assets/sass/base/mixins';

.{$this->block_name} {
	/* Add your block editor specific styles here */
}
CSS;

		$this->write_file($this->block_path . '/src/editor.scss', $content);
	}

	private function generate_block_js(): void {
		$content = <<<JS
	/* Add block specific JS here */
JS;

		$this->write_file($this->block_path . '/src/view.js', $content);
	}

	private function write_file(string $path, string $content): void {
		if (file_put_contents($path, $content) === false) {
			throw new \RuntimeException("Failed to write file: {$path}");
		}
	}
}
