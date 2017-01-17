<?php
$logo        = st()->get_option( 'logo' , get_template_directory_uri() . '/img/logo-invert.png' );
$footer_menu = '<ul style="list-style: none; text-align: center;">
            <li style="display: inline-block;"><a href="#" style="color: #818181; text-decoration:none ;">' . __( "About us" , ST_TEXTDOMAIN ) . '</a> |</li>
            <li style="display: inline-block;"><a href="#" style="color: #818181 ; text-decoration:none ;">' . __( "Contact us" , ST_TEXTDOMAIN ) . '</a> |</li>
            <li style="display: inline-block;"><a href="#" style="color: #818181;text-decoration:none ;">' . __( "News" , ST_TEXTDOMAIN ) . '</a></li>
            </ul>';
$social_icon = '
            <a href="' . site_url() . '"><img class="alignnone wp-image-6292" src="' . get_template_directory_uri() . '/img/email/fa.png" alt="eb_face" width="35" height="35" /></a>
            <a style="margin: 5px;" href="' . site_url() . '"><img class="alignnone wp-image-6296" src="' . get_template_directory_uri() . '/img/email/tw.png" alt="" width="35" height="35" /></a>
            <a style="margin: 5px;" href="' . site_url() . '"><img class="alignnone wp-image-6295" src="' . get_template_directory_uri() . '/img/email/gg.png" alt="" width="35" height="35" /></a>
            ';
?>
<table id="booking-infomation" class="wrapper" width="90%" cellspacing="0" align="center" style="width:1000px">
    <tbody>
    <tr>
        <td style="padding: 20px 10px; background: #ED8323;" width="20%">
            <a href="<?php echo esc_url( site_url() ) ?>">
                <img class="alignnone wp-image-7442 size-full" src="<?php echo esc_url( $logo ) ?>" alt="logo"
                     width="110" height="40"/>
            </a>
        </td>
        <td style="background: #ed8323 none repeat scroll 0 0;color: #fff;font-size: 17px;padding: 21px 45px;text-align: right;"
            width="80%">
            <a href="#" style="color: #fff; padding-left: 12px; text-decoration:none ;">Hotel</a>
            <a href="#" style="color: #fff; padding-left: 20px; text-decoration:none ;">Rental</a>
            <a href="#" style="color: #fff; padding-left: 20px; text-decoration:none ;">Car</a>
            <a href="#" style="color: #fff; padding-left: 20px; text-decoration:none ;">Tour</a>
            <a href="#" style="color: #fff; padding-left: 20px; text-decoration:none ;">Activity</a>
        </td>
    </tr>
    </tbody>
</table>


<table id="" class="wrapper" width="90%" cellspacing="0" align="center"
       style="padding-top: 70px; width:1000px ; color:#666"
    >
    <tbody>
    <tr>
        <td style="padding-bottom: 20px; font-size: 20px;">
            <strong style="font-size: 30px;">Hello Partner</strong>,
        </td>
    </tr>
    <tr>
        <td>
            <span style="text-decoration: underline;">[st_email_booking_first_name] [st_email_booking_last_name]</span> booked a service in your system. Below are customer"s booking details:
        </td>
    </tr>
    <tr>
    </tr>
    </tbody>
</table>

<table id="" class="wrapper" width="90%" cellspacing="0" align="center"  style=" width: 1000px; color: rgb(102, 102, 102); border: 1px solid #666; margin-top: 70px;color:#666" >
    <tbody>
    <tr>
        <td style="" width="65%">
            [st_email_booking_thumbnail]
        </td>
        <td  width="35%" style="">

            <table style=" width: 100%; text-align: left; padding-left: 19px; top: 20px; color: #666;">
                <tr>
                    <td colspan="2" style="font-size: 22px; font-weight: bold;">
                        [st_email_booking_item_link]
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 20px;" colspan="2">
                        [st_email_booking_item_address]
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 10px;" colspan="2">
                        [st_email_booking_item_website]
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 20px;" colspan="2">
                        [st_email_booking_item_email]
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 20px;" colspan="2">
                        [st_email_booking_item_phone]
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 20px;" colspan="2">
                        [st_email_booking_item_fax]
                    </td>
                </tr>
            </table>


        </td>
    </tr>

    </tbody>
</table>


<table id="" class="wrapper" width="90%" cellspacing="0" align="center"
       style="padding-top: 40px; width:1000px ; color:#666"
    >
    <tbody>
    <tr>
        <td style="padding-bottom: 20px; text-align: center; font-size: 30px; font-weight: bold;">
            Client Informations
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="1px" style="color: #666;	border-collapse: collapse;">
                <tr>
                    <td width="50%" colspan="2" style="text-align: center; padding: 10px; font-weight: bold;">
                        Booking Code: <span style="background-color: #ccffcc; color: #339933; padding: 3px 10px;">
                            [st_email_booking_id]
                        </span>
                    </td>
                    <td width="50%" colspan="2" style="text-align: center; padding: 10px; font-weight: bold;">
                        Status: <span style="background-color: #ffcccc; color: #993333; padding: 3px 10px;" >
                            [st_email_booking_status]
                        </span>
                    </td>
                </tr>
                <tr>
                    <td  style="padding: 10px 20px;">
                        <strong>First Name:</strong>
                    </td>
                    <td  style="padding: 10px 20px; ; color: #cc3333 ;border-color:#666">
                        <strong>[st_email_booking_first_name]</strong>
                    </td>
                    <td  style="padding: 10px 20px;">
                        <strong>Last Name:</strong>
                    </td>
                    <td  style="padding: 10px 20px; ; color: #cc3333 ;border-color:#666">
                        <strong>[st_email_booking_last_name]</strong>
                    </td>
                </tr>
                <tr>
                    <td  style="padding: 10px 20px;">
                        <strong>Phone:</strong>
                    </td>
                    <td  style="padding: 10px 20px; ; color: #cc3333 ;border-color:#666">
                        [st_email_booking_phone]
                    </td>
                    <td  style="padding: 10px 20px;">
                        <strong>Country:</strong>
                    </td>
                    <td  style="padding: 10px 20px; ;">
                        [st_email_booking_country]
                    </td>
                </tr>
                <tr>
                    <td  style="padding: 10px 20px;">
                        <strong>Email:</strong>
                    </td>
                    <td  style="padding: 10px 20px;">
                        [st_email_booking_email]
                    </td>
                    <td  style="padding: 10px 20px;">
                        <strong>City:</strong>
                    </td>
                    <td  style="padding: 10px 20px; ;">
                        [st_email_booking_city]
                    </td>
                </tr>
                <tr>
                    <td  style="padding: 10px 20px;">
                        <strong>Address Line 1:</strong>
                    </td>
                    <td colspan="3" style="padding: 10px 20px;">
                        [st_email_booking_address]
                    </td>
                </tr>
                <tr>
                    <td   style="padding: 10px 20px;">
                        <strong>Special Requirements:</strong>
                    </td>
                    <td colspan="3" style="padding: 10px 20px;">
                        [st_email_booking_note]
                    </td>
                </tr>
                <tr>
                    <td   style="padding: 10px 20px;">
                        <strong>Date:</strong>
                    </td>
                    <td colspan="3" style="padding: 10px 20px;">
                        [st_email_booking_date]
                    </td>
                </tr>
            </table>

        </td>
    </tr>
    <tr>
    </tr>
    </tbody>
</table>



<table id="" class="wrapper" width="90%" cellspacing="0" align="center"
       style="padding-top: 40px; width:1000px ; color:#666"
    >
    <tbody>
    <tr>
        <td style="padding-bottom: 20px; text-align: center; font-size: 30px; font-weight: bold;">
            Booking Details
        </td>
    </tr>
    <tr>
        <td>
            <div style="text-align: center; padding: 10px 0px; font-weight: bold;border: solid 1px #666">
                Payment Method: [st_email_booking_payment_method]
            </div>
            <div>
                [st_email_booking_room_name tag="" title="Room Name:"]
            </div>
            <div>
                [st_email_booking_number_item]
            </div>
            <div style="text-align: center; padding: 10px 0px; font-weight: bold;border-left: solid 1px #666;border-right: solid 1px #666;border-bottom: solid 1px #666;">
                [st_email_booking_item_price]
                [st_email_booking_adult_info]
                [st_email_booking_children_info]
                [st_email_booking_infant_info]
            </div>
            <div style="text-align: center; padding: 10px 0px; font-weight: bold;border-left: solid 1px #666;border-right: solid 1px #666;border-bottom: solid 1px #666;">
                <span style="text-align: left; width: 48%; display: inline-block; padding-left: 10px;">
                            [st_check_in_out_title]
                </span>
                <span style="text-align: right; width: 48%; display: inline-block;">
                    [st_check_in_out_value]
                </span>
            </div>
            <div>
                [st_email_booking_extra_items title="Extra service:"]
                [st_email_booking_equipments title="Equipments"]
            </div>
            <div style="padding: 10px 0px; border-left: solid 1px #666;border-right: solid 1px #666;border-bottom: solid 1px #666;">
                <table width="100%" style="padding: 15px; color:#666">
                    <tr>
                        <td width="25%"></td>
                        <td width="25%"></td>
                        <td width="25%" style="padding-bottom: 20px;">
                            Origin Price:
                        </td>
                        <td width="25%" style="text-align: right">
                            <strong>[st_email_booking_origin_price]</strong>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%"></td>
                        <td width="25%"></td>
                        <td width="25%" colspan="2" style="">
                            [st_email_booking_extra_price] [st_email_booking_equipment_price]
                        </td>
                    </tr>
                    <tr>
                        <td width="25%"></td>
                        <td width="25%"></td>
                        <td width="25%" style="padding-bottom: 20px;">
                            Sale Price:
                        </td>
                        <td width="25%" style="text-align: right">
                            <strong>[st_email_booking_sale_price]</strong>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%"></td>
                        <td width="25%"></td>
                        <td width="25%" style="padding-bottom: 20px;">
                            Tax:
                        </td>
                        <td width="25%" style="text-align: right">
                            <strong>[st_email_booking_tax]</strong>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%"></td>
                        <td width="25%"></td>
                        <td width="25%" style="padding-bottom: 20px;">
                            Total Price ( with tax ):
                        </td>
                        <td width="25%" style="text-align: right">
                            <strong>[st_email_booking_price_with_tax]</strong>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%"></td>
                        <td width="25%"></td>
                        <td width="25%" colspan="2" style="">
                            [st_email_booking_deposit_price]
                        </td>
                    </tr>
                    <tr>
                        <td width="25%"></td>
                        <td width="25%"></td>
                        <td width="25%" style="padding-bottom: 20px;">
                            <strong>Pay Amount:</strong>
                        </td>
                        <td width="25%" style="text-align: right">
                            <strong style="font-size: 30px; color: #cc3333">[st_email_booking_total_price]</strong>
                        </td>
                    </tr>
                </table>
            </div>

        </td>
    </tr>
    <tr>
    </tr>
    </tbody>
</table>


<table width="100%" cellspacing="0" align="center" style="color: #818181 ; width:1000px">
    <tbody>
    <tr>
        <td style="padding-top: 30px; padding-bottom: 20px;" align="center">
            <hr style="color: #ddd">
        </td>
    </tr>
    <tr>
        <td style="" align="center"><?php echo balanceTags( $social_icon ) ?></td>
    </tr>
    <tr>
        <td style="padding-top: 20px;" align="center">

            <p>Booking, reviews and advices on hotels, resorts, flights, vacation rentals, travel packages, and lots
                more!</p>

            <?php echo balanceTags( $footer_menu ) ?>
        </td>
    </tr>
    </tbody>
</table>



























