<div class="testimonial_widget">
<?php do_action( 'streamline_widget_testimonial_before' ); ?>
<?php  foreach ($feedbacks as $key => $feedback){ ?>
	<div class="testimonial">
		<h4><a href="<?php echo StreamlineCore_Wrapper::get_unit_permalink( $feedback['seo_page_name'] ); ?>"><?php echo $feedback['unit_name']; ?></a></h4>
		<?php if(is_string($feedback['title'])): ?>
		<p>"<?php echo $feedback['title']; ?>"</p>
		<?php endif; ?>
		<div class="star-rating">
		<?php for($i = 0; $i < 5; $i++): ?>
			<?php if($feedback['points'] > $i){
				?>
					<i class="fa fa-star"></i>
				<?php
			}else{
				?>
					<i class="fa fa-star-o"></i>
				<?php
			}
			?>

		<?php endfor; ?>
		</div>
		<p class="feedback_author">
		By <?php echo $feedback['guest_name']; ?> on <?php echo $feedback['creation_date']; ?>
		</p>
	</div>
<?php } ?>
<?php do_action( 'streamline_widget_testimonial_after' ); ?>
</div>