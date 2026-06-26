<?php

/**
 * Block Template: CTA Panel
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 */

$cover_video = get_field('cover_video', $block['id']);

$attrs = get_block_wrapper_attributes(['class' => 'cta-panel position-relative overflow-hidden']);

$allowed_blocks = ['core/heading', 'core/paragraph', 'core/buttons','core/group'];

$template = [
    [
        'core/group',
        [
            'className' => 'container',
        ],
        [
            [
                'core/group',
                [
                    'className' => 'cta-panel__section cta-panel__section--left ani-top ani-fade',
                ],
                [
                    [
                        'core/heading',
                        [
                            'level' => 2,
                            'content' => "Let's talk",
                            'className' => 'cta-panel__heading',
                        ],
                    ],
                    [
                        'core/paragraph',
                        [
                            'content' => "Have a project you'd like to discuss with us?",
                            'className' => 'cta-panel__text',
                        ],
                    ],
                    [
                        'core/group',
                        [
                            'className' => 'cta-panel__contact',
                        ],
                        [
                            [
                                'core/image',
                                [
                                    'className' => 'cta-panel__icon',
                                    'url' => get_template_directory_uri() . '/assets/images/email.svg',
                                    'alt' => 'Email Icon',
                                ],
                            ],
                            [
                                'core/group',
                                [
                                    'className' => 'cta-panel__details',
                                ],
                                [
                                    [
                                        'core/paragraph',
                                        [
                                            'content' => '<span class="cta-panel__label">Email:</span>',
                                            'className' => 'cta-panel__label',
                                        ],
                                    ],
                                    [
                                        'core/paragraph',
                                        [
                                            'content' => '<a href="mailto:sales@smsmarineinteriors.com" class="cta-panel__value">sales@smsmarineinteriors.com</a>',
                                            'className' => 'cta-panel__value',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'core/separator',
                        [
                            'className' => 'cta-panel__divider is-style-wide',
                        ],
                    ],
                    [
                        'core/paragraph',
                        [
                            'content' => 'Alternatively, send us a message and a member of the team will get back to you as soon as possible.',
                            'className' => 'cta-panel__inner-text',
                        ],
                    ],
                    [
                        'core/button',
                        [
                            'text' => 'Get in Touch',
                            'url' => '#contact',
                            'className' => 'cta-panel__button',
                        ],
                    ],
                ],
            ],
            [
                'core/group',
                [
                    'className' => 'cta-panel__section cta-panel__section--right ani-top',
                ],
                [
                    [
                        'core/heading',
                        [
                            'level' => 3,
                            'content' => 'SMS Group',
                            'className' => 'cta-panel__company',
                        ],
                    ],
                    [
                        'core/paragraph',
                        [
                            'content' => 'SMS Marine Interiors<br>Empress House, Empress Road<br>Southampton, SO14 0JW<br>United Kingdom',
                            'className' => 'cta-panel__address',
                        ],
                    ],
                    [
                        'core/group',
                        [
                            'className' => 'cta-panel__contact',
                        ],
                        [
                            [
                                'core/image',
                                [
                                    'className' => 'cta-panel__icon',
                                    'url' => get_template_directory_uri() . '/assets/images/call.svg',
                                    'alt' => 'Phone Icon',
                                ],
                            ],
                            [
                                'core/group',
                                [
                                    'className' => 'cta-panel__details',
                                ],
                                [
                                    [
                                        'core/paragraph',
                                        [
                                            'content' => '<span class="cta-panel__label">Tel:</span>',
                                            'className' => 'cta-panel__label',
                                        ],
                                    ],
                                    [
                                        'core/paragraph',
                                        [
                                            'content' => '<a href="tel:08450130481" class="cta-panel__value">0845 013 0481</a>',
                                            'className' => 'cta-panel__value',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'core/group',
                        [
                            'className' => 'cta-panel__social',
                        ],
                        [
                            [
                                'core/image',
                                [
                                    'url' => get_template_directory_uri() . '/assets/images/linkedin.svg',
                                    'alt' => 'LinkedIn',
                                    'className' => 'cta-panel__social-link',
                                ],
                            ],
                            [
                                'core/image',
                                [
                                    'url' => get_template_directory_uri() . '/assets/images/twitter.svg',
                                    'alt' => 'Twitter',
                                    'className' => 'cta-panel__social-link',
                                ],
                            ],
                            [
                                'core/image',
                                [
                                    'url' => get_template_directory_uri() . '/assets/images/instagram.svg',
                                    'alt' => 'Instagram',
                                    'className' => 'cta-panel__social-link',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];

?>

<section <?= $attrs ?> >
	<div class="cta-panel__overlay"></div>

	<?php if ( !empty( $cover_video ) ) {
		$video_type = get_file_type_from_url($cover_video);
		?>
		<div class="cta-panel__video">
			<?php if( $video_type === 'video' ) { ?>
				<video autoplay muted loop playsinline>
					<source src="<?php echo esc_url($cover_video); ?>" type="video/mp4">
					Your browser does not support the video tag.
				</video>
			<?php } else if( $video_type === 'image' ) { ?>
				<img src="<?php echo esc_url($cover_video); ?>" class="" />
			<?php } ?>
		</div>
	<?php } ?>

    <InnerBlocks
        allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"
        template="<?php echo esc_attr(wp_json_encode($template)); ?>"
    />
</section>