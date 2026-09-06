<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package rozer
 */
?>
</main>
	<footer id="footer" class="site-footer">
		<?php do_action( 'rozer_footer' ) ?>
	</footer><!-- #colophon -->
	<?php do_action( 'rozer_after_site' ) ?>
</div><!-- #page -->
<div class="rozer-close-side"></div>
<?php do_action('rozer_float_right_position'); ?>
<?php wp_footer(); ?>
</body>
</html>