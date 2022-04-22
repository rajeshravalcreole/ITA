<?php
/**
 * Displays the post header
 *
 * @package WordPress
 * @subpackage Investors_Trust
 * @since 1.0.0
 */

$discussion = ! is_page() && investorstrust_can_show_post_thumbnail() ? investorstrust_get_discussion_data() : null; ?>

<?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>

<?php if ( ! is_page() ) : ?>
<div class="entry-meta">
	<?php investorstrust_posted_by(); ?>
	<?php investorstrust_posted_on(); ?>
	<span class="comment-count">
		<?php
		if ( ! empty( $discussion ) ) {
			investorstrust_discussion_avatars_list( $discussion->authors );
		}
		?>
		<?php investorstrust_comment_count(); ?>
	</span>
	<?php
	// Edit post link.
		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers. */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'investorstrust' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">' . investorstrust_get_icon_svg( 'edit', 16 ),
			'</span>'
		);
	?>
</div><!-- .entry-meta -->
<?php endif; ?>
