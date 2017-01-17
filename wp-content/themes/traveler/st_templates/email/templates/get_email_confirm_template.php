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


<table id="booking-infomation" class="wrapper" width="90%" cellspacing="0" align="center" style="width:1000px;color:#666">
    <tbody>
    <tr id="title">
        <td style="padding: 10px; border-top: 1px solid #CCC;">
            <h3 style="text-align: center;"><strong>Hi,</strong></h3>
            <p style="text-align: center;">You added an email address to your account <br /> Click "confirm" to import the bookings you've made with that address <br /><br /> <a class="btn btn-primary" style="text-decoration: none; color: white; background: #ED8323; font-size: 30px; padding: 14px 30px 14px 30px;" href="[st_email_confirm_link]" target="_blank">Confirm</a><br /><br /> Can't see the button? Try this link: <a href="[st_email_confirm_link]" target="_blank">[st_email_confirm_link]</a></p>
        </td>
    </tr>
    </tbody>
</table>


<table width="100%" cellspacing="0" align="center" style="color: #818181 ; width:1000px ;color:#666">
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



























