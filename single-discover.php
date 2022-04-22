<?php
 get_header();  ?>
<?php
$type = get_field('select_type');
if($type == 'news'){
?>
<section class="banner-section">
	<?php 
		$image = get_field('banner_image');
		if($image){ ?>
			<div class="img-section" style="background-image: url('<?php echo $image; ?>');">
				<img src="<?php echo $image; ?>">
			</div>
		<?php } ?>
		<div class="banner-content container">
			<div class="title"><?php the_title(); ?></div>
			<?php 
			$short_description = get_field('short_description');
			if($short_description){ ?>
				<div class="subtitle"><?php echo $short_description; ?></div>
			<?php } ?>
			<?php $post_date = get_the_date( 'F j, Y',get_the_ID()); ?>
			<div class="date"><?php echo $post_date; ?></div>
			<?php
			$long_description = get_field('long_description');
			if($long_description){ ?>
				<div class="desc">
					<?php echo $long_description; ?>
				</div>
			<?php } ?>
		</div>
</section>
<?php }else{ ?>
	<section class="banner-section">
		<div class="banner-content container">
			<div class="title">No data found</div>
		</div>
	</section>
<?php } ?>
<?php get_footer(); ?>