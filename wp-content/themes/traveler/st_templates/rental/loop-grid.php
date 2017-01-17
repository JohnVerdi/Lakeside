<?php
$link=st_get_link_with_search(get_permalink(),array('start','end','room_num_search','adult_number', 'child_num'),$_GET);
$thumb_url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );

$check_in = '';
$check_out = '';
if(!isset($_REQUEST['start']) || empty( $_REQUEST['start'] )){
    $check_in = date('m/d/Y', strtotime("now"));
}else{
    $check_in = TravelHelper::convertDateFormat(STInput::request('start'));
}

if(!isset($_REQUEST['end']) || empty( $_REQUEST['end'] )){
    $check_out = date('m/d/Y', strtotime("+1 day"));
}else{
    $check_out = TravelHelper::convertDateFormat(STInput::request('end'));
}

$numberday = TravelHelper::dateDiff($check_in, $check_out);
?>
<div class="col-md-4">
    <?php echo STFeatured::get_featured(); ?>
    <div class="thumb">
        <header class="thumb-header">
            <a class="hover-img" href="<?php echo esc_url($link)?>">
                <?php the_post_thumbnail(array(360, 270, 'bfi_thumb' => true)) ?>
                <h5 class="hover-title-center"><?php st_the_language('rental_book_now')?> </h5>
            </a>
        </header>
        <div class="thumb-caption">
            <ul class="icon-group text-tiny text-color">
                <?php
                $avg = STReview::get_avg_rate();
                echo TravelHelper::rate_to_string($avg);
                ?>
            </ul>
            <h5 class="thumb-title"><a class="text-darken" href="<?php echo esc_url($link)?>"><?php the_title()?></a></h5>
            <?php if ($address = get_post_meta(get_the_ID(), 'address', true)): ?>
                <p class="mb0"><small> <?php echo esc_html($address) ?></small>
                </p>
            <?php endif;?>
            <?php
            $is_sale=STRental::is_sale();
            $orgin_price=STPrice::getRentalPriceOnlyCustomPrice(get_the_ID(), strtotime($check_in), strtotime($check_out));
            $price= STPrice::getSalePrice(get_the_ID(), strtotime($check_in), strtotime($check_out));

            $show_price = st()->get_option('show_price_free');
            ?>
            <?php
            $features=get_post_meta(get_the_ID(),'fetures',true);
            if(!empty($features)):?>
                <?php
                echo '<ul class="booking-item-features booking-item-features-rentals booking-item-features-sign clearfix mt5 mb5">';
                foreach($features as $key=>$value):

                    $d=array('icon'=>'','title'=>'');
                    $value=wp_parse_args($value,$d);

                    echo '<li rel="tooltip" data-placement="top" title="" data-original-title="'.$value['title'].'"><i class="'.TravelHelper::handle_icon($value['icon']).'"></i>';
                    if($value['number']){
                        echo '<span class="booking-item-feature-sign">x '.$value['number'].'</span>';
                    }

                    echo '</li>';
                endforeach;
                echo "</ul>";
                ?>
            <?php endif;?>
            <div class="text-darken">
                <?php echo st()->load_template( 'rental/elements/attribute' , 'list' ,array("taxonomy"=>$taxonomy));?>
            </div>
            <p class="mb0 text-darken">
                <?php
                if($is_sale):

                    echo "<span class='booking-item-old-price'>".TravelHelper::format_money($orgin_price)."</span>";
                endif;
                ?>
                <?php if($show_price == 'on' || $price): ?>
                    <span class="text-lg lh1em text-color"><?php echo TravelHelper::format_money($price) ?></span><small> /<?php echo ' '.$numberday.' '.__('night(s)', ST_TEXTDOMAIN); ?></small>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>