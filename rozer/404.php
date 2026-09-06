<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package rozer
 */
get_header();
$custom_content = rdt_get_option('404page_custom_content');
?>
	<main id="primary" class="site-main">
		<?php if(!$custom_content) : ?>
			<?php
				$image = rdt_get_option('404page_image', '');
				$text1 = rdt_get_option('404page_text1', '');
				$text2 = rdt_get_option('404page_text2', '');
			?>
			<section class="error-404 not-found">
				<?php if($image): ?>
					<div class="image-404"><img src="<?php echo esc_url($image); ?>" alt="404"/></div>
				<?php else : ?>
					<div class="nummber-404"><?php esc_html_e( '404', 'rozer' ); ?></div>
				<?php endif; ?>
				<h1 class="page-title">
					<?php if($text1) : 
						echo esc_html($text1);
					else : ?>	
						<?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'rozer' ); ?>
					<?php endif; ?>	
				</h1>
				<p>
					<?php if($text2) : 
						echo esc_html($text2);
					else : ?>	
						<?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'rozer' ); ?>
					<?php endif; ?>	
				</p>
				<a class="button outlined" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Back to homepage', 'rozer'); ?></a>
			</section><!-- .error-404 -->
		<?php else : 
			$args = array('p' => $custom_content, 'post_type' => 'rt_custom_block');
			$loop = new WP_Query($args);
			while ( $loop->have_posts() ) : $loop->the_post();
			    global $post;
			    $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 5600,1000 ),  false, '' ); ?>  
			    <div class="container">
				    <?php the_content (); ?>
				</div>
			<?php endwhile;
		endif; ?>	
	</main><!-- #main -->
<?php
get_footer();
