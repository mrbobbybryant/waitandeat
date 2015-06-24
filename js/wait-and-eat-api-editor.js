/**
 * Created by Bobby on 6/23/15.
 */
jQuery(document).ready(function($) {
    $( '#editor').on( 'submit', function(e) {
        e.preventDefault();

        var title = $('#title').val();
        var phoneNumber = $( '#phone-number').val();
        var guestCount = $( '#guest-count').val();
        var JSONObj = {
            "title" :title,
            "status" :'publish'
        };

        var data = JSON.stringify( JSONObj );
        var url = WAIT_AND_EAT.url + '/guests';

        $.ajax({
            type:"POST",
            url: url,
            dataType : 'json',
            data: data,
            beforeSend : function( xhr ) {
                xhr.setRequestHeader( 'X-WP-Nonce', WAIT_AND_EAT.nonce );
            },

            success: function(response) {
                alert( WAIT_AND_EAT.successMessage );
                $( "#results").append( JSON.stringify( response ) );
            },
            failure: function( response ) {
                alert( WAIT_AND_EAT.failureMessage );
            }
        });
    });
});