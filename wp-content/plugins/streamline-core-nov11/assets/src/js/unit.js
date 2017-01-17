/**
 * Plugin script for when display a single property.
 *
 * Note: this script depends streamlinecoreConfig (ajaxUrl) being enqueued by WordPress
 *
 * [TODO] this script makes masterslider.js throw the following error:
 *        "Uncaught TypeError: Cannot read property 'wakeup' of undefined"
 *        Seams to be when no image are available, not confirmed.
 */
jQuery(document).ready(function ($) {
  var height = jQuery('#masterslider2').data('slider-height');

  if(!jQuery.isNumeric(height))
    height = 420;

  var slider = new MasterSlider();
  slider.setup('masterslider2', {
    width: 750,
    height: height,
    space: 0,
    loop: true,
    view: 'fadeWave',
    layout: 'boxed'
  });

  slider.control('arrows');
  slider.control('lightbox');
  slider.control('circletimer', {color: "#FFFFFF", stroke: 9});
  slider.control('thumblist' , {autohide:false ,dir:'h',arrows:false});

  var height2 = jQuery('#masterslider').data('slider-height');

  if(!jQuery.isNumeric(height2))
    height2 = 420;

  var slider2 = new MasterSlider();
  slider2.setup('masterslider', {
    width: 540,
    height: height2,
    space: 0,
    loop: true,
    view: 'fadeWave',
    layout: 'partialview'
  });

  slider2.control('arrows');
  slider2.control('lightbox');
  slider2.control('circletimer', {color: "#FFFFFF", stroke: 9});

  offset = 0;
  if( $('body.admin-bar').length > 0 ) offset = 32;
  var topSpacingVal = offset + parseInt(jQuery('.sticky').attr('data-top-spacing'));
  var bottomSpacingVal = parseInt(jQuery('.sticky').attr('data-bottom-spacing'));

  jQuery('.sticky').sticky({topSpacing: topSpacingVal, bottomSpacing: bottomSpacingVal});
  // jQuery('#modal_days').change(function () {
  //   run_waitMe('#myModal .modal-content', 'bounce');
  //   var unit_id = jQuery('#unit_id').val();



    // jQuery.ajax({
    //   url: streamlinecoreConfig.ajaxUrl,
    //   method: 'POST',
    //   dataType: 'json',
    //   data: {
    //     action: 'streamlinecore-get-price',
    //     unit_id: unit_id,
    //     startdate: jQuery('#modal_checkin').val(),
    //     enddate: jQuery('#modal_days').val(),
    //     occupants: jQuery('#modal_adults').val(),
    //     occupants_small: jQuery('#modal_childs').val()
    //   },
    //   success: function (resp) {
    //     // hide waitMe
    //     hide_waitMe('#myModal .modal-content');

    //     // check response status
    //     if (resp.success === true) {
    //       jQuery('#myModal .modal-body .errorMsg').html('');
    //       jQuery('#group-enddate').css('display', 'block');
    //       jQuery('#group-adults').css('display', 'block');
    //       jQuery('#group-childs').css('display', 'block');
    //       jQuery('#group-days').css('display', 'none');
    //       jQuery('#btn-modal-book').removeAttr('disabled');
    //       jQuery('#modal_checkout').val(resp.data.checkout);
    //       jQuery('#td_modal_rent').html(resp.data.rent);
    //       jQuery('#td_modal_fees').html(resp.data.fees);
    //       jQuery('#td_modal_taxes').html(resp.data.taxes);
    //       jQuery('#td_modal_total').html(resp.data.total);
    //       jQuery('#td_modal_deposits').html(resp.data.deposit);
    //       jQuery('#table-breakdown').css('display', 'table');
    //     } else {
    //       jQuery('#myModal .modal-body .errorMsg').html('<div class="alert alert-danger">'+resp.data.message+'</div>');
    //     }
    //   }
    // });
  //});

  //   jQuery.ajax({
  //     url: resortproConfig.ajaxUrl+"get_price.php",
  //     dataType: 'json',
  //     method: 'post',
  //     data: 'ROOT=' + resortproConfig.root + '&unit_id=' + unit_id + '&startdate=' + jQuery('#modal_checkin').val() + '&enddate=' + jQuery('#modal_days').val() + '&occupants=' + jQuery('#modal_adults').val() + '&occupants_small=' + jQuery('#modal_childs').val(),
  //     success: function (data) {
  //       hide_waitMe('#myModal .modal-content');
  //       if (data.status) {
  //         jQuery('#myModal .modal-body .errorMsg').html('<div class="alert alert-danger">'+data.status.description+'</div>');
  //       } else {
  //         jQuery('#myModal .modal-body .errorMsg').html('');
  //         jQuery('#group-enddate').css('display', 'block');
  //         jQuery('#group-adults').css('display', 'block');
  //         jQuery('#group-childs').css('display', 'block');
  //         jQuery('#group-days').css('display', 'none');
  //         jQuery('#btn-modal-book').removeAttr('disabled');
  //         jQuery('#modal_checkout').val(data.checkout);
  //         jQuery('#td_modal_rent').html(data.rent);
  //         jQuery('#td_modal_fees').html(data.fees);
  //         jQuery('#td_modal_taxes').html(data.taxes);
  //         jQuery('#td_modal_total').html(data.total);
  //         jQuery('#td_modal_deposits').html(data.deposit);
  //         jQuery('#table-breakdown').css('display', 'table');
  //       }
  //     }
  //   });
  // });


  //   jQuery.ajax({
  //     url: resortproConfig.ajaxUrl+"get_price.php",
  //     dataType: 'json',
  //     method: 'post',
  //     data: 'ROOT=' + resortproConfig.root + '&unit_id=' + unit_id + '&startdate=' + jQuery('#modal_checkin').val() + '&enddate=' + jQuery('#modal_days').val() + '&occupants=' + jQuery('#modal_adults').val() + '&occupants_small=' + jQuery('#modal_childs').val(),
  //     success: function (data) {
  //       hide_waitMe('#myModal .modal-content');
  //       if (data.status) {
  //         jQuery('#myModal .modal-body .errorMsg').html('<div class="alert alert-danger">'+data.status.description+'</div>');
  //       } else {
  //         jQuery('#myModal .modal-body .errorMsg').html('');
  //         jQuery('#group-enddate').css('display', 'block');
  //         jQuery('#group-adults').css('display', 'block');
  //         jQuery('#group-childs').css('display', 'block');
  //         jQuery('#group-days').css('display', 'none');
  //         jQuery('#btn-modal-book').removeAttr('disabled');
  //         jQuery('#modal_checkout').val(data.checkout);
  //         jQuery('#td_modal_rent').html(data.rent);
  //         jQuery('#td_modal_fees').html(data.fees);
  //         jQuery('#td_modal_taxes').html(data.taxes);
  //         jQuery('#td_modal_total').html(data.total);
  //         jQuery('#td_modal_deposits').html(data.deposit);
  //         jQuery('#table-breakdown').css('display', 'table');
  //       }
  //     }
  //   });
  // });


  // jQuery('#myModal').on('hidden.bs.modal', function (e) {
  //   jQuery('#group-days').css('display', 'block');
  //   jQuery('#btn-modal-book').attr('disabled', 'disabled');
  //   jQuery('#table-breakdown').css('display', 'none');
  //   jQuery('#group-enddate').css('display', 'none');
  //   jQuery('#group-adults').css('display', 'none');
  //   jQuery('#group-childs').css('display', 'none');
  //   jQuery("#modal_days").val(jQuery("#modal_days option:first").val());
  // });



  jQuery('#btn-modal-book').click(function () {
    run_waitMe('#myModal .modal-content', 'bounce', 'Processing request...');
    jQuery("#modal_form").submit();
  });
});
