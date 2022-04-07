$(function() {
    function removeNote() {
        $("#note-full-container").off('click', '.remove-note').on('click', '.remove-note', function(event) {
          event.stopPropagation();
          let idVal = $(this).data('notice-id');
        // Run AJax.
        $.ajax({
            url: '_delete_notice.php',
            type: 'post',
            data: {'notice_id':idVal},
            beforeSend: function( xhr ) {
            }
          })
            .done(function( d ) {
                //$(this).parents('.single-note-item').remove();
                // Call get notices.
                getNotes();
            })
            .fail(function( d ) {
                alert( 'Sorry, note could not be deleted.' );
            });
        });
    }

    function getNotes() {
        let data = null;
        // Run AJax.
        $.ajax({
            url: (typeof userPage !== 'undefined' && userPage == true ? 'admin/' : '' ) + '_get_notices.php',
            data: {},
          })
          .done(function( d ) {
            data = d.data;
            $( '#note-full-container' ).html(data);
            })
            .fail(function( d ) {
                data = d.responseJSON.data;
                $( '#note-full-container' ).html(data);
            })
            .always(function( d ) {
                $( '#note-full-container' ).html(data);
            })
    }

    // Run every 20s for auto update.
    setInterval(getNotes, 20000);

    var $btns = $('.note-link').click(function() {
        if (this.id == 'all-category') {
          var $el = $('.' + this.id).fadeIn();
          $('#note-full-container > div').not($el).hide();
        } if (this.id == 'important') {
          var $el = $('.' + this.id).fadeIn();
          $('#note-full-container > div').not($el).hide();
        } else {
          var $el = $('.' + this.id).fadeIn();
          $('#note-full-container > div').not($el).hide();
        }
        $btns.removeClass('active');
        $(this).addClass('active');  
    })

    $('#add-notes').on('click', function(event) {
        $('#addnotesmodal').modal('show');
        $('#btn-n-save').hide();
        $('#btn-n-add').show();
    })

    // Button add
    $("#btn-n-add").on('click', function(event) {
        event.preventDefault();

        /* Act on the event */
        var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth()); //January is 0!
      var yyyy = today.getFullYear();
      var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
      today = dd + ' ' + monthNames[mm]  + ' ' + yyyy;

        var $_noteTitle = document.getElementById('note-has-title').value;
        var $_noteDescription = document.getElementById('note-has-description').value;

        // Run AJax.
        $.ajax({
            url: '_add_notice.php',
            type: 'post',
            data: {'subject':$_noteTitle, 'message':$_noteDescription},
            beforeSend: function( xhr ) {
            }
          })
            .done(function( d ) {
                $('#addnotesmodal').modal('hide');
                // Call get notices.
                getNotes();
            })
            .fail(function( d ) {
               let data = d.responseJSON.data;
                $( '.alert-place' ).html(data);
            })

        removeNote();
    });

    $('#addnotesmodal').on('hidden.bs.modal', function (event) {
        event.preventDefault();
        document.getElementById('note-has-title').value = '';
        document.getElementById('note-has-description').value = '';
    })

    removeNote();
})

 $('#note-has-title').keyup(function() {
      var empty = false;
      $('#note-has-title').each(function() {
          if ($(this).val() == '') {
                  empty = true;
          }
      });

      if (empty) {
          $('#btn-n-add').attr('disabled', 'disabled'); 
      } else {
          $('#btn-n-add').removeAttr('disabled');
      }
}); 