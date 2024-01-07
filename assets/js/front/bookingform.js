jQuery(document).ready(function($){
    $('#qazproperty_bookingform_submit').on('click',function(e){
        e.preventDefault();
        $.ajax({
            url: qazproperty_bookingform_var.ajaxurl,
            type: 'post',
            data: {
                action: 'booking_form',
                nonce: qazproperty_bookingform_var.nonce,
                name: $('#qazproperty_name').val(),
                email: $('#qazproperty_email').val(),
                phone: $('#qazproperty_phone').val(),
                price: $('#qazproperty_bookingform_price').val(),
                location: $('#qazproperty_bookingform_location').val(),
                agent: $('#qazproperty_bookingform_agent').val(),
            },
            success: function(data){
                $('#qazproperty_result').html(data);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        })
    });
})