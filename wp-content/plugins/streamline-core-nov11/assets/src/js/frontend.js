/**
 * jQuery functions
 */
(function ($) {
  // star ratings
  $.fn.stars = function () {
    return $(this).each(function () {
      // get the value
      var val = parseFloat($(this).html());
      // Make sure that the value is in 0 - 5 range, multiply to get width
      var size = Math.max(0, (Math.min(5, val))) * 16;
      // val = Math.round(val * 2) / 2;
      // Create stars holder
      var $span = $('<span />').width(size);
      // Replace the numerical value with stars
      $(this).html($span);
    })
  };

  $(document).ready(function () {    
    
    $('body').tooltip({
      selector: '.btn-fav, .petFriendly'
    });

    $('.btn-sort').click(function(){
      $('.btn-sort').removeClass('active');
      $(this).addClass('active');

    });
    $('span.rating').stars();

    var checkin_days = 0;
    if(!isNaN($('.datepicker').attr('data-checkin-days'))){
       checkin_days = $('.datepicker').attr('data-checkin-days');
    }

    $('.datepicker').datepicker({minDate: "+"+checkin_days+"d",dateFormat: 'm/d/yy' });
    $('.datepicker-end').datepicker({minDate: "+2d", dateFormat: 'm/d/yy' });
    $(".datepicker").change(function () {
      var frm = new Date(jQuery(this).val());
      var the_year = frm.getFullYear();
      if (the_year < 2000) the_year = 2000 + the_year %100;
      var nts = 1;
      if ($('#resortpro_min_days').attr('value')) {
        $nts = parseInt(jQuery('#resortpro_min_days').attr('value'));
      }

      var endpicker = $(this).attr('data-bindpicker');

      if (!(!endpicker || endpicker.length === 0)) {
        $(endpicker).datepicker("option", "minDate", to);

        if ($(this).attr('data-min-stay'))
          nts = parseInt(jQuery(this).attr('data-min-stay'));

        var to = new Date(the_year, frm.getMonth(), frm.getDate() + nts);

        $(endpicker).datepicker("option", "minDate", to);
        $(endpicker).val(to.format("mm/dd/yyyy"));
        $(endpicker).trigger('input');
      }
    });

    $('#slider-range').slider({
      range: true,
      min: 0,
      max: 500,
      values: [75, 300],
      slide: function (event, ui) {
        $('#amount').val('$' + ui.values[0] + ' - $' + ui.values[1]);
      }
    });

    $('#amount').val('$' + $('#slider-range').slider('values', 0) + ' - $' + $('#slider-range').slider('values', 1));

    $('#collapseFilters').on('shown.bs.collapse', function () {
      $('#btn-collapse span').html('Hide filters');
    });

    $('#collapseFilters').on('hidden.bs.collapse', function () {
      $('#btn-collapse span').html('Show more filters');
    });

    $('.btn-price-breakdown').click(function () {
      $('.breakdown-days-hidden').each(function () {

        if($(this).data('visible') == false){
          $(this).css('display', 'table-row');
          $(this).data('visible', true);

          $('.btn-price-breakdown span').html('Hide Breakdown');
        } else {
          $(this).css('display', 'none');
          $(this).data('visible', false);

          $('.btn-price-breakdown span').html('View Breakdown');
        }
      })
      return false;
    });

    $('.btn-taxes-breakdown').click(function () {
      
      $('.breakdown-taxes-hidden').each(function () {

        if ($(this).data('visible') == false) {
          $(this).css('display', 'table-row');
          $(this).data('visible', true);

          $('.btn-taxes-breakdown span').html('Hide Breakdown');
        } else {
          $(this).css('display', 'none');
          $(this).data('visible', false);

          $('.btn-taxes-breakdown span').html('View Breakdown');
        }
      })
      return false;
    });
  });

  $(document).delegate('*[data-toggle="lightbox"]', "click", function (event) {
    event.preventDefault();
    $(this).ekkoLightbox();
  });
})(jQuery);

function add_tooltip() {
  jQuery('.available').tooltipster({
    interactive: true,
    multiple: true
  });
}

function run_waitMe(container, effect, message) {
  message = typeof message !== 'undefined' ? message : 'Please wait ...';
  effect = typeof effect !== 'undefined' ? effect : 'bounce';

  jQuery(container).waitMe({
    effect: effect,
    text: message,
    bg: 'rgba(255,255,255,0.7)',
    color: '#000',
    sizeW: '',
    sizeH: '',
    source: 'img.svg'
  });
}

function hide_waitMe(container) {
  jQuery(container).waitMe('hide');
}
