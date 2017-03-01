<?php
//$data = json_encode($this->result_data);

?>
<div class="full-page-absolute .hidden" style="display: none; position: fixed;top: 0px;left: 0px;right: 0px;bottom: 0px;z-index: 10000;display: none;">
    <div class="bg-holder full">
        <div class="bg-mask"></div>
        <div class="bg-img" style="background-image:url(/images/hot-tub-background.jpg)"></div>
        <div class="bg-holder-content full text-white text-center">
            <a class="logo-holder" href="http://www.lakeside.loc">
                <img src="/images/lakeside-drk-blue.svg" alt="logo" title="logo">
            </a>
            <div class="full-center">
                <div class="container">
                    <div class="spinner-clock">
                        <div class="spinner-clock-hour"></div>
                        <div class="spinner-clock-minute"></div>
                    </div>
                    <h2 class="mb5">
                        Looking for  Hotels...                    </h2>
                    <p class="text-bigger">it will take a couple of seconds</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb20">
    <div class="vc_row wpb_row st bg-holder">
        <div class="container ">
            <div class="row">
                <div class="wpb_column column_container col-md-12">
                    <div class="vc_column-inner wpb_wrapper">
                        <h3 class="booking-title"><?php echo $data['total'] ?> hotels
                            <small><a class="popup-text" href="#search-dialog" data-effect="mfp-zoom-out">Change
                                    search</a></small>
                        </h3>
                    </div>
                </div>
            </div><!--End .row--></div><!--End .container--></div>
    <div class="vc_row wpb_row st bg-holder">
        <div class="container ">
            <div class="row">
                <div class="col-sm-7 wpb_column column_container col-md-9">
                    <div class="vc_column-inner wpb_wrapper">
                        <div class="nav-drop booking-sort">
                        </div>
                        <div class="sort_top">
                            <div class="row">
                                <div class="col-md-10 col-sm-9 col-xs-9">
                                    <ul class="nav nav-pills">
                                        <li class="sort_menu active"><a href="#" class="sort" data-orderby='fav' data-sort="desc">Favorites</a></li>
                                        <li class="sort_menu"><a href="#" class="sort" data-orderby='price_data' data-sort="asc">Price (low to
                                                high)</a></li>
                                        <li class="sort_menu"><a href="#" class="sort" data-orderby='price_data' data-sort="desc">Price (high to
                                                low)</a></li>
                                        <li class="sort_menu"><a href="#" class="sort" data-orderby='name' data-sort="asc">Name (A-Z)</a>
                                        </li>
                                        <li class="sort_menu"><a href="#" class="sort" data-orderby='name' data-sort="desc">Name (Z-A)</a>
                                        </li>
                                    </ul>
                                </div>
<!--                                <div class="col-md-2 col-sm-3 col-xs-3 text-center">-->
<!--                                    <div class="sort_icon fist"><a class="" href="/search-result-hotel-1/?style=2"><i-->
<!--                                                    class="fa fa-th-large "></i></a></div>-->
<!--                                    <div class="sort_icon last"><a class="" href="/search-result-hotel-1/?style=1"><i-->
<!--                                                    class="fa fa-list "></i></a></div>-->
<!--                                </div>-->
                            </div>
                        </div>

                        <div class="row row-wrap loop_hotel loop_grid_hotel style_box">
                            <?php foreach ($data['data'] as $d): ?>
                                <div class="col-md-4">
                                <div class="thumb">
                                    <header class="thumb-header">
                                        <a class="hover-img"
                                           href="<?php echo '/'.$d['id'] ?>">

                                            <img width="360" height="270"
                                                 src="<?php echo $d['default_thumbnail_path'] ?>"
                                                 class="attachment-360x270 size-360x270 wp-post-image" alt="img41"
                                                 srcset="<?php echo $d['default_thumbnail_path'] ?>"
                                                 sizes="(max-width: 360px) 100vw, 360px">                <h5
                                                    class="hover-title-center">More Details </h5>
                                        </a>
                                    </header>
                                    <div class="thumb-caption">
                                        <?php if($d['rating_average'] > 0): ?>
                                            <ul class="icon-group text-color">
                                                <?php for ($i=20;$i<=100;$i=$i+20): ?>
                                                    <li><i class="fa  fa-star<?php echo $d['rating_average']>$i?'':'-o' ?>"></i></li>
                                                <?php endfor; ?>
                                            </ul>
                                        <?php else: ?>
                                           <span>No rating</span>
                                        <?php endif; ?>
                                        <h5 class="thumb-title"><a class="text-darken"
                                                                   href="<?php echo '/'.$d['id'] ?>"> <?php echo $d['name'] ?></a></h5>
                                        <p class="mb0">
                                            <small><i class="fa fa-map-marker"></i><?php echo $d['location_area_name'] .', '.$d['location_name'] ?>
                                            </small>
                                        </p>
                                        <div class="text-darken">
                                        </div>
                                        <p class="mb0 text-darken">
                                            <span class="text-lg lh1em">$<?php echo $d['price_data'] ?>.00 /night</span>
                                        </p>
                                        <a class="btn-fav" data-hotel="<?php echo $d['id']; ?>" data-toggle="tooltip" data-placement="right" title="">
                                            <i class="fa <?php echo in_array($d['id'], $fav)? 'fa-heart': 'fa-heart-o'?>"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="row" style="margin-bottom: 40px;">
                            <div class="col-sm-12">
                                <hr>
                                <p class="gap"></p>
                            </div>
                            <div class="col-md-6">
                                    <small><?php echo $data['total'] ?> hotels. &nbsp;&nbsp;
                                        Showing <span id="start_count"><?php echo $data['showing_start'] ?></span> - <span id="end_count"><?php echo $data['showing_end'] ?></span>
                                    </small>
                                </p>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <ul class="col-xs-12 pagination 1_pag">
                                            <li><a class="prev col-xs-12 pagination 1_pag"  data-page="1"> <i class="fa fa-angle-left"></i></a></li>
                                            <?php for ($i=1;$i<=ceil($data['total']/$this->perPage);$i++): ?>
                                                <li><a class="col-xs-12 pagination 1_pag pager <?php echo $i == $data['current_page']?'current':'' ?>" data-page="<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                            <?php endfor; ?>
                                            <li><a class="next col-xs-12 pagination 1_pag" data-page="<?php echo $i-1; ?>" ><i class="fa fa-angle-right"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <p>
                                    Not what you're looking for? <a class="popup-text" href="#search-dialog"
                                                                    data-effect="mfp-zoom-out">
                                        Try your search again </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 wpb_column column_container col-md-3">
                    <div class="vc_column-inner wpb_wrapper">
                        <aside class="st-elements-filters booking-filters text-white">
                            <form action="/search-results" method="GET" name="search_form">
                                <?php //        echo st()->load_template( 'search-loading' );
                                if(isset($_GET['start'])):
                                    ?>
                                    <input type="hidden" name="start" value="<?php echo $_GET['start'] ?>">
                                    <?php
                                endif;
                                ?>
                                <?php //        echo st()->load_template( 'search-loading' );
                                if(isset($_GET['end'])):
                                    ?>
                                    <input type="hidden" name="end" value="<?php echo $_GET['end'] ?>">
                                    <?php
                                endif;
                                ?>
                            <h3>Filter By:</h3>
                                <ul class="list booking-filters-list">
                                    <li><h5 class="booking-filters-title arrow">Bedrooms</h5>
                                        <div>

                                            <?php foreach ($bedRooms as $k=>$bedRoom): ?>
                                            <div class="checkbox" style="margin-left: 0px">
                                                <?php
                                                $checked = false;
                                                if(in_array(implode(',', array_keys($bedRoom)), $selectedBedrooms)){
                                                    $checked = true;
                                                }else if($k > 3 && $selectedBedrooms[0] > 3){
                                                    $checked = true;
                                                }
                                                ?>
                                                <label>
                                                    <input value="<?php echo implode(',', array_keys($bedRoom))?>" <?php echo $checked?'checked':''; ?>
                                                                                class="i-check i-check-tax" type="checkbox" name="bedrooms[]"
                                                                                style="position: absolute; opacity: 0;">
                                                        <ins class="iCheck-helper"
                                                             style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                                  <?php echo $k > 3?'3+':$k;?>
                                                </label>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </li>
                                    <li><h5 class="booking-filters-title arrow">Communities</h5>
                                        <div>
                                            <?php foreach ($locationResorts as $locationResort): ?>
                                                <div class="checkbox" style="margin-left: 0px">
                                                    <label>
                                                        <input value="<?php echo $locationResort->id; ?>" <?php echo in_array($locationResort->id, $selectedLocation)?'checked':''; ?>
                                                               class="i-check i-check-tax" type="checkbox" name="locations[]"
                                                               style="position: absolute; opacity: 0;">
                                                        <ins class="iCheck-helper"
                                                             style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                                        <?php echo $locationResort->name;?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </li>
                                    <li><h5 class="booking-filters-title arrow">Rental Type</h5>
                                        <div>
                                            <?php foreach ($rentalTypes as $rentalType): ?>
                                                <div class="checkbox" style="margin-left: 0px">
                                                    <label>
                                                        <input value="<?php echo $rentalType->id; ?>" <?php echo in_array($rentalType->id, $selectedRentalType)?'checked':''; ?>
                                                               class="i-check i-check-tax" type="checkbox" name="rental_type[]"
                                                               style="position: absolute; opacity: 0;">
                                                        <ins class="iCheck-helper"
                                                             style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                                        <?php echo $rentalType->name;?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </li>
                                    <li><h5 class="booking-filters-title"></h5>
                                        <div><button style="margin-top: 4px;" type="submit" class="btn btn-primary">Filter</button></div>
                                    </li>
                                </ul>
                            </form>
                        </aside>
                    </div>
                </div>
            </div><!--End .row--></div><!--End .container--></div>
</div>
<script type="application/javascript">
    jQuery(document).ready(function ($) {
            current_page = 1;

        $('li .pagination').click(function(){
            $('.full-page-absolute').show();
           if(!$(this).hasClass('current')){
               current_page = $(this).data('page');
               console.log(readCookie('favorites'));
               $.ajax( {
                    url:'<?php echo admin_url('admin-ajax.php'); ?>',
                    method:'POST',
                   xhrFields: {
                       withCredentials: true
                   },
                    data:{
                        action: 'paginate_results',
                        page: $(this).data('page')
                    },
                   success: function(response){
                        if(response.status == 'success'){
                            $('.loop_hotel').html(response.html);
                            $('.full-page-absolute').hide();
                            checkCurrentPage();
                            setCounts(response.data.showing_start, response.data.showing_end);
                        }
                   }
            })
           }
        })
        
        function checkCurrentPage() {
            $('.current').removeClass('current');
            $('.pager[data-page="'+current_page+'"]').addClass('current');
        }

        function setCounts(start, end) {
            $('#start_count').html(start);
            $('#end_count').html(end);
        }
        function readCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length >= 2) return parts.pop().split(";").shift();
        }
        $('.btn-fav').live('click', function(){
            hotel = $(this).data('hotel');
            elem = $(this);
            if($(this).children('i').hasClass('fa-heart-o')){
                method = 'add';
                $(this).children('i').removeClass('fa-heart-o');
                $(this).children('i').addClass('fa-heart');
            }else{
                method = 'remove';
                $(this).children('i').removeClass('fa-heart');
                $(this).children('i').addClass('fa-heart-o');
            }
            $.ajax( {
                url:'<?php echo admin_url('admin-ajax.php'); ?>',
                method:'POST',
                data:{
                    action: 'change_favorite',
                    method: method,
                    hotel : hotel,
                    fav : readCookie('favorites')
                },
                success: function(response){
                    console.log(response);
                    document.cookie = "favorites="+response+';path=/';
                }
            })
        })

        $('.sort').click(function () {
            $('.sort_menu').removeClass('active');
            $(this).parent('li').addClass('active')
            sort = $(this).data('sort');
            orderby = $(this).data('orderby');
            $('.full-page-absolute').show();
            $.ajax( {
                url:'<?php echo admin_url('admin-ajax.php'); ?>',
                method:'GET',
                data:{
                    action: 'change_sort',
                    orderby : orderby,
                    sort : sort,
                },
                success: function(response){
                    if(response.status == 'success'){
                        $('.loop_hotel').html(response.html);
                        $('.full-page-absolute').hide();
                        current_page = 1;
                        checkCurrentPage();
                        setCounts(response.data.showing_start, response.data.showing_end);
                    }
                }
            })
        })
//
    })
</script>