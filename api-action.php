<?php     
    add_action( 'wp_ajax_whatcx_widget_post', 'whatcx_widget_post' );
   
    /**
     * Handles my AJAX request.
     */
    function whatcx_widget_post() {    
        
        function whatcx_widget_twoway_encrypt($stringToHandle = "",$encryptDecrypt = 'e'){
            // Set default output value
            $output = null;
            // Set secret keys
            $secret_key = wp_salt('NONCE_SALT'); // Change this!
            $secret_iv = wp_salt('auth'); // Change this!
            $key = hash('sha256',$secret_key);
            $iv = substr(hash('sha256',$secret_iv),0,16);
            if($encryptDecrypt == 'e'){
            $output = base64_encode(openssl_encrypt($stringToHandle,"AES-256-CBC",$key,0,$iv));
            }else if($encryptDecrypt == 'd'){
            $output = openssl_decrypt(base64_decode($stringToHandle),"AES-256-CBC",$key,0,$iv);
            }
            return $output;
        }    

        // Handle the ajax request here
        $appkey = whatcx_widget_twoway_encrypt(sanitize_text_field($_POST["appkey"]),'e');
        $apikey = whatcx_widget_twoway_encrypt(sanitize_text_field($_POST["apikey"]),'e');
        $id = whatcx_widget_twoway_encrypt(sanitize_text_field($_POST["id"]),'e');
        $nonce = whatcx_widget_twoway_encrypt(sanitize_text_field($_POST["nonce"]),'e');
        $title = sanitize_text_field($_POST["title"]);
        $status = sanitize_text_field($_POST["status"]);
    
        $fetchWidgetAppKey = get_option('whatcx_widget_app_key');   
        $fetchWidgetApiKey = get_option('whatcx_widget_api_key');   
        $fetchWidget = get_option('whatcx_widget_active');
        if($appkey != $fetchWidgetAppKey && $apikey != $fetchWidgetApiKey){
            $appkeyinsert = update_option('whatcx_widget_app_key',$appkey);
            $apikeyinsert = update_option('whatcx_widget_api_key',$apikey);
            $data = ['status' => 200, 'message' => 'Updated'];
            echo json_encode($data);  
        }else{
            if($fetchWidgetAppKey && $fetchWidgetApiKey){
                $data = ['app-key' => whatcx_widget_twoway_encrypt($fetchWidgetAppKey,'d'), 'api-key' => whatcx_widget_twoway_encrypt($fetchWidgetApiKey,'d'),'widgetID' => whatcx_widget_twoway_encrypt($fetchWidget['widgetID'],'d'), 'widgetNonse' => whatcx_widget_twoway_encrypt($fetchWidget['widgetNonse'],'d'), 'widgetTitle' => $fetchWidget['widgetTitle'] ];
                echo json_encode($data);       
                if($status == 'Active'){
                    $data = ['widgetID' => $id, 'widgetNonse' => $nonce, 'widgetTitle' => $title ];
                    $update = update_option('whatcx_widget_active',$data);
                }elseif($status == 'InActive'){ 
                    $data = ['widgetID' => NULL, 'widgetNonse' => NULL, 'widgetTitle' => NULL ];
                    $update = update_option('whatcx_widget_active',$data);
                }
            }else{
                $appkeyinsert = add_option('whatcx_widget_app_key',$appkey);
                if($appkeyinsert){
                    $apikeyinsert = add_option('whatcx_widget_api_key',$apikey);
                    if($apikeyinsert){
                        echo 'true';
                        die;
                    }else{
                        echo 'false';
                        die;
                    }
                }else{
                    echo 'false';
                    die;
                }
            }    
        }
        wp_die(); // All ajax handlers die when finished
        
    }          
   
    
        
?>