define( [
        'jquery'
    ],
    function($) {
        "use strict";
        $(document).ready(function(){
            let link = BASE_URL+"customprice/index/processajax";
            let id = $('#product-id').text();
            $.ajax({url: link,
                dataType: 'json',
                data : {
                    'product' : id
                },
            success: function(result){
                if(result){
                    $('#custom-price').text('Your Price is '+result);
                    $('.price-wrapper').css('display','none');
                }
            }});
        });
    });
