<?php

add_action( 'wp_dashboard_setup', 'add_custom_dashboard_widgets' );
/**
 * Add custom dashboard widgets for video tutorials
 */
function add_custom_dashboard_widgets() {
	wp_add_dashboard_widget(
		'tutorial_dashboard_widget',
		'Video Tutorials',
		'tutorial_dashboard_widget_content'
	);
}

/**
 * Render custom dashboard widget content
 */
function tutorial_dashboard_widget_content() {

    $videos = [
        [
            'title' => 'Duplicating & Editing Project Pages',
            'url'   => 'https://player.vimeo.com/video/1196020862?h=d558410e7b'
        ]
    ];

    if (!empty($videos)) {

        foreach ($videos as $video) {

            $video_url = $video['url'] . '&title=1&byline=1&portrait=1&controls=1';

            echo '<div class="vid-tut">';

                echo '<h2>' . esc_html($video['title']) . '</h2>';

                echo '<div class="video-wrapper">';

                    echo '<iframe 
                        src="' . esc_url($video_url) . '" 
                        frameborder="0"
                        allow="autoplay; fullscreen; picture-in-picture"
                        allowfullscreen>
                    </iframe>';

                echo '</div>';

            echo '</div>';
        }
    }
}