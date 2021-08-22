(function($){

    $('#ail-update-audio').on('click', function(){

        $(this).css('background', '#999');
        $(this).text('generating...');

        $.ajax({
            url: Ajax.ajax_url,
            type: 'post',
            data: {
                action: 'update_speak_audio',
                post_id: Ajax.post_id
            },
            success: function(response){
				$('#ail-update-audio').css('background', '#ddd');
				$('#ail-update-audio').text('Done!');
				$('#ail-update-audio').prop('disabled', true)
            }
        })

    });

}(jQuery));
