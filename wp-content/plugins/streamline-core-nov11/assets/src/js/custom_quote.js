(function($) {
  $(document).ready(function () {
    $('#customQuote .sticky').sticky({
      topSpacing: parseInt(jQuery('.sticky').attr('data-top-spacing')),
      bottomSpacing: parseInt(jQuery('.sticky').attr('data-bottom-spacing'))
    });

    $('.btn-book').click(function () {
      run_waitMe('.modal-dialog', 'bounce');
    });

    $('.btn-book2').click(function () {
      run_waitMe('body', 'bounce');
    });

    $('.btn-more-info').click(function() {
      run_waitMe('body', 'bounce');
      var id = jQuery(this).attr("data-id");
      jQuery("#more_info_"+id).submit();
      return false;
    });

    $('.share-with-friends').click(function(e) {
      e.preventDefault();

      // set element
      ele = $(this);

      // reset form
      $('#share-with-friends-messages').html('');
      $.each(["fnames", "femails", "name", "email", "msg", "slug", "hash"], function(idx, val) {
        $('#share-with-friends-' + val).val('');
      });

      // update form hidden values
      $('#share-with-friends-slug').val(ele.attr('data-slug'));
      $('#share-with-friends-hash').val(ele.attr('data-hash'));

      // ensure waitMe is not toggled on
      hide_waitMe('#share-with-friends-modal .modal-dialog');

      // show modal
      $('#share-with-friends-modal').modal();
    })

    $('#btn-share-with-friends-submit').click(function() {
      // toggle waitMe
      run_waitMe('#share-with-friends-modal .modal-dialog', 'bounce');
      // submit request
      $.ajax({
        url: streamlinecoreConfig.ajaxUrl,
        method: 'POST',
        dataType: 'json',
        data: {
          action: 'streamlinecore-share-with-friends',
          fnames: $('#share-with-friends-fnames').val(),
          femails: $('#share-with-friends-femails').val(),
          name: $('#share-with-friends-name').val(),
          email: $('#share-with-friends-email').val(),
          msg: $('#share-with-friends-msg').val(),
          slug: $('#share-with-friends-slug').val(),
          hash: $('#share-with-friends-hash').val(),
          nonce: $('#share-with-friends-nonce').val(),
        },
        success: function (resp) {
          // toggle waitMe
          hide_waitMe('#share-with-friends-modal .modal-dialog');
          // promt user of success/failure
          $('#share-with-friends-messages').html('<div class="alert alert-'+(resp.success === false ? 'danger' : 'success' )+' text-center"><p style="font-size:2em">' + resp.data.message + '</p></div>');
        }
      });
    });
/*
        $('.btn-share').click(function () {
          // set modal id
          var modal_id = $(this).attr('data-id');
          // toggle waitMe
          run_waitMe('#share-' + modal_id + ' .modal-dialog', 'bounce');
          // submit request
          $.ajax({
            url: streamlinecoreConfig.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            data: {
              action: 'streamlinecore-share-with-friends',
              fnames: $('#fnames').val(),
              femails: $('#emails').val(),
              name: $('#name').val(),
              email: $('#email').val(),
              hash: $('#hash').val(),
              msg: $('#msg').val(),
              url: $('#url-'+modal_id).val()
            },
            success: function (resp) {
              // toggle waitMe
              hide_waitMe('#share-' + modal_id + ' .modal-dialog');
              // promt user of success/failure
              $('.messages').html('<div class="alert alert-'+(resp.success === false ? 'danger' : 'success' )+' text-center"><p style="font-size:2em">' + resp.data.message + '</p></div>');
            }
          });
        });
*/


    function run_waitMe(container, effect) {
      $(container).waitMe({
        effect: effect,
        text: 'Please wait...',
        bg: 'rgba(255,255,255,0.7)',
        color: '#000',
        sizeW: '',
        sizeH: '',
        source: 'img.svg',
        onClose: function () {}
      });
    }

    function hide_waitMe(container) {
      jQuery(container).waitMe('hide');
    }
  });
})( jQuery );
