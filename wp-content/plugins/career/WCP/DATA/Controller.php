<?php
//session_start();
// Make sure we don't expose any info if called directly
if (!defined('WPINC')) {
  die;
}


include_once(dirname(__FILE__) . "/View.php");
 

//add_action( 'admin_init', 'redirect_non_logged_users_to_specific_page' );
class WCP_Career_Controller {

    //Backend career page
    public static function render_view_front_screen() {
        print WCP_Career_View::build_html();
    }
     //Backend career page
    public static function render_view_result_screen() {
        print WCP_Career_View::list_result();
    }

    public static function view_career_current_recruitments() {
        print WCP_Career_View::career_current_recruitments();
    }
    public static function view_career_in_progress_recruitments() {
        print WCP_Career_View::career_in_progress_recruitments();
    }

     public static function view_career_close_recruitments() {
        print WCP_Career_View::career_close_recruitments();
    }


  
  
    //in backend show plugin and set name in admin menu
    public static function wcp_tenant_screen() {


        add_menu_page('Careers', 'Careers', 'manage_options', 'wcp-career','','dashicons-businessman','8' );
        add_submenu_page('wcp-career', 'All Recruitments', 'All Recruitments', 'manage_options', 'wcp-career',array('WCP_Career_Controller', 'render_view_front_screen') );
        add_submenu_page('wcp-career', 'Shortlisted Recruitments', 'Shortlisted or Closed Recruitments', 'manage_options', 'wcp-career-result',array('WCP_Career_Controller', 'render_view_result_screen') );
       

    }

  

    // Get all career data 
    public static function get_career(){

       
       
        
        $requestData = $_REQUEST;
    
        global $wpdb,$wp;
        $data = array();
 
        $sql = "SELECT *  FROM tbl_careers_curentjob ";

        if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " WHERE (Job_Heading_English LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
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
            $sql .= " ORDER BY Curentjobid  DESC LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;

        foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }
             
            $temp['OpeningDate'] = date("d-M-Y",strtotime($row->OpeningDate));
            if($row->File_Size != null){
            $temp['link'] = '<a href="'.get_site_url().'/wp-content/plugins/career/WCP/DATA/'.str_replace("~","",$row->Link).'">View</a>';
        }else{
            $temp['link'] = '<a href="'.$row->Link.'">View</a>';
        }
           
            
            $id = $row->Curentjobid;
            $action = '<div style="display: flex;">';
            // temporarily removed
            $action .= '<input type="button" value="Edit" class="btn btn-info"  onclick="career_update(' . $id . ')">&nbsp; &nbsp;';
            $action .= "<input type='button' value='Delete' class='btn btn-danger' onclick='career_delete(" . $id . ")'>&nbsp;";
            $action .= "<input type='button' value='Corrigendum' class='btn btn-info' onclick='career_corr(" . $id . ")'>&nbsp;";
            $action .= '</div>';
            
            $temp['action'] = $action;

            $data[] = $temp;
            $id = "";
        }


        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
        exit(0);
    }

    public static function get_career_result(){

       
       
        
        $requestData = $_REQUEST;
    
        global $wpdb,$wp;
        $data = array();
 
        $sql = "SELECT *  FROM tbl_career_shortlistedjob ";

        if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " WHERE (Titel_eng LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
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
            $sql .= " ORDER BY Formatid   DESC LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;

        foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }
             
            $temp['OpeningDate'] = date("d-M-Y",strtotime($row->Date));
            
           
            
            $id = $row->Formatid;
            $action = '<div style="display: flex;">';
            // temporarily removed
            $action .= '<input type="button" value="Edit" class="btn btn-info"  onclick="career_update(' . $id . ')">&nbsp; &nbsp;';
            $action .= "<input type='button' value='Delete' class='btn btn-danger' onclick='career_delete(" . $id . ")'>&nbsp;";
            $action .= '</div>';
            
            $temp['action'] = $action;

            $data[] = $temp;
            $id = "";
        }


        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
        exit(0);
    }

    // Get all career data 
    public static function get_corr(){

       
       
        
        $requestData = $_REQUEST;
    
        global $wpdb,$wp;
        $data = array();
 
    

        $sql = "SELECT * FROM tbl_careers_curentjob_corrigendum   ";
        $sql .= " WHERE (tbl_careers_curentjob_corrigendum.Curentjobid = '" . esc_sql($_POST['id']) . "') ";
        if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= "(tbl_careers_curentjob_corrigendum.Job_Heading_English LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
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
            $sql .= " ORDER BY tbl_careers_curentjob_corrigendum.CorrgendumID  DESC LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;

        foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }
             
            $temp['OpeningDate'] = date("d-M-Y",strtotime($row->OpeningDate));
             if($row->File_size != null){
            $temp['links'] = '<a href="'.get_site_url().'/wp-content/plugins/career/WCP/DATA/'.str_replace("~","",$row->Link).'">View</a>';
        }else{
            $temp['links'] = '<a href="'.$row->Link.'">View</a>';
        }
           
           
            
            $id = $row->CorrgendumID;
            $action = '<div style="display: flex;">';
            // temporarily removed
            $action .= '<input type="button" value="Edit" class="btn btn-info"  onclick="corr_update(' . $id . ')">&nbsp; &nbsp;';
            $action .= "<input type='button' value='Delete' class='btn btn-danger' onclick='corr_delete(" . $id . ")'>&nbsp;";
          
            $action .= '</div>';
            
            $temp['action'] = $action;

            $data[] = $temp;
            $id = "";
        }


        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
        exit(0);
    }
 
    // add trade
    public static function Add_career_info(){
       
        $result_array['status'] = 0;
        
            global $wpdb;

          
        $file_size=null;
        $file_names=null;
       
             
            if(isset($_FILES["tender_document"]["name"]))
        {
              
                $validextensions = array("pdf", "zip", "rar");
                 if( $_FILES["tender_document"]["size"] >0){
                $temporary = explode(".", $_FILES["tender_document"]["name"]);
                $file_extension = end($temporary);
                
                if($file_extension != ""  ){
                if (in_array($file_extension, $validextensions)) {
                    if ($_FILES["tender_document"]["error"] > 0)
                    {
                         
                        $response['is_ok'] = "no";
                            $response['error'] = "Return Code: " . $_FILES["tender_document"]["error"] . "<br/><br/>";
                    }
                    else
                    {
                        if (file_exists("/wp-content/plugins/career/WCP/DATA/Writereaddata/Career/" . $_FILES["tender_document"]["name"])) {
                             
                            $response['is_ok'] = "no";
                            $response['error'] = "already exists.";
                        }
                        else
                        {
                             
                             $file_name= $_FILES["tender_document"]['name'];
                            if (@move_uploaded_file($_FILES['tender_document']['tmp_name'],dirname(__FILE__).'/Writereaddata/Career/'. $file_name)) {
                                $response['is_ok'] = "yes";
                                $file_size=$_FILES["tender_document"]["size"];
                                $file_names='~/Writereaddata/Career/'.$file_name;
                                $response['file_name'] = $file_name;
                            } else {
                                $response['is_ok'] = "no";
                                $response['error'] = "File not uploaded";
                            }
                            
                        }
                    }
                }
                else
                {
                    $response['is_ok'] = "no";
                    $response['error'] = "Invalid file Size or Type";
                     
                }
            
                if($response['is_ok'] == "no"){
                    echo json_encode($response);

                    wp_die();
                }
            }
            }
        } 
  
            if(isset($_POST["weblink"]) && $_POST["weblink"] != ""){
                $file_names=$_POST["weblink"];
            }

    
                    $start_date = str_replace('-', '/', $_POST['start_date']);
                    $start_date = date('Y-m-d', strtotime($start_date));
                    $end_date = str_replace('-', '/', $_POST['end_date']);
                    $end_date = date('Y-m-d', strtotime($end_date));
                   
            $data = array(  
                'Job_Heading_English' => $_POST['job_titel_english'],
                'Job_Heading_hindi' => $_POST['job_titel_hindi'],
                'Link' => $file_names,
                'OpeningDate' => $start_date,
                'CloseingDate' => $end_date,
                'Extend' => null,
                'Advertisement_No' => $_POST['advertiement_no'],
                'File_Size' => $file_size,
                'Link_hnd' => null,
                'File_size_hnd' => null,
                'Job_Type' => null
            );
          
$data_result = $wpdb->insert('tbl_careers_curentjob', $data);

if ($data_result === false) {
    // Handle insertion error
    $error_message = $wpdb->last_error;
    $result_array['msg'] = 'Database error: ' . $error_message;
} else {
    // Insertion was successful
    $lastid = $wpdb->insert_id;
    if ($lastid) {
        $result_array['status'] = 1;
        $result_array['msg'] = 'Record Added Successfully.';
    } else {
        $result_array['msg'] = 'No record ID returned.';
    }
}

        
        echo json_encode($result_array);
        exit;
    }
    
    public static function getEncryptedFileName($sanitizedFileName) {
		$encryptedFileName = $sanitizedFileName;
		if ($sanitizedFileName) {
			$fileNameParts = explode('.', html_entity_decode($sanitizedFileName));
			$fileType = array_pop($fileNameParts);
            $encryptedFileName = md5(md5(microtime(true)).implode('.', $fileNameParts)).'.'.$fileType;
		}
		return $encryptedFileName;
	}

    // add trade
    public static function add_corr_info(){
       
        $result_array['status'] = 0;
        
            global $wpdb;

          
 
          
             
         $file_size=null;
        $file_names=null;
       
             
            if(isset($_FILES["tender_document"]["name"]))
        {
              
                $validextensions = array("pdf", "zip", "rar");
                 if( $_FILES["tender_document"]["size"] >0){
                $temporary = explode(".", $_FILES["tender_document"]["name"]);
                $file_extension = end($temporary);
                
                if($file_extension != ""  ){
                if (in_array($file_extension, $validextensions)) {
                    if ($_FILES["tender_document"]["error"] > 0)
                    {
                         
                        $response['is_ok'] = "no";
                            $response['error'] = "Return Code: " . $_FILES["tender_document"]["error"] . "<br/><br/>";
                    }
                    else
                    {
                        if (file_exists("/wp-content/plugins/career/WCP/DATA/Writereaddata/Career/" . $_FILES["tender_document"]["name"])) {
                             
                            $response['is_ok'] = "no";
                            $response['error'] = "already exists.";
                        }
                        else
                        {
                             
                             $file_name= $_FILES["tender_document"]['name'];
                            if (@move_uploaded_file($_FILES['tender_document']['tmp_name'],dirname(__FILE__).'/Writereaddata/Career/'. $file_name)) {
                                $response['is_ok'] = "yes";
                                $file_size=$_FILES["tender_document"]["size"];
                                $file_names='~/Writereaddata/Career/'.$file_name;
                                $response['file_name'] = $file_name;
                            } else {
                                $response['is_ok'] = "no";
                                $response['error'] = "File not uploaded";
                            }
                            
                        }
                    }
                }
                else
                {
                    $response['is_ok'] = "no";
                    $response['error'] = "Invalid file Size or Type";
                     
                }
            
                if($response['is_ok'] == "no"){
                    echo json_encode($response);

                    wp_die();
                }
            }
            }
        } 
  
            if(isset($_POST["weblink"]) && $_POST["weblink"] != ""){
                $file_names=$_POST["weblink"];
            }

    
                    $start_date = str_replace('-', '/', $_POST['start_date']);
                    $start_date = date('Y-m-d', strtotime($start_date));
                    $end_date = str_replace('-', '/', $_POST['end_date']);
                    $end_date = date('Y-m-d', strtotime($end_date));

              
              $data = array(  
                'Job_Heading_English' => $_POST['job_titel_english'],
                'Job_Heading_hindi' => $_POST['job_titel_hindi'],
                'Link' => $file_names,
                'OpeningDate' => $start_date,
                'CloseingDate' => $end_date,
                'Curentjobid' => $_POST['Curentjobid'], 
                'File_Size' => $file_size
            );
          
  
             
            $data_result = $wpdb->insert('tbl_careers_curentjob_corrigendum', $data);
            $lastid = $wpdb->insert_id;
            if ($lastid) {
                $result_array['status'] = 1;
                $result_array['msg'] = 'Record Add Succefully.';
            } else {
                $result_array['msg'] = 'Please try again.';
            }
        
        
        echo json_encode($result_array);
        exit;
    }


      // add trade
    public static function add_career_result_info(){
      
        $result_array['status'] = 0;
        
            global $wpdb;

          
 
          
             
     
        $file_size_hindi=array();
        $file_names=array();
       
             
        if(isset($_FILES["tender_document_hindi"]))
        {
              
               // Number of uploaded files
            $num_files = count($_FILES['tender_document_hindi']);

            /** loop through the array of files ***/
            for($i=0; $i < $num_files;$i++)
            {
                $validextensions = array("pdf", "zip", "rar");
                 if( $_FILES["tender_document_hindi"]["size"][$i] >0){
                $temporary = explode(".", $_FILES["tender_document_hindi"]["name"][$i]);
                $file_extension = end($temporary);
                
                if($file_extension != ""  ){
                if (in_array($file_extension, $validextensions)) {
                    if ($_FILES["tender_document_hindi"]["error"][$i] > 0)
                    {
                         
                        $response['is_ok'] = "no";
                            $response['error'] = "Return Code: " . $_FILES["tender_document_hindi"]["error"][$i] . "<br/><br/>";
                    }
                    else
                    {
                        if (file_exists("/wp-content/plugins/career/WCP/DATA/Writereaddata/Career/" . $_FILES["tender_document_hindi"]["name"][$i])) {
                             
                            $response['is_ok'] = "no";
                            $response['error'] = "already exists.";
                        }
                        else
                        {
                             
                             $file_name= $_FILES["tender_document_hindi"]['name'][$i];
                            if (@move_uploaded_file($_FILES['tender_document_hindi']['tmp_name'][$i],dirname(__FILE__).'/Writereaddata/Career/'. $file_name)) {
                                $response['is_ok'] = "yes";
                                $file_size_hindi[]=$_FILES["tender_document_hindi"]["size"][$i];
                                $file_names_hindi[]='/Writereaddata/Career/'.$file_name;
                                $response['file_name'] = $file_name;
                            } else {
                                $response['is_ok'] = "no";
                                $response['error'] = "File not uploaded";
                            }
                            
                        }
                    }
                }
                else
                {
                    $response['is_ok'] = "no";
                    $response['error'] = "Invalid file Size or Type";
                     
                }
            
                if($response['is_ok'] == "no"){
                    echo json_encode($response);

                    wp_die();
                }
            }
        }
            }
        }

           $file_size_eng=array();
        $file_names_eng=array();

        if(isset($_FILES["tender_document_eng"]))
        {
               // Number of uploaded files
            $num_files = count($_FILES['tender_document_eng']);

            /** loop through the array of files ***/
            for($i=0; $i < $num_files;$i++)
            {
                $validextensions = array("pdf", "zip", "rar");
                 if( $_FILES["tender_document_eng"]["size"] >0){
                $temporary = explode(".", $_FILES["tender_document_eng"]["name"][$i]);
                $file_extension = end($temporary);
                
                if($file_extension != ""  ){
                if (in_array($file_extension, $validextensions)) {
                    if ($_FILES["tender_document"]["error"][$i] > 0)
                    {
                         
                        $response['is_ok'] = "no";
                            $response['error'] = "Return Code: " . $_FILES["tender_document_eng"]["error"][$i] . "<br/><br/>";
                    }
                    else
                    {
                        if (file_exists("/wp-content/plugins/career/WCP/DATA/Writereaddata/Career/" . $_FILES["tender_document_eng"]["name"][$i])) {
                             
                            $response['is_ok'] = "no";
                            $response['error'] = "already exists.";
                        }
                        else
                        {
                             
                             $file_name= $_FILES["tender_document_eng"]['name'][$i];
                            if (@move_uploaded_file($_FILES['tender_document_eng']['tmp_name'][$i],dirname(__FILE__).'/Writereaddata/Career/'. $file_name)) {
                                $response['is_ok'] = "yes";
                                $file_size_eng[]=$_FILES["tender_document_eng"]["size"][$i];
                                $file_names_eng[]='/Writereaddata/Career/'.$file_name;
                                $response['file_name'] = $file_name;
                            } else {
                                $response['is_ok'] = "no";
                                $response['error'] = "File not uploaded";
                            }
                            
                        }
                    }
                }
                else
                {
                    $response['is_ok'] = "no";
                    $response['error'] = "Invalid file Size or Type";
                     
                }
            
                if($response['is_ok'] == "no"){
                    echo json_encode($response);

                    wp_die();
                }
            }
            }
        }
        } 
        
  
         $sql = "select * FROM tbl_careers_curentjob WHERE Curentjobid = %d";
        $user_details = $wpdb->get_results($wpdb->prepare($sql, array(esc_sql($_POST['curentjobid']))), "ARRAY_A");
        if (!empty($user_details)) {
            $filePath = str_replace("~", "", dirname(__FILE__) . $user_details[0]['Link']);
            $decidedFileName = '/Writereaddata/Career/' . self::getEncryptedFileName($user_details[0]['Link']);
            copy($filePath, dirname(__FILE__) . $decidedFileName);
            $file_size_eng[] = filesize($filePath);
            $file_names_eng[] = $decidedFileName;
            $_POST['document_title_eng'][] =  $user_details[0]['Job_Heading_English'];
        }

        // copying CORRIGENDUM documnets
        $sql = "SELECT * FROM tbl_careers_curentjob_corrigendum   ";
        $sql .= " WHERE (tbl_careers_curentjob_corrigendum.Curentjobid = '" . esc_sql($_POST['curentjobid']) . "') ";
        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, array()), "OBJECT");
        foreach ($service_price_list as $row) {
            $filePath = str_replace("~", "", dirname(__FILE__) .$row->Link);
            $decidedFileName = '/Writereaddata/Career/' . self::getEncryptedFileName($row->Link);
            copy($filePath, dirname(__FILE__) . $decidedFileName);
            $file_size_eng[] = filesize($filePath);
            $file_names_eng[] = $decidedFileName;
            $_POST['document_title_eng'][] =  $row->Job_Heading_English;
        }
        
        if (is_array($_POST['document_title_eng'])) {
            $newPostArray = [];
            foreach ($_POST['document_title_eng'] as $arrVal) {
                if (!empty($arrVal)) {
                    array_push($newPostArray, $arrVal);
                }
            }
            $_POST['document_title_eng'] = $newPostArray;
        }

    
                    $start_date = str_replace('-', '/', $_POST['start_date']);
                    $start_date = date('Y-m-d', strtotime($start_date));
                
              
              $data = array(  
                'Type' => $_POST['type'],
                'Date' => $start_date,
                'Titel_eng' => $_POST['job_titel_english'],
                'Titel_Hindi' => $_POST['job_titel_hindi'],
                'format_file' => implode("$",$file_names_eng),
                'File_size' => implode("$",$file_size_eng), 
                'format_file_hnd' => implode("$",$file_names_hindi),
                'File_size_hnd' => implode("$",$file_size_hindi),
                'Docname_eng' => implode("$",$_POST['document_title_eng']),
                'Docname_hnd' => implode("$",$_POST['document_title_hindi']), 
                'Curentjobid' => $_POST['curentjobid'],
                'Advertisement_No' => $_POST['adv_no'],
                'CloseingDate' => $start_date,
                'isDisabled' => 0
            );
          
  
             
            $data_result = $wpdb->insert('tbl_career_shortlistedjob', $data);
            $lastid = $wpdb->insert_id;
            if ($lastid) {
                $result_array['status'] = 1;
                $result_array['msg'] = 'Record Add Succefully.';
            } else {
                $result_array['msg'] = 'Please try again.';
                 $result_array['data'] = $data;
            }
        
        
        echo json_encode($result_array);
        exit;
    }


    public static function update_career_result_info(){
       
        $result_array['status'] = 0;
        
            global $wpdb;

          
 
          
             
     
        $file_size_hindi=array();
        $file_names=array();
       

    
                    $start_date = str_replace('-', '/', $_POST['start_date']);
                    $start_date = date('Y-m-d', strtotime($start_date));
                
              
              $data = array(  
                'Type' => $_POST['type'],
                'Date' => $start_date,
                'Titel_eng' => $_POST['job_titel_english'],
                'Titel_Hindi' => $_POST['job_titel_hindi'],
                'Curentjobid' => $_POST['curentjobid'],
                'Advertisement_No' => $_POST['adv_no'],
                'CloseingDate' => $start_date,
                'isDisabled' => 0
            );
          


             
        if(isset($_FILES["tender_document_hindi"]))
        {
              
               // Number of uploaded files
            $num_files = count($_FILES['tender_document_hindi']);

            /** loop through the array of files ***/
            for($i=0; $i < $num_files;$i++)
            {
                $validextensions = array("pdf", "zip", "rar");
                 if( $_FILES["tender_document_hindi"]["size"][$i] >0){
                $temporary = explode(".", $_FILES["tender_document_hindi"]["name"][$i]);
                $file_extension = end($temporary);
                
                if($file_extension != ""  ){
                if (in_array($file_extension, $validextensions)) {
                    if ($_FILES["tender_document_hindi"]["error"][$i] > 0)
                    {
                         
                        $response['is_ok'] = "no";
                            $response['error'] = "Return Code: " . $_FILES["tender_document_hindi"]["error"][$i] . "<br/><br/>";
                    }
                    else
                    {
                        if (file_exists("/wp-content/plugins/career/WCP/DATA/Writereaddata/Career/" . $_FILES["tender_document_hindi"]["name"][$i])) {
                             
                            $response['is_ok'] = "no";
                            $response['error'] = "already exists.";
                        }
                        else
                        {
                             
                             $file_name= $_FILES["tender_document_hindi"]['name'][$i];
                            if (@move_uploaded_file($_FILES['tender_document_hindi']['tmp_name'][$i],dirname(__FILE__).'/Writereaddata/Career/'. $file_name)) {
                                $response['is_ok'] = "yes";
                                $file_size_hindi[]=$_FILES["tender_document_hindi"]["size"][$i];
                                $file_names_hindi[]='/Writereaddata/Career/'.$file_name;
                                $response['file_name'] = $file_name;
                            } else {
                                $response['is_ok'] = "no";
                                $response['error'] = "File not uploaded";
                            }
                            
                        }
                    }
                }
                else
                {
                    $response['is_ok'] = "no";
                    $response['error'] = "Invalid file Size or Type";
                     
                }
            
                if($response['is_ok'] == "no"){
                    echo json_encode($response);

                    wp_die();
                }
            }
        }
            }
        }

           $file_size_eng=array();
        $file_names_eng=array();

        if(isset($_FILES["tender_document_eng"]))
        {
               // Number of uploaded files
            $num_files = count($_FILES['tender_document_eng']);

            /** loop through the array of files ***/
            for($i=0; $i < $num_files;$i++)
            {
                $validextensions = array("pdf", "zip", "rar");
                 if( $_FILES["tender_document_eng"]["size"] >0){
                $temporary = explode(".", $_FILES["tender_document_eng"]["name"][$i]);
                $file_extension = end($temporary);
                
                if($file_extension != ""  ){
                if (in_array($file_extension, $validextensions)) {
                    if ($_FILES["tender_document"]["error"][$i] > 0)
                    {
                         
                        $response['is_ok'] = "no";
                            $response['error'] = "Return Code: " . $_FILES["tender_document_eng"]["error"][$i] . "<br/><br/>";
                    }
                    else
                    {
                        if (file_exists("/wp-content/plugins/career/WCP/DATA/Writereaddata/Career/" . $_FILES["tender_document_eng"]["name"][$i])) {
                             
                            $response['is_ok'] = "no";
                            $response['error'] = "already exists.";
                        }
                        else
                        {
                             
                             $file_name= $_FILES["tender_document_eng"]['name'][$i];
                            if (@move_uploaded_file($_FILES['tender_document_eng']['tmp_name'][$i],dirname(__FILE__).'/Writereaddata/Career/'. $file_name)) {
                                $response['is_ok'] = "yes";
                                $file_size_eng[]=$_FILES["tender_document_eng"]["size"][$i];
                                $file_names_eng[]='/Writereaddata/Career/'.$file_name;
                                $response['file_name'] = $file_name;
                            } else {
                                $response['is_ok'] = "no";
                                $response['error'] = "File not uploaded";
                            }
                            
                        }
                    }
                }
                else
                {
                    $response['is_ok'] = "no";
                    $response['error'] = "Invalid file Size or Type";
                     
                }
            
                if($response['is_ok'] == "no"){
                    echo json_encode($response);

                    wp_die();
                }
            }
            }
        }
        } 
        $tender_document_eng=count(array_filter($_FILES["tender_document_eng"]));
        $tender_document_hindi=count(array_filter($_FILES["tender_document_hindi"]));


          if($tender_document_hindi>0) {
 
                $data['format_file_hnd'] = $_POST["ffh"]."$".implode("$",$file_names_hindi);
                $data['File_size_hnd'] = $_POST["fsh"]."$".implode("$",$file_size_hindi);
                $data['Docname_hnd'] = $_POST["dh"]."$".implode("$",$_POST['document_title_hindi']); 
            }
             if($tender_document_eng>0) {
             
                $data['format_file'] = $_POST["ffe"]."$".implode("$",$file_names_eng);
                $data['File_size'] = $_POST["fse"]."$".implode("$",$file_size_eng);
                $data['Docname_eng'] = $_POST["de"]."$".implode("$",$_POST['document_title_eng']);
            }



             
      
         
             $data_result = $wpdb->update('tbl_career_shortlistedjob', $data, array('Formatid' => $_POST['Formatid']));
           
           
                $result_array['status'] = 1;
                $result_array['msg'] = 'Record Update Succefully.';
        
        
        echo json_encode($result_array);
        exit;
    }

    // add trade
    public static function update_corr_info(){
       
        $result_array['status'] = 0;
        
            global $wpdb;

         
            $file_size=null;
        $file_names=null;
         $start_date = str_replace('-', '/', $_POST['start_date']);
                    $start_date = date('Y-m-d', strtotime($start_date));
                    $end_date = str_replace('-', '/', $_POST['end_date']);
                    $end_date = date('Y-m-d', strtotime($end_date));
                   
            $data = array(  
                'Job_Heading_English' => $_POST['job_titel_english'],
                'Job_Heading_hindi' => $_POST['job_titel_hindi'],
                'OpeningDate' => $start_date,
                'CloseingDate' => $end_date,
            );
         

            if(isset($_FILES["tender_document"]["name"]))
        {
              
                $validextensions = array("pdf", "zip", "rar");

                 if( $_FILES["tender_document"]["size"] >0){

                $temporary = explode(".", $_FILES["tender_document"]["name"]);
                   
             
                $file_extension = end($temporary);
                
                if($file_extension != ""  ){
                if (in_array($file_extension, $validextensions)) {
                    if ($_FILES["tender_document"]["error"] > 0)
                    {
                         
                        $response['is_ok'] = "no";
                            $response['error'] = "Return Code: " . $_FILES["tender_document"]["error"] . "<br/><br/>";
                    }
                    else
                    {
                        if (file_exists("/wp-content/plugins/career/WCP/DATA/Writereaddata/Career/" . $_FILES["tender_document"]["name"])) {
                             
                            $response['is_ok'] = "no";
                            $response['error'] = "already exists.";
                        }
                        else
                        {
                             
                             $file_name= $_FILES["tender_document"]['name'];
                            if (@move_uploaded_file($_FILES['tender_document']['tmp_name'],dirname(__FILE__).'/Writereaddata/Career/'. $file_name)) {
                                $response['is_ok'] = "yes";
                                $file_size=$_FILES["tender_document"]["size"];
                                $file_names='~/Writereaddata/Career/'.$file_name;
                                $response['file_name'] ='~/Writereaddata/Career/'.$file_name;
                            } else {
                                $response['is_ok'] = "no";
                                $response['error'] = "File not uploaded";
                            }
                            
                        }
                    }
                }
                else
                {
                    $response['is_ok'] = "no";
                    $response['error'] = "Invalid file Size or Type";
                     
                }
            
                if($response['is_ok'] == "no"){
                    echo json_encode($response);

                    wp_die();
                }
            }
            }
        } 

   if( $file_names != null && $file_names != "" && $file_size != null && $file_size != ""  ){ 
                $data['Link'] = $file_names;
                
          
            
                $data['File_Size'] = $file_size;
            }
          

     
                    if(isset($_POST["weblink"]) && $_POST["weblink"] != ""){
                $file_names=$_POST["weblink"];
                  $file_size=null;
                    $data['Link'] = $file_names;
                     $data['File_Size'] = $file_size;
            }

    
                  
  
            $data_result = $wpdb->update('tbl_careers_curentjob_corrigendum', $data, array('CorrgendumID' => $_POST['CorrgendumID']));
           
            // $lastid = $wpdb->insert_id;
            // if ($lastid) {
                $result_array['status'] = 1;
                $result_array['msg'] = 'Record Update Succefullyyyy.';
            // } else {
            //     $result_array['msg'] = 'Please try again.';
            // }
          
   
        
        
        echo json_encode($result_array);
        exit;
    }

      

 

    // deletes career
    public static function delete_career(){
      
        $result_array = array();
        $result_array['status'] = 0;
        if (!empty($_POST)){
            global $wpdb;
            // user id is really id
            $id = ($_POST['id']);

            $sql = "select * FROM tbl_careers_curentjob WHERE Curentjobid = %d";
            $result=$wpdb->get_results($wpdb->prepare($sql, Array($id)), "ARRAY_A");
            if (count($result) > 0) {
                $career_data = $result[0];
                 $sql = "select * FROM tbl_careers_curentjob_corrigendum WHERE Curentjobid= %d";
                $result=$wpdb->get_results($wpdb->prepare($sql, Array($id)), "ARRAY_A");
                if (count($result) > 0) {
                    for($i=0;$i== count($result);$i++){
                    $Link = $result[$i]['Link'];
                    if (file_exists(dirname(__FILE__) .str_replace("~","",$Link))) {
                        unlink(dirname(__FILE__).str_replace("~","",$Link));
                    } 
                    $sql = "DELETE FROM tbl_careers_curentjob_corrigendum WHERE Curentjobid= %d";
                    $result=$wpdb->get_results($wpdb->prepare($sql, Array($id)), OBJECT);
                    }
                }


                $sql = "DELETE FROM tbl_careers_curentjob WHERE Curentjobid = %d";
                $result=$wpdb->get_results($wpdb->prepare($sql, Array($id)), OBJECT);

                $result_array['status'] = 1;
               
            }else{
                $result_array['status'] = 0;
                $result_array['msg'] = 'Data Not Founds.';
            }
           
            
        }
        echo json_encode($result_array);exit;
    }
    // deletes career
    public static function delete_corr(){
      
        $result_array = array();
        $result_array['status'] = 0;
        if (!empty($_POST)){
            global $wpdb;
            // user id is really id
            $id = ($_POST['id']);

            $sql = "select * FROM tbl_careers_curentjob_corrigendum WHERE CorrgendumID = %d";
            $result=$wpdb->get_results($wpdb->prepare($sql, Array($id)), "ARRAY_A");
            if (count($result) > 0) {
                $Link = $result[0]['Link'];
                if (file_exists(dirname(__FILE__).str_replace("~","",$Link))) {
                    unlink(dirname(__FILE__).str_replace("~","",$Link));
                } 
                $sql = "DELETE FROM tbl_careers_curentjob_corrigendum WHERE CorrgendumID= %d";
                $result=$wpdb->get_results($wpdb->prepare($sql, Array($id)), OBJECT);

                $result_array['status'] = 1;
               
            }else{
                $result_array['status'] = 0;
                $result_array['msg'] = 'Data Not Founds.';
            }
           
            
        }
        echo json_encode($result_array);exit;
    }

   
 

    public static function get_detail_by_id(){
       
        $result = array();
        $result['status'] = 0;
        if (!empty($_POST)) {

            global $wpdb;
            $id = esc_sql($_POST['id']);

            
          $sql = "select * FROM tbl_careers_curentjob WHERE Curentjobid = %d";
            $user_details = $wpdb->get_results($wpdb->prepare($sql, Array($id)), "ARRAY_A");
      
            $result['status'] = 1;
            $result['user_details'] = $user_details;
          
        }
        echo json_encode($result);
        exit;
    }
    public static function get_career_advertisement_no(){
       
        $result = array();
        $result['status'] = 0;
   

            global $wpdb;
 

            
          $sql = "select Advertisement_No,Curentjobid  FROM tbl_careers_curentjob ";
            $user_details = $wpdb->get_results($wpdb->prepare($sql, Array()), "ARRAY_A");
      
            $result['status'] = 1;
            $result['user_details'] = $user_details;
      
        echo json_encode($result);
        exit;
    }

    public static function get_career_result_update(){
       
        $result = array();
        $result['status'] = 0;
        if (!empty($_POST)) {

            global $wpdb;
            $id = esc_sql($_POST['id']);

             $sql = "select Advertisement_No,Curentjobid  FROM tbl_careers_curentjob ";
            $drop_down = $wpdb->get_results($wpdb->prepare($sql, Array()), "ARRAY_A");
            
          $sql = "select * FROM tbl_career_shortlistedjob WHERE Formatid = %d";
            $user_details = $wpdb->get_results($wpdb->prepare($sql, Array($id)), "ARRAY_A");
      
            $result['status'] = 1;
            $result['user_details'] = $user_details;
             $result["drop_down"] = $drop_down;
          
        }
        echo json_encode($result);
        exit;
    }


     public static function delete_career_result(){
       
        $result = array();
        $result['status'] = 0;
        if (!empty($_POST)) {

            global $wpdb;
            $id = esc_sql($_POST['id']);
          
 $sql = "DELETE FROM tbl_career_shortlistedjob WHERE Formatid= %d";
                $result=$wpdb->get_results($wpdb->prepare($sql, Array($id)), OBJECT);
            $result['msg'] = 'Record Delete Succefully.';

            
            $result['status'] = 1;
         
          
        }
        echo json_encode($result);
        exit;
    }
    
   


    public static function delete_file(){
       
        $result = array();
        $result['status'] = 0;
        if (!empty($_POST)) {

            global $wpdb;
            $id = esc_sql($_POST['id']);
            $type = esc_sql($_POST['type']);

               $sql = "select * FROM tbl_career_shortlistedjob WHERE Formatid = %d";
            $results = $wpdb->get_results($wpdb->prepare($sql, Array($id)), "ARRAY_A");
          if (count($results) > 0) {
                $career_data = $results[0];
                 
                $result['results'] =$results;
                if($type == "eng"){
                $wh = explode("$",$results[0]['Docname_eng']);
                $tf = explode("$",$results[0]['format_file']);
                $fs = explode("$",$results[0]['File_size']);
                 $result['wh'] =$wh;
                  $result['tf'] =$tf;
                   $result['fs'] =$fs;
                $array_index =  array_search($_POST['file_name'],$tf);
                 $result['array_index']=$array_index;
               
                unset($wh[$array_index]);
                unset($tf[$array_index]);
                unset($fs[$array_index]);
                if (file_exists(dirname(__FILE__).$_POST['file_name'])) {
                        unlink(dirname(__FILE__).$_POST['file_name']);
                    } 
                
                   $result['whs'] =$wh;
                  $result['tfs'] =$tf;
                   $result['fss'] =$fs;
                $data=array();

                $data['Docname_eng'] = implode("$",$wh);
           
                $data['format_file'] =  implode("$",$tf);
                $data['File_size'] =  implode("$",$fs);
                $data_result = $wpdb->update('tbl_career_shortlistedjob', $data, array('Formatid' => $id));
                $result['status'] = 1;
            }else{
                      $wh = explode("$",$results[0]['Docname_hnd']);
                $tf = explode("$",$results[0]['format_file_hnd']);
                $fs = explode("$",$results[0]['File_size_hnd']);
                 $result['wh'] =$wh;
                  $result['tf'] =$tf;
                   $result['fs'] =$fs;
                $array_index =  array_search($_POST['file_name'],$tf);
                 $result['array_index']=$array_index;
               
                unset($wh[$array_index]);
                unset($tf[$array_index]);
                unset($fs[$array_index]);
                  if (file_exists(dirname(__FILE__).$_POST['file_name'])) {
                        unlink(dirname(__FILE__).$_POST['file_name']);
                    } 
                   $result['whs'] =$wh;
                  $result['tfs'] =$tf;
                   $result['fss'] =$fs;
                $data=array();

                $data['Docname_hnd'] = implode("$",$wh);
           
                $data['format_file_hnd'] =  implode("$",$tf);
                $data['File_size_hnd'] =  implode("$",$fs);
                $data_result = $wpdb->update('tbl_career_shortlistedjob', $data, array('Formatid' => $id));
                $result['status'] = 1;

                }
            $result['msg'] = 'Record Delete Succefully.';;
  
               
            }else{
                $result['status'] = 0;
                $result['msg'] = 'Data Not Founds.';
            }
          
        
        echo json_encode($result);
        exit;
        }
    }




    public static function get_detail_corr_by_id(){
       
        $result = array();
        $result['status'] = 0;
        if (!empty($_POST)) {

            global $wpdb;
            $id = esc_sql($_POST['id']);

            $sql = "SELECT * FROM tbl_careers_curentjob_corrigendum  ";
        $sql .= " WHERE ( CorrgendumID = '" . esc_sql($_POST['id']) . "') ";
      
            $user_details = $wpdb->get_results($wpdb->prepare($sql, Array()), "ARRAY_A");
      
            $result['status'] = 1;
            $result['user_details'] = $user_details;
          
        }
        echo json_encode($result);
        exit;
    }

    

    // update career
    // needs to be fixed
    public static function Update_career_info() {
        $result_array['status'] = 0;
        
            global $wpdb;

          
 
            $file_size=null;
        $file_names=null;

          $start_date = str_replace('-', '/', $_POST['start_date']);
                    $start_date = date('Y-m-d', strtotime($start_date));
                    $end_date = str_replace('-', '/', $_POST['end_date']);
                    $end_date = date('Y-m-d', strtotime($end_date));
                   
            $data = array(  
                'Job_Heading_English' => $_POST['job_titel_english'],
                'Job_Heading_hindi' => $_POST['job_titel_hindi'],
                'OpeningDate' => $start_date,
                'CloseingDate' => $end_date,
                'Advertisement_No' => $_POST['advertiement_no']
            );
       
             
            if(isset($_FILES["tender_document"]["name"]))
        {
              
                $validextensions = array("pdf", "zip", "rar");
                 if( $_FILES["tender_document"]["size"] >0){
                $temporary = explode(".", $_FILES["tender_document"]["name"]);
                $file_extension = end($temporary);
                
                if($file_extension != ""  ){
                if (in_array($file_extension, $validextensions)) {
                    if ($_FILES["tender_document"]["error"] > 0)
                    {
                         
                        $response['is_ok'] = "no";
                            $response['error'] = "Return Code: " . $_FILES["tender_document"]["error"] . "<br/><br/>";
                    }
                    else
                    {
                        if (file_exists("/wp-content/plugins/career/WCP/DATA/Writereaddata/Career/" . $_FILES["tender_document"]["name"])) {
                             
                            $response['is_ok'] = "no";
                            $response['error'] = "already exists.";
                        }
                        else
                        {
                             
                             $file_name= $_FILES["tender_document"]['name'];
                            if (@move_uploaded_file($_FILES['tender_document']['tmp_name'],dirname(__FILE__).'/Writereaddata/Career/'. $file_name)) {
                                $response['is_ok'] = "yes";
                                $file_size=$_FILES["tender_document"]["size"];
                                $file_names=$file_name;
                                $response['file_name'] ='~/Writereaddata/Career/'.$file_name;
                            } else {
                                $response['is_ok'] = "no";
                                $response['error'] = "File not uploaded";
                            }
                            
                        }
                    }
                }
                else
                {
                    $response['is_ok'] = "no";
                    $response['error'] = "Invalid file Size or Type";
                     
                }
            
                if($response['is_ok'] == "no"){
                    echo json_encode($response);

                    wp_die();
                }
            }
            }
        } 

if ($file_names != null && $file_names != "" && $file_size != null && $file_size != "") {
    $data['Link'] = '~/Writereaddata/Career/' . $file_name;
    $data['File_Size'] = $file_size;
} elseif (isset($_POST["weblink"]) && $_POST["weblink"] != "") {
    $data['Link'] = $_POST["weblink"];
    $data['File_Size'] = null; 
}


    
                  
           
          
  
            $data_result = $wpdb->update('tbl_careers_curentjob', $data, array('Curentjobid' => $_POST['Curentjobid']));
           
            // $lastid = $wpdb->insert_id;
            // if ($lastid) {
                $result_array['status'] = 1;
                $result_array['msg'] = 'Record Update Succefully.';
            // } else {
            //     $result_array['msg'] = 'Please try again.';
            // }
        
        
        echo json_encode($result_array);
        exit;
    }

   
    public static function career_current_recruitments(){

       
        $requestData = $_REQUEST;
    
        global $wpdb,$wp;
        $data = array();
 
        $sql = "SELECT * FROM tbl_careers_curentjob ";

     

        $date=date("Y-m-d h:m:i");
          $sql .= " where OpeningDate <= '".$date."' AND CloseingDate >= '".$date."'";
    
    if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " AND ( Advertisement_No LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
        }
$sql .= " ORDER BY Curentjobid DESC, CloseingDate DESC";
        $result=$wpdb->get_results($wpdb->prepare($sql, Array()), OBJECT);

        
        $totalData = 0;
        $totalFiltered = 0;
         if (count($result) > 0) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }
      $k=1;
        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " ORDER BY CloseingDate ASC LIMIT " . $requestData['start'] . "," . $requestData['length'];
             $k=$requestData['start']+1;
        }

       
           
       
        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;
      
        foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }
                    $html="<div>";
           
            $temp['CloseingDate'] = date("d M Y",strtotime($row->CloseingDate));

       if($row->File_Size != null){
          $temporary = explode(".",$row->Link);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {

                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }

            $html= $html.'<div><a href="'.get_site_url().'/wp-content/plugins/career/WCP/DATA/'.str_replace("~","",$row->Link).'">'.$row->Job_Heading_English.'<img src="'.home_url().'/wp-content/plugins/tenders/WCP/DATA/uploads/'.$file_img.'">'.$row->File_Size.'</a> </div>';
        }else{
             $html= $html.'<div><a href="'.$row->Link.'">'.$row->Job_Heading_English.'</a></div>';
        }


        

        

                 $sqlcorr = "SELECT * FROM tbl_careers_curentjob_corrigendum  ";
            $sqlcorr .= " WHERE ( tbl_careers_curentjob_corrigendum.Curentjobid = '" . esc_sql($row->Curentjobid) . "') ";
      
            $details = $wpdb->get_results($wpdb->prepare($sqlcorr, Array()), "OBJECT");
			//print_r($details);
            $i=0;
            foreach ($details as $rows) { 
 if($row->File_Size != null){
          $temporary = explode(".",$rows->Link);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {
                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }
	 

            $html= $html.'<div><a href="'.get_site_url().'/wp-content/plugins/career/WCP/DATA/'.str_replace("~","",$rows->Link).'">'.$rows->Job_Heading_English.'<img src="'.home_url().'/wp-content/plugins/tenders/WCP/DATA/uploads/'.$file_img.'">'.$rows->File_size.'</a> </div>';
        }else{
             $html= $html.'<div><a href="'.$rows->Link.'">'.$rows->Job_Heading_English.'</a></div>';
        }


            }

                $html=$html."</div>";
         $temp['Description'] = $html;
            $temp['id']=$k;
            $data[] = $temp;
            $id = "";
            $k++;
        }


        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
		
        exit(0);
    }
	
	
	
	
	
	
	
	

    public static function career_in_progress_recruitments(){

       
        $requestData = $_REQUEST;
    
        global $wpdb,$wp;
        $data = array();
 
        $sql = "SELECT * FROM tbl_careers_curentjob ";

     

        $date=date("Y-m-d h:m:i");
          $sql .= " where tbl_careers_curentjob.Advertisement_No NOT IN(SELECT Advertisement_No FROM tbl_career_shortlistedjob Where Type LIKE '%close%') AND  CloseingDate <= '".$date."'";
    
    if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " AND ( Advertisement_No LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
        }

        $result=$wpdb->get_results($wpdb->prepare($sql, Array()), OBJECT);

        
        $totalData = 0;
        $totalFiltered = 0;
         if (count($result) > 0) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }
      $k=1;
        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " ORDER BY CloseingDate ASC LIMIT " . $requestData['start'] . "," . $requestData['length'];
             $k=$requestData['start']+1;
        }

       
           
       
        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;
		
//		print_r($service_price_list);
      
        foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }
                    $html="<div>";
           
            $temp['CloseingDate'] = date("d M Y",strtotime($row->CloseingDate));

       if($row->File_Size != null){
          $temporary = explode(".",$row->Link);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {

                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }

            $html= $html.'<div><a href="'.get_site_url().'/wp-content/plugins/career/WCP/DATA/'.str_replace("~","",$row->Link).'">'.$row->Job_Heading_English.'<img src="'.home_url().'/wp-content/plugins/tenders/WCP/DATA/uploads/'.$file_img.'">'.$row->File_Size.'</a> </div>';
        }else{
             $html= $html.'<div><a href="'.$row->Link.'">'.$row->Job_Heading_English.'</a></div>';
        }


        

        

                 $sqlcorr = "SELECT * FROM tbl_careers_curentjob_corrigendum  ";
            $sqlcorr .= " WHERE ( Curentjobid = '" .$row->Curentjobid . "') ";
       $temp['sqlcorr'] = $sqlcorr;
            $details = $wpdb->get_results($wpdb->prepare($sqlcorr, Array()), "OBJECT");
            $i=0;
            foreach ($details as $rowsss) { 
                  

 if($row->File_Size != null){
          $temporary = explode(".",$row->Link);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {

                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }

            $html= $html.'<div><a href="'.get_site_url().'/wp-content/plugins/career/WCP/DATA/'.str_replace("~","",$rowsss->Link).'">'.$rowsss->Job_Heading_English.'<img src="'.home_url().'/wp-content/plugins/tenders/WCP/DATA/uploads/'.$file_img.'">'.$rowsss->File_size.'</a> </div>';
        }else{
             $html= $html.'<div><a href="'.$rowsss->Link.'">'.$rowsss->Job_Heading_English.'</a></div>';
        }


            }

                $html=$html."</div>";
         $temp['Description'] = $html;
            $temp['id']=$k;
            $data[] = $temp;
            $id = "";
            $k++;
        }


        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
        exit(0);
    }

    public static function career_close_recruitments(){

       
        
    $requestData = $_REQUEST;
        global $wpdb,$wp;
        $data = array();
 
        $sql = "SELECT * FROM tbl_career_shortlistedjob  INNER JOIN tbl_careers_curentjob ON tbl_career_shortlistedjob.Advertisement_No = tbl_careers_curentjob.Advertisement_No ";

     

        $date=date("Y-m-d h:m:i");
          $sql .= " where Type LIKE '%close%'";

      if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " AND ( Advertisement_No LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
        }
    
        $result=$wpdb->get_results($wpdb->prepare($sql, Array()), OBJECT);

        
        $totalData = 0;
        $totalFiltered = 0;
         if (count($result) > 0) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }
      $k=1;
        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " ORDER BY CloseingDate ASC LIMIT " . $requestData['start'] . "," . $requestData['length'];
             $k=$requestData['start']+1;
        }

       
           
       
        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;
      
        foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }


                    $html="<div>";
           
            $temp['CloseingDate'] = date("d M Y",strtotime($row->CloseingDate));

              

 if($row->File_Size != null){
          $temporary = explode(".",$row->Link);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {

                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }

            $html= $html.'<div><a href="'.get_site_url().'/wp-content/plugins/career/WCP/DATA/'.str_replace("~","",$row->Link).'">'.$row->Job_Heading_English.'<img src="'.home_url().'/wp-content/plugins/tenders/WCP/DATA/uploads/'.$file_img.'">'.$row->File_Size.'</a> </div>';
        }else{
             $html= $html.'<div><a href="'.$row->Link.'">'.$row->Job_Heading_English.'</a></div>';
        }


             $wh = explode("$",$row->Docname_eng);
                $tf = explode("$",$row->format_file);
                $fs = explode("$",$row->File_size);
                $html="<table>";

                for ($i=0; $i <= count($tf) ; $i++) { 
                    if($tf[$i] != ""){
            $temporary = explode(".", $tf[$i]);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {

                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }

                        $html= $html.'<div><a href="'.get_site_url().'/wp-content/plugins/career/WCP/DATA/'.str_replace("~","",$tf[$i]).'">'.$wh[$i].'<img src="'.home_url().'/wp-content/plugins/tenders/WCP/DATA/uploads/'.$file_img.'">'.$fs[$i].'</a> </div>';
                }
            }

       

                $html=$html."</div>";
         $temp['Description'] = $html;
            $temp['id']=$k;
            $data[] = $temp;
            $id = "";
            $k++;
        }


        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
        exit(0);
    }
  
  
}


$wcp_career_controller = new WCP_Career_Controller();

/// Shortcodes

add_action('admin_menu', array($wcp_career_controller, 'wcp_tenant_screen'));
add_shortcode('career_current_recruitments', array($wcp_career_controller, 'view_career_current_recruitments'));  

add_shortcode('career_in_progress_recruitments', array($wcp_career_controller, 'view_career_in_progress_recruitments'));  


add_shortcode('career_close_recruitments', array($wcp_career_controller, 'view_career_close_recruitments'));  



/// Ajax
 

add_action('wp_ajax_get_career', Array('WCP_Career_Controller', 'get_career'));
add_action('wp_ajax_nopriv_get_career', array('WCP_Career_Controller', 'get_career')); 

add_action('wp_ajax_delete_career', Array('WCP_Career_Controller', 'delete_career'));
add_action('wp_ajax_nopriv_delete_career', array('WCP_Career_Controller', 'delete_career'));

add_action('wp_ajax_get_detail_by_id', Array('WCP_Career_Controller', 'get_detail_by_id'));
add_action('wp_ajax_nopriv_get_detail_by_id', array('WCP_Career_Controller', 'get_detail_by_id'));

add_action('wp_ajax_Add_career_info', Array('WCP_Career_Controller', 'Add_career_info'));
add_action('wp_ajax_nopriv_Add_career_info', array('WCP_Career_Controller', 'Add_career_info'));

 
add_action('wp_ajax_Update_career_info', Array('WCP_Career_Controller', 'Update_career_info'));
add_action('wp_ajax_nopriv_Update_career_info', array('WCP_Career_Controller', 'Update_career_info'));
 
 
add_action('wp_ajax_get_corr', Array('WCP_Career_Controller', 'get_corr'));
add_action('wp_ajax_nopriv_get_corr', array('WCP_Career_Controller', 'get_corr')); 

add_action('wp_ajax_add_corr_info', Array('WCP_Career_Controller', 'add_corr_info'));
add_action('wp_ajax_nopriv_add_corr_info', array('WCP_Career_Controller', 'add_corr_info')); 
 
add_action('wp_ajax_delete_corr', Array('WCP_Career_Controller', 'delete_corr'));
add_action('wp_ajax_nopriv_delete_corr', array('WCP_Career_Controller', 'delete_corr')); 

add_action('wp_ajax_get_detail_corr_by_id', Array('WCP_Career_Controller', 'get_detail_corr_by_id'));
add_action('wp_ajax_nopriv_get_detail_corr_by_id', array('WCP_Career_Controller', 'get_detail_corr_by_id')); 

add_action('wp_ajax_update_corr_info', Array('WCP_Career_Controller', 'update_corr_info'));
add_action('wp_ajax_nopriv_update_corr_info', array('WCP_Career_Controller', 'update_corr_info'));


add_action('wp_ajax_delete_file', Array('WCP_Career_Controller', 'delete_file'));
add_action('wp_ajax_nopriv_delete_file', array('WCP_Career_Controller', 'delete_file')); 

add_action('wp_ajax_get_career_result', Array('WCP_Career_Controller', 'get_career_result'));
add_action('wp_ajax_nopriv_get_career_result', array('WCP_Career_Controller', 'get_career_result')); 
 
 add_action('wp_ajax_get_career_advertisement_no', Array('WCP_Career_Controller', 'get_career_advertisement_no'));
add_action('wp_ajax_nopriv_get_career_advertisement_no', array('WCP_Career_Controller', 'get_career_advertisement_no')); 

 add_action('wp_ajax_add_career_result_info', Array('WCP_Career_Controller', 'add_career_result_info'));
add_action('wp_ajax_nopriv_add_career_result_info', array('WCP_Career_Controller', 'add_career_result_info')); 

 add_action('wp_ajax_get_career_result_update', Array('WCP_Career_Controller', 'get_career_result_update'));
add_action('wp_ajax_nopriv_get_career_result_update', array('WCP_Career_Controller', 'get_career_result_update')); 

 add_action('wp_ajax_update_career_result_info', Array('WCP_Career_Controller', 'update_career_result_info'));
add_action('wp_ajax_nopriv_update_career_result_info', array('WCP_Career_Controller', 'update_career_result_info')); 


 add_action('wp_ajax_career_current_recruitments', Array('WCP_Career_Controller', 'career_current_recruitments'));
add_action('wp_ajax_nopriv_career_current_recruitments', array('WCP_Career_Controller', 'career_current_recruitments')); 


 add_action('wp_ajax_career_in_progress_recruitments', Array('WCP_Career_Controller', 'career_in_progress_recruitments'));
add_action('wp_ajax_nopriv_career_in_progress_recruitments', array('WCP_Career_Controller', 'career_in_progress_recruitments')); 


 add_action('wp_ajax_career_close_recruitments', Array('WCP_Career_Controller', 'career_close_recruitments'));
add_action('wp_ajax_nopriv_career_close_recruitments', array('WCP_Career_Controller', 'career_close_recruitments')); 

 add_action('wp_ajax_delete_career_result', Array('WCP_Career_Controller', 'delete_career_result'));
add_action('wp_ajax_nopriv_delete_career_result', array('WCP_Career_Controller', 'delete_career_result'));