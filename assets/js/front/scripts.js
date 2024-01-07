jQuery(document).ready(function($){

    $('a.qazproperty_add_to_wishlist').on('click',function(event) {
        event.preventDefault();
        var id = $(this).data('property-id');

        var qazproperty_add_to_wishlist = {
            success: function(){
                $('#post-' + id + ' a.qazproperty_add_to_wishlist').hide();

                $('#post-' + id + ' span.successfull_added').delay(2000).show();

            }
        };
        $('#qazproperty_add_to_wishlist_form_'+id).ajaxSubmit(qazproperty_add_to_wishlist);
    });

    $('a.qazproperty_remove_from_wishlist').on('click',function(event) {
        event.preventDefault();
        var id = $(this).data('property-id');

        $.ajax({
            url:$(this).attr('href'),
            type: 'POST',
            data: {
                qaz_property_id: $(this).data('property-id'),
                qaz_user_id: $(this).data('user-id'),
                action: 'qazproperty_remove_wishlist',
            },            
            dataType: 'html',
            success: function (result){
                $('#post-' + id).hide();
            }
        });
    });
    
});

