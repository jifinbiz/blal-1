<?php
// Make sure we don't expose any info if called directly
if (!defined('WPINC')) {
  die;
}


include_once(dirname(__FILE__) . "/View.php");
 

//add_action( 'admin_init', 'redirect_non_logged_users_to_specific_page' );
class WCP_Tenders_Controller {

    //Backend tenders page
    public static function render_view_front_screen() {
        print WCP_Tenders_View::build_html();
    }

    //Frontend tenders page
    public static function view_front_active_tenders() {
        print WCP_Tenders_View::active_tender_view();
    }
     //Frontend tenders page
    public static function view_front_archived_tenders() {
        print WCP_Tenders_View::archived_tender_view();
    }

    //backend tenders page
    public static function render_view_download_front_screen() {
        print WCP_Tenders_View::tender_download_view();
    }
  
  
    //in backend show plugin and set name in admin menu
    public static function wcp_tenant_screen() {


        add_menu_page('Tenders', 'Tenders', 'manage_options', 'wcp-tenders','','dashicons-edit','9' );
        add_submenu_page('wcp-tenders', 'Active Tenders', 'Active Tenders', 'manage_options', 'wcp-tenders',array('WCP_Tenders_Controller', 'render_view_front_screen') );
       
       add_submenu_page('wcp-tenders', 'Downloaded Tenders', 'Downloaded Tenders', 'manage_options', 'wcp-tenders-download',array('WCP_Tenders_Controller', 'render_view_download_front_screen') );
       

    }

  

    // Get all tenders data 
    public static function get_tenders(){

       
       
        
        $requestData = $_REQUEST;
    
        global $wpdb,$wp;
        $data = array();
 
        $sql = "SELECT Tenderid as id,Tenderno,Workdetails_eng,Start_date,End_date,Opening_date,Status  FROM tbl_tenders ";

        if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " WHERE (Tenderno LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
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
            $sql .= " ORDER BY id DESC LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;

        foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }
             
            $temp['Start_date'] = date("d-M-Y",strtotime($row->Start_date));
            $temp['End_date'] = date("d-M-Y",strtotime($row->End_date));
            $temp['Opening_date'] = date("d-M-Y",strtotime($row->Opening_date));
           
            
            $id = $row->id;
            $action = '<div style="display: flex;">';
            // temporarily removed
            $action .= '<input type="button" value="Edit" class="btn btn-info"  onclick="tenders_update(' . $id . ')">&nbsp; &nbsp;';
            $action .= "<input type='button' value='Delete' class='btn btn-danger' onclick='tenders_delete(" . $id . ")'>&nbsp;";
            $action .= "<input type='button' value='Corrigendum' class='btn btn-info' onclick='tenders_corr(" . $id . ")'>&nbsp;";
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

    public static function tender_get_download_tenders(){

       
       
        
        $requestData = $_REQUEST;
    
        global $wpdb,$wp;
        $data = array();
 
        $sql = "SELECT * FROM  tbl_tendordownload ";

        if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= " WHERE (Tenderno LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
        }

        $result=$wpdb->get_results($wpdb->prepare($sql, Array()), OBJECT);

        
        $totalData = 0;
        $totalFiltered = 0;
         if (count($result) > 0) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }
    
 $i=1;
        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " ORDER BY Id  DESC LIMIT " . $requestData['start'] . "," . $requestData['length'];
             $i=$requestData['start']+1;
        }

        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;
       
        foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }
            
           
            
            $temp['id'] = $i;
        

            $data[] = $temp;
            $id = "";
            $i++;
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


     // Get all tenders data 
    public static function tender_get_frontend(){

       
        
    
        global $wpdb,$wp;
        $data = array();


        // for EOI tender

        $sql = "SELECT * FROM tbl_tenders ";

        if (isset($_POST['location']) && $_POST['location'] != '' && $_POST['location'] != null) {
            $sql .= " WHERE (Location LIKE '%" . esc_sql($_POST['location']) . "%') ";
        }else{
            $sql .= " WHERE (Location LIKE '%CORPORATE OFFICE%') ";
        }

        $date=date("Y-m-d");
          $sql .= "  AND Workdetails_hindi LIKE '%EoI %' AND  (Opening_date >= '".$date."'  OR  ( End_date = '1900-01-01' ) ) ";

        $result=$wpdb->get_results($wpdb->prepare($sql, Array()), OBJECT);

        
        $totalData = 0;
        $totalFiltered = 0;
         if (count($result) > 0) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }
    
        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " ORDER BY End_date DESC LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }else{
             $sql .= " ORDER BY End_date DESC ";
        }

       
           
       
        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
      
        $arr_data = Array();
        $arr_data = $result;

        foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }
           $Extended_date=($row->Extended_date != "")?$row->Extended_date."<br>":'';
            $temp['End_date'] =$Extended_date.date("d-M-Y",strtotime($row->End_date))."<br>".$row->EndDate_Time;
            $temp['Opening_date'] = date("d-M-Y",strtotime($row->Opening_date))."<br>".$row->OpeningDate_Time;
            $Tenderid = $row->Tenderid;

             $wh = explode("$",$row->Workdetails_hindi);
                $tf = explode("$",$row->Tenderfile);
                $fs = explode("$",$row->file_size);
                $html="<table class='borderless'>";

                for ($i=0; $i <= count($tf) ; $i++) { 
                       if($tf[$i] != ""){
            $temporary = explode(".", $tf[$i]);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {

                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }

                $html=$html."<tr><td><a href='#' onclick='download_tender(".$Tenderid.",".$i.",1);'  >".$wh[$i]."</a></td><td><a href='#' onclick='download_tender(".$Tenderid.",".$i.",1);'  ><img src='".home_url()."/wp-content/plugins/tenders/WCP/DATA/uploads/".$file_img."''></a></td> <td><a href='#' onclick='download_tender(".$Tenderid.",".$i.",1);'  >".$fs[$i]."</a></td> </tr>";
                }

            }



                 $sqlcorr = "SELECT * FROM tbl_corrigendum  ";
            $sqlcorr .= " WHERE ( Tenderid = '" . esc_sql($row->Tenderid) . "') ";
      
            $details = $wpdb->get_results($wpdb->prepare($sqlcorr, Array()), "OBJECT");
            $i=0;
            foreach ($details as $rows) { 
           $wh =  !empty($rows->CG_NameEng)?$rows->CG_NameEng:$rows->CG_NameHnd;
                $tf = $rows->CG_File;
                $fs = $rows->file_size;
               
                
                      
            $temporary = explode(".", $tf);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {

                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }

                       $html=$html."<tr><td><a href='#' onclick='download_tender(".$Tenderid.",".$i.",2);' >".$wh."</a></td><td><a href='#' onclick='download_tender(".$Tenderid.",".$i.",2);'  ><img src='".home_url()."/wp-content/plugins/tenders/WCP/DATA/uploads/".$file_img."''></a></td> <td><a href='#' onclick='download_tender(".$Tenderid.",".$i.",2);'  >".$fs."</a></td> </tr>";
                $i++;
            }

                $html=$html."</table>";
            $temp['Description'] =$row->Tenderno."<br>".$row->Workdetails_eng."<br>".$html;
            $temp["End_date"]="";
            $temp["Opening_date"]="";
            $data[] = $temp;
            $id = "";
        }


        // for normal tender
 
        $sql = "SELECT * FROM tbl_tenders ";

        if (isset($_POST['location']) && $_POST['location'] != '' && $_POST['location'] != null) {
            $sql .= " WHERE (Location LIKE '%" . esc_sql($_POST['location']) . "%') ";
        }else{
            $sql .= " WHERE (Location LIKE '%CORPORATE OFFICE%') ";
        }

        $date=date("Y-m-d");
          $sql .= "  AND Workdetails_hindi NOT LIKE '%EoI %' AND  (Opening_date >= '".$date."'  OR  ( End_date = '1900-01-01' ) ) ";

        $result=$wpdb->get_results($wpdb->prepare($sql, Array()), OBJECT);

        
        $totalData = 0;
        $totalFiltered = 0;
         if (count($result) > 0) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }
    
        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " ORDER BY End_date DESC LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }else{
             $sql .= " ORDER BY End_date DESC ";
        }

       
           
       
        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
      
        $arr_data = Array();
        $arr_data = $result;

        foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }
           $Extended_date=($row->Extended_date != "")?$row->Extended_date."<br>":'';
            $temp['End_date'] =$Extended_date.date("d-M-Y",strtotime($row->End_date))."<br>".$row->EndDate_Time;
            $temp['Opening_date'] = date("d-M-Y",strtotime($row->Opening_date))."<br>".$row->OpeningDate_Time;
            $Tenderid = $row->Tenderid;

             $wh = explode("$",$row->Workdetails_hindi);
                $tf = explode("$",$row->Tenderfile);
                $fs = explode("$",$row->file_size);
                $html="<table class='borderless'>";

                for ($i=0; $i <= count($tf) ; $i++) { 
                       if($tf[$i] != ""){
            $temporary = explode(".", $tf[$i]);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {

                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }

                $html=$html."<tr><td><a href='#' onclick='download_tender(".$Tenderid.",".$i.",1);'  >".$wh[$i]."</a></td><td><a href='#' onclick='download_tender(".$Tenderid.",".$i.",1);'  ><img src='".home_url()."/wp-content/plugins/tenders/WCP/DATA/uploads/".$file_img."''></a></td> <td><a href='#' onclick='download_tender(".$Tenderid.",".$i.",1);'  >".$fs[$i]."</a></td> </tr>";
            if(strpos($wh[$i], "EoI ") !== false){
                $temp["End_date"]="";
                $temp["Opening_date"]="";
            }

                }

            }



                 $sqlcorr = "SELECT * FROM tbl_corrigendum  ";
            $sqlcorr .= " WHERE ( Tenderid = '" . esc_sql($row->Tenderid) . "') ";
      
            $details = $wpdb->get_results($wpdb->prepare($sqlcorr, Array()), "OBJECT");
            $i=0;
            foreach ($details as $rows) { 
           $wh =  !empty($rows->CG_NameEng)?$rows->CG_NameEng:$rows->CG_NameHnd;
                $tf = $rows->CG_File;
                $fs = $rows->file_size;
               
                
                      
            $temporary = explode(".", $tf);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {

                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }

                       $html=$html."<tr><td><a href='#' onclick='download_tender(".$Tenderid.",".$i.",2);' >".$wh."</a></td><td><a href='#' onclick='download_tender(".$Tenderid.",".$i.",2);'  ><img src='".home_url()."/wp-content/plugins/tenders/WCP/DATA/uploads/".$file_img."''></a></td> <td><a href='#' onclick='download_tender(".$Tenderid.",".$i.",2);'  >".$fs."</a></td> </tr>";
                $i++;
                if(strpos($wh, "EoI ") !== false){
                    $temp["End_date"]="";
                    $temp["Opening_date"]="";
                }

            }

                $html=$html."</table>";
         $temp['Description'] =$row->Tenderno."<br>".$row->Workdetails_eng."<br>".$html;

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

        // Get all tenders data 
    public static function tender_get_old_data_frontend(){

       
        
    
        global $wpdb,$wp;
        $data = array();

        //for EoI tender

        $sql = "SELECT * FROM tbl_tenders ";

        if (isset($_POST['location']) && $_POST['location'] != '' && $_POST['location'] != null) {
            $sql .= " WHERE (Location LIKE '%" . esc_sql($_POST['location']) . "%') ";
        }else{
            $sql .= " WHERE (Location LIKE '%CORPORATE OFFICE%') ";
        }

        $date=date("Y-m-d");
          $sql .= "  AND Workdetails_hindi  LIKE '%EoI %'  AND  End_date <= '".$date."'";

        $result=$wpdb->get_results($wpdb->prepare($sql, Array()), OBJECT);

        
        $totalData = 0;
        $totalFiltered = 0;
         if (count($result) > 0) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }
    

       
          //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " ORDER BY End_date DESC LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }else{
             $sql .= " ORDER BY End_date DESC " ;
        }

       
        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;

         foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }
           
            $Extended_date=($row->Extended_date != "")?$row->Extended_date."<br>":'';
            $temp['End_date'] =$Extended_date.date("d-M-Y",strtotime($row->End_date))."<br>".$row->EndDate_Time;
            $temp['Opening_date'] = date("d-M-Y",strtotime($row->Opening_date))."<br>".$row->OpeningDate_Time;
            $Tenderid = $row->Tenderid;

             $wh = explode("$",$row->Workdetails_hindi);
                $tf = explode("$",$row->Tenderfile);
                $fs = explode("$",$row->file_size);
                $html="<div class='borderless'>";

                for ($i=0; $i <= count($tf) ; $i++) { 
                       if($tf[$i] != ""){
            $temporary = explode(".", $tf[$i]);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {

                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }

                $html=$html."<div> <a href='#' onclick='download_tender(".$Tenderid.",".$i.",1);'  >".$wh[$i]."</a> <a href='#' onclick='download_tender(".$Tenderid.",".$i.",1);'  ><img src='".home_url()."/wp-content/plugins/tenders/WCP/DATA/uploads/".$file_img."''></a> <a href='#' onclick='download_tender(".$Tenderid.",".$i.",1);'  >".$fs[$i]."</a> </div>";

                }
            }

                 $sqlcorr = "SELECT * FROM tbl_corrigendum  ";
            $sqlcorr .= " WHERE ( Tenderid = '" . esc_sql($row->Tenderid) . "') ";
      
            $details = $wpdb->get_results($wpdb->prepare($sqlcorr, Array()), "OBJECT");
            $i=0;
            foreach ($details as $rows) { 
           $wh =  !empty($rows->CG_NameEng)?$rows->CG_NameEng:$rows->CG_NameHnd;
                $tf = $rows->CG_File;
                $fs = $rows->file_size;
               
                
                      
            $temporary = explode(".", $tf);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {

                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }

                       $html=$html."<div> <a href='#' onclick='download_tender(".$Tenderid.",".$i.",2);' >".$wh."</a> <a href='#' onclick='download_tender(".$Tenderid.",".$i.",2);'  ><img src='".home_url()."/wp-content/plugins/tenders/WCP/DATA/uploads/".$file_img."''></a> <a href='#' onclick='download_tender(".$Tenderid.",".$i.",2);'  >".$fs."</a></div> ";
                $i++;

            }

                $html=$html."</div>";
            $temp['Description'] =$row->Tenderno."<br>".$row->Workdetails_eng."<br>".$html;
            $temp["End_date"]="";
            $temp["Opening_date"]="";
            $data[] = $temp;
            $id = "";
        }


        // for normal tender
 
        $sql = "SELECT * FROM tbl_tenders ";

        if (isset($_POST['location']) && $_POST['location'] != '' && $_POST['location'] != null) {
            $sql .= " WHERE (Location LIKE '%" . esc_sql($_POST['location']) . "%') ";
        }else{
            $sql .= " WHERE (Location LIKE '%CORPORATE OFFICE%') ";
        }

        $date=date("Y-m-d");
          $sql .= " AND Workdetails_hindi NOT LIKE '%EoI %'  AND  End_date <= '".$date."'";

        $result=$wpdb->get_results($wpdb->prepare($sql, Array()), OBJECT);

        
        $totalData = 0;
        $totalFiltered = 0;
         if (count($result) > 0) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }
    

       
          //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " ORDER BY End_date DESC LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }else{
             $sql .= " ORDER BY End_date DESC " ;
        }

       
        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;

         foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }
           
            $Extended_date=($row->Extended_date != "")?$row->Extended_date."<br>":'';
            $temp['End_date'] =$Extended_date.date("d-M-Y",strtotime($row->End_date))."<br>".$row->EndDate_Time;
            $temp['Opening_date'] = date("d-M-Y",strtotime($row->Opening_date))."<br>".$row->OpeningDate_Time;
            $Tenderid = $row->Tenderid;

             $wh = explode("$",$row->Workdetails_hindi);
                $tf = explode("$",$row->Tenderfile);
                $fs = explode("$",$row->file_size);
                $html="<div class='borderless'>";

                for ($i=0; $i <= count($tf) ; $i++) { 
                       if($tf[$i] != ""){
            $temporary = explode(".", $tf[$i]);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {

                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }

                $html=$html."<div> <a href='#' onclick='download_tender(".$Tenderid.",".$i.",1);'  >".$wh[$i]."</a> <a href='#' onclick='download_tender(".$Tenderid.",".$i.",1);'  ><img src='".home_url()."/wp-content/plugins/tenders/WCP/DATA/uploads/".$file_img."''></a> <a href='#' onclick='download_tender(".$Tenderid.",".$i.",1);'  >".$fs[$i]."</a> </div>";
                if(strpos($wh[$i], "EoI ") !== false){
                    $temp["End_date"]="";
                    $temp["Opening_date"]="";
                }

                }
            }

                 $sqlcorr = "SELECT * FROM tbl_corrigendum  ";
            $sqlcorr .= " WHERE ( Tenderid = '" . esc_sql($row->Tenderid) . "') ";
      
            $details = $wpdb->get_results($wpdb->prepare($sqlcorr, Array()), "OBJECT");
            $i=0;
            foreach ($details as $rows) { 
           $wh =  !empty($rows->CG_NameEng)?$rows->CG_NameEng:$rows->CG_NameHnd;
                $tf = $rows->CG_File;
                $fs = $rows->file_size;
               
                
                      
            $temporary = explode(".", $tf);
            $file_extension = end($temporary);
            if ($file_extension == "pdf") {

                $file_img="pdf.jpg";
            }else{
                $file_img="book.png";
            }

                       $html=$html."<div> <a href='#' onclick='download_tender(".$Tenderid.",".$i.",2);' >".$wh."</a> <a href='#' onclick='download_tender(".$Tenderid.",".$i.",2);'  ><img src='".home_url()."/wp-content/plugins/tenders/WCP/DATA/uploads/".$file_img."''></a> <a href='#' onclick='download_tender(".$Tenderid.",".$i.",2);'  >".$fs."</a></div> ";
                $i++;
                if(strpos($wh, "EoI ") !== false){
                $temp["End_date"]="";
                $temp["Opening_date"]="";
            }


            }

                $html=$html."</div>";
         $temp['Description'] =$row->Tenderno."<br>".$row->Workdetails_eng."<br>".$html;

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

    // Get all tenders data 
    public static function get_corr(){

       
       
        
        $requestData = $_REQUEST;
    
        global $wpdb,$wp;
        $data = array();
 
    

        $sql = "SELECT tbl_corrigendum.CG_ID,tbl_tenders.Tenderid as id,tbl_tenders.Tenderno,tbl_tenders.Workdetails_eng,tbl_tenders.Start_date,tbl_tenders.End_date,tbl_tenders.Opening_date,tbl_tenders.Status FROM tbl_corrigendum  INNER JOIN tbl_tenders ON tbl_corrigendum.Tenderid = tbl_tenders.Tenderid ";
        $sql .= " WHERE (tbl_tenders.Tenderid= '" . esc_sql($_POST['id']) . "') ";
        if (isset($requestData['search']['value']) && $requestData['search']['value'] != '') {
            $sql .= "(tbl_tenders.Tenderno LIKE '%" . esc_sql($requestData['search']['value']) . "%') ";
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
            $sql .= " ORDER BY tbl_corrigendum.CG_ID DESC LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        $service_price_list = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
        $arr_data = Array();
        $arr_data = $result;

        foreach ($service_price_list as $row) { 

               foreach ( $row as  $key =>  $value) { 
                    $temp[$key]=$value;
                    }
             
            $temp['Start_date'] = date("d-M-Y",strtotime($row->Start_date));
            $temp['End_date'] = date("d-M-Y",strtotime($row->End_date));
            $temp['Opening_date'] = date("d-M-Y",strtotime($row->Opening_date));
           
            
            $id = $row->CG_ID;
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
    public static function Add_tenders_info(){
       
        $result_array['status'] = 0;
        
            global $wpdb;

          
        $file_size=array();
        $file_names=array();
          
             
            if(isset($_FILES["tender_document"]))
        {
            // Number of uploaded files
            $num_files = count($_FILES['tender_document']);

            /** loop through the array of files ***/
            for($i=0; $i < $num_files;$i++)
            {
                $validextensions = array("pdf", "zip", "rar");
                $temporary = explode(".", $_FILES["tender_document"]["name"][$i]);
                $file_extension = end($temporary);
                
                if($file_extension != ""){
                if (in_array($file_extension, $validextensions)) {
                    if ($_FILES["tender_document"]["error"][$i] > 0)
                    {
                         
                        $response['is_ok'] = "no";
                            $response['error'] = "Return Code: " . $_FILES["tender_document"]["error"][$i] . "<br/><br/>";
                    }
                    else
                    {
                        if (file_exists("/wp-content/plugins/tenders/WCP/DATA/upload/" . $_FILES["tender_document"]["name"][$i])) {
                             
                            $response['is_ok'] = "no";
                            $response['error'] = "already exists.";
                        }
                        else
                        {
                             
                             $file_name= $_FILES["tender_document"]['name'][$i];
                            if (@move_uploaded_file($_FILES['tender_document']['tmp_name'][$i],dirname(__FILE__).'/uploads/'. $file_name)) {
                                $response['is_ok'] = "yes";
                                $bytes=$_FILES["tender_document"]["size"][$i];
                                if ($bytes >= 1073741824)
                                {
                                    $bytes = number_format($bytes / 1073741824, 2) . ' GB';
                                }
                                elseif ($bytes >= 1048576)
                                {
                                    $bytes = number_format($bytes / 1048576, 2) . ' MB';
                                }
                                elseif ($bytes >= 1024)
                                {
                                    $bytes = number_format($bytes / 1024, 2) . ' KB';
                                }
                                elseif ($bytes > 1)
                                {
                                    $bytes = $bytes . ' bytes';
                                }
                                elseif ($bytes == 1)
                                {
                                    $bytes = $bytes . ' byte';
                                }
                                else
                                {
                                    $bytes = '0 bytes';
                                }
                                $file_size[]=$bytes;
                                $file_names[]=$file_name;
                                $response['file_name'] =$file_name;
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
            }
                if($response['is_ok'] == "no"){
                    echo json_encode($response);

                    wp_die();
                }
            }
        } 


    
                    $start_date = str_replace('-', '/', $_POST['start_date']);
                    $start_date = date('Y-m-d', strtotime($start_date));
                    $end_date = str_replace('-', '/', $_POST['end_date']);
                    $end_date = date('Y-m-d', strtotime($end_date));
                    $opening_date = str_replace('-', '/', $_POST['opening_date']);
                    $opening_date = date('Y-m-d', strtotime($opening_date));
                    $document_title=(isset($_POST['document_title']))?$_POST['document_title']:'';
            $data = array(  
                'Tenderno' => $_POST['tender_no'],
                'Inviting_Officer' => $_POST['inviting_officer'],
                'Start_date' => $start_date,
                'End_date' => $end_date,
                'Opening_date' => $opening_date,
                'TenderSpecifEng' => null,
                'TenderSpecifHnd' => null,
                'Workdetails_eng' => $_POST['tender_description'],
                'Workdetails_hindi' => implode("$",$document_title),
                'Location' => $_POST['location'],
                'Type' => $_POST['type'],
                'Status' => 1,
                'Tenderfile' => implode("$",$file_names),
                'CG_File' => null,
                'Extended_date' => null,
                'EndDate_Time' => $_POST['end_date_time'],
                'OpeningDate_Time' => $_POST['opening_date_time'],
                'file_size' => implode("$",$file_size)
            );
          
  
             
            $data_result = $wpdb->insert('tbl_tenders', $data);
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
    public static function add_corr_info(){
       
        $result_array['status'] = 0;
        
            global $wpdb;

          $bytes = '0 bytes';
   
          
             
            if(isset($_FILES["tender_document"]["type"]))
        {
            $validextensions = array("pdf", "zip", "rar");
            $temporary = explode(".", $_FILES["tender_document"]["name"]);
            $file_extension = end($temporary);
            if (in_array($file_extension, $validextensions)) {
                if ($_FILES["tender_document"]["error"] > 0)
                {
                     
                    $response['is_ok'] = "no";
                        $response['error'] = "Return Code: " . $_FILES["tender_document"]["error"] . "<br/><br/>";
                }
                else
                {
                    if (file_exists("/wp-content/plugins/tenders/WCP/DATA/upload/" . $_FILES["tender_document"]["name"])) {
                         
                        $response['is_ok'] = "no";
                        $response['error'] = "already exists.";
                    }
                    else
                    {
                         
                         $file_name= $_FILES["tender_document"]['name'];
                        if (@move_uploaded_file($_FILES['tender_document']['tmp_name'],dirname(__FILE__).'/uploads/'. $file_name)) {
                            $response['is_ok'] = "yes";
                            $response['file_name'] =$file_name;
                            $bytes=$_FILES["tender_document"]["size"];
                            if ($bytes >= 1073741824)
                            {
                                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
                            }
                            elseif ($bytes >= 1048576)
                            {
                                $bytes = number_format($bytes / 1048576, 2) . ' MB';
                            }
                            elseif ($bytes >= 1024)
                            {
                                $bytes = number_format($bytes / 1024, 2) . ' KB';
                            }
                            elseif ($bytes > 1)
                            {
                                $bytes = $bytes . ' bytes';
                            }
                            elseif ($bytes == 1)
                            {
                                $bytes = $bytes . ' byte';
                            }
                            else
                            {
                                $bytes = '0 bytes';
                            }
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

        //  $sql = "SELECT tbl_corrigendum.CG_ID FROM tbl_corrigendum   ";
        // $sql .= " WHERE (tbl_corrigendum.Tenderid= '" . esc_sql($_POST['Tenderid']) . "') ";
        

        // $check_data=$wpdb->get_results($wpdb->prepare($sql, Array()), OBJECT);

     

         $start_date = str_replace('-', '/', $_POST['start_date']);
                    $start_date = date('Y-m-d', strtotime($start_date));

                    $opening_date = str_replace('-', '/', $_POST['opening_date']);
                    $opening_date = date('Y-m-d', strtotime($opening_date));

                    $end_date = str_replace('-', '/', $_POST['end_date']);
                    $end_date = date('Y-m-d', strtotime($end_date));


              
            $data = array(  
                'CG_TenderNo' => $_POST['tender_no'],
                'Invitinfficer' => $_POST['inviting_officer'],
                'CG_NameEng' =>$_POST['corrigendum_title_english'],
                'CG_NameHnd' => $_POST['corrigendum_title_hindi'],
                'CG_StratDate' => $start_date,
                'CG_OpeningDate' => $opening_date,
                'CG_EndDate' => $end_date,
                'CG_Location' => $_POST['location'],
                'CG_Type' => $_POST['type'],
                'CG_File' => $_FILES["tender_document"]["name"],
                'Status' => 1,
                'Tenderid' => $_POST['Tenderid'],
                'file_size' => $bytes
            );
          
   
            $data_result = $wpdb->insert('tbl_corrigendum', $data);
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
    public static function Add_tender_download(){
       
        $result_array['status'] = 0;
        
            global $wpdb;
        $Tenderno="";
          
        if($_POST['type'] == 1){
            $sql = "SELECT * FROM tbl_tenders ";
             $sql .= " WHERE ( Tenderid = '" . esc_sql($_POST['tender_no']) . "') ";
            $data = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
         

            foreach ($data as $row) { 

            $Tenderno=$row->Tenderno;
               $wh = explode("$",$row->Workdetails_hindi);
                $tf = explode("$",$row->Tenderfile);
                $fs = explode("$",$row->file_size);
                
             
                    if($tf[$_POST['pos']] != ""){
       
                        $result_array['file_path'] =home_url()."/wp-content/plugins/tenders/WCP/DATA/uploads/".$tf[$_POST['pos']];

                    $result_array['file_name'] =$wh[$_POST['pos']];
                }
            }
        }else{
            $sql = "SELECT * FROM tbl_corrigendum ";
             $sql .= " WHERE ( Tenderid = '" . esc_sql($_POST['tender_no']) . "') ";
            $data = $wpdb->get_results($wpdb->prepare($sql, Array()), "OBJECT");
     
            $i=0;
            foreach ($data as $row) { 
                if($i == $_POST['pos']){

                    $wh =  $row->CG_NameEng;
                    $tf = $row->CG_File;
                    $fs = $row->file_size;
                   
                
                  $result_array['file_path'] =home_url()."/wp-content/plugins/tenders/WCP/DATA/uploads/".$tf;

                        $result_array['file_name'] =$wh;

                   
                }
                $i++;
          
            }
        
        }
       
       
 




              
            $data = array(  
                'Name' => $_POST['name'],
                'Org_name' =>$_POST['organisation_name'],
                'MobileNo' => $_POST['phone_number'],
                'EmailId' => $_POST['email'],
                'Tenderno' => $Tenderno,
                'Date' => date("Y-m-d")
            );
          
  
             
            $data_result = $wpdb->insert('tbl_tendordownload', $data);
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
    public static function update_corr_info(){
       
        $result_array['status'] = 0;
        
            global $wpdb;

           $start_date = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['start_date'])));
        $end_date = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['end_date'])));
        $opening_date = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['opening_date'])));
              
            $data = array(  
                'CG_NameEng' =>$_POST['corrigendum_title_english'],
                'CG_NameHnd' => $_POST['corrigendum_title_hindi'],
                'CG_StratDate' => $start_date,
                'CG_OpeningDate' => $opening_date,
                'CG_EndDate' => $end_date
            );
 
          
             
            if(isset($_FILES["tender_document"]["type"]) && $_FILES["tender_document"]["size"] >0)
        {
            $validextensions = array("pdf", "zip", "rar");
            $temporary = explode(".", $_FILES["tender_document"]["name"]);
            $file_extension = end($temporary);
            if (in_array($file_extension, $validextensions)) {
                if ($_FILES["tender_document"]["error"] > 0)
                {
                     
                    $response['is_ok'] = "no";
                        $response['error'] = "Return Code: " . $_FILES["tender_document"]["error"] . "<br/><br/>";
                }
                else
                {
                    if (file_exists("/wp-content/plugins/tenders/WCP/DATA/upload/" . $_FILES["tender_document"]["name"])) {
                         
                        $response['is_ok'] = "no";
                        $response['error'] = "already exists.";
                    }
                    else
                    {
                         
                         $file_name= $_FILES["tender_document"]['name'];
                        if (@move_uploaded_file($_FILES['tender_document']['tmp_name'],dirname(__FILE__).'/uploads/'. $file_name)) {
                            $response['is_ok'] = "yes";
                            $response['file_name'] =$file_name;
                            $data["CG_File"]= $_FILES["tender_document"]["name"];
                            $bytes=$_FILES["tender_document"]["size"];
                            if ($bytes >= 1073741824)
                            {
                                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
                            }
                            elseif ($bytes >= 1048576)
                            {
                                $bytes = number_format($bytes / 1048576, 2) . ' MB';
                            }
                            elseif ($bytes >= 1024)
                            {
                                $bytes = number_format($bytes / 1024, 2) . ' KB';
                            }
                            elseif ($bytes > 1)
                            {
                                $bytes = $bytes . ' bytes';
                            }
                            elseif ($bytes == 1)
                            {
                                $bytes = $bytes . ' byte';
                            }
                            else
                            {
                                $bytes = '0 bytes';
                            }
                            $data["file_size"]=$bytes;
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


             $data_result = $wpdb->update('tbl_corrigendum', $data, array('CG_ID' => $_POST['CG_ID']));
           
           
                $result_array['status'] = 1;
                $result_array['msg'] = 'Record Update Succefully.';
          
   
        
        
        echo json_encode($result_array);
        exit;
    }

     // add trade
    public static function import_tenders_info(){
       
        $result_array['status'] = 0;
        $result_array['type'] =$_FILES["tender_document"]["type"];
        
            global $wpdb;
 
            include_once(dirname(__FILE__) . "/library/php-excel-reader/excel_reader2.php");
            include_once(dirname(__FILE__) . "/library/SpreadsheetReader.php");
             $mimes = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.oasis.opendocument.spreadsheet','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
              if(in_array($_FILES["tender_document"]["type"],$mimes)){


                $uploadFilePath =dirname(__FILE__).'/uploads/'.basename($_FILES['tender_document']['name']);
                move_uploaded_file($_FILES['tender_document']['tmp_name'], $uploadFilePath);
 
 
                $Reader = new SpreadsheetReader($uploadFilePath);

 
                $totalSheet = count($Reader->sheets());

 


                /* For Loop for all sheets */
                for($i=0;$i<$totalSheet;$i++){


                  $Reader->ChangeSheet($i);


                  foreach ($Reader as $Row)
                  {
                    
                    $Tenderno = isset($Row[0]) ? $Row[0] : '';
                        if(isset($Tenderno) && $Tenderno !="" && $Tenderno !="Tender No"){
                    $Inviting_Officer = isset($Row[1]) ? $Row[1] : '';
                    $Workdetails_eng = isset($Row[2]) ? $Row[2] : '';
                    $start_date = isset($Row[3]) ? $Row[3] : '';

                    $end_date = isset($Row[4]) ? $Row[4] : '';
                    $EndDate_Time = isset($Row[5]) ? $Row[5] : '';
                    $opening_date = isset($Row[6]) ? $Row[6] : '';
                    $OpeningDate_Time = isset($Row[7]) ? $Row[7] : '';

                    $Location = isset($Row[8]) ? $Row[8] : '';
                    $Type = isset($Row[9]) ? $Row[9] : '';
                    $Tenderfile = isset($Row[10]) ? $Row[10] : '';
                 
                    $start_date = str_replace('-', '/', $start_date);
                   $start_date = date('Y-m-d', strtotime($start_date));
                    $end_date = str_replace('-', '/', $end_date);
                   $end_date = date('Y-m-d', strtotime($end_date));
                    $opening_date = str_replace('-', '/', $opening_date);
                   $opening_date = date('Y-m-d', strtotime($opening_date));
                  
                        $data = array(  
                            'Tenderno' => $Tenderno,
                            'Inviting_Officer' => $Inviting_Officer,
                            'Start_date' => $start_date,
                            'End_date' => $end_date,
                            'Opening_date' => $opening_date,
                            'TenderSpecifEng' => null,
                            'TenderSpecifHnd' => null,
                            'Workdetails_eng' => $Workdetails_eng,
                            'Workdetails_hindi' => null,
                            'Location' => $Location,
                            'Type' => $Type,
                            'Status' => 1,
                            'Tenderfile' =>$Tenderfile,
                            'CG_File' => null,
                            'Extended_date' => null,
                            'EndDate_Time' => $EndDate_Time,
                            'OpeningDate_Time' => $OpeningDate_Time,
                            'file_size' => null
                        );
           
  
             
                             $data_result = $wpdb->insert('tbl_tenders', $data);
                         }


               
                   }


                }

                  
            $lastid = $wpdb->insert_id;
            if ($lastid) {
                $result_array['status'] = 1;
                $result_array['msg'] = 'Record Add Succefully.';
            } else {
                $result_array['msg'] = 'Please try again.';
            }
        
               

              }else { 
                 $result_array['msg'] = 'Sorry, File type is not allowed. Only Excel file.';
           
              }
        
        echo json_encode($result_array);
        exit;
    }

 

    // deletes tenders
    public static function delete_tenders(){
      
        $result_array = array();
        $result_array['status'] = 0;
        if (!empty($_POST)){
            global $wpdb;
            // user id is really id
            $id = ($_POST['id']);

            $sql = "select * FROM tbl_tenders WHERE Tenderid= %d";
            $result=$wpdb->get_results($wpdb->prepare($sql, Array($id)), "ARRAY_A");
            if (count($result) > 0) {
                $tenders_data = $result[0];
                 $sql = "select * FROM tbl_corrigendum WHERE CG_ID= %d";
                $result=$wpdb->get_results($wpdb->prepare($sql, Array($id)), "ARRAY_A");
                if (count($result) > 0) {
                    $CG_File = $result[0]['CG_File'];
                    if (file_exists(dirname(__FILE__).'/uploads/'.$CG_File)) {
                        unlink(dirname(__FILE__).'/uploads/'.$CG_File);
                    } 
                    $sql = "DELETE FROM tbl_corrigendum WHERE Tenderid= %d";
                    $result=$wpdb->get_results($wpdb->prepare($sql, Array($id)), OBJECT);
     
                }


                $sql = "DELETE FROM tbl_tenders WHERE Tenderid= %d";
                $result=$wpdb->get_results($wpdb->prepare($sql, Array($id)), OBJECT);

                $result_array['status'] = 1;
               
            }else{
                $result_array['status'] = 0;
                $result_array['msg'] = 'Data Not Founds.';
            }
           
            
        }
        echo json_encode($result_array);exit;
    }
    // deletes tenders
    public static function delete_corr(){
      
        $result_array = array();
        $result_array['status'] = 0;
        if (!empty($_POST)){
            global $wpdb;
            // user id is really id
            $id = ($_POST['id']);

            $sql = "select * FROM tbl_corrigendum WHERE CG_ID= %d";
            $result=$wpdb->get_results($wpdb->prepare($sql, Array($id)), "ARRAY_A");
            if (count($result) > 0) {
                $CG_File = $result[0]['CG_File'];
                if (file_exists(dirname(__FILE__).'/uploads/'.$CG_File)) {
                    unlink(dirname(__FILE__).'/uploads/'.$CG_File);
                } 
                $sql = "DELETE FROM tbl_corrigendum WHERE CG_ID= %d";
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

            
          $sql = "select * FROM tbl_tenders WHERE Tenderid= %d";
            $user_details = $wpdb->get_results($wpdb->prepare($sql, Array($id)), "ARRAY_A");
      
            $result['status'] = 1;
            $result['user_details'] = $user_details;
          
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

            $sql = "select * FROM tbl_tenders WHERE Tenderid = %d";
            $results = $wpdb->get_results($wpdb->prepare($sql, Array($id)), "ARRAY_A");
          if (count($results) > 0) {
                $tenders_data = $results[0];
                 
                $result['results'] =$results;
                $wh = explode("$",$results[0]['Workdetails_hindi']);
                $tf = explode("$",$results[0]['Tenderfile']);
                $fs = explode("$",$results[0]['file_size']);
                 $result['wh'] =$wh;
                  $result['tf'] =$tf;
                   $result['fs'] =$fs;
                $array_index =  array_search($_POST['file_name'],$tf);
                 $result['array_index']=$array_index;
               
                unset($wh[$array_index]);
                unset($tf[$array_index]);
                unset($fs[$array_index]);
                
                   $result['whs'] =$wh;
                  $result['tfs'] =$tf;
                   $result['fss'] =$fs;
                $data=array();

                $data['Workdetails_hindi'] = implode("$",$wh);
           
                $data['Tenderfile'] =  implode("$",$tf);
                $data['file_size'] =  implode("$",$fs);
                $data_result = $wpdb->update('tbl_tenders', $data, array('Tenderid' => $id));
                $result['status'] = 1;
            
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

            $sql = "SELECT * FROM tbl_corrigendum  ";
        $sql .= " WHERE ( CG_ID= '" . esc_sql($_POST['id']) . "') ";
      
            $user_details = $wpdb->get_results($wpdb->prepare($sql, Array()), "ARRAY_A");
      
            $result['status'] = 1; 
            $result['user_details'] = $user_details;
          
        }
        echo json_encode($result);
        exit;
    }

    

    // update tenders
    // needs to be fixed
    public static function Update_tenders_info() {
        $result_array['status'] = 0;
        
            global $wpdb;

          
 
          $file_size=array();
          $file_names=array();
             
        if(isset($_FILES["tender_document"]))
        {
            // Number of uploaded files
            $num_files = count($_FILES['tender_document']);
             /** loop through the array of files ***/
            for($i=0; $i < $num_files;$i++)
            {

                $validextensions = array("pdf", "zip", "rar");
                if($_FILES["tender_document"]["size"][$i] > 1){
                $temporary = explode(".", $_FILES["tender_document"]["name"][$i]);
                    $file_extension = end($temporary);
                    if (in_array($file_extension, $validextensions)) {
                        if ($_FILES["tender_document"]["error"][$i] > 0)
                        {
                             
                            $response['is_ok'] = "no";
                                $response['error'] = "Return Code: " . $_FILES["tender_document"]["error"][$i] . "<br/><br/>";
                        }
                        else
                        {
                            if (file_exists("/wp-content/plugins/tenders/WCP/DATA/uploads/" . $_FILES["tender_document"]["name"][$i])) {
                                 
                                $response['is_ok'] = "no";
                                $response['error'] = "already exists.";
                            }
                            else
                            {
                                 
                                 $file_name= $_FILES["tender_document"]['name'][$i];
                                if (@move_uploaded_file($_FILES['tender_document']['tmp_name'][$i],dirname(__FILE__).'/uploads/'. $file_name)) {
                                    $response['is_ok'] = "yes";
                                     $bytes=$_FILES["tender_document"]["size"][$i];
                                    if ($bytes >= 1073741824)
                                    {
                                        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
                                    }
                                    elseif ($bytes >= 1048576)
                                    {
                                        $bytes = number_format($bytes / 1048576, 2) . ' MB';
                                    }
                                    elseif ($bytes >= 1024)
                                    {
                                        $bytes = number_format($bytes / 1024, 2) . ' KB';
                                    }
                                    elseif ($bytes > 1)
                                    {
                                        $bytes = $bytes . ' bytes';
                                    }
                                    elseif ($bytes == 1)
                                    {
                                        $bytes = $bytes . ' byte';
                                    }
                                    else
                                    {
                                        $bytes = '0 bytes';
                                    }
                                    $file_size[]=$bytes;
                                    $file_names[]=$file_name;
                                    $response['file_name'] =$file_name;
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
                }
            }
        } 


     
                    $start_date = str_replace('-', '/', $_POST['start_date']);
                    $start_date = date('Y-m-d', strtotime($start_date));

                    $opening_date = str_replace('-', '/', $_POST['opening_date']);
                    $opening_date = date('Y-m-d', strtotime($opening_date));

                    $end_date = str_replace('-', '/', $_POST['end_date']);
                    $end_date = date('Y-m-d', strtotime($end_date));



              
            $data = array(  
                'Tenderno' => $_POST['tender_no'],
                'Inviting_Officer' => $_POST['inviting_officer'],
                'Start_date' => $start_date,
                'End_date' => $end_date,
                'Opening_date' => $opening_date,
                'TenderSpecifEng' => null,
                'TenderSpecifHnd' => null,
                'Workdetails_eng' => $_POST['tender_description'],
                
                'Location' => $_POST['location'],
                'Type' => $_POST['type'],
                'Status' => 1,
                'CG_File' => null,
                'Extended_date' => null,
                'EndDate_Time' => $_POST['end_date_time'],
                'OpeningDate_Time' => $_POST['opening_date_time']
            );
           
            if(isset($_FILES["tender_document"]["tmp_name"])){

              

                if(count($_POST['document_title']) > 0){
                    $data['Workdetails_hindi'] = $_POST['wh'].'$'.implode("$",$_POST['document_title']);
                }else{
                    $data['Workdetails_hindi'] = $_POST['wh'];
                }

          
                if(count($file_names) > 0){
                    $data['Tenderfile'] = $_POST['tf'].'$'.implode("$",$file_names);
                }else{
                    $data['Tenderfile'] = $_POST['tf'];
                }

                if(count($file_size) > 0){
                    $data['file_size'] = $_POST['fs'].'$'.implode("$",$file_size);
                }else{
                    $data['file_size'] = $_POST['fs'];
                }
            }
          
  
            $data_result = $wpdb->update('tbl_tenders', $data, array('Tenderid' => $_POST['Tenderid']));
           
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

 
  
  
}


$wcp_tenders_controller = new WCP_Tenders_Controller();

/// Shortcodes

add_action('admin_menu', array($wcp_tenders_controller, 'wcp_tenant_screen'));
add_shortcode('wcp_active_tenders', array($wcp_tenders_controller, 'view_front_active_tenders'));  
add_shortcode('wcp_archived_tenders', array($wcp_tenders_controller, 'view_front_archived_tenders'));  
/// Ajax
 

add_action('wp_ajax_tender_get_tenders', Array('WCP_Tenders_Controller', 'get_tenders'));
add_action('wp_ajax_nopriv_tender_get_tenders', array('WCP_Tenders_Controller', 'get_tenders')); 

add_action('wp_ajax_tender_delete_tenders', Array('WCP_Tenders_Controller', 'delete_tenders'));
add_action('wp_ajax_nopriv_tender_delete_tenders', array('WCP_Tenders_Controller', 'delete_tenders'));

add_action('wp_ajax_tender_get_detail_by_id', Array('WCP_Tenders_Controller', 'get_detail_by_id'));
add_action('wp_ajax_nopriv_tender_get_detail_by_id', array('WCP_Tenders_Controller', 'get_detail_by_id'));

add_action('wp_ajax_tender_Add_tenders_info', Array('WCP_Tenders_Controller', 'Add_tenders_info'));
add_action('wp_ajax_nopriv_tender_Add_tenders_info', array('WCP_Tenders_Controller', 'Add_tenders_info'));

add_action('wp_ajax_tender_import_tenders_info', Array('WCP_Tenders_Controller', 'import_tenders_info'));
add_action('wp_ajax_nopriv_tender_import_tenders_info', array('WCP_Tenders_Controller', 'import_tenders_info'));

add_action('wp_ajax_tender_Update_tenders_info', Array('WCP_Tenders_Controller', 'Update_tenders_info'));
add_action('wp_ajax_nopriv_tender_Update_tenders_info', array('WCP_Tenders_Controller', 'Update_tenders_info'));
 
 
add_action('wp_ajax_tender_get_corr', Array('WCP_Tenders_Controller', 'get_corr'));
add_action('wp_ajax_nopriv_tender_get_corr', array('WCP_Tenders_Controller', 'get_corr')); 

add_action('wp_ajax_tender_add_corr_info', Array('WCP_Tenders_Controller', 'add_corr_info'));
add_action('wp_ajax_nopriv_tender_add_corr_info', array('WCP_Tenders_Controller', 'add_corr_info')); 
 
add_action('wp_ajax_tender_delete_corr', Array('WCP_Tenders_Controller', 'delete_corr'));
add_action('wp_ajax_nopriv_tender_delete_corr', array('WCP_Tenders_Controller', 'delete_corr')); 

add_action('wp_ajax_tender_get_detail_corr_by_id', Array('WCP_Tenders_Controller', 'get_detail_corr_by_id'));
add_action('wp_ajax_nopriv_tender_get_detail_corr_by_id', array('WCP_Tenders_Controller', 'get_detail_corr_by_id')); 

add_action('wp_ajax_tender_update_corr_info', Array('WCP_Tenders_Controller', 'update_corr_info'));
add_action('wp_ajax_nopriv_tender_update_corr_info', array('WCP_Tenders_Controller', 'update_corr_info'));


add_action('wp_ajax_tender_delete_file', Array('WCP_Tenders_Controller', 'delete_file'));
add_action('wp_ajax_nopriv_tender_delete_file', array('WCP_Tenders_Controller', 'delete_file')); 
 
 
add_action('wp_ajax_tender_get_frontend', Array('WCP_Tenders_Controller', 'tender_get_frontend'));
add_action('wp_ajax_nopriv_tender_get_frontend', array('WCP_Tenders_Controller', 'tender_get_frontend')); 

add_action('wp_ajax_tender_get_old_data_frontend', Array('WCP_Tenders_Controller', 'tender_get_old_data_frontend'));
add_action('wp_ajax_nopriv_tender_get_old_data_frontend', array('WCP_Tenders_Controller', 'tender_get_old_data_frontend')); 
 

add_action('wp_ajax_Add_tender_download', Array('WCP_Tenders_Controller', 'Add_tender_download'));
add_action('wp_ajax_nopriv_Add_tender_download', array('WCP_Tenders_Controller', 'Add_tender_download')); 
 
add_action('wp_ajax_tender_get_download_tenders', Array('WCP_Tenders_Controller', 'tender_get_download_tenders'));
add_action('wp_ajax_nopriv_tender_get_download_tenders', array('WCP_Tenders_Controller', 'tender_get_download_tenders'));