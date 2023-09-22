<?php
// Make sure we don't expose any info if called directly
if (!defined('WPINC')) {
  die;
}


include_once(dirname(__FILE__) . "/View.php");
 

//add_action( 'admin_init', 'redirect_non_logged_users_to_specific_page' );
class WCP_downpdf_Controller {

    //Backend downpdf page
    public static function render_view_front_screen() {
        print WCP_downpdf_View::build_html();
    }
  
  
    //in backend show plugin and set name in admin menu
    public static function wcp_tenant_screen() {


        add_menu_page('BROCHURE ANALYTICS ', 'BROCHURE ANALYTICS ', 'manage_options', 'wcp-download-pdf','','dashicons-download');
        add_submenu_page('wcp-download-pdf', 'Downloaded Brochure Stats', 'Downloaded Brochure Stats', 'manage_options', 'wcp-download-pdf',array('WCP_downpdf_Controller', 'render_view_front_screen') );
       

    }

  

    // Get all downpdf data 
    public static function get_downpdf(){

         global $wpdb; 
    $download_pdf_emails=$wpdb->prefix.'download_pdf_emails';
       
        
        $requestData = $_REQUEST;
    
        global $wpdb,$wp;
        $data = array();
 
        $sql = "SELECT *  FROM  $download_pdf_emails";

        if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " WHERE (email LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
        }

        $result=$wpdb->get_results($wpdb->prepare($sql, Array()), OBJECT);

        
        $totalData = 0;
        $totalFiltered = 0;
    if (count($result) > 0) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }
    

        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " ORDER BY ID ASC LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
  

        foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }
             
          
            
            $id = $row->id;
 
           $temp['product_name'] = get_the_title($row->product_id);

             

            $data[] = $temp;
            $id = "";
        }


        $json_data = array(
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
        exit(0);
    }
 

    public static function Add_email_info(){
     
        global $wpdb;
        
        $product = wc_get_product($_POST['product_id'] );
        $pdf="";
        $name="";
        
        if ( $product->is_downloadable() ) {
        
            $downloads = $product->get_downloads();
             
            if(count($downloads) > 0){
                foreach( $downloads as $key => $each_download ) {
                     
                   $pdf=$each_download["file"];
                    $name=$each_download["name"];
                }
            }
        }
        


    $download_pdf_emails=$wpdb->prefix.'download_pdf_emails';

        $result = array();
        $result['status'] = 0;
            $data = array(  
                'product_id' => $_POST['product_id'],
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'company_name' => $_POST['company_name'],
                'phone_no' => $_POST['phone_no']
            );
          
  
            $data_result = $wpdb->insert($download_pdf_emails, $data);
           
            $lastid = $wpdb->insert_id;
            if ($lastid) {
                $result_array['status'] = 1;
                 $result_array['msg'] = $pdf;
                 $result_array['name'] = $name;
                
            } else {
                $result_array['msg'] = 'Please try again.';
            }
        
        
        echo json_encode($result_array);
        exit;
    }
    
    public static function custom_mail_sent() {
 $result_array['status'] = 0;
        
          $email =$_POST['email'];
          $message = "Dear ".$_POST['name']." 

Thank you for reaching BEML and showing interest in downloading our Brochure. 

Please input the below OTP to complete your download. 

OTP CODE : ".$_POST['otp'];



        //php mailer variables
          $to = $email ;
          $subject = "BEML :: EMAIL OTP FOR  BROCHURE DOWNLOAD";
          $headers = 'From: SUPPORT BEML <support@bemlindia.in>' . "\r\n" .
            'Reply-To: ' . 'support@bemlindia.in' . "\r\n";
       
        //Here put your Validation and send mail
        $sent = wp_mail($to, $subject, strip_tags($message), $headers);
              if($sent) {
                     $result_array['status'] = 1;
               
              }//message sent!
              else  {
                     $result_array['status'] = 0;
              }//message wasn't sent

                echo json_encode($result_array);
        exit;
    } 
function show_popup(){
       ?>

       <style type="text/css">
           a:hover,a:active{
               text-decoration:none !important;
           }
           .modal-desc,input{
               color:#fff;
           }
           @media (min-width: 768px) and (max-width: 991.98px) {
            .modal-body{
                padding: 20px 10px !important;
            }
            .form-group input[type=email]{
                width: 100% !important;
                border-radius: 5px !important;
            }
            .form-group input[type=submit]{
                margin-left: 160px !important;
                font-size: 14px !important;
                border-radius: 5px !important;

            }
            .modal-content{
                width: 80% !important;
                margin-left: 61px !important;
            }
            }
            @media (min-width: 425px) and (max-width: 767.98px) { 
                .modal-content{
                width: 80% !important;
                height: 50% !important;
                margin: auto;
                
            }
            .modal-title{
                font-size: 20px !important;
            }
            .modal-body{
                padding: 10px 5px !important;
            }
            }
           .error{
               color:red;
           }
       </style>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" style="background-image:url('https://webdemonstration.bid/sugoi/wp-content/uploads/2022/06/360_F_274639050_ddLeyFuQGwEjtTP0e2Y9ZZesSo2Mj6JD.jpg'); margin-top:300px;">
      
        <div class="modal-body" style="padding:60px 30px;">
            <h4 class="modal-title" style="color:white; text-align: center; margin-top: 20px; margin-bottom: 0px; font-size:27px;">Welcome to BEML LIMITED</h4>
            <p class="modal-desc" style="color:white; text-align: center; margin-top: 20px; margin-bottom: 40px; font-size:17px;">Please provide your contact info to download the brochure. </p>
             
            <form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data"style="display: inline-block;width: 100%;">      
                <input type="hidden" id="action" name="action" value="Add_email_info">  
                <input type="hidden" id="product_id" name="product_id" >            
                <div class="step-one">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                       
                        <input type="text" name="company_name" class="form-control" style="color:white;float: left;width:100%;  font-size: 16px;padding: 20px 20px;margin: 10px 0 0 0;" placeholder="Company Name"  id="company_name" required>          
                       
                    </div> 
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                       
                        <input type="text" name="name" class="form-control" style="color:white;float: left;width:100%;  font-size: 16px;padding: 20px 20px;margin: 10px 0 0 0;" placeholder="Name"  id="name" required>          
                       
                    </div> 
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                       
                        <input type="email" name="email" class="form-control" style="color:white;float: left;width:100%;  font-size: 16px;padding: 20px 20px;margin: 10px 0 0 0;" placeholder="Email Address"  id="email_address" required>          
                       
                    </div> 
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                       
                        <input type="text" name="phone_no" class="form-control" style="color:white;float: left;width:100%;  font-size: 16px;padding: 20px 20px;margin: 10px 0 0 0;" placeholder="Mobile Number"  id="phone_no" required>          
                        
                    </div> 
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 30px;text-align: center;">                       
                    <a href="#"  class="btn next-form" style="text-align: center; background-color: #000; color:#fff;font-size: 16px;padding: 11px 20px;margin: 10px 0 0 0; border: 1px solid #fff;" >Next</a>          
                </div> 
                </div>
                <div class="step-two">
                    
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                       
                        <input type="text" name="otp" class="form-control" style="color:white;float: left;width:100%;  font-size: 16px;padding: 20px 20px;margin: 10px 0 0 0;" placeholder="Please enter OTP sent to your email id"  id="otp" required>          
                       
                    </div> 
                      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 30px;text-align: center;">                       
                    <a href="#"  class="btn back-form" style="text-align: center; background-color: #000; color:#fff;font-size: 16px;padding: 11px 20px;margin: 10px 0 0 0; border: 1px solid #fff;" >Back</a>          
                           
                        <input type="submit" name="submit" class="btn submit-dis-btn" disabled style="text-align: center; background-color: #000; color:#fff;font-size: 16px;padding: 11px 20px;margin: 10px 0 0 0; border: 1px solid #fff;"  value="Submit" >          
                    </div> 
                    
                </div>

              
            </form>
             
               
        </div>
      
      </div>
      
    </div>
  </div>
  <script type="text/javascript">
 jQuery(".error").hide();
  jQuery(".step-two").hide();
  var pdf="";
      var name ="";
       jQuery(document).ready(function(){
  jQuery(".single-product-download").on("click",function(){
    pdf=jQuery(this).attr("data-href");
       name=jQuery(this).attr("data-name");
     
  });
       });                  
                            var otp="";
        jQuery(".next-form").click(function(){
             jQuery("#email_address").trigger('keyup');
              jQuery("#phone_no").trigger('keyup');
            jQuery("#name").trigger('keyup');
            if(jQuery(".error").length == 0){
                 otp=Math.floor(100000 + Math.random() * 900000);
            jQuery(".step-one").hide();
            jQuery(".step-two").show();
                  jQuery(".modal-desc").text("Please input the OTP sent to your email for verification.");
            var email= jQuery("#email_address").val();
                var name= jQuery("#name").val();
                    jQuery.ajax({
                      type: 'POST',
                      url: '<?php echo admin_url('admin-ajax.php'); ?>',
                      data: {"action":"custom_mail_sent","otp":otp,"email":email,"name":name},
                      success: function (data) {
                          var result = JSON.parse(data);
                        
                          if (result.status == 1) {
                           
                             
                             
                          }else{
                              alert(result.msg);
                          }
                      }
                  });
             }
        });
        jQuery(".back-form").click(function(){
            jQuery(".modal-desc").text("Please provide your contact info to download the brochure.");
            jQuery(".step-two").hide();
            jQuery(".step-one").show();
       
        });

        jQuery("#phone_no").keyup(function(){
                var inputVal = jQuery(this).val();
                var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;
                if(!numericReg.test(inputVal)) {
                     jQuery(".error-keyup-1").remove();
                    jQuery(this).after('<span class="error error-keyup-1">Enter Valid Mobile Number.</span>');
                }else if(inputVal.length != 10){
                     jQuery(".error-keyup-1").remove();
                    jQuery(this).after('<span class="error error-keyup-1">Maximum 10 Characters.</span>');
                }else{
                    jQuery(".error-keyup-1").remove();
                }  
        });
        jQuery("#name").keyup(function(){
                var inputVal = jQuery(this).val();
                var numericReg = /^[a-zA-Z\s]*$/;
                if(!numericReg.test(inputVal)) {
                     jQuery(".error-keyup-5").remove();
                    jQuery(this).after('<span class="error error-keyup-5">Enter Valid Name.</span>');
                }else if(inputVal.length == 0) {
                     jQuery(".error-keyup-5").remove();
                    jQuery(this).after('<span class="error error-keyup-5">Enter Valid Name.</span>');
                }else if(inputVal.length >= 50){
                     jQuery(".error-keyup-5").remove();
                    jQuery(this).after('<span class="error error-keyup-5">Maximum 50 Characters.</span>');
                }else{
                    jQuery(".error-keyup-5").remove();
                }  
        });

       
      
        jQuery("#email_address").keyup(function(){
                var inputVal = jQuery(this).val();
                 var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
                if(!testEmail.test(inputVal)) {
                     jQuery(".error-keyup-2").remove();
                    jQuery(this).after('<span class="error error-keyup-2">Enter Valid Email Address.</span>');
                }else{
                    jQuery(".error-keyup-2").remove();
                }  
        });
        jQuery("#otp").keyup(function(){
                var inputVal = jQuery(this).val();
                jQuery(".submit-dis-btn").attr("disabled","disabled");
                var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;
                if(!numericReg.test(inputVal)) {
                     jQuery(".error-keyup-3").remove();
                    jQuery(this).after('<span class="error error-keyup-3">Enter Valid OTP.</span>');
                }else if(inputVal.length != 6){
                    jQuery(".error-keyup-3").remove();
                    jQuery(this).after('<span class="error error-keyup-3">Invalid Valid OTP.</span>');
                }else if(inputVal != otp){
                    jQuery(".error-keyup-3").remove();
                    jQuery(this).after('<span class="error error-keyup-3">Invalid Valid OTP.</span>');

                }else{
                    jQuery(".error-keyup-3").remove();
                                jQuery(".submit-dis-btn").removeAttr("disabled");
                }
        });


       jQuery( "#UserUpdateform" ).on("submit",function(){
    
            var form_data = new FormData(document.getElementById('UserUpdateform'));                
         jQuery(".error").hide();
  

                  jQuery.ajax({
                      type: 'POST',
                      url: '<?php echo admin_url('admin-ajax.php'); ?>',
                      data: form_data,
                       cache: false,
                        contentType: false,
                        processData: false, 
                      success: function (data) {
                          var result = JSON.parse(data);
                          if (result.status == 1) {
                          
                              jQuery("#myModal").modal('hide');
                              jQuery('#UserUpdateform')[0].reset();
                                 var element = document.createElement('a');
                                jQuery(".modal-desc").text("Please provide your contact info to download the brochure.");
                              
                                element.href = pdf;
                              element.target = "_blank";
                                element.id = "download-pdf-custom-link";
                      
                                // Name to display as download
                                element.download = name;
                      
                                // Adding element to the body
                                document.documentElement.appendChild(element);
                                jQuery("#download-pdf-custom-link").attr("target","_blank");
                      
                                // Trigger the file download
                                element.click();
                      
                                // Remove the element from the body
                                jQuery("#download-pdf-custom-link").remove();
                               location.reload();
                             
                          }else{
                              alert(result.msg);
                          }
                      }
                  });
         
              });
  </script>
       <?php
    }

   
 
 
  
}


$wcp_downpdf_controller = new WCP_downpdf_Controller();

/// Shortcodes

add_action('admin_menu', array($wcp_downpdf_controller, 'wcp_tenant_screen'));
add_shortcode('wcp_download',array($wcp_downpdf_controller,'show_popup'));  

add_action('woocommerce_after_single_product_summary', 'move_stuff_to_first_full_width_section', );
 
function move_stuff_to_first_full_width_section() {
 
    echo do_shortcode("[wcp_download]");
 
}

 

add_action('wp_ajax_get_downpdf', Array('WCP_downpdf_Controller', 'get_downpdf'));
add_action('wp_ajax_nopriv_get_downpdf', array('WCP_downpdf_Controller', 'get_downpdf')); 
 

add_action('wp_ajax_Add_email_info', Array('WCP_downpdf_Controller', 'Add_email_info'));
add_action('wp_ajax_nopriv_Add_email_info', array('WCP_downpdf_Controller', 'Add_email_info'));

add_action('wp_ajax_custom_mail_sent', Array('WCP_downpdf_Controller', 'custom_mail_sent'));
add_action('wp_ajax_nopriv_custom_mail_sent', array('WCP_downpdf_Controller', 'custom_mail_sent'));