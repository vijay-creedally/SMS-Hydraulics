<?php
get_header();
?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main">

			<div class="full-width">
				<?php 
					if ( have_posts() ) : ?>

						<div class="page-header">
							<?php
								the_archive_title( '<h1 class="page-title">', '</h1>' );
							?>
						</div>

						<div class="col-3">
						<?php
							while ( have_posts() ) :
								the_post();
								
								get_template_part( 'template-parts/content/content', 'archive' );
							endwhile;

						the_posts_navigation();

					else :
						get_template_part( 'template-parts/content/content', 'none' );

					endif;
				?>
				</div>
			</div>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
