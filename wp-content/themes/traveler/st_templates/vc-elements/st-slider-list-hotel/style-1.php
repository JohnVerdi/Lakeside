<div class="st-slider-list-hotel owl-carousel" data-effect="<?php echo $st_effect; ?>">
<?php 
	if(!empty($st_hotel_id)):
		$st_hotel_id = explode(',', $st_hotel_id);
		foreach($st_hotel_id as $hotel_id):
			$thumbnail = get_post_thumbnail_id($hotel_id);
			$img = wp_get_attachment_url($thumbnail);
			if(empty($img)){
			    $img = get_template_directory_uri().'/img/no-image.png';
			}
?>
	<div class="item" style="background: url('<?php echo $img; ?>') no-repeat center center">
	</div>
<?php endforeach; endif; ?>
</div>