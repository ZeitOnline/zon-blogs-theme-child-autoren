<?php


add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
	wp_enqueue_style(
		'zon-blogs-autorenblog',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'zon-blogs-style' ),
		@filemtime( get_stylesheet_directory() . '/style.css' )
	);
}


function zb_posted_by() {
	$byline = sprintf(
		esc_html_x( '%s', 'post author', 'zb' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="zb-byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}

function zb_cats_nav() {
  return "";
}

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function zb_author_widgets_init() {
	register_sidebar( array(
		'name'          => 'home Area',
		'id'            => 'home-author',
		'before_widget' => '<div class="widget-area">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => 'Article Area',
		'id'            => 'article-author',
		'before_widget' => '<div class="widget-area">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'zb_author_widgets_init' );


if ( ! function_exists( 'zeitonline_list_authors' ) ) :
/**
 * Print a list of all site contributors who published at least one post.
 *
 * @return void
 */
function zeitonline_list_authors() {
	$contributor_ids = get_users( array(
		'fields'  => 'ID',
		'orderby' => 'display_name',
		'order'   => 'ASC',
		// 'orderby' => 'post_count',
		// 'order'   => 'DESC',
		'who'     => 'authors',
	) );

	$author_ids = get_users( array(
		'fields'  => 'ID',
		'orderby' => 'display_name',
		'order'   => 'ASC',
		'role'    => 'author',
	) );

	$user_ids = array();

	echo '<div class="widget widget-authors-list">';

	foreach ( $contributor_ids as $id ) :
		$post_count = count_user_posts( $id );

		// Move on if user has not published a post (yet).
		if ( ! $post_count ) {
			continue;
		}

		$user_ids[] = $id;
	?>

	<div class="widget-authors">
		<a href="<?php echo esc_url( get_author_posts_url( $id ) ); ?>">
			<?php echo get_avatar( $id, 60 ); ?>
			<h2 class="widget-item"><?php echo get_the_author_meta( 'display_name', $id ); ?></h2>
			<p class="widget-description">
				<?php echo get_the_author_meta( 'description', $id ); ?>
			</p>
		</a>
	</div>

	<?php
	endforeach;

	$author_ids = array_diff( $author_ids, $user_ids );

	foreach ( $author_ids as $id ) :
	?>

	<div class="widget-authors">
		<?php echo get_avatar( $id, 60 ); ?>
		<h2 class="widget-item"><?php echo get_the_author_meta( 'display_name', $id ); ?></h2>
		<p class="widget-description">
			<?php echo get_the_author_meta( 'description', $id ); ?>
		</p>
	</div>

	<?php
	endforeach;

	echo '</div>';
}
endif;


/**
 * Add a box to the main column on the Post edit screens.
 */
function zeitonline_meta_box_add() {
	add_meta_box( 'zon-metadata', 'Metadaten', 'zeitonline_metadata_box', 'post', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'zeitonline_meta_box_add' );

/**
 * Print the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function zeitonline_metadata_box($post) {
	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'zeitonline_metadata_box', 'zeitonline_metadata_box_nonce' );

	$value = esc_attr( get_post_meta( $post->ID, 'zon-kicker', true ) );

	echo <<<EOM
		<label for="zon-kicker">Spitzmarke</label>
		<input type="text" id="zon-kicker" name="zon-kicker" value="$value" />
EOM;
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function zeitonline_save_metadata( $post_id ) {
	// Check if our nonce is set.
	if ( ! isset( $_POST['zeitonline_metadata_box_nonce'] ) )
		return $post_id;

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['zeitonline_metadata_box_nonce'], 'zeitonline_metadata_box' ) )
		return $post_id;

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;

	// Check the user's permissions.
	if ( 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) )
			return $post_id;

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	}

	// Update the meta field in the database.
	update_post_meta( $post_id, 'zon-kicker', sanitize_text_field( $_POST['zon-kicker'] ) );
}
add_action( 'save_post', 'zeitonline_save_metadata' );

// Allow more HTML in user description
// remove_filter('pre_user_description', 'wp_filter_kses');
// add_filter( 'pre_user_description', 'wp_filter_post_kses' );

// Disallow HTML in user description
add_filter( 'pre_user_description', 'wp_filter_nohtml_kses' );

// remove admin footer text
add_filter( 'admin_footer_text', function () {} );

// remove the so called 'smart quotes' which convert double ticks
// to the typographically more approriate curly quotation marks,
// but don't always do the right thing™
remove_filter('the_content', 'wptexturize');



function zb_author_add_meta_box() {
	add_meta_box(
		'zb-layout',
		__( 'Autorenbox', 'zb' ),
		'zb_author_metabox_html',
		'post',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'zb_author_add_meta_box' );

function zb_author_metabox_html( $post) {
	wp_nonce_field( '_zb_nonce', 'zb_nonce' ); ?>

	<p>
    	<input type="radio" name="zb_author_box" id="zb_paragraph_author_box" value="paragraph-author-box" <?php echo ( (zb_get_meta( 'zb_author_box' ) === 'paragraph-author-box') OR !zb_get_meta( 'zb_author_box' ) ) ? 'checked' : ''; ?>>
  		<label for="zb_paragraph_author_box">nach dem <input type="number" name="zb_author_box_paragraph" id="zb_author_box_paragraph" value="<?php echo ( !zb_get_meta( 'zb_author_box_paragraph' ) ? '1' : zb_get_meta( 'zb_author_box_paragraph' ) ); ?>" style="width:40px;">. Absatz anzeigen</label><br />
  		<input type="radio" name="zb_author_box" id="zb_bottom_author_box" value="bottom-author-box" <?php echo ( zb_get_meta( 'zb_author_box' ) === 'bottom-author-box' ) ? 'checked' : ''; ?>>
  		<label for="zb_paragraph_author_box">unten anzeigen</label><br />
  		<input type="radio" name="zb_author_box" id="zb_hide_author_box" value="hide-author-box" <?php echo ( (zb_get_meta( 'zb_author_box' ) === 'hide-author-box') OR (zb_get_meta( 'zb_hide_author_box' ) === 'hide-author-box') ) ? 'checked' : ''; ?>>
  		<label for="zb_hide_author_box">ausblenden</label>
    </p>
  <?php
}

function zb_author_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['zb_nonce'] ) || ! wp_verify_nonce( $_POST['zb_nonce'], '_zb_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['zb_author_box'] ) )
		update_post_meta( $post_id, 'zb_author_box', esc_attr( $_POST['zb_author_box'] ) );
	else
		update_post_meta( $post_id, 'zb_author_box', null );

  if ( isset( $_POST['zb_author_box_paragraph'] ) )
		update_post_meta( $post_id, 'zb_author_box_paragraph', esc_attr( $_POST['zb_author_box_paragraph'] ) );
}
add_action( 'save_post', 'zb_author_save' );
