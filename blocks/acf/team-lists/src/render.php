<?php
/**
 * Block Template: Block Name
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 */

$attrs = get_block_wrapper_attributes(['class' => 'team team-lists-block']);

$description = get_field('description', $block['id']);
$team_image = get_field('team_image', $block['id']);
$hide_team_members = get_field('hide_team_members', $block['id']);

$allowed_blocks = ['core/heading'];

$template = [
    ['core/heading', [
    'level' => 2,
    'content' => 'The Team',
    'className' => 'team__title ani-top ani-fade',
  ]],
  ['core/paragraph', [
    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam ac ante mollis, fermentum nunc in, ultricies nunc. Nullam ac ante mollis, fermentum nunc in, ultricies nunc. Nullam ac ante mollis, fermentum nunc in, ultricies nunc.',
    'className' => 'team__content ani-top ani-fade',
  ]]
];

$teams = [];
if( ! $hide_team_members ) {

  $teams = get_all_team_members([
    'meta_key'       => 'order',
      'orderby'        => 'meta_value_num',
      'order'          => 'ASC',
  ]);
}

?>

<div <?= $attrs ?>>
  <div class="container">
    <div class="team__inner">
      <InnerBlocks
        allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"
        template="<?php echo esc_attr(wp_json_encode($template)); ?>"
      />

      <?php if( !empty( $teams ) && is_array( $teams ) ) { ?>

        <div class="team__grid">

          <?php foreach( $teams as $team ) {

            $title      = get_the_title($team);
            $designation  = get_field('designation', $team);
            $image_url    = get_the_post_thumbnail_url($team, 'large');
            $image_url    = !empty( $image_url ) ? $image_url : get_template_directory_uri()."/assets/images/team-placholder.png";
            ?>
            
            <div class="team__item">
              <div class="team__photo-wrap">
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_html($title); ?>" class="team__photo" />
              </div>
              <div class="team__info">
                <h3 class="team__name"><?php echo esc_html($title); ?></h3>
                <p class="team__role"><?php echo esc_html( $designation ); ?></p>
              </div>
            </div>

          <?php  } ?>

        </div>

      <?php } ?>

      <div class="team-group">
        <?php if( !empty( $description ) ) { ?>
          <div class="team-group__content"><?php echo wp_kses_post($description); ?></div>
        <?php } ?>

        <?php if( !empty( $team_image ) ) { ?>
          <div class="team-group__image-wrap">
            <img 
            src="<?php echo esc_url($team_image); ?>" 
            alt="SMS Marine Team" 
            class="team-group__image"
            />
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>