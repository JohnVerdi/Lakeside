<?php
    /*
     * Template Name: Checkout
    */
    /**
     * @package WordPress
     * @subpackage Traveler
     * @since 1.0
     *
     * Template checkout
     *
     * Created by ShineTheme
     *
     */
    get_header();
?>
    <div class="gap"></div>
    <div class="container">
        <?php
            echo STTemplate::message();
        ?>
        <?php if (!STCart::check_cart()): ?>
            <div class="alert alert-danger">
                <p><?php st_the_language('cart_empty') ?></p>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="row row-wrap">
                        <div class="col-md-12">
                            <h3><?php st_the_language('booking_submission') ?></h3>

                            <div class="entry-content">
                                <?php
                                    while (have_posts()) {
                                        the_post();
                                        the_content();
                                    }
                                ?>
                            </div>
                            <form id="cc-form" class="" method="post" onsubmit="return false">
                                <?php echo st()->load_template('check_out/check_out') ?>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h4><?php st_the_language('your_booking') ?>:</h4>

                    <div class="booking-item-payment">
                        <?php
                            $all_items = STCart::get_items();
                            if (!empty($all_items) and is_array($all_items)) {
                                foreach ($all_items as $key => $value) {
                                    if (get_post_status($key)) {
                                        $post_type = get_post_type($key);
                                        switch ($post_type) {
                                            case "st_hotel":
                                                $hotel = new STHotel();
                                                echo balanceTags($hotel->get_cart_item_html($key));
                                                break;
                                            case "hotel_room":
                                                $room = new STRoom();
                                                echo balanceTags($room->get_cart_item_html($key));
                                                break;
                                            case "st_cars":
                                                $cars = new STCars();
                                                echo balanceTags($cars->get_cart_item_html($key));
                                                break;
                                            case "st_tours":
                                                $tours = new STTour();
                                                echo balanceTags($tours->get_cart_item_html($key));
                                                break;
                                            case "st_rental":
                                                $object = STRental::inst();
                                                echo balanceTags($object->get_cart_item_html($key));
                                                break;
                                            case "st_activity":
                                                $object = STActivity::inst();
                                                echo balanceTags($object->get_cart_item_html($key));
                                                break;
                                        }
                                    }
                                }
                            }
                        ?>
                        <!-- <div class="booking-item-coupon p10">
                            <form method="post" action="<?php the_permalink() ?>">
                                <?php if (isset(STCart::$coupon_error['status'])): ?>
                                    <div
                                        class="alert alert-<?php echo STCart::$coupon_error['status'] ? 'success' : 'danger'; ?>">
                                        <p>
                                            <?php echo STCart::$coupon_error['message'] ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                <input type="hidden" name="st_action" value="apply_coupon">

                                <div class="form-group">

                                    <label><?php _e('Coupon Code', ST_TEXTDOMAIN) ?></label>
                                    <input value="<?php echo STInput::post('coupon_code') ?>" type="text"
                                           class="form-control" name="coupon_code">
                                </div>
                                <button class="btn btn-primary"
                                        type="submit"><?php _e('Apply Coupon', ST_TEXTDOMAIN) ?></button>
                            </form>
                        </div>
                        <div class="booking-item-payment-total text-right">
                            <?php /*if(st()->get_option('tax_enable','off')=='on'):*/


                                $tax = st()->get_option('tax_value', 0);
                                $tax_money = STCart::get_tax_amount();
                                $total_without_tax=STCart::get_total_with_out_tax();
                                if(STCart::is_tax_included_listing_page()){
                                    $tax_money=($total_without_tax)/100*STCart::get_tax(true);
                                    $total_without_tax-=$tax_money;
                                }
                            ?>
                            <table border="0" class="table_checkout">
                                <tr>
                                    <td class="text-left title"><?php st_the_language('sub_total:') ?> </td>
                                    <td class="text-right "><?php echo TravelHelper::format_money($total_without_tax) ?></td>
                                </tr>
                                <?php if (STCart::use_coupon()):
                                    ?>
                                    <tr>
                                        <td class="text-left title">
                                            <?php printf(st_get_language('coupon_key'), STCart::get_coupon_code()) ?> (
                                            <a href="<?php echo st_get_link_with_search(get_permalink(), array('remove_coupon'), array('remove_coupon' => STCart::get_coupon_code())) ?>"
                                               class="danger"><?php st_the_language('delete') ?></a> )
                                        </td>
                                        <td class="text-right "><?php echo TravelHelper::format_money('-' . STCart::get_coupon_amount()) ?></td>
                                    </tr>
                                <?php endif; ?>
                                    <tr>
                                        <td class="text-left title"><?php st_the_language('tax:'); ?></td>
                                        <td class="text-right"><?php echo TravelHelper::format_money($tax_money); ?></td>
                                    </tr>
                                <tr>
                                    <td class="text-left title"><?php st_the_language('total:') ?></td>
                                    <td class="text-right"><?php echo TravelHelper::format_money(STCart::get_total()) ?></td>
                                </tr>
                            </table>

                        </div> -->
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
    <span class="hidden st_template_checkout"></span>
<?php


    get_footer();