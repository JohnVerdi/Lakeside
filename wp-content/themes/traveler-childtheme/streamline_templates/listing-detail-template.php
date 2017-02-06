<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all resortpro listings by default.
 * You can override this template by creating your own "my_theme/template/page-resortpro-listing-detail.php" file
 *
 * @package    ResortPro
 * @since      v2.0
 */
//print_r($property['gallery']);
?>
<style>
    .thumb-img-pages {
        background: 50% 75% no-repeat;
        background-size: cover ;
        height: 500px;
        width: 100%;
    }
    .booking-item-details {
        border: 1px solid #aaa;
        -webkit-border-radius:6px;
        -moz-border-radius:6px;
        border-radius:6px;
        padding: 15px;
    }
</style>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="/"><?php _e( 'Home', 'streamline-core' ) ?></a></li>
        <li><a href="/search-results"><?php _e( 'All Rentals', 'streamline-core' ) ?></a></li>
        <li><a href="/search-results?area_id=<?php echo $property['location_area_id'] ?>"><?php echo $property['location_area_name'] ?></a></li>
        <li class="active">
            <?php
            if(empty($property['name']) || $property['name'] == 'Home' ){
                echo $property['location_name'];
            }else{
                echo $property['name'];
            }
            ?>
        </li>
    </ol>
</div>
<div id="single-room" class="booking-item-details">
    <div class="thumb">
        <div class="thumb-img-pages" style="background-image: url('<?php echo $property['gallery']['image'][0]['image_path'];?>')" ></div>
    </div>
    <div class="container">
        <div class="vc_row wpb_row st bg-holder custom-row-single-room">
            <div class="container ">
                <div class="row">
                    <div class="custom-row-single-room wpb_column column_container col-md-8">
                        <div class="vc_column-inner wpb_wrapper">

                            <div class="booking-item-details no-border-top">
                                <div class="row">
                                    <div class="col-xs-12 col-md-12">
                                        <h2 class="title"><?php echo $property['name'];?></h2>
                                        <div class="booking-item-rating">
                                            <div class="pull-left" style="margin: 20px 0;">
                                                <strong><a href="#" title="locateion"><?php echo $property['city'];?></a></strong>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-6 col-sm-3">
                                                <div class="facility-item" rel="tooltip" data-placement="top" title="" data-original-title="Sleeps">
                                                    <i class="fa fa-male"></i>
                                                    <h5 class="booking-item-feature-sign">Sleeps <?php echo $property['max_adults'];?></h5>
                                                </div>
                                            </div>
<!--                                            <div class="col-xs-6 col-sm-3">-->
<!--                                                <div class="facility-item" rel="tooltip" data-placement="top" title="" data-original-title="Childs">-->
<!--                                                    <i class="im im-children"></i>-->
<!--                                                    <h5 class="booking-item-feature-sign">4 children</h5>-->
<!--                                                </div>-->
<!--                                            </div>-->
                                            <div class="col-xs-6 col-sm-3">
                                                <div class="facility-item" rel="tooltip" data-placement="top" title="" data-original-title="Beds">
                                                    <i class="im im-bed"></i>
                                                    <h5 class="booking-item-feature-sign">Beds <?php echo $property['max_occupants'];?></h5>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-3">
                                                <div class="facility-item" rel="tooltip" data-placement="top" title="" data-original-title="Room footage (square feet)">
                                                    <i class="fa fa-tint" aria-hidden="true"></i>
                                                    <h5 class="booking-item-feature-sign"><?php echo $property['square_foots'];?> m</h5>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="vc_empty_space" style="height: 30px"><span class="vc_empty_space_inner"></span></div>
                            <div class="wpb_tabs wpb_content_element" data-interval="0">
                                <div class="wpb_wrapper wpb_tour_tabs_wrapper ui-tabs vc_clearfix ui-widget ui-widget-content ui-corner-all">
                                    <!-- Slider -->
                                    <div id="tab-ab33b9ce-599d-6" class="ui-tabs-panel wpb_ui-tabs-hide vc_clearfix ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="false" style="display: block;">
                                        <div class="fotorama"
                                             data-allowfullscreen="true"
                                             data-nav="thumbs">
                                            <?php foreach($property['gallery']['image'] as $image):?>
                                                <img src="<?php echo $image['image_path'];?>">
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                    <!-- Slider End -->
                                    <div id="tab-2adfa48d-4e61-4" class="ui-tabs-panel wpb_ui-tabs-hide vc_clearfix ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-2" role="tabpanel" aria-hidden="true" style="display: none;">
                                        <div class="room-facility about_listing">
                                            <h3 class="booking-item-title">About This Listing</h3>
                                            <div class="row list-facility">
                                                <div class="col-xs-12">
                                                    <div class="col-xs-12 item">
                                                        <div class="row">
                                                            <div class="col-xs-5 col-sm-3">
                                                                <strong>Check in/out time</strong>
                                                            </div>
                                                            <div class="col-xs-7 col-sm-9">
                                                                <div class="row">
                                                                    <div class="col-xs-12 col-sm-6 sub-item">
                                                                        <span>Check In time: <strong>12:00 pm</strong></span>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-6 sub-item">
                                                                        <span>Check Out time: <strong>12:00 pm</strong></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 item">
                                                        <div class="row">
                                                            <div class="col-xs-5 col-sm-3">
                                                                <strong>The Space</strong>
                                                            </div>
                                                            <div class="col-xs-7 col-sm-9">
                                                                <div class="row">
                                                                    <div class="col-xs-12 col-sm-6 sub-item">
                                                                        <span>Adult number: <strong>4</strong></span>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-6 sub-item">
                                                                        <span>Bed number: <strong>4</strong></span>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-6 sub-item">
                                                                        <span>Children number: <strong>4</strong></span>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-6 sub-item">
                                                                        <span>Room Footage: <strong>45</strong></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 item">
                                                        <div class="row">
                                                            <div class="col-xs-5 col-sm-3">
                                                                <strong>Prices</strong>
                                                            </div>
                                                            <div class="col-xs-7 col-sm-9">
                                                                <div class="row">
                                                                    <div class="col-xs-12 col-sm-6 sub-item">
                                                                        <span>Price: <strong>$335,00</strong></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 item">
                                                        <div class="row">
                                                            <div class="col-xs-5 col-sm-3">
                                                                <strong>Room facilities</strong>
                                                            </div>
                                                            <div class="col-xs-7 col-sm-9">
                                                                <div class="row">
                                                                    <div class="col-xs-12 col-sm-6 sub-item">
                                                    <span>
                                                    <i class="im im-air mr10"></i>
                                                    Air Conditioning                        	</span>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-6 sub-item">
                                                    <span>
                                                    <i class="im im-bathtub mr10"></i>
                                                    Bathtub                        	</span>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-6 sub-item">
                                                    <span>
                                                    <i class="im im-bar mr10"></i>
                                                    Mini Bar                        	</span>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-6 sub-item">
                                                    <span>
                                                    <i class="im im-soundproof mr10"></i>
                                                    Soundproof                        	</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab-1439375290816-2-0" class="ui-tabs-panel wpb_ui-tabs-hide vc_clearfix ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-3" role="tabpanel" aria-hidden="true" style="display: none;">
                                        <div class="vc_empty_space" style="height: 15px"><span class="vc_empty_space_inner"></span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="wpb_text_column wpb_content_element ">
                                <div class="wpb_wrapper">
                                    <h3>Availability</h3>
                                </div>
                            </div>
                            <div class="row calendar-wrapper mb20" data-post-id="4587">
                                <div class="col-xs-12 calendar-wrapper-inner">
                                    <div class="overlay-form" style="display: none;"><i class="fa fa-refresh text-color"></i></div>
                                    <div class="calendar-content fc fc-ltr fc-unthemed">
                                        <div class="fc-toolbar">
                                            <div class="fc-left"><button type="button" class="fc-prev-button fc-button fc-state-default fc-corner-left fc-corner-right"><span class="fc-icon fc-icon-left-single-arrow"></span></button></div>
                                            <div class="fc-right"><button type="button" class="fc-next-button fc-button fc-state-default fc-corner-left fc-corner-right"><span class="fc-icon fc-icon-right-single-arrow"></span></button></div>
                                            <div class="fc-center">
                                                <h2>February 2017</h2>
                                            </div>
                                            <div class="fc-clear"></div>
                                        </div>
                                        <div class="fc-view-container">
                                            <div class="fc-view fc-month-view fc-basic-view">
                                                <table>
                                                    <thead class="fc-head">
                                                    <tr>
                                                        <td class="fc-widget-header">
                                                            <div class="fc-row fc-widget-header">
                                                                <table>
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="fc-day-header fc-widget-header fc-mon">Mon</th>
                                                                        <th class="fc-day-header fc-widget-header fc-tue">Tue</th>
                                                                        <th class="fc-day-header fc-widget-header fc-wed">Wed</th>
                                                                        <th class="fc-day-header fc-widget-header fc-thu">Thu</th>
                                                                        <th class="fc-day-header fc-widget-header fc-fri">Fri</th>
                                                                        <th class="fc-day-header fc-widget-header fc-sat">Sat</th>
                                                                        <th class="fc-day-header fc-widget-header fc-sun">Sun</th>
                                                                    </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="fc-body">
                                                    <tr>
                                                        <td class="fc-widget-content">
                                                            <div class="fc-day-grid-container">
                                                                <div class="fc-day-grid">
                                                                    <div class="fc-row fc-week fc-widget-content" style="height: 51px;">
                                                                        <div class="fc-bg">
                                                                            <table>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="fc-day fc-widget-content fc-mon fc-other-month fc-past" data-date="2017-01-30"></td>
                                                                                    <td class="fc-day fc-widget-content fc-tue fc-other-month fc-past" data-date="2017-01-31"></td>
                                                                                    <td class="fc-day fc-widget-content fc-wed fc-past" data-date="2017-02-01"></td>
                                                                                    <td class="fc-day fc-widget-content fc-thu fc-today fc-state-highlight" data-date="2017-02-02"></td>
                                                                                    <td class="fc-day fc-widget-content fc-fri fc-future" data-date="2017-02-03"></td>
                                                                                    <td class="fc-day fc-widget-content fc-sat fc-future" data-date="2017-02-04"></td>
                                                                                    <td class="fc-day fc-widget-content fc-sun fc-future" data-date="2017-02-05"></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="fc-content-skeleton">
                                                                            <table>
                                                                                <thead>
                                                                                <tr>
                                                                                    <td class="fc-day-number fc-mon fc-other-month fc-past" data-date="2017-01-30">30</td>
                                                                                    <td class="fc-day-number fc-tue fc-other-month fc-past" data-date="2017-01-31">31</td>
                                                                                    <td class="fc-day-number fc-wed fc-past" data-date="2017-02-01">1</td>
                                                                                    <td class="fc-day-number fc-thu fc-today fc-state-highlight" data-date="2017-02-02">2</td>
                                                                                    <td class="fc-day-number fc-fri fc-future" data-date="2017-02-03">3</td>
                                                                                    <td class="fc-day-number fc-sat fc-future" data-date="2017-02-04">4</td>
                                                                                    <td class="fc-day-number fc-sun fc-future" data-date="2017-02-05">5</td>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled next_month btn" title="" '="" data-original-title="">30</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled next_month btn" title="" '="" data-original-title="">31</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">01</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">02</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">03</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">04</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">05</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="fc-row fc-week fc-widget-content" style="height: 51px;">
                                                                        <div class="fc-bg">
                                                                            <table>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="fc-day fc-widget-content fc-mon fc-future" data-date="2017-02-06"></td>
                                                                                    <td class="fc-day fc-widget-content fc-tue fc-future" data-date="2017-02-07"></td>
                                                                                    <td class="fc-day fc-widget-content fc-wed fc-future" data-date="2017-02-08"></td>
                                                                                    <td class="fc-day fc-widget-content fc-thu fc-future" data-date="2017-02-09"></td>
                                                                                    <td class="fc-day fc-widget-content fc-fri fc-future" data-date="2017-02-10"></td>
                                                                                    <td class="fc-day fc-widget-content fc-sat fc-future" data-date="2017-02-11"></td>
                                                                                    <td class="fc-day fc-widget-content fc-sun fc-future" data-date="2017-02-12"></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="fc-content-skeleton">
                                                                            <table>
                                                                                <thead>
                                                                                <tr>
                                                                                    <td class="fc-day-number fc-mon fc-future" data-date="2017-02-06">6</td>
                                                                                    <td class="fc-day-number fc-tue fc-future" data-date="2017-02-07">7</td>
                                                                                    <td class="fc-day-number fc-wed fc-future" data-date="2017-02-08">8</td>
                                                                                    <td class="fc-day-number fc-thu fc-future" data-date="2017-02-09">9</td>
                                                                                    <td class="fc-day-number fc-fri fc-future" data-date="2017-02-10">10</td>
                                                                                    <td class="fc-day-number fc-sat fc-future" data-date="2017-02-11">11</td>
                                                                                    <td class="fc-day-number fc-sun fc-future" data-date="2017-02-12">12</td>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">06</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">07</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">08</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">09</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">10</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">11</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">12</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="fc-row fc-week fc-widget-content" style="height: 51px;">
                                                                        <div class="fc-bg">
                                                                            <table>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="fc-day fc-widget-content fc-mon fc-future" data-date="2017-02-13"></td>
                                                                                    <td class="fc-day fc-widget-content fc-tue fc-future" data-date="2017-02-14"></td>
                                                                                    <td class="fc-day fc-widget-content fc-wed fc-future" data-date="2017-02-15"></td>
                                                                                    <td class="fc-day fc-widget-content fc-thu fc-future" data-date="2017-02-16"></td>
                                                                                    <td class="fc-day fc-widget-content fc-fri fc-future" data-date="2017-02-17"></td>
                                                                                    <td class="fc-day fc-widget-content fc-sat fc-future" data-date="2017-02-18"></td>
                                                                                    <td class="fc-day fc-widget-content fc-sun fc-future" data-date="2017-02-19"></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="fc-content-skeleton">
                                                                            <table>
                                                                                <thead>
                                                                                <tr>
                                                                                    <td class="fc-day-number fc-mon fc-future" data-date="2017-02-13">13</td>
                                                                                    <td class="fc-day-number fc-tue fc-future" data-date="2017-02-14">14</td>
                                                                                    <td class="fc-day-number fc-wed fc-future" data-date="2017-02-15">15</td>
                                                                                    <td class="fc-day-number fc-thu fc-future" data-date="2017-02-16">16</td>
                                                                                    <td class="fc-day-number fc-fri fc-future" data-date="2017-02-17">17</td>
                                                                                    <td class="fc-day-number fc-sat fc-future" data-date="2017-02-18">18</td>
                                                                                    <td class="fc-day-number fc-sun fc-future" data-date="2017-02-19">19</td>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">13</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">14</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">15</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">16</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">17</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">18</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">19</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="fc-row fc-week fc-widget-content" style="height: 51px;">
                                                                        <div class="fc-bg">
                                                                            <table>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="fc-day fc-widget-content fc-mon fc-future" data-date="2017-02-20"></td>
                                                                                    <td class="fc-day fc-widget-content fc-tue fc-future" data-date="2017-02-21"></td>
                                                                                    <td class="fc-day fc-widget-content fc-wed fc-future" data-date="2017-02-22"></td>
                                                                                    <td class="fc-day fc-widget-content fc-thu fc-future" data-date="2017-02-23"></td>
                                                                                    <td class="fc-day fc-widget-content fc-fri fc-future" data-date="2017-02-24"></td>
                                                                                    <td class="fc-day fc-widget-content fc-sat fc-future" data-date="2017-02-25"></td>
                                                                                    <td class="fc-day fc-widget-content fc-sun fc-future" data-date="2017-02-26"></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="fc-content-skeleton">
                                                                            <table>
                                                                                <thead>
                                                                                <tr>
                                                                                    <td class="fc-day-number fc-mon fc-future" data-date="2017-02-20">20</td>
                                                                                    <td class="fc-day-number fc-tue fc-future" data-date="2017-02-21">21</td>
                                                                                    <td class="fc-day-number fc-wed fc-future" data-date="2017-02-22">22</td>
                                                                                    <td class="fc-day-number fc-thu fc-future" data-date="2017-02-23">23</td>
                                                                                    <td class="fc-day-number fc-fri fc-future" data-date="2017-02-24">24</td>
                                                                                    <td class="fc-day-number fc-sat fc-future" data-date="2017-02-25">25</td>
                                                                                    <td class="fc-day-number fc-sun fc-future" data-date="2017-02-26">26</td>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">20</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">21</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">22</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">23</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">24</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">25</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">26</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="fc-row fc-week fc-widget-content" style="height: 51px;">
                                                                        <div class="fc-bg">
                                                                            <table>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="fc-day fc-widget-content fc-mon fc-future" data-date="2017-02-27"></td>
                                                                                    <td class="fc-day fc-widget-content fc-tue fc-future" data-date="2017-02-28"></td>
                                                                                    <td class="fc-day fc-widget-content fc-wed fc-other-month fc-future" data-date="2017-03-01"></td>
                                                                                    <td class="fc-day fc-widget-content fc-thu fc-other-month fc-future" data-date="2017-03-02"></td>
                                                                                    <td class="fc-day fc-widget-content fc-fri fc-other-month fc-future" data-date="2017-03-03"></td>
                                                                                    <td class="fc-day fc-widget-content fc-sat fc-other-month fc-future" data-date="2017-03-04"></td>
                                                                                    <td class="fc-day fc-widget-content fc-sun fc-other-month fc-future" data-date="2017-03-05"></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="fc-content-skeleton">
                                                                            <table>
                                                                                <thead>
                                                                                <tr>
                                                                                    <td class="fc-day-number fc-mon fc-future" data-date="2017-02-27">27</td>
                                                                                    <td class="fc-day-number fc-tue fc-future" data-date="2017-02-28">28</td>
                                                                                    <td class="fc-day-number fc-wed fc-other-month fc-future" data-date="2017-03-01">1</td>
                                                                                    <td class="fc-day-number fc-thu fc-other-month fc-future" data-date="2017-03-02">2</td>
                                                                                    <td class="fc-day-number fc-fri fc-other-month fc-future" data-date="2017-03-03">3</td>
                                                                                    <td class="fc-day-number fc-sat fc-other-month fc-future" data-date="2017-03-04">4</td>
                                                                                    <td class="fc-day-number fc-sun fc-other-month fc-future" data-date="2017-03-05">5</td>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">27</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled  btn" title="" '="" data-original-title="">28</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled next_month btn" title="" '="" data-original-title="">01</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled next_month btn" title="" '="" data-original-title="">02</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button disabled="" data-toggle="tooltip" data-placement="top" class="btn-disabled next_month btn" title="" '="" data-original-title="">03</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button data-toggle="tooltip" data-placement="top" class="btn-available next_month btn" title="" '="" data-original-title="Origin Price: $335,00">04</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button data-toggle="tooltip" data-placement="top" class="btn-available next_month btn" title="" '="" data-original-title="Origin Price: $335,00">05</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="fc-row fc-week fc-widget-content" style="height: 54px;">
                                                                        <div class="fc-bg">
                                                                            <table>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="fc-day fc-widget-content fc-mon fc-other-month fc-future" data-date="2017-03-06"></td>
                                                                                    <td class="fc-day fc-widget-content fc-tue fc-other-month fc-future" data-date="2017-03-07"></td>
                                                                                    <td class="fc-day fc-widget-content fc-wed fc-other-month fc-future" data-date="2017-03-08"></td>
                                                                                    <td class="fc-day fc-widget-content fc-thu fc-other-month fc-future" data-date="2017-03-09"></td>
                                                                                    <td class="fc-day fc-widget-content fc-fri fc-other-month fc-future" data-date="2017-03-10"></td>
                                                                                    <td class="fc-day fc-widget-content fc-sat fc-other-month fc-future" data-date="2017-03-11"></td>
                                                                                    <td class="fc-day fc-widget-content fc-sun fc-other-month fc-future" data-date="2017-03-12"></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="fc-content-skeleton">
                                                                            <table>
                                                                                <thead>
                                                                                <tr>
                                                                                    <td class="fc-day-number fc-mon fc-other-month fc-future" data-date="2017-03-06">6</td>
                                                                                    <td class="fc-day-number fc-tue fc-other-month fc-future" data-date="2017-03-07">7</td>
                                                                                    <td class="fc-day-number fc-wed fc-other-month fc-future" data-date="2017-03-08">8</td>
                                                                                    <td class="fc-day-number fc-thu fc-other-month fc-future" data-date="2017-03-09">9</td>
                                                                                    <td class="fc-day-number fc-fri fc-other-month fc-future" data-date="2017-03-10">10</td>
                                                                                    <td class="fc-day-number fc-sat fc-other-month fc-future" data-date="2017-03-11">11</td>
                                                                                    <td class="fc-day-number fc-sun fc-other-month fc-future" data-date="2017-03-12">12</td>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button data-toggle="tooltip" data-placement="top" class="btn-available next_month btn" title="" '="" data-original-title="Origin Price: $335,00">06</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button data-toggle="tooltip" data-placement="top" class="btn-available next_month btn" title="" '="" data-original-title="Origin Price: $335,00">07</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button data-toggle="tooltip" data-placement="top" class="btn-available next_month btn" title="" '="" data-original-title="Origin Price: $335,00">08</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button data-toggle="tooltip" data-placement="top" class="btn-available next_month btn" title="" '="" data-original-title="Origin Price: $335,00">09</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button data-toggle="tooltip" data-placement="top" class="btn-available next_month btn" title="" '="" data-original-title="Origin Price: $335,00">10</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button data-toggle="tooltip" data-placement="top" class="btn-available next_month btn" title="" '="" data-original-title="Origin Price: $335,00">11</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td class="fc-event-container">
                                                                                        <a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end">
                                                                                            <div class="fc-content"><button data-toggle="tooltip" data-placement="top" class="btn-available next_month btn" title="" '="" data-original-title="Origin Price: $335,00">12</button></div>
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 mt10">
                                    <div class="calendar-bottom">
                                        <div class="item ">
                                            <span class="color available"></span>
                                            <span> Available</span>
                                        </div>
                                        <div class="item cellgrey">
                                            <span class="color"></span>
                                            <span>  Not Available</span>
                                        </div>
                                        <div class="item still ">
                                            <span class="color"></span>
                                            <span>  Still Available</span>
                                        </div>
                                        <!-- <div class="item ">
                                           <span class="color bgr-main"></span>
                                           <span> Selected</span>
                                           </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpb_column column_container col-md-4">
                        <div class="vc_column-inner wpb_wrapper">
                            <div class="hotel-room-form" style="width: auto; top: 0px;">
                                <!-- <div class="overlay-form"><i class="fa fa-refresh text-color"></i></div> -->
                                <div class="price bgr-main clearfix">
                                    <div class="pull-left">
                                        <span class="text-lg">$335,00</span>
                                    </div>
                                    <div class="pull-right">
                                        per 1 Night(s)
                                    </div>
                                </div>
                                <form id="form-booking-inpage" class="single-room-form ng-pristine ng-valid" method="post">
                                    <div class="search_room_alert "></div>
                                    <div class="message_box mb10"></div>
                                    <input type="hidden" id="room_search" name="room_search" value="67dfe232c9"><input type="hidden" name="_wp_http_referer" value="/hotel_room/sample-detail-page/">
                                    <div class="input-daterange" data-date-format="mm/dd/yyyy" data-booking-period="30">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group form-group-icon-left">
                                                    <label for="field-hotelroom-checkin">Check in</label>
                                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                    <input id="field-hotelroom-checkin" data-post-id="4587" placeholder="Select date" class="form-control checkin_hotel" value="02/02/2017" name="check_in" type="text">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group form-group-icon-left">
                                                    <label for="field-hotelroom-checkout">Check out</label>
                                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                    <input id="field-hotelroom-checkout" data-post-id="4587" placeholder="Select date" class="form-control checkout_hotel" value="02/03/2017" name="check_out" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group form-group-select-plus">
                                                <label for="field-hotelroom-room">Room(s)</label>
                                                <select id="field-hotelroom-room" name="room_num_search" class="form-control room_num_search">
                                                    <option selected="selected" value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                    <option value="16">16</option>
                                                    <option value="17">17</option>
                                                    <option value="18">18</option>
                                                    <option value="19">19</option>
                                                    <option value="20">20</option>
                                                    <option value="21">21</option>
                                                    <option value="22">22</option>
                                                    <option value="23">23</option>
                                                    <option value="24">24</option>
                                                    <option value="25">25</option>
                                                    <option value="26">26</option>
                                                    <option value="27">27</option>
                                                    <option value="28">28</option>
                                                    <option value="29">29</option>
                                                    <option value="30">30</option>
                                                    <option value="31">31</option>
                                                    <option value="32">32</option>
                                                    <option value="33">33</option>
                                                    <option value="34">34</option>
                                                    <option value="35">35</option>
                                                    <option value="36">36</option>
                                                    <option value="37">37</option>
                                                    <option value="38">38</option>
                                                    <option value="39">39</option>
                                                    <option value="40">40</option>
                                                    <option value="41">41</option>
                                                    <option value="42">42</option>
                                                    <option value="43">43</option>
                                                    <option value="44">44</option>
                                                    <option value="45">45</option>
                                                    <option value="46">46</option>
                                                    <option value="47">47</option>
                                                    <option value="48">48</option>
                                                    <option value="49">49</option>
                                                    <option value="50">50</option>
                                                    <option value="51">51</option>
                                                    <option value="52">52</option>
                                                    <option value="53">53</option>
                                                    <option value="54">54</option>
                                                    <option value="55">55</option>
                                                    <option value="56">56</option>
                                                    <option value="57">57</option>
                                                    <option value="58">58</option>
                                                    <option value="59">59</option>
                                                    <option value="60">60</option>
                                                    <option value="61">61</option>
                                                    <option value="62">62</option>
                                                    <option value="63">63</option>
                                                    <option value="64">64</option>
                                                    <option value="65">65</option>
                                                    <option value="66">66</option>
                                                    <option value="67">67</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group form-group-select-plus">
                                                <label for="field-hotelroom-adult">Adults</label>
                                                <select id="field-hotelroom-adult" name="adult_number" class="form-control adult_number">
                                                    selected='selected'
                                                    <option selected="selected" value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group form-group-select-plus">
                                                <label for="field-hotelroom-children">Children</label>
                                                <select id="field-hotelroom-children" name="child_number" class="form-control child_number">
                                                    selected='selected'
                                                    <option selected="selected" value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <label>Extra</label>
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td width="80%">
                                                <label for="field-extra_adult" class="ml20 mt5">Adult ($268,00)</label>
                                                <input type="hidden" name="extra_price[price][extra_adult]" value="268">
                                                <input type="hidden" name="extra_price[title][extra_adult]" value="Adult">
                                            </td>
                                            <td width="20%">
                                                <select style="width: 100px" class="form-control app" name="extra_price[value][extra_adult]" id="field-extra_adult">
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="80%">
                                                <label for="field-extra_children" class="ml20 mt5">Children ($234,50)</label>
                                                <input type="hidden" name="extra_price[price][extra_children]" value="234.5">
                                                <input type="hidden" name="extra_price[title][extra_children]" value="Children">
                                            </td>
                                            <td width="20%">
                                                <select style="width: 100px" class="form-control app" name="extra_price[value][extra_children]" id="field-extra_children">
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="80%">
                                                <label for="field-extra_service" class="ml20 mt5">Vip services ($167,50)</label>
                                                <input type="hidden" name="extra_price[price][extra_service]" value="167.5">
                                                <input type="hidden" name="extra_price[title][extra_service]" value="Vip services">
                                            </td>
                                            <td width="20%">
                                                <select style="width: 100px" class="form-control app" name="extra_price[value][extra_service]" id="field-extra_service">
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="text-right">
                                        <input class=" btn btn-primary btn_hotel_booking" value="Book Now" type="submit">
                                    </div>
                                    <input name="action" value="hotel_add_to_cart" type="hidden">
                                    <input name="item_id" value="986" type="hidden">
                                    <input name="room_id" value="4587" type="hidden">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<span class="hidden st_single_hotel_room" data-fancy_arr="1"></span>

<!--OLD THEME-->
<div id="primary" class="container content-area" ng-controller="PropertyController as pCtrl">
    <main id="main" class="site-main" role="main" ng-init="propertyId=<?php echo $property['id']; ?>;initializeData();getRatesDetails(<?php echo $property['id']; ?>);getRoomDetails(<?php echo $property['id']; ?>);">

        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li><a href="/"><?php _e( 'Home', 'streamline-core' ) ?></a></li>
                    <li><a href="/search-results"><?php _e( 'All Rentals', 'streamline-core' ) ?></a></li>
                    <li><a href="/search-results?area_id=<?php echo $property['location_area_id'] ?>"><?php echo $property['location_area_name'] ?></a></li>
                    <li class="active">
                        <?php
                        if(empty($property['name']) || $property['name'] == 'Home' ){
                            echo $property['location_name'];
                        }else{
                            echo $property['name'];
                        }
                        ?>
                    </li>
                </ol>
            </div>

            <div class="col-md-8">
                <h1 class="property-title">
                <?php
                if(empty($property['name']) || $property['name'] == 'Home' ){
                    echo $property['location_name'];
                }else{
                    echo $property['name'];
                }
                ?>
                </h1>
            </div>

            <div class="col-md-4 unit-rating">
                <?php if($property['rating_average'] > 0): ?>
                    <div class="star-rating text-right" style="vertical-align:top">
                        <div style="display: inline-block"
                             class="star-rating"
                             star-rating
                             rating-value="<?php echo $property['rating_average'] ?>"
                             data-max="5">
                        </div>
                        <?php
                        $reviews_txt = ' ' . ($property['rating_count'] > 1) ? __( 'reviews', 'streamline-core' ) : __( 'review', 'streamline-core' );
                        ?>
                        <p style="vertical-align:top; display:inline-block; font-size:1em !important; line-height:36px; width:auto !important">(<?php echo $property['rating_count'] ?> <?php echo $reviews_txt ?> )</p>
                    </div>
                <?php endif;?>
            </div>
            <input type="hidden" value="<?php echo $property['id'] ?>" id="unit_id">
        </div>
        <div class="row">
            <div class="col-md-8">

                <div class="ms-partialview-template" id="partial-view-1">

                    <div class="master-slider ms-skin-default" id="masterslider" data-slider-height="<?php echo $slider_height; ?>">
                            <?php
                            if(count($property_gallery) > 0){
                                foreach ($property_gallery as $image): ?>
                                <div class="ms-slide">


                                     <?php if($show_captions && is_string($image['description'])): ?>
                                    <div class="ms-layer ms-caption" style="top:10px; left:30px;">
                                        <?php echo $image['description'] ?>
                                    </div>
                                    <?php endif; ?>

                                </div>
                            <?php
                                endforeach;
                            }else{
                            ?>
                                <div class="ms-slide">
                                    <img src="<?php ResortPro()->assets_url('masterslider/blank.gif'); ?>"
                                         data-src="<?php echo $property['default_image_path']; ?>"
                                         alt="<?php echo $property['name']; ?>" />
                                </div>
                            <?php
                            }
                            ?>
                    </div>
                </div>

                <table class="table table-details">
                    <tr>
                        <td><?php _e( 'Sleeps:', 'streamline-core' ) ?> <span><?php echo $property['max_occupants']; ?></span></td>
                        <td><?php _e( 'Bedrooms:', 'streamline-core' ) ?> <span><?php echo $property['bedrooms_number']; ?></span></td>
                        <td><?php _e( 'Bathrooms:', 'streamline-core' ) ?> <span><?php echo $property['bathrooms_number']; ?></span></td>
                        <td><?php _e( 'Pets:', 'streamline-core' ) ?> <span><?php echo $property['max_pets']; ?></span></td>
                    </tr>
                </table>

                <div ng-init="getPropertyRatesAndStay(<?php echo $property['id'] ?>);getPropertyReviews();">

                    <div class="hidden-sm hidden-xs">
                        <ul id="property-detail-tabs" class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#property-details-pane" aria-controls="property-details-pane"
                                   data-toggle="tab"><?php _e( 'Details', 'streamline-core' ) ?></a>
                            </li>
                            <?php if (isset($options['unit_tab_amenities']) && $options['unit_tab_amenities'] == 1 &&  (count($property['unit_amenities']['amenity']) > 0)): ?>
                                <li role="presentation">
                                    <a href="#property-amenities-pane" aria-contorls="property-amenities-pane"
                                       data-toggle="tab"><?php _e( 'Amenities', 'streamline-core' ) ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if (isset($options['unit_tab_location']) && $options['unit_tab_location'] == 1 && (!empty($property['location_latitude']) && !empty($property['location_longitude']))): ?>
                            <li role="presentation" ng-click="mapResize();">
                                <a href="#property-location-pane" aria-controls="property-location-pane"
                                   data-toggle="tab"><?php _e( 'Location', 'streamline-core' ) ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if(isset($options['unit_tab_reviews']) && $options['unit_tab_reviews'] == 1): ?>
                            <li role="presentation" ng-if="reviews.length > 0">
                                <a href="#property-reviews-pane" aria-controls="property-reviews-pane"
                                   data-toggle="tab"><?php _e( 'Reviews', 'streamline-core' ) ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if(isset($options['unit_tab_rates']) && $options['unit_tab_rates'] == 1): ?>
                            <li role="presentation">
                                <a href="#property-rates-pane" aria-controls="property-rates-pane"
                                   data-toggle="tab"><?php _e( 'Rates', 'streamline-core' ) ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if(isset($options['unit_tab_room_details']) && $options['unit_tab_room_details'] == 1): ?>
                            <li role="presentation">
                                <a href="#property-rooms-pane" aria-controls="property-rooms-pane"
                                   data-toggle="tab"><?php _e( 'Room Details', 'streamline-core' ) ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if(isset($options['unit_tab_availability']) && $options['unit_tab_availability'] == 1): ?>
                            <li role="presentation">
                                <a href="#property-availability-pane"
                                   aria-controls="property-availability-pane" data-toggle="tab"><?php _e( 'Availability', 'streamline-core' ) ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if(isset($options['unit_tab_floorplan']) && $options['unit_tab_floorplan'] == 1 && (!empty($property['floor_plan_url']))): ?>
                            <li role="presentation">
                                <a href="#property-floorplan-pane"
                                   aria-controls="property-floorplan-pane" data-toggle="tab"><?php _e( 'Floor Plan', 'streamline-core' ) ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if(isset($options['unit_tab_virtualtour']) && $options['unit_tab_virtualtour'] == 1 && (!empty($property['virtual_tour_url']))): ?>
                            <li role="presentation">
                                <a href="#property-virtualtour-pane"
                                   aria-controls="property-virtualtour-pane" data-toggle="tab"><?php _e( 'Virtual Tour', 'streamline-core' ) ?></a>
                            </li>
                            <?php endif; ?>
                        </ul>


                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="property-details-pane">
                                <div id="property-description">
                                    <h3 id="property-details-pane"><?php _e( 'Description', 'streamline-core' ) ?></h3>

                                    <div class="property_description">
                                        <?php
                                        if (is_string($property['description'])) {
                                            echo $property['description'];
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <?php if (isset($options['unit_tab_amenities']) && $options['unit_tab_amenities'] == 1 &&  (count($property['unit_amenities']['amenity']) > 0)): ?>
                                <div role="tabpanel" class="tab-pane" id="property-amenities-pane">
                                    <h3><?php _e( 'Amenities', 'streamline-core' ) ?></h3>
                                    <ul class="list-group row amenities">
                                        <?php
                                        if($property['unit_amenities']['amenity']['amenity_name']){
                                            ?>
                                                <li class="list-group-item col-xs-4">
                                                    <?php echo $property['unit_amenities']['amenity']['amenity_name']; ?>
                                                </li>
                                            <?php
                                        }else{
                                            foreach ($property['unit_amenities']['amenity'] as $amenity) {
                                            ?>
                                                <li class="list-group-item col-xs-4">
                                                    <?php echo $amenity['amenity_name']; ?>
                                                </li>
                                            <?php }
                                        }
                                         ?>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($options['unit_tab_location']) && $options['unit_tab_location'] == 1 && (!empty($property['location_latitude']) && !empty($property['location_longitude']))): ?>
                                <div role="tabpanel" class="tab-pane" id="property-location-pane">
                                    <div id="property-location">
                                        <h3 id=""><?php _e( 'Location', 'streamline-core' ) ?></h3>
                                        <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAQCDSl4cy4e3p23iVcjYV_CHWLMtxIKC8
  &q=<?php echo $property['location_latitude'] ?>,<?php echo $property['location_longitude'] ?>" style="width: 100%; height: 300px"></iframe>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(isset($options['unit_tab_availability']) && $options['unit_tab_availability'] == 1): ?>
                            <div id="property-availability-pane" role="tabpanel" class="tab-pane" class="availability">
                                <h3><?php _e( 'Availability', 'streamline-core' ) ?></h3>
                                <?php do_action('streamline-insert-calendar', $property['id'] ); ?>
                            </div>
                            <?php endif; ?>

                            <?php if(isset($options['unit_tab_reviews']) && $options['unit_tab_reviews'] == 1): ?>
                            <div id="property-reviews-pane" role="tabpanel" class="tab-pane">
                                <h3><?php _e( 'Reviews', 'streamline-core' ) ?></h3>
                                <div id="property-reviews">
                                    <div class="row row-review" ng-show="reviews.length > 0" ng-repeat="review in reviews">
                                        <div class="col-sm-8">
                                            <h3 ng-cloak ng-if="!isEmptyObject(review.title)">{[review.title]}</h3>
                                            <h3 ng-cloak ng-if="isEmptyObject(review.title)"><?php _e( 'Guest Review', 'streamline-core' ) ?></h3>
                                            <span class="by"><?php _e( 'by', 'streamline-core' ) ?> {[review.guest_name]} <?php _e( 'on', 'streamline-core' ) ?> {[review.creation_date]}</span>

                                            <div class="review-details">
                                                {[review.comments]}
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div style="display: inline-block" class="star-rating text-right" star-rating
                                            rating-value="review.points" data-max="5"></div>
                                        </div>
                                    </div>
                                    <div ng-show="!reviews.length > 0">
                                        <p><?php _e( 'No reviews have been entered for this unit.', 'streamline-core' ) ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(isset($options['unit_tab_rates']) && $options['unit_tab_rates'] == 1): ?>
                            <div id="property-rates-pane" role="tabpanel" class="tab-pane">
                                <h3><?php _e( 'Rates', 'streamline-core' ) ?></h3>
                                <div id="property-rates">
                                    <div id="rates-details">
                                        <table class="table table-striped table-bordered table-condensed table-hover" ng-if="rates_details.length >0">
                                            <thead>
                                                <tr>
                                                    <th><?php _e( 'Season', 'streamline-core' ) ?></th>
                                                    <th><?php _e( 'Period', 'streamline-core' ) ?></th>
                                                    <th><?php _e( 'Min. Stay', 'streamline-core' ) ?></th>
                                                    <th><?php _e( 'Nightly Rate', 'streamline-core' ) ?></th>
                                                    <th><?php _e( 'Weekly Rate', 'streamline-core' ) ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="row-rate" ng-repeat="rate in rates_details">
                                                    <td>{[rate.season_name]}</td>
                                                    <td>{[rate.period_begin]} - {[rate.period_end]}</td>
                                                    <td>{[rate.narrow_defined_days]}</td>
                                                    <td class="text-center"><span ng-if="rate.daily_first_interval_price" ng-bind="calculateMarkup(rate.daily_first_interval_price) | currency"></span></td>
                                                    <td class="text-center"><span ng-if="rate.weekly_price" ng-bind="calculateMarkup(rate.weekly_price) | currency"></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(isset($options['unit_tab_room_details']) && $options['unit_tab_room_details'] == 1): ?>
                            <div id="property-rooms-pane" role="tabpanel" class="tab-pane">
                                <h3>Rooms</h3>
                                <div id="room-details">
                                    <table class="table table-striped table-hover table-condensed table-bordered" ng-if="room_details.length >0">
                                        <thead>
                                            <tr>
                                                <th><?php _e( 'Room', 'streamline-core' ) ?></th>
                                                <th><?php _e( 'Beds', 'streamline-core' ) ?></th>
                                                <th><?php _e( 'Baths', 'streamline-core' ) ?></th>
                                                <th><?php _e( 'TVs', 'streamline-core' ) ?></th>
                                                <th><?php _e( 'Comments', 'streamline-core' ) ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr class="row-review" ng-repeat="room in room_details">
                                                <td>{[room.name]}</td>

                                                <td>
                                                    <span ng-if="!isArray(room.beds_details) && !isEmptyObject(room.beds_details)">
                                                        {[room.beds_details]}
                                                    </span>
                                                    <span ng-if="isArray(room.beds_details)">
                                                        <ul>
                                                            <li ng-repeat="bd in room.beds_details">
                                                                {[bd]}
                                                            </li>
                                                        </ul>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span ng-if="!isArray(room.bathroom_details) && !isEmptyObject(room.bathroom_details)">
                                                         {[room.bathroom_details]}
                                                    </span>
                                                    <span ng-if="isArray(room.bathroom_details)">
                                                        <ul>
                                                            <li ng-repeat="bd in room.bathroom_details">
                                                                {[bd]}
                                                            </li>
                                                        </ul>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span ng-if="!isArray(room.television_details) && !isEmptyObject(room.television_details)">
                                                         {[room.television_details]}
                                                    </span>
                                                    <span ng-if="isArray(room.television_details)">
                                                        <ul>
                                                            <li ng-repeat="bd in room.television_details">
                                                                {[bd]}
                                                            </li>
                                                        </ul>
                                                    </span>
                                                </td>
                                                <td style="width:250px"><span ng-if="!isEmptyObject(room.comments)">{[room.comments]}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if(isset($options['unit_tab_floorplan']) && $options['unit_tab_floorplan'] == 1 && (!empty($property['floor_plan_url']))): ?>
                            <div id="property-floorplan-pane" role="tabpanel" class="tab-pane">
                                <h3><?php _e( 'Floor Plan', 'streamline-core' ) ?></h3>
                                <div id="floor_plan">
                                    <?php echo $property['floor_plan_url']; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if(isset($options['unit_tab_virtualtour']) && $options['unit_tab_virtualtour'] == 1 && (!empty($property['virtual_tour_url']))): ?>
                            <div id="property-virtualtour-pane" role="tabpanel" class="tab-pane">
                                <h3><?php _e( 'Virtual Tour', 'streamline-core' ) ?></h3>
                                <div id="virtual_tour">
                                    <?php echo $property['virtual_tour_url']; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="hidden-md hidden-lg">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <?php _e( 'Details', 'streamline-core' ) ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        <div class="property_description">
                                            <?php
                                            if (is_string($property['description'])) {
                                                echo $property['description'];
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if (isset($options['unit_tab_amenities']) && $options['unit_tab_amenities'] == 1 &&  (count($property['unit_amenities']['amenity']) > 0)): ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                               aria-controls="collapseTwo">
                                                <?php _e( 'Amenities', 'streamline-core' ) ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="headingTwo">
                                        <div class="panel-body">
                                            <ul class="list-group row amenities">
                                                <?php
                                                foreach ($property['unit_amenities']['amenity'] as $amenity) {
                                                    ?>
                                                    <li class="list-group-item col-xs-6 col-sm-6">
                                                        <?php echo $amenity['amenity_name']; ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($options['unit_tab_location']) && $options['unit_tab_location'] == 1 && (!empty($property['location_latitude']) && !empty($property['location_longitude']))): ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingThree">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#collapseThree" aria-expanded="false"
                                               aria-controls="collapseThree">
                                                <?php _e( 'Location', 'streamline-core' ) ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="headingThree">
                                        <div class="panel-body">
                                            <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAQCDSl4cy4e3p23iVcjYV_CHWLMtxIKC8
  &q=<?php echo $property['location_latitude'] ?>,<?php echo $property['location_longitude'] ?>" style="width: 100%; height: 300px"></iframe>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(isset($options['unit_tab_reviews']) && $options['unit_tab_reviews'] == 1): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingFour">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapseReviews" aria-expanded="true" aria-controls="collapseReviews">
                                            <?php _e( 'Reviews', 'streamline-core' ) ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseReviews" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingFour">
                                    <div class="panel-body">
                                        <div class="row row-reviews" ng-show="reviews.length > 0" ng-repeat="review in reviews">
                                            <div class="col-sm-8">
                                                <h3 ng-cloak ng-if="!isEmptyObject(review.title)">{[review.title]}</h3>
                                                <h3 ng-cloak ng-if="isEmptyObject(review.title)"><?php _e( 'Guest Review', 'streamline-core' ) ?></h3>
                                                <span class="by"><?php _e( 'by', 'streamline-core' ) ?> {[review.guest_name]} <?php _e( 'on', 'streamline-core' ) ?> {[review.creation_date]}</span>

                                                <div class="review-details">
                                                    {[review.comments]}
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <span class="rating">{[review.points]}</span>
                                            </div>
                                        </div>
                                        <div ng-show="!reviews.length > 0">
                                            <?php _e( 'No reviews have been entered for this unit.', 'streamline-core' ) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(isset($options['unit_tab_availability']) && $options['unit_tab_availability'] == 1): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingFive">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapseAvailability" aria-expanded="true"
                                           aria-controls="collapseAvailability">
                                            <?php _e( 'Availability', 'streamline-core' ) ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseAvailability" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingFive">
                                    <div class="panel-body">
                                        <?php do_action('streamline-insert-calendar', $property['id'] ); ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(isset($options['unit_tab_rates']) && $options['unit_tab_rates'] == 1): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingRates">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapseRate" aria-expanded="true"
                                           aria-controls="collapseRate">
                                            <?php _e( 'Rates', 'streamline-core' ) ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseRate" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingRates">
                                    <div class="panel-body">
                                        <table class="table table-striped table-bordered table-condensed table-hover" ng-if="rates_details.length >0">
                                            <thead>
                                                <tr>
                                                    <th><?php _e( 'Season', 'streamline-core' ) ?></th>
                                                    <th><?php _e( 'Period', 'streamline-core' ) ?></th>
                                                    <th><?php _e( 'Min. Stay', 'streamline-core' ) ?></th>
                                                    <th><?php _e( 'Nightly Rate', 'streamline-core' ) ?></th>
                                                    <th><?php _e( 'Weekly Rate', 'streamline-core' ) ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="row-rate" ng-repeat="rate in rates_details">
                                                    <td>{[rate.season_name]}</td>
                                                    <td>{[rate.period_begin]} - {[rate.period_end]}</td>
                                                    <td>{[rate.narrow_defined_days]}</td>
                                                    <td class="text-center"><span ng-if="rate.daily_first_interval_price" ng-bind="calculateMarkup(rate.daily_first_interval_price) | currency"></span></td>
                                                    <td class="text-center"><span ng-if="rate.weekly_price" ng-bind="calculateMarkup(rate.weekly_price) | currency"></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(isset($options['unit_tab_room_details']) && $options['unit_tab_room_details'] == 1): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingRooms">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapseRooms" aria-expanded="true"
                                           aria-controls="collapseRooms">
                                            <?php _e( 'Room Details', 'streamline-core' ) ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseRooms" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingRooms">
                                    <div class="panel-body">
                                        <div id="room-details-mobile">
                                            <table class="table table-striped table-hover table-condensed table-bordered" ng-if="room_details.length >0">

                                                <thead>
                                                    <tr>
                                                        <th><?php _e( 'Room', 'streamline-core' ) ?></th>
                                                        <th><?php _e( 'Beds', 'streamline-core' ) ?></th>
                                                        <th><?php _e( 'Baths', 'streamline-core' ) ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="row-review" ng-repeat="room in room_details">
                                                        <td>{[room.name]}</td>

                                                        <td>
                                                            <span ng-if="!isArray(room.beds_details) && !isEmptyObject(room.beds_details)">
                                                                {[room.beds_details]}
                                                            </span>
                                                            <span ng-if="isArray(room.beds_details)">
                                                                <ul>
                                                                    <li ng-repeat="bd in room.beds_details">
                                                                        {[bd]}
                                                                    </li>
                                                                </ul>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span ng-if="!isArray(room.bathroom_details) && !isEmptyObject(room.bathroom_details)">
                                                                 {[room.bathroom_details]}
                                                            </span>
                                                            <span ng-if="isArray(room.bathroom_details)">
                                                                <ul>
                                                                    <li ng-repeat="bd in room.bathroom_details">
                                                                        {[bd]}
                                                                    </li>
                                                                </ul>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(isset($options['unit_tab_floorplan']) && $options['unit_tab_floorplan'] == 1 && (!empty($property['floor_plan_url']))): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingFloor">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapseFloorplan" aria-expanded="true"
                                           aria-controls="collapseFloorplan">
                                            <?php _e( 'Floor Plan', 'streamline-core' ) ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFloorplan" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingFloor">
                                    <div class="panel-body">
                                        <div id="floorplan-mobile">
                                            <?php echo $property['floor_plan_url']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(isset($options['unit_tab_virtualtour']) && $options['unit_tab_virtualtour'] == 1 && (!empty($property['virtual_tour_url']))): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTour">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapseVirtualtour" aria-expanded="true"
                                           aria-controls="collapseVirtualtour">
                                            <?php _e( 'Virtual Tour', 'streamline-core' ) ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseVirtualtour" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="headingTour">
                                    <div class="panel-body">
                                        <div id="virtualtour-mobile">
                                            <?php echo $property['virtual_tour_url']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4" id="resortpro-book-unit"
                 ng-init="maxOccupants='<?php echo $max_children; ?>'; isDisabled=true; total_reservation=0; book.unit_id=<?php echo $property['id'] ?>;book.checkin='<?php echo $start_date; ?>';book.checkout='<?php echo $end_date ?>';getPreReservationPrice(book,<?php echo $res ?>)">


                <div class="inquiry right-side">

                    <div class="alert alert-{[alert.type]} animate"
                         ng-repeat="alert in alerts">
                        <div ng-bind-html="alert.message | trustedHtml"></div>
                    </div>


                    <?php if(!empty($booknow_title)): ?>
                    <h3 class="text-center"><?php echo $booknow_title; ?></h3>
                    <?php endif; ?>


                    <form action="<?php echo $checkout_url ?>" method="post" name="resortpro_form_checkout">
                        <input type="hidden" name="resortpro_book_unit" value="<?php echo $nonce; ?>"/>
                        <input type="hidden" name="book_unit" value="<?php echo $property['id'] ?>"/>
                        <?php if(!empty($hash)): ?>
                        <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
                        <?php endif; ?>
                        <h3 class="price" ng-show="res == 0" ng-cloak >{[first_day_price | currency:undefined:0]}<span
                                class="text"> <?php _e( 'Per Night', 'streamline-core' ) ?></span>
                        </h3>

                        <h3 class="price" ng-show="res == 1 && total_reservation > 0" ng-cloak >{[total_reservation |
                            currency:undefined:0]} <span
                                class="text" style="font-size: 0.6em"><?php _e( 'including taxes and fees', 'streamline-core' ) ?></span></h3>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php _e( 'Arrive', 'streamline-core' ) ?></label>
                                    <input type="text"
                                           ng-model="book.checkin"
                                           id="book_start_date"
                                           name="book_start_date"
                                           class="form-control"
                                           show-days="renderCalendar(date, false)"
                                           update-price="getPreReservationPrice(book,1)"
                                           update-checkout="setCheckoutDate(date)"
                                           bookcheckin
                                           data-min-stay="<?php echo $min_stay ?>"
                                           data-checkin-days="<?php echo $checkin_days ?>"
                                        />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php _e( 'Depart', 'streamline-core' ) ?></label>
                                    <input type="text"
                                           ng-model="book.checkout"
                                           id="book_end_date"
                                           name="book_end_date"
                                           class="form-control"
                                           show-days="renderCalendar(date, true)"
                                           update-price="getPreReservationPrice(book,1)"
                                           bookcheckout
                                        />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" ng-init="book.occupants='<?php echo $occupants; ?>'">
                                    <label for="book_occupants"><?php echo $adults_label ?></label>
                                    <select
                                        ng-model="book.occupants"
                                        ng-change="getPreReservationPrice(book,1);"
                                        name="book_occupants"
                                        class="form-control">
                                        <?php
                                        for ($i = 1; $i <= $max_adults; $i++) {
                                        echo "<option value=\"{$i}\">{$i}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php if ($max_children > 0): ?>
                                <div class="col-md-6">
                                    <div class="form-group" ng-init="book.occupants_small='<?php echo $occupants_small; ?>'">
                                        <label for="book_occupants_small"><?php echo $children_label ?></label>
                                        <select
                                            name="book_occupants_small"
                                            class="form-control"
                                            ng-model="book.occupants_small"
                                            ng-change="getPreReservationPrice(book,1);">
                                            <?php
                                            for ($i = 0; $i <= $max_children; $i++) {
                                            echo "<option value=\"{$i}\">{$i}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($max_pets > 0): ?>
                            <div class="col-md-12">
                                <div class="form-group" ng-init="book.pets='<?php echo $pets; ?>'">
                                    <label for="book_pets"><?php echo $pets_label ?></label>
                                    <select
                                        name="book_pets"
                                        class="form-control"
                                        ng-model="book.pets"
                                        ng-change="getPreReservationPrice(book,1);">
                                        <?php
                                        for ($i = 0; $i <= $max_pets; $i++) {
                                        echo "<option value=\"{$i}\">{$i}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <table ng-show="res == 1 && total_reservation > 0"
                               class="table table-stripped table-bordered table-condensed">

                            <tr ng-if="days" ng-repeat="day in reservation_days">
                                <td ng-bind="day.date"></td>
                                <td class="text-right" ng-bind="calculateMarkup(day.price.toString()) | currency:undefined:2"></td>
                            </tr>
                            <tr ng-if="!days">
                                <td ng-bind="reservation_days.date"></td>
                                <td class="text-right"><span ng-bind="subTotal | currency:undefined:2"></span></td>
                            </tr>
                            <tr style="border-top:solid 2px #333">
                                <td><?php _e( 'Subtotal', 'streamline-core' ) ?></td>
                                <td class="text-right"><span ng-bind="subTotal | currency:undefined:2"></span></td>
                            </tr>
                            <tr ng-if="coupon_discount > 0">
                                <td><?php _e( 'Discount', 'streamline-core' ) ?></td>
                                <td class="text-right"><span ng-bind="coupon_discount | currency:undefined:0"></span></td>
                            </tr>
                            <tr>
                                <td><?php _e( 'Taxes and fees', 'streamline-core' ) ?></td>
                                <td class="text-right"><span ng-bind="taxes | currency:undefined:2"></span></td>
                            </tr>
                        </table>

                        <?php if(!(is_numeric($property['online_bookings']) && $property['online_bookings'] == 0)): ?>
                        <div class="form-group">
                            <button ng-disabled="isDisabled" id="resortpro_unit_submit" href="this.submit()"
                                    class="btn btn-lg btn-block btn-success">
                                <i class="glyphicon glyphicon-check"></i> <?php _e( 'Book Now', 'streamline-core' ) ?>
                            </button>
                        </div>
                        <?php endif; ?>

                    </form>

                    <?php do_action('streamline-insert-share', $property['seo_page_name'], $property['id'], $start_date, $end_date, $occupants, $occupants_small, $pets ); ?>
                </div>

                <div class="inquiry right-side" id="inquiry_box" style="margin-top:24px">

                    <?php if(!empty($inquiry_title)): ?>
                    <h3 class="text-center"><?php echo $inquiry_title; ?></h3>
                    <?php endif; ?>

                    <?php do_action('streamline-insert-inquiry', $property['location_name'], $property['id'], $max_adults, $max_children, $max_pets, $min_stay, $checkin_days, false, $start_date, $end_date, $occupants, $occupants_small, $pets ); ?>
                </div>

            </div>
        </div>

    </main>


    <!-- .site-main -->
</div><!-- .content-area -->
