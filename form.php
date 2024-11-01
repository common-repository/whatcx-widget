<?php 

    wp_enqueue_style( 'bootstrap.min.css', plugin_dir_url( __FILE__ ) . '/assets/css/bootstrap.min.css');
    wp_enqueue_style( 'dataTables.bootstrap4.min.css', plugin_dir_url( __FILE__ ) . '/assets/css/dataTables.bootstrap4.min.css');
    wp_enqueue_style( 'bootstrap4-toggle.min.css', plugin_dir_url( __FILE__ ) . '/assets/css/bootstrap4-toggle.min.css');
    wp_enqueue_style( 'own.css', plugin_dir_url( __FILE__ ) . '/assets/css/own.css');
    wp_enqueue_script( 'jquery.dataTables.min.js', plugin_dir_url( __FILE__ ) . '/assets/js/jquery.dataTables.min.js');
    wp_enqueue_script( 'dataTables.bootstrap4.min.js', plugin_dir_url( __FILE__ ) . '/assets/js/dataTables.bootstrap4.min.js');
    wp_enqueue_script( 'bootstrap4-toggle.min.js', plugin_dir_url( __FILE__ ) . '/assets/js/bootstrap4-toggle.min.js');
    wp_enqueue_script( 'my-great-script', get_template_directory_uri() . '/js/my-great-script.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'ajax.js', 'ajax', array('url' => admin_url( 'admin-ajax.php' )) );

    $fetchWidgetAppKey = whatcx_widget_twoway_encrypt(get_option('whatcx_widget_app_key'),'d');   
    $fetchWidgetApiKey = whatcx_widget_twoway_encrypt(get_option('whatcx_widget_api_key'),'d');    

    function whatcx_widget_twoway_encrypt($stringToHandle = "",$encryptDecrypt = 'e'){
        $output = null;
        $secret_key = wp_salt('NONCE_SALT');
        $secret_iv = wp_salt('auth');
        $key = hash('sha256',$secret_key);
        $iv = substr(hash('sha256',$secret_iv),0,16);
        if($encryptDecrypt == 'e'){
            $output = base64_encode(openssl_encrypt($stringToHandle,"AES-256-CBC",$key,0,$iv));
        }else if($encryptDecrypt == 'd'){
            $output = openssl_decrypt(base64_decode($stringToHandle),"AES-256-CBC",$key,0,$iv);
        }
        return $output;
    }
?>

<section class="pt-5 pe-lg-4 form-bg-img">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12 logo-sec">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/img/logo.png';?>">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-7 col-lg-7 mt-3">
                <div class="border-box h-100 p-3">
                    <p class="fw-600 fs-18">Integrate Widget</p>
                    <form class="form-widget">
                        <div class="mb-3">
                            <label for="" class="form-label fs-16 fw-semibold">APP Key<span
                                    class="fw-bold text-danger">*</span></label>
                            <input type="text" class="form-control fs-16 appkey" value="<?php if(!empty($fetchWidgetAppKey)){echo esc_attr($fetchWidgetAppKey);}?>"  name="appkey" id="appkey" placeholder="Enter your App key">
                            <span class="appkeyerror"></span>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label fs-16 fw-semibold">API Key<span class="fw-bold text-danger">*</span></label>
                            <input type="text" class="form-control fs-16 apikey" value="<?php if(!empty($fetchWidgetApiKey)){ echo esc_attr($fetchWidgetApiKey);}?>" name="apikey" id="apikey"
                                placeholder="Enter your API key">
                            <span class="apikeyerror"></span>

                        </div>
                        <div class="d-flex justify-content-end">
                            <input class="btn yellow-btn fs-16 fw-semibold submit" style="background:#f4ca17" id="submit" type="button" name="Submit" value="Submit" > 
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-5 col-lg-5 mt-3">
                <div class="border-box h-100 p-4">
                    <div class="inActiveWidget">
                        <p class="pb-3 fs-18 txt-grey text-center">No Widget is Activated Yet !</p>
                        <div class="d-flex justify-content-center">
                            <img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/img/not-activated.png';?>">
                        </div>
                    </div>
                    <div class="activeWidget" style="display:none">
                        <p class="fs-18 txt-green fw-semibold mb-1">Current Active Widget</p>
                        <p class="fs-16 widgetName">Widget QR</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center currentActiveWidget b-code-bg"></div>
                            <button class="btn-red currentActiveButton statusButton ms-lg-4" style="background: #e91b38;border:1px solid #e91b38;border-radius: 4px;color: white;padding: 2px 10px;" value="InActive">Deactivate</button>
                            <a href="" target="blank" class="currentActiveWidgetUrl"><div class="bg-box BR-5"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/img/link.png';?>"></div></a>
                            <a href="" target="blank" class="currentActiveWidgetQr"><div class="bg-box BR-5"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/img/scanner.png';?>" ></div></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 mt-4">
                <div class="bg-white pt-3 pb-3">
                    <div class="table-scroll">
                    <table class="table widget-table mt-3 example" id="example" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col" class="w-25">Widget Name</th>
                                <th scope="col" class="w-25">Intent</th>
                                <th scope="col">Change Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
jQuery(document).ready(function(){
    
    <?php if(!empty($fetchWidgetAppKey && $fetchWidgetApiKey)){?>
        window.onload = function(){
            document.getElementById('submit').click();
        }
    <?php } ?>
    
    jQuery('.submit').click(function(){
        window.alert=()=>{};
        var appkey = jQuery('.appkey').val();
        var apikey = jQuery('.apikey').val();
        if(appkey == '' && apikey == ''){
            jQuery('.apikeyerror').html('Please Enter Api Key').css('color','red');
            jQuery('.appkeyerror').html('Please Enter App Key').css('color','red');
            return false;
        }
        if(appkey == ''){
            jQuery('.appkeyerror').html('Please Enter App Key').css('color','red');
            jQuery('.apikeyerror').html('').css('color','red');
            return false;
        }
        if(apikey == ''){
            jQuery('.apikeyerror').html('Please Enter Api Key').css('color','red');
            jQuery('.appkeyerror').html('').css('color','red');
            return false;
        }        
        jQuery('.apikeyerror').html('').css('color','red');
        jQuery('.appkeyerror').html('').css('color','red');
        if(appkey != '' && apikey != ''){
            jQuery.ajax({
                type : 'POST',
                url : '<?php echo admin_url( 'admin-ajax.php' );?>',
                data : {appkey : appkey,apikey : apikey,action : 'whatcx_widget_post'},
                success : function(response){                          
                    if(response != ''){        
                        var obj = jQuery.parseJSON(response);
                        var status = obj['status'];
                        var message = obj['message'];
                        if(status == 200 && message == 'Updated'){
                            location.reload();
                        }
                        var widgetID = obj['widgetID'];
                        var widgetNonse = obj['widgetNonse'];
                        if(widgetID != '' && widgetID != null && widgetID != false && widgetNonse != '' && widgetNonse != null && widgetNonse != false){
                            jQuery.ajax({
                                type : 'GET',
                                url : 'https://api-apac-1.whatcx.com/v0/qr-widget/'+widgetID,
                                headers: {"app-key": appkey,"api-key" : apikey},                               
                                datatype : 'json', 
                                success : function(response){
                                    var qrcode = response.link.qrcode;
                                    var title = response.title;
                                    var url = response.link.url;
                                    var status = response.active;
                                    if(status === true){
                                        jQuery('.inActiveWidget').hide();
                                        jQuery('.activeWidget').show();
                                        jQuery('.currentActiveWidgetDiv').show();
                                        jQuery('.currentActiveWidget').html('<image src="'+qrcode+'" class="b-code">');
                                        jQuery('.currentActiveButton').attr('data-id',widgetID);
                                        jQuery('.currentActiveButton').attr('data-nonce',widgetNonse);
                                        jQuery('.currentActiveButton').attr('data-appkey',appkey);
                                        jQuery('.currentActiveButton').attr('data-apikey',apikey);
                                        jQuery('.widgetName').html(title);
                                        jQuery('.qrcode').attr('data-id',widgetID);
                                        jQuery('.currentActiveWidgetUrl').attr('href',url);
                                        jQuery('.currentActiveWidgetQr').attr('href',qrcode);
                                    }
                                }
                            }); 
                        }
                        jQuery('#example').DataTable({
                            bInfo : true,
                            processing: true,
                            serverSide: true,
                            searching: true,
                            search: {
                                return: true,
                            },
                            lengthMenu: [
                                [10, 25, 50],
                                [10, 25, 50],
                            ],
                            ajax: {
                                url: "https://api-apac-1.whatcx.com/v0/qr-widget",
                                type: "GET",
                                headers: {"app-key": appkey,"api-key" : apikey},
                                data: function ( d ) {
                                    d.page = (jQuery('#example').DataTable().page.info().start / jQuery('#example').DataTable().page.len()) + 1;
                                    d.size = jQuery('#example').DataTable().page.len();
                                    d.query = jQuery('#example_filter input[type="search"]').val();
                                },
                                dataSrc: function(json) {
                                    json['recordsTotal'] = json['totalCount'];
                                    json['recordsFiltered'] = json['totalCount'];
                                    json['data'] = json['records'];
                                    return json.records;
                                }
                            },
                            columns: [
                                { data: "title" },
                                { 
                                    data: "intent",
                                    render: function (data, type, row) {
                                        var intent = row.intent;
                                        const text = intent.replaceAll("_", " ").toLocaleLowerCase();
                                        return text[0].toUpperCase() + text.slice(1);
                                    },
                                },
                                {
                                    data: 'nonce',
                                    render: function (data, type, row) {
                                        var id = row._id;
                                        var nonce = row.nonce;
                                        if(id == widgetID && nonce == widgetNonse){
                                            return '<button class="grey-btn fs-14 statusButton" style="background: #e91b38;border:1px solid #e91b38;border-radius: 4px;color: white;padding: 5px 15px;" data-id="'+row._id+'" value="InActive" data-appkey="'+appkey+'" data-apikey="'+apikey+'" data-nonce="'+row.nonce+'" data-title="'+row.title+'">Deactivate</a>';
                                        }else{
                                            return '<button class="grey-btn fs-14 statusButton" style="background: #389E0D;border:1px solid #389E0D;border-radius: 4px;color: white;padding: 5px 15px;" data-id="'+row._id+'" value="Active" data-appkey="'+appkey+'" data-apikey="'+apikey+'" data-nonce="'+row.nonce+'" data-title="'+row.title+'">Activate</a>';
                                        }
                                    },
                                },
                                {
                                    data : 'nonce',
                                    render: function (data, type, row) {                                        
                                        var id = row._id;
                                        var url = row.link.url;
                                        var qrcode = row.link.qrcode;
                                        return '<div class="d-flex align-items-center"><a target="blank" href="'+qrcode+'"><div class="bg-box rounded-circle me-3"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/img/scanner.png';?>" class="qrcode" data-id="'+row._id+'" data-appkey="'+appkey+'" data-apikey="'+apikey+'"></div></a><a target="blank" href="'+url+'"><div class="bg-box rounded-circle me-3"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/img/link.png';?>"></div></a><a target="blank" href="https://app.whatcx.com/qr-and-widgets/'+id+'"><div class="bg-box rounded-circle"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/img/pencil.png';?>" style="width:13px"></div></a></div>';

                                    },
                                },
                            ]  
                        });
                    }else{                       
                    }                         
                }
            });
        } 
    });
});


jQuery(document).on("click", ".statusButton", function () {    
    window.alert=()=>{}
    var jQuerythis = jQuery(this);
    var appkey = jQuery(this).data('appkey');
    var apikey = jQuery(this).data('apikey');
    var id = jQuery(this).data('id');
    var nonce = jQuery(this).data('nonce');
    var title = jQuery(this).data('title');
    var status = jQuery(this).val();  
    jQuery.ajax({
        type : 'POST',
        url : '<?php echo admin_url( 'admin-ajax.php' );?>',
        data : {appkey : appkey,apikey : apikey,id : id,nonce : nonce,title : title, status:status,action : 'whatcx_widget_post'},
        success : function(response){ 
            location.reload();
        }
    });
});
</script>