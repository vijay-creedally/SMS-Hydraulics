<?php
get_header();
?>

<section id="primary" class="content-area">
	<main id="main" class="site-main archive-case-studies">

		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="page-header archive-case-studies__header">
                    <h1 class="page-title archive-case-studies__header-title">
                        <?php post_type_archive_title(); ?>
                    </h1>
                </div>

				 <div class="row archive-case-studies__cols">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                    
                        $args = [
                            'image_url'   => get_the_post_thumbnail_url( get_the_ID(), 'medium' ),
                            'image_alt'   => get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ),
                            'title'       => get_the_title(),
                            'description' => wp_trim_words( get_the_excerpt(), 20, '...' ),
                            'link'        => get_permalink(),
                        ];
                    ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 archive-case-studies__col">
                            <?php
                            get_template_part(
                                'template-parts/components/case-study-card',
                                null,
                                $args
                            );
                            ?>
                        </div>
                    <?php endwhile; ?>
                </div>
                        
                <div class="pagination-wrapper">
                    <?php
                    echo paginate_links([
                        'total'     => $wp_query->max_num_pages,
                        'current'   => max( 1, get_query_var('paged') ),
                        'type'      => 'list',
                        'prev_text' => 'Prev',
                        'next_text' => 'Next',
                    ]);
                    ?>
                </div>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
			<?php endif; ?>

		</div>

	</main>
</section>

<?php
get_footer();
