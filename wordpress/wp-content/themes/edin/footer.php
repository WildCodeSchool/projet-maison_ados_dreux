<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Edin
 */
?>

	</div><!-- #content -->

	<?php get_sidebar( 'footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="footer-wrapper clear">
			<div class="site-info">
				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'edin' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'edin' ), 'WordPress' ); ?></a>
				<span class="sep"> | </span>
				<?php printf( __( 'Theme: %1$s by %2$s.', 'edin' ), 'Edin', '<a href="http://wordpress.com/themes/edin/" rel="designer">WordPress.com</a>' ); ?>
			</div><!-- .site-info -->
			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav class="footer-navigation" role="navigation">
					<?php
						wp_nav_menu( array(
							'theme_location'  => 'footer',
							'menu_class'      => 'clear',
							'depth'           => 1,
						) );
					?>
				</nav><!-- .footer-navigation -->
			<?php endif; ?>
		</div><!-- .footer-wrapper -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>