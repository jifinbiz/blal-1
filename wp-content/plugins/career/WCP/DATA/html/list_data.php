  <link rel="stylesheet" href="<?php echo WP_PLUGIN_URL;?>/career/WCP/DATA/css/bootstrap.min.css">
  <script src="<?php echo WP_PLUGIN_URL;?>/career/WCP/DATA/js/jquery.min.js"></script>
  <script src="<?php echo WP_PLUGIN_URL;?>/career/WCP/DATA/js/bootstrap.min.js"></script>
 <link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL;?>/career/WCP/DATA/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL;?>/career/WCP/DATA/js/datatables.min.js"></script>
 <script src="<?php echo WP_PLUGIN_URL;?>/career/WCP/DATA/js/jquery.validate.min.js" ></script>
<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL;?>/career/WCP/DATA/css/jquery-ui.css">
 
 
  <script src="<?php echo WP_PLUGIN_URL;?>/career/WCP/DATA/js/jqueryui.js"></script>



<style type="text/css">
  .f-r{
    float: right;
  }
  .f-l{
    float: left;
  }
  .t-c{
    text-align: center;
  }
 .error{
        color:red;
    }
input[type=number],.wp-admin select
{
  height:34px;
}
.odd img ,.even img{
      width: 30px;
}
.submit-btn{
        width: 1px;
    padding: 0;
    height: 0;
    background: #fff;
    border: 0;
}
</style>
 
<div class="flex_container career_details" style="padding-top:20px;">
    <div class="col-sm-12">
        <div class="col-sm-12">
            <h3>ALL RECRUITMENTS</h3>
        </div>
        <div class="col-sm-12"  style="margin:0 0 20px 0; ">
            <input type="button" value="Add Job" name="btn_add_user" id="btn_add_user" class="btn btn-info btn-sm" onclick="add_career()"  />
           
        </div>

        <hr style="background-color:#000000; height:2px; width: 100%;">
        <div style="padding-bottom:10px;">
            <div style="clear:both;"></div>
        </div>
        <div class="table-responsive">
            <table id='service-table' class="table table-bordered">
                <thead>
                    <tr>
                        <!-- <th class="all">Client Name</th> -->
                        <th class="all">Type</th>
                        <th class="all">Advertisement No.</th>
                        <th class="all">Date</th>
                        <th class="all">Link</th>
                        <th class="all">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="flex_container corr_details" style="padding-top:20px;">
    <div class="col-sm-12">
        <div class="col-sm-12">
            <h3>RECRUITMENTS CORRIGENDUM</h3>
        </div>
        <div class="col-sm-12"  style="margin:0 0 20px 0; ">
            <input type="button" value="Add Corrigendum" name="btn_add_corr" id="btn_add_corr" class="btn btn-info btn-sm" onclick="add_corr()"  />
            <input type="button" value="Back Career" name="btn_back_corr" id="btn_back_corr" class="btn btn-info btn-sm" onclick="career_back()"  />
          
        </div>

        <hr style="background-color:#000000; height:2px; width: 100%;">
        <div style="padding-bottom:10px;">
            <div style="clear:both;"></div>
        </div>
        <div class="table-responsive">
            <table id='corr-table' class="table table-bordered">
                <thead>
                    <tr>
                        <!-- <th class="all">Client Name</th> -->
                        <th class="all">Type</th> 
                        <th class="all">Date</th>
                        <th class="all">Link</th>
                        <th class="all">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!--  Model Popup -->

<div class="modal fade" id="AddUserModal" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
        
        <h4 class="modal-title f-l"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
            <div class="modal-body">
                    
        </div>
        <div class="modal-footer">
            <input type="submit" name="btnAddUser" id="btnAddUser" class="btn btn-info" value="Save" />
                    <button type="cancel" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
    </div>
</div>
<!-- Close Model Popup -->
<script>
    jQuery(".corr_details").hide();
    $(document).ready(function () {
      
        $ = jQuery;
        reload_table();
        var error_count=0;
        
    });
    
    function reload_table() {
       
        $('#service-table').dataTable({
                    "paging": true,
                    "pageLength": 10,
                    "bProcessing": true,
                    "serverSide": true,
                     "bDestroy": true,
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {"action": "get_career"}

                    },
                    "aoColumns": [
                        {mData: 'Job_Heading_English'},
                        {mData: 'Advertisement_No'},
                        {mData: 'OpeningDate'},
                        {mData: 'link'}, 
                        {mData: 'action'}
                    ],
                    "order": [[ 0, "desc" ]],        

                    "columnDefs": [{
                        "targets": [2],
                        "orderable": false
                    }],
                    "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                    
                  }
            });  
    }

    function career_back() {
        jQuery(".career_details").show();
        jQuery(".corr_details").hide();
         reload_table();

    }
    function career_corr(id) {
        jQuery(".career_details").hide();
        jQuery(".corr_details").show();
        jQuery("#btn_add_corr").attr("data-id",id);
        $('#corr-table').dataTable({
                    "paging": true,
                    "pageLength": 10,
                    "bProcessing": true,
                    "serverSide": true,
                     "bDestroy": true,
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {"action": "get_corr","id":id}

                    },
                    "aoColumns": [
                      
                         {mData: 'Job_Heading_English'},
                        {mData: 'OpeningDate'},
                        {mData: 'links'}, 
                        {mData: 'action'}
                    ],
                    "order": [[ 0, "desc" ]],        

                    "columnDefs": [{
                        "targets": [3],
                        "orderable": false
                    }],
                    "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                    
                  }
            });  
    }

    function career_delete(id) {
      if (confirm("Are you sure?")) {
          
       
          $.ajax({
              type: 'POST',
              url: '<?php echo admin_url('admin-ajax.php'); ?>',
              data: {"action": "delete_career", id: id},
              success: function (data) {
                  var result = JSON.parse(data)
                  if (result.status == 1) {
                  
                      reload_table();
                  }else{
                     alert(result.msg);
                      reload_table();
                     
                  }
              }
          });
      }
       return false;
    }
    function corr_delete(id) {
        if (confirm("Are you sure?")) {
          
       
          $.ajax({
              type: 'POST',
              url: '<?php echo admin_url('admin-ajax.php'); ?>',
              data: {"action": "delete_corr", id: id},
              success: function (data) {
                  var result = JSON.parse(data)
                  if (result.status == 1) {
                         var  id=jQuery("#btn_add_corr").attr("data-id");
                      career_corr(id);
                  }else{
                     alert(result.msg);
                      var  id=jQuery("#btn_add_corr").attr("data-id");
                      career_corr(id);
                     
                  }
              }
          });
      }
       return false;
    }
    function career_update(id) {
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "get_detail_by_id", id: id},
            success: function (data) {
               
                var result = JSON.parse(data);
                if (result.status == 1) {
                     for (var i = result['user_details'].length - 1; i >= 0; i--) {

                             var  data=result['user_details'][i] ;

                             var Curentjobid =data['Curentjobid'];
                             var job_titel_english =data['Job_Heading_English'];
                             var job_titel_hindi =data['Job_Heading_hindi'];
                             var start_date =data['OpeningDate'];
                             var end_date =data['CloseingDate'];
                             var advertiement_no =data['Advertisement_No'];
                 
                      
                       
                             
                    $(".modal-title").html("Edit Career Details");
                            $(".modal-body").html('        <form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data"style="display: inline-block;">      <input type="hidden" name="Curentjobid" value="'+Curentjobid+'">           <input type="hidden" id="action" name="action" value="Update_career_info">                   <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                  <label   class="col-form-label">Date:<em style="color:#f00">*</em></label>                        <input type="text" name="start_date" class="form-control date" value="'+start_date+'" id="start_date" required>                 </div>            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                 <label   class="col-form-label">Closing Date:<em style="color:#f00">*</em></label>                      <input type="text" name="end_date" class="form-control date" value="'+end_date+'"  id="end_date" required>                </div>                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                    <label  class="col-form-label">Job Title English:</label>                              <input  class="form-control" type="text" id="job_titel_english" value="'+job_titel_english+'" name="job_titel_english" required>           </div>           <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                   <label  class="col-form-label">Job Title Hindi:</label>                              <input  class="form-control" type="text" id="job_titel_hindi" value="'+job_titel_hindi+'" name="job_titel_hindi" >           </div>           <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                    <label  class="col-form-label">Advertisement No.:</label>                              <input  class="form-control" type="text" id="advertiement_no" value="'+advertiement_no+'" name="advertiement_no" required>           </div>             <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                    <a href="#" onClick="openweb();">Web Link</a>            <a href="#" onClick="openpdf();">PDF</a>          </div>             <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 openweb">                                    <label  class="col-form-label">Web Link:</label>                              <input  class="form-control" id="document_title" name="weblink">           </div>                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 openpdf">                     <label  class="col-form-label">Upload File:<em style="color:#f00">*</em></label>                                    <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document" name="tender_document" >                     </div>            <input type="submit" class=" submit-btn" value="submit">  </form>');
      
                    
                                  $(".openweb").hide();
                                        $(".openpdf").hide();
                                $('#AddUserModal').modal('show');
                                 

                    
                     
                        $(".date").datepicker();
      

                    
                    }
                    
                }
            }
        });
       
    }
    function add_career(){
         
        $(".modal-title").html("Add Career Details");
        $(".modal-body").html('<form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data"style="display: inline-block;">               <input type="hidden" id="action" name="action" value="Add_career_info">                   <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                  <label   class="col-form-label">Date:<em style="color:#f00">*</em></label>                        <input type="text" name="start_date" class="form-control date"  id="start_date" required>                 </div>            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                 <label   class="col-form-label">Closing Date:<em style="color:#f00">*</em></label>                      <input type="text" name="end_date" class="form-control date"  id="end_date" required>                </div>                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                    <label  class="col-form-label">Job Title English:<em style="color:#f00">*</em></label>                              <input  class="form-control" type="text" id="job_titel_english" name="job_titel_english" required>           </div>           <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                   <label  class="col-form-label">Job Title Hindi:</label>                              <input  class="form-control" type="text" id="job_titel_hindi" name="job_titel_hindi" >           </div>           <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                    <label  class="col-form-label">Advertisement No.:<em style="color:#f00">*</em></label>                              <input  class="form-control" type="text" id="advertiement_no" name="advertiement_no" required>           </div>             <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                    <a href="#" onClick="openweb();">Web Link</a>            <a href="#" onClick="openpdf();">PDF</a>          </div>             <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 openweb">                                    <label  class="col-form-label">Web Link:</label>                              <input  class="form-control" id="document_title" name="weblink">           </div>                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 openpdf">                     <label  class="col-form-label">Upload File:<em style="color:#f00">*</em></label>                                    <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document" name="tender_document" >                     </div>            <input type="submit" class=" submit-btn" value="submit">  </form>');
      $(".openweb").hide();
        $(".openpdf").hide();
            $('#AddUserModal').modal('show');
            
        $(".date").datepicker();
       
      
      
    }

    function openweb(){
         
        $(".openweb").show();
        $(".openpdf").hide();
    }
  
 
    function openpdf(){
         
       $(".openweb").hide();
        $(".openpdf").show();
    }

    function add_corr(){
         var  id=jQuery("#btn_add_corr").attr("data-id");
        
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "get_detail_by_id", id: id},
            success: function (data) {
               
                var result = JSON.parse(data);
                if (result.status == 1) {
                     for (var i = result['user_details'].length - 1; i >= 0; i--) {

                             var  data=result['user_details'][i] ;

                             var Curentjobid  =data['Curentjobid'];
                             var Job_Heading_English =data['Job_Heading_English'];
                             var Job_Heading_hindi =data['Job_Heading_hindi'];
                             var OpeningDate =data['OpeningDate'];
                             var CloseingDate =data['CloseingDate'];
                           

                             
                    $(".modal-title").html("Edit Corrigendum Detail");
                    $(".modal-body").html('<form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data"style="display: inline-block;">              <input type="hidden" name="Curentjobid" value="'+Curentjobid+'">     <input type="hidden" id="action" name="action" value="add_corr_info">                       <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                  <label   class="col-form-label">Date:<em style="color:#f00">*</em></label>                        <input type="text" name="start_date" class="form-control date"  value="'+OpeningDate+'" id="start_date" required>                 </div>            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                 <label   class="col-form-label">Closing Date:<em style="color:#f00">*</em></label>                      <input type="text" name="end_date" class="form-control date"  value="'+CloseingDate+'" id="end_date" required>                </div>                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                    <label  class="col-form-label">Job Title English:<em style="color:#f00">*</em></label>                              <input  class="form-control" type="text" id="job_titel_english" value="'+Job_Heading_English+'" name="job_titel_english" required>           </div>           <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                   <label  class="col-form-label">Job Title Hindi:</label>                              <input  class="form-control" type="text" value="'+Job_Heading_hindi+'" id="job_titel_hindi" name="job_titel_hindi" >           </div>                     <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                    <a href="#" onClick="openweb();">Web Link</a>            <a href="#" onClick="openpdf();">PDF</a>          </div>             <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 openweb">                                    <label  class="col-form-label">Web Link:</label>                              <input  class="form-control" id="document_title" name="weblink">           </div>                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 openpdf">                     <label  class="col-form-label">Upload File:<em style="color:#f00">*</em></label>                                    <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document" name="tender_document" >                     </div>            <input type="submit" class=" submit-btn" value="submit">  </form>');
                    $(".openweb").hide();
        $(".openpdf").hide();
                                $('#AddUserModal').modal('show');
                                 

                       
                     
                        $(".date").datepicker();
      

                       
                    }
                    
                }
            }
        });
      
    }

    function corr_update(id){
            
            
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "get_detail_corr_by_id", id: id},
            success: function (data) {
               
                var result = JSON.parse(data);
                if (result.status == 1) {
                     for (var i = result['user_details'].length - 1; i >= 0; i--) {

                             var  data=result['user_details'][i] ;

                             var CorrgendumID   =data['CorrgendumID'];
                             var Job_Heading_English =data['Job_Heading_English'];
                             var Job_Heading_hindi =data['Job_Heading_hindi'];
                             var OpeningDate =data['OpeningDate'];
                             var CloseingDate =data['CloseingDate'];
                        
                         

                             
                    $(".modal-title").html("Edit Corrigendum Detail");
                    $(".modal-body").html('<form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data"style="display: inline-block;">              <input type="hidden" name="CorrgendumID" value="'+CorrgendumID+'">     <input type="hidden" id="action" name="action" value="update_corr_info">                       <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                  <label   class="col-form-label">Date:<em style="color:#f00">*</em></label>                        <input type="text" name="start_date" class="form-control date"  value="'+OpeningDate+'" id="start_date" required>                 </div>            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                 <label   class="col-form-label">Closing Date:<em style="color:#f00">*</em></label>                      <input type="text" name="end_date" class="form-control date"  value="'+CloseingDate+'" id="end_date" required>                </div>                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                    <label  class="col-form-label">Job Title English:<em style="color:#f00">*</em></label>                              <input  class="form-control" type="text" id="job_titel_english" value="'+Job_Heading_English+'" name="job_titel_english" required>           </div>           <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                   <label  class="col-form-label">Job Title Hindi:</label>                              <input  class="form-control" type="text" value="'+Job_Heading_hindi+'" id="job_titel_hindi" name="job_titel_hindi">           </div>                     <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                    <a href="#" onClick="openweb();">Web Link</a>            <a href="#" onClick="openpdf();">PDF</a>          </div>             <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 openweb">                                    <label  class="col-form-label">Web Link:</label>                              <input  class="form-control" id="document_title" name="weblink">           </div>                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 openpdf">                     <label  class="col-form-label">Upload File:<em style="color:#f00">*</em></label>                                    <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document" name="tender_document" >                     </div>            <input type="submit" class=" submit-btn" value="submit">  </form>');
                    $(".openweb").hide();
        $(".openpdf").hide();
                                $('#AddUserModal').modal('show');
                                 
 
                     
                        $(".date").datepicker();
      

                       
                    }
                    
                }
            }
        });
      
    }
    function insert_data() {   

        if($( "#UserUpdateform" ).valid()){
            var file_data = $('#tender_document')[0].files[0];  
            var data=jQuery('#UserUpdateform').serialize() 
            var form_data = new FormData(document.getElementById('UserUpdateform'));                



              $.ajax({
                  type: 'POST',
                  url: '<?php echo admin_url('admin-ajax.php'); ?>',
                  data: form_data,
                   cache: false,
                    contentType: false,
                    processData: false, 
                  success: function (data) {
                      var result = JSON.parse(data);
                      if (result.status == 1) {
                       
                          jQuery("#AddUserModal").modal('hide');
                          jQuery('#UserUpdateform')[0].reset();
                          alert(result.msg);
                          reload_table();
                          if(jQuery(".corr_details").is(":visible")){
                               var  id=jQuery("#btn_add_corr").attr("data-id");
                              career_corr(id);
                          }
                      }else{
                          alert(result.msg);
                      }
                  }
              });
          }
        
           
    }     
    $( document ).ready( function () {
          $("#btnAddUser").on("click",function(){
            console.log("test");
            insert_data();
            // $( ".submit-btn" ).trigger("click");
        })


    

               
   });
 
</script>