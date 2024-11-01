jQuery(document).ready(function(){
    jQuery('.submit').click(function(){
        //alert(1);
        var appkey = jQuery('.appkey').val();
        var apikey = jQuery('.apikey').val();
        var page = 1;
        var size = 10;
        jQuery.ajax({
            type : 'GET',
            url : 'https://public-staging.whatcx.com/v0/qr-widget?page='+page+'&size='+size+'',
            headers: {"app-key": appkey,"api-key" : apikey},
            success : function(response){
                jQuery.ajax({
                    type : 'GET',
                    url : 'https://jsonplaceholder.typicode.com/posts',
                    //headers: {"app-key": appkey,"api-key" : apikey},
                    success : function(response){
                        console.log(response);
                        jQuery.each(response, function(i, value) {
                            i = i + 1;
                            var body = "<tr>";
                            body    += "<td>" + i + "</td>";
                            body    += "<td>" +value.title+ "</td>";
                            body    += "<td><input type='checkbox' class='active cm-toggle'></td>";
                            body    += "</tr>";
                            $( body ).appendTo( jQuery( "tbody" ) );
                        });
                        jQuery('#example').DataTable({
                            bInfo : false
                        });
                    }
                });
            }
        });
        
    });

    jQuery('.switch').click(function(){
        alert(1);
        // alert(1);
        // if(jQuery('.active').is(':checked')){
        //     var id = jQuery(this).attr('data-id');
        //     alert(id);
        // }else{
        //     alert('false');
        // }
    });
});

