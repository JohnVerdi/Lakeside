<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 3/6/15
 * Time: 3:16 PM
 */
$link=get_template_directory_uri().'/';
?>
<div class="demo_changer" id="demo_changer">
    <div class="demo-icon fa fa-sliders"></div>
    <div class="form_holder">
        <div class="line"></div>
        <p>Color Scheme</p>
        <div class="predefined_styles" id="styleswitch_area">
            <a class="styleswitch" href="#" style="background:#ED8323;"></a>
            <a class="styleswitch" href="#" data-src="bright-turquoise" style="background:#0EBCF2;"></a>
            <a class="styleswitch" href="#" data-src="turkish-rose" style="background:#B66672;"></a>
            <a class="styleswitch" href="#" data-src="salem" style="background:#12A641;"></a>
            <a class="styleswitch" href="#" data-src="hippie-blue" style="background:#4F96B6;"></a>
            <a class="styleswitch" href="#" data-src="mandy" style="background:#E45E66;"></a>
            <a class="styleswitch" href="#" data-src="green-smoke" style="background:#96AA66;"></a>
            <a class="styleswitch" href="#" data-src="horizon" style="background:#5B84AA;"></a>
            <a class="styleswitch" href="#" data-src="cerise" style="background:#CA2AC6;"></a>
            <a class="styleswitch" href="#" data-src="brick-red" style="background:#cf315a;"></a>
            <a class="styleswitch" href="#" data-src="de-york" style="background:#74C683;"></a>
            <a class="styleswitch" href="#" data-src="shamrock" style="background:#30BBB1;"></a>
            <a class="styleswitch" href="#" data-src="studio" style="background:#7646B8;"></a>
            <a class="styleswitch" href="#" data-src="leather" style="background:#966650;"></a>
            <a class="styleswitch" href="#" data-src="denim" style="background:#3366cc;"></a>
            <a class="styleswitch" href="#" data-src="scarlet" style="background:#FF1D13;"></a>
        </div>
        <div class="line"></div>
        <p>Layout</p>
        <div class="predefined_styles"><a class="btn btn-sm" href="#" id="btn-wide">Wide</a><a class="btn btn-sm" href="#" id="btn-boxed">Boxed</a>
        </div>
        <div class="line"></div>
        <p>Background Patterns</p>
        <div class="predefined_styles" id="patternswitch_area">
            <a class="patternswitch" href="#" style="background-image: url(<?php echo $link?>img/patterns/demo.png);background-position: 0px 0 ;" data-src="<?php echo $link?>img/patterns/binding_light.png"></a>
            <a class="patternswitch" href="#" style="background-image: url(<?php echo $link?>img/patterns/demo.png);background-position: -25px 0 ;" data-src="<?php echo $link?>img/patterns/binding_dark.png"></a>
            <a class="patternswitch" href="#" style="background-image: url(<?php echo $link?>img/patterns/demo.png);background-position: -50px 0 ;" data-src="<?php echo $link?>img/patterns/dark_fish_skin.png"></a>
            <a class="patternswitch" href="#" style="background-image: url(<?php echo $link?>img/patterns/demo.png);background-position: -75px 0 ;" data-src="<?php echo $link?>img/patterns/dimension.png"></a>
            <a class="patternswitch" href="#" style="background-image: url(<?php echo $link?>img/patterns/demo.png);background-position: -100px 0 ;" data-src="<?php echo $link?>img/patterns/escheresque_ste.png"></a>
            <a class="patternswitch" href="#" style="background-image: url(<?php echo $link?>img/patterns/demo.png);background-position: -125px 0 ;" data-src="<?php echo $link?>img/patterns/food.png"></a>
            <a class="patternswitch" href="#" style="background-image: url(<?php echo $link?>img/patterns/demo.png);background-position: -0px -25px ;" data-src="<?php echo $link?>img/patterns/giftly.png"></a>
            <a class="patternswitch" href="#" style="background-image: url(<?php echo $link?>img/patterns/demo.png);background-position: -25px -25px ;" data-src="<?php echo $link?>img/patterns/grey_wash_wall.png"></a>
            <a class="patternswitch" href="#" style="background-image: url(<?php echo $link?>img/patterns/demo.png);background-position: -50px -25px ;" data-src="<?php echo $link?>img/patterns/ps_neutral.png"></a>
            <a class="patternswitch" href="#" style="background-image: url(<?php echo $link?>img/patterns/demo.png);background-position: -75px -25px ;" data-src="<?php echo $link?>img/patterns/pw_maze_black.png"></a>
            <a class="patternswitch" href="#" style="background-image: url(<?php echo $link?>img/patterns/demo.png);background-position: -100px -25px ;" data-src="<?php echo $link?>img/patterns/pw_pattern.png"></a>
            <a class="patternswitch" href="#" style="background-image: url(<?php echo $link?>img/patterns/demo.png);background-position: -125px -25px ;" data-src="<?php echo $link?>img/patterns/simple_dashed.png"></a>
        </div>
        <div class="line"></div>
        <p>Background Images</p>
        <div class="predefined_styles" id="bgimageswitch_area">
            <a class="bgimageswitch" href="#" style="background-image: url(<?php echo $link?>img/switcher/demo.jpg);background-position: -25px 0 ; " data-src="<?php echo $link?>img/backgrounds/bike.jpg"></a>
            <a class="bgimageswitch" href="#" style="background-image: url(<?php echo $link?>img/switcher/demo.jpg);background-position: -25px -25px ;" data-src="<?php echo $link?>img/backgrounds/flowers.jpg"></a>
            <a class="bgimageswitch" href="#" style="background-image: url(<?php echo $link?>img/switcher/demo.jpg);background-position: -50px -75px ;" data-src="<?php echo $link?>img/backgrounds/wood.jpg"></a>
            <a class="bgimageswitch" href="#" style="background-image: url(<?php echo $link?>img/switcher/demo.jpg);background-position: -25px -75px ;" data-src="<?php echo $link?>img/backgrounds/taxi.jpg"></a>
            <a class="bgimageswitch" href="#" style="background-image: url(<?php echo $link?>img/switcher/demo.jpg);background-position: -25px -50px ;" data-src="<?php echo $link?>img/backgrounds/demo.jpg"></a>
            <a class="bgimageswitch" href="#" style="background-image: url(<?php echo $link?>img/switcher/demo.jpg);background-position: -50px -50px ;" data-src="<?php echo $link?>img/backgrounds/road.jpg"></a>
            <a class="bgimageswitch" href="#" style="background-image: url(<?php echo $link?>img/switcher/demo.jpg);background-position: -50px -25px ;" data-src="<?php echo $link?>img/backgrounds/keyboard.jpg"></a>
            <a class="bgimageswitch" href="#" style="background-image: url(<?php echo $link?>img/switcher/demo.jpg);background-position: -0px -0px ;" data-src="<?php echo $link?>img/backgrounds/beach.jpg"></a>
            <a class="bgimageswitch" href="#" style="background-image: url(<?php echo $link?>img/switcher/demo.jpg);background-position: -0px -75px ;" data-src="<?php echo $link?>img/backgrounds/street.jpg"></a>
            <a class="bgimageswitch" href="#" style="background-image: url(<?php echo $link?>img/switcher/demo.jpg);background-position: -0px -50px ;" data-src="<?php echo $link?>img/backgrounds/nature.jpg"></a>
            <a class="bgimageswitch" href="#" style="background-image: url(<?php echo $link?>img/switcher/demo.jpg);background-position: -50px -0px ;" data-src="<?php echo $link?>img/backgrounds/bridge.jpg"></a>
            <a class="bgimageswitch" href="#" style="background-image: url(<?php echo $link?>img/switcher/demo.jpg);background-position: -00px -25px ;" data-src="<?php echo $link?>img/backgrounds/cameras.jpg"></a>
        </div>
        <div class="line"></div>
    </div>
</div>