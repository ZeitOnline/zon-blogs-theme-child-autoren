<?php
/**
 * Template part for displaying posts.
 *
 * @package Zeit Online Blogs Twentyeighteen
 */

?>
<!-- overwritten in autorenblog: template-parts/content.php -->
<article id="post-<?php the_ID(); ?>" class="teaser-big">
	<header class="entry-header">
  	<?php if ( 'post' == get_post_type() ) : ?>
  	<div class="teaser-big__entry-avatar"><?php echo get_avatar( get_the_author_meta('ID'), $size='80' ); ?></div>
		<div class="teaser-big__entry-meta">
			<?php zb_posted_by(); ?> <?php zb_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
		<br style="clear: both;" />
		<div class="teaser-big__entry-kicker">
  		<?php echo get_post_meta( get_the_ID(), 'zon-kicker', true ); ?>
		</div>
		<?php the_title( sprintf( '<h2 class="teaser-big__entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->
  <?php //the_post_thumbnail(); ?>
	<div class="teaser-big__entry-content">
		<?php
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'zb' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
		?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'zb' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="teaser-big__entry-footer">

	</footer><!-- .entry-footer -->
	<div class="horizontal-line">&nbsp;</div>
</article><!-- #post-## -->
