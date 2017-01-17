<?php
/**
 * Order details
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$order = wc_get_order( $order_id );

$customer_name=$order->billing_first_name;
if(!$customer_name) $customer_name = $order->billing_email;
?>
<header>
    <h2><?php _e('Booking Detail',ST_TEXTDOMAIN) ?></h2>
</header>
<ul class="order-payment-list list mb30">
    <?php
    if ( sizeof( $order->get_items() ) > 0 ) {

        foreach( $order->get_items() as $item_id => $item ) {
            $_product  = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
            $item_meta = new WC_Order_Item_Meta( $item['item_meta'], $_product );
            $post_type=!empty($item['item_meta']['_st_st_booking_post_type'])?$item['item_meta']['_st_st_booking_post_type']:false;

            $st_booking_id=$item['product_id'];
            if(!empty($item['item_meta']['_st_st_booking_id'][0])){
                $st_booking_id=$item['item_meta']['_st_st_booking_id'][0];
            }

            if(is_array($post_type) and isset($post_type[0])) $post_type=$post_type[0];
            if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
                ?>
                <li class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
                    <div class="row">
                        <div class="col-xs-9">
                            <h5><i class="<?php echo apply_filters('st_post_type_'.$post_type.'_icon','') ?>"></i>
                                <?php
                                if ( $_product && ! $_product->is_visible() ) {
                                    echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
                                } else {
                                    echo apply_filters( 'woocommerce_order_item_name', sprintf( '<a href="%s">%s</a>', get_permalink( $st_booking_id ), $item['name'] ), $item );
                                }
                                ?>
                            </h5>
                        </div>
                        <div class="col-xs-3">
                            <p class="text-right"><span class="text-lg"><?php echo $order->get_formatted_line_subtotal( $item ); ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="order-item-meta-box">
                        <?php
                        // Allow other plugins to add additional product information here
                        do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order );

                        $order->display_item_meta( $item );
                        $order->display_item_downloads( $item );
                        // Allow other plugins to add additional product information here
                        do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order );
                        ?>
                    </div>
                </li>
            <?php
            }

            if ( $order->has_status( array( 'completed', 'processing' ) ) && ( $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) ) {
                ?>
                <li class="product-purchase-note">
                    <div colspan="3"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></div>
                </li>
            <?php
            }
        }
    }

    do_action( 'woocommerce_order_items_table', $order );
    ?>
</ul>
<div class="row">
    <div class="col-xs-9">
    </div>
    <div class="col-xs-3">
        <p class="text-right">
            <span class="text-lg"><?php _e("Total ") ?>: </span>
            <span class="text-lg"><?php echo esc_html(TravelHelper::format_money($order->order_total)) ?></span>
        </p>
    </div>
</div>

<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>

<header>
	<h2><?php _e( 'Customer details', 'woocommerce' ); ?></h2>
</header>
<table class="shop_table shop_table_responsive customer_details">
<?php
	if ( $order->billing_email ) {
		echo '<tr><th>' . __( 'Email:', 'woocommerce' ) . '</th><td data-title="' . __( 'Email', 'woocommerce' ) . '">' . $order->billing_email . '</td></tr>';
	}

	if ( $order->billing_phone ) {
		echo '<tr><th>' . __( 'Telephone:', 'woocommerce' ) . '</th><td data-title="' . __( 'Telephone', 'woocommerce' ) . '">' . $order->billing_phone . '</td></tr>';
	}

	// Additional customer details hook
	do_action( 'woocommerce_order_details_after_customer_details', $order );
?>
</table>

<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

<div class="col2-set addresses">

	<div class="col-1">

<?php endif; ?>

		<header class="title">
			<h3><?php _e( 'Billing Address', 'woocommerce' ); ?></h3>
		</header>
		<address>
			<?php
				if ( ! $order->get_formatted_billing_address() ) {
					_e( 'N/A', 'woocommerce' );
				} else {
					echo $order->get_formatted_billing_address();
				}
			?>
		</address>

<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

	</div><!-- /.col-1 -->
    <?php
    if(st()->get_option('woo_checkout_show_shipping')=='on'){?>
	<div class="col-2">

		<header class="title">
			<h3><?php _e( 'Shipping Address', 'woocommerce' ); ?></h3>
		</header>
		<address>
			<?php
				if ( ! $order->get_formatted_shipping_address() ) {
					_e( 'N/A', 'woocommerce' );
				} else {
					echo $order->get_formatted_shipping_address();
				}
			?>
		</address>

	</div><!-- /.col-2 -->
    <?php }?>

</div><!-- /.col2-set -->

<?php endif; ?>

<div class="clear"></div>
