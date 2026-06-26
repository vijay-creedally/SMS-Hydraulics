# SMS
SMS is a hybrid Gutenberg/ACF theme for WordPress. It is designed to be a flexible and powerful theme that can be used for any type of website. It is built with the latest technologies and best practices to ensure that it is fast, secure, and easy to use.

## How to code an ACF block
An ACF block is similar to a flexible content block. It allows you to create custom blocks with custom fields. The block can be used in the block picker and can be added to any page or post.

### 1. Create a new block
While in the SMS theme directory, run the following command in your terminal to create a new block:
`composer create-block block-name`
where "block-name" is the name of the block you want to create.

This will create the boilerplate code in `/blocks/acf/block-name` and the block will be available for use in the block picker.

Your block has a namespace of `SMS` and the block name is `block-name`. You can change the block name to whatever you want, but it must be unique. The block name is used to identify the block in the block picker.

`sms/block-name` is the block name you will use in the `allowed_blocks` array in the block render file, but more on that later.

Your block will always have the following files:
- `block.json` - This is where you define the block settings for the block editor.
- `README.md` - This is where you can document the block for future reference. It is picked up and used by the themes documentation generator.
- `fields.php` - This is where you add the fields for the block. This currently uses Extended ACF builder to create fields programmatically.
- `render.php` - This is where you render the block.
- `style.scss` - This is where you add the block styles.
- `functions.php` - This is where you add any PHP functions specific to this block.
- `view.js` - This is where you add any JavaScript specific to the block.
- `editor.scss` - This is where you add the styles that will ONLY apply to the editor. Useful for hiding elements in the editor that you don't want to show to the user.

### 2. Add fields to the block
In the `/src/fields.php` file of your new block, you'll see Extended ACF builder is ready to go. This is to add ACF fields programmatically with PHP which is faster and more efficient than using the ACF UI. Read more about the Extended ACF builder [here](https://github.com/vinkla/extended-acf).

### 3. Building the block
In the `/src/render.php` file of your new block, you'll see the block is ready to go with a handy helper function to load in the block attributes. This is the only thing different to a normal ACF flex block. You can use the `get_field()` functions as normal to get the field values and render them in the block and all your markup for a block will be here. You need to think of your blocks as FULL-WIDTH blocks, just as you would with a normal ACF block. For MAXIMUM flexibility and efficiency here, I'd recommend using `<InnerBlocks />` to allow for nested blocks. The idea is you create an ALLOWED_BLOCKS array in the block and then use that to allow for nested blocks.

Example:
```php
<?php
$allowed_blocks = [
		'core/paragraph',
		'core/image',
		'sms/block-name', // remember, your block namespace is sms followed by your block name.
];
?>

<InnerBlocks allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" />
```

You can even create a template to avoid having to manually add the blocks each time. You can read more about templates [here](https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#templates).

Example:
```php
<?php
$block_template = [
		['core/paragraph', ['placeholder' => 'Add your content here']],
		['core/image', ['align' => 'full']],
		['sms/block-name'], // remember, your block namespace is sms followed by your block name.
];
?>

<InnerBlocks template="<?= esc_attr( wp_json_encode( $block_template ) ); ?>" />
```

#### Note on allowed blocks
The block editor experience is tailored to only show SMS blocks. The CORE blocks are not disabled but they are prevented from loading in the inserter. However, they can still be added via InnerBlocks if you want to allow them in your custom blocks. You *must* add blocks that are allowed in your block to a `$allowed_blocks (array)` variable for them to be available in the block inserter when using InnerBlocks. E.G

```php
$allowed_blocks = [
		'core/paragraph',
		'core/image',
		'core/heading',
		'namespace/block-name',
];

<InnerBlocks allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" />
```

If the format is not followed exactly, the blocks will not show in the inserter.

### 4. Run the build process
While in the sms theme directory, run the following command to watch and build the blocks:
`npm start`
This will begin the development processing for styles and javascript for your theme AND blocks.

## Building a site using the theme
You'll build your website header, footer, archives, etc in the pre-FSE method. This is to prevent clients having too much control over anything outside of pure content. Of course, feel free to make your ACF blocks as flexible as you like, incorporating background colours via the block settings (rather than ACF fields) for example.

## CSS/SASS Variables
The theme uses SASS variables for colours, font sizes, spacing, etc. These are located in the `/src/scss/_variables.scss` file. You can use these variables in your block styles to ensure consistency across the theme.

The variable names for colours should not be changed and should follow the format provided (primary, secondary, accent-1, accent-2, etc). You can add more variables as needed, but try to keep the naming convention consistent. The idea is that in the future, we will have a block repository of our own we can pull blocks from and use in our projects. Having a consistent naming convention for variables will help prevent issues with missing variable names.

## Things to note
- Images - Try to avoid using the `core/image` blocks for images. Instead, use an ACF image field and render the image in the block. This is to retain our control over the output of the image for optimisation purposes.

## Helpers

### ACF block Starter
While in the sms theme directory, run the following command to create a new block:
`composer create-block block-name`
where "block-name" is the name of the block you want to create.

This will create the boilerplate code in `/blocks/acf/block-name` and the block will be available for use in the block picker.

### theme.json Colour Generator
To help generate the colour palette for the `theme.json` file, you can use the following command:
`composer generate-colours`
This will read the colours from the `/src/scss/_variables.scss` file and generate the colour palette for the `theme.json` file. It will output the JSON to the terminal which you can then copy and paste into the `theme.json` file.

### Animations
Scrolling animations can be used by uncommenting the line: `// import './utilities scrollAnimations';` in the `main.js` file. You are then free to use the following classes on any element:
- `ani-fade` - Fades in the element on scroll.
- `ani-left` - Slides in the element from left on scroll.
- `ani-right` - Slides in the element from right on scroll.
- `ani-top` - Slides in the element from top on scroll.
- `ani-bottom` - Slides in the element from bottom on scroll.

These animation classes can also be combined to run multiple animations on the same element:

Example:
```html
<div class="ani-fade ani-left">This element will fade in and slide in from the left on scroll.</div>
```

## Deployment
To deploy the theme, run the following command:
`npm run build`
This will build the theme for production with minification and removal of developement tools in the code.
