<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package micro
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div id="footer-content-left">
			<div id="footer-widgets">
				<?php
					if(is_active_sidebar('footer-widgets')){
					dynamic_sidebar('footer-widgets');
					}
				?>
			</div>
			<div class="site-info">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'micro' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'micro' ), 'WordPress' ); ?></a>
				<span class="sep"> | </span>
				<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'micro' ), 'micro', '<a href="http://www.cagrimmett.com/" rel="designer">cagrimmett</a>' ); ?>
			</div><!-- .site-info -->
		</div><!-- #footer-content -->
		<div id="footer-content-right"></div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
