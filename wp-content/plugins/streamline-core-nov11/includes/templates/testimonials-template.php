<div class="row row-review">
    <div class="col-sm-8">
        <h3 class="testimonial_title"><?php echo $feedback['title']; ?></h3>
        <span class="by">
          <?php printf( __( 'by %s on %s', 'streamline-core'), $feedback['guest_name'], $feedback['creation_date'] ) ?>
        </span>
        <div class="review-details">
            <?php echo $feedback['comments']; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div style="display: inline-block" class="star-rating text-right" star-rating
        rating-value="<?php echo $feedback['points']/$divider; ?>" data-max="5"></div>
    </div>
</div>
