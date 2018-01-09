<?php
/*
Template Name: Autoren Liste
*/

get_header(); ?>

	<div id="main" role="main">
		<?php
			while ( have_posts() ) : the_post();
		?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<h1 class="entry-title">
					<span class="entry-headline"><?php the_title(); ?></span>
				</h1>
			</header>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>

			<?php
				// Output the authors list.
				zeitonline_list_authors();
			?>
      <br style="clear:both;" />
		</article>

		<?php
			endwhile;
		?>
	</div>

<?php
get_sidebar();
get_footer();