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
            <h3>SHORTLISTED RECRUITMENTS</h3>
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
                       
                        <th class="all">Advertisement No</th>
                        <th class="all">Type</th>
                        <th class="all">Description</th>
                        <th class="all">Date</th> 
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
                        data: {"action": "get_career_result"}

                    },
                    "aoColumns": [
                      
                        {mData: 'Advertisement_No'},
                        {mData: 'Type'},
                        {mData: 'Titel_eng'},
                        {mData: 'OpeningDate'}, 
                        {mData: 'action'}
                    ],
                    "order": [[ 0, "desc" ]],        

                    "columnDefs": [{
                        "targets": [4],
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
              data: {"action": "delete_career_result", id: id},
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
    
    function career_update(id) {
        var option="";
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "get_career_result_update", id: id},
            success: function (data) {
               
                var result = JSON.parse(data);
                if (result.status == 1) {
                     for (var i = result['drop_down'].length - 1; i >= 0; i--) {

                             var  data=result['drop_down'][i] ;

                            option=option+"<option value='"+data['Advertisement_No']+"' data-id='"+data['Curentjobid']+"'>"+data['Advertisement_No']+"</option>";
                           
                      
                      
                         
                    }
                     for (var i = result['user_details'].length - 1; i >= 0; i--) {

                             var  data=result['user_details'][i] ;

                             var Formatid =data['Formatid'];
                             var Titel_eng =data['Titel_eng'];
                             var Titel_Hindi =data['Titel_Hindi'];
                             var Type =data['Type'];
                              var date =data['Date'];
                             var adv_no =data['Advertisement_No'];


                            if(data['format_file_hnd'] != "" && data['format_file_hnd'] != null){
                             var format_file_hnd =data['format_file_hnd'].split('$');
                             }else{
                               var format_file_hnd =data['format_file_hnd'];
                             
                             }
                             if(data['format_file'] != "" && data['format_file'] != null){
                             var format_file =data['format_file'].split('$');
                             }else{
                               var format_file =data['format_file'];
                             
                             }
                             if(data['Docname_eng'] != "" && data['Docname_eng'] != null){
                             var Docname_eng =data['Docname_eng'].split('$');
                             }else{
                               var Docname_eng =data['Docname_eng'];
                             
                             }
                             if(data['Docname_hnd'] != "" && data['Docname_hnd'] != null){
                             var Docname_hnd =data['Docname_hnd'].split('$');
                             }else{
                               var Docname_hnd =data['Docname_hnd'];
                             
                             }

                              if(data['File_size'] != "" && data['File_size'] != null){
                             var file_size =data['File_size'].split('$');
                             }else{
                               var file_size =data['File_size'];
                             
                             }

                              if(data['File_size_hnd'] != "" && data['File_size_hnd'] != null){
                             var file_size_hnd =data['File_size_hnd'].split('$');
                             }else{
                               var file_size_hnd =data['File_size_hnd'];
                             
                             }
                             


                              var ffh =data['format_file_hnd'];
                             var ffe =data['format_file'];
                             var de =data['Docname_eng'];
                             var dh =data['Docname_hnd'];
                             var fse =data['File_size'];
                             var fsh =data['File_size_hnd'];

 
                         
                        var html_eng="";
                        var html_hindi="";
                      if(format_file != null){
                         for (let i =  0; i < format_file.length; ++i) {
                             html_eng=html_eng+"<tr><td>"+i+"</td><td>"+Docname_eng[i]+"</td><td>"+format_file[i]+"</td><td><a href='<?php echo home_url(); ?>/wp-content/plugins/career/WCP/DATA"+format_file[i]+"'  >View</a></td><td>"+file_size[i]+"KB</td><td><a href='#' data-type='eng' data-tenderfile='"+format_file[i]+"' class='delete-file' >Del</a></td></tr>";
                        }
                      }
                        if(format_file_hnd != null){
                        for (let i =  0; i < format_file_hnd.length; ++i) {
                             html_hindi=html_hindi+"<tr><td>"+i+"</td><td>"+Docname_hnd[i]+"</td><td>"+format_file_hnd[i]+"</td><td><a href='<?php echo home_url(); ?>/wp-content/plugins/career/WCP/DATA"+format_file_hnd[i]+"'  >View</a></td><td>"+file_size_hnd[i]+"KB</td><td><a href='#' data-tenderfile='"+format_file_hnd[i]+"' data-type='hindi' class='delete-file' >Del</a></td></tr>";
                        }
                      }
                             
                    $(".modal-title").html("Edit Career Details");
                    $(".modal-body").html('<form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data"style="display: inline-block;">     <input type="hidden" id="Formatid" name="Formatid" value="'+Formatid+'">                       <input type="hidden" id="ffh" name="ffh" value="'+ffh+'">                      <input type="hidden" id="ffe" name="ffe" value="'+ffe+'">                       <input type="hidden" id="de" name="de" value="'+de+'">                        <input type="hidden" id="dh" name="dh" value="'+dh+'">                         <input type="hidden" id="fse" name="fse" value="'+fse+'">                          <input type="hidden" id="fsh" name="fsh" value="'+fsh+'">  <input type="hidden" id="action" name="action" value="update_career_result_info"> <input type="hidden" id="curentjobid" name="curentjobid" >      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                <label   class="col-form-label">Status:</label>              <select name="type" required   class="form-control type">                            <option selected="selected" value="">-Select-</option>                          <option value="Shortlisted">Shortlisted</option>                          <option value="Closed">Closed</option>                        </select>                           </div>     <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                <label   class="col-form-label">Select Advertisement No:</label>              <select name="adv_no" required   class="form-control  adv_no">                            <option selected="selected" value="">Select Advertisement No</option>     '+option+'            </select>                           </div>                           <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                            <label  class="col-form-label">Job Title English:<em style="color:#f00">*</em></label>           <input  class="form-control" type="text" id="job_titel_english"  name="job_titel_english" value="'+Titel_eng+'" required>               </div>               <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                           <label  class="col-form-label">Job Title Hindi:</label>             <input  class="form-control" type="text"   id="job_titel_hindi" name="job_titel_hindi"  value="'+Titel_Hindi+'" >               </div>              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                          <label   class="col-form-label">Date:<em style="color:#f00">*</em></label>                                <input type="text" name="start_date" class="form-control date" value="'+date+'"   id="start_date" required>                     </div>                       <div class="clone-inputes-eng">  <div class="form-group col-lg-11 col-md-11 col-sm-11 col-xs-12 ">                        <label  class="col-form-label">Document Name English:</label>                  <textarea  class="form-control" id="document_title" name="document_title_eng[]"></textarea>                     <label  class="col-form-label">Upload English File:</label>                        <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document_eng" name="tender_document_eng[]" >           </div> <div class="col-lg-1 col-xs-1" > <button type="button"  class="add-more" onClick="add_more_eng();">+</button></div> </div><div class="clone-data-eng"></div> <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                 <table>                        <tr>                          <th>Slno</th>                          <th>Document Title</th>                          <th>Document Description</th>                          <th>View</th>                          <th>File Size</th>                          <th>Delete</th>                        </tr>     '+html_eng+' </table>                    </div>     <div class="clone-inputes-hindi">  <div class="form-group col-lg-11 col-md-11 col-sm-11 col-xs-12 ">                        <label  class="col-form-label">Document Name Hindi:</label>                  <textarea  class="form-control" id="document_title" name="document_title_hindi[]"></textarea>                     <label  class="col-form-label">Upload Hindi File:</label>                        <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document_hindi" name="tender_document_hindi[]" >           </div> <div class="col-lg-1 col-xs-1" > <button type="button"  class="add-more" onClick="add_more_hindi();">+</button></div> </div><div class="clone-data-hindi"></div>  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                 <table>                        <tr>                          <th>Slno</th>                          <th>Document Title</th>                          <th>Document Description</th>                          <th>View</th>                          <th>File Size</th>                          <th>Delete</th>                        </tr>    '+html_hindi+'   </table>                    </div>       <input type="submit" class=" submit-btn" value="submit">  </form>');
                    

                                $('#AddUserModal').modal('show');
                                 

                        $('.adv_no option[value="'+adv_no+'"]').attr('selected','selected');
                        $('.type option[value="'+Type+'"]').attr('selected','selected');
                     
                        $(".date").datepicker();
                         jQuery(".adv_no").on("change",function(){
                jQuery("#curentjobid").val(jQuery('option:selected', this).attr("data-id"));
            })

                       jQuery(".delete-file").click(function(){
                        if (confirm("Are you sure?")) {
                            var tenderfile =jQuery(this).attr('data-tenderfile');
                            var type =jQuery(this).attr('data-type');
                              $.ajax({
                                  type: 'POST',
                                  url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                  data: {"action": "delete_file", id: id,'file_name':tenderfile,"type":type},
                                  success: function (data) {
                                      var result = JSON.parse(data);
                                      if (result.status == 1) {
                                       
                                          jQuery("#AddUserModal").modal('hide');
                                          jQuery('#UserUpdateform')[0].reset();
                                          alert(result.msg);
                                          reload_table();
                                          
                                      }else{
                                          alert(result.msg);
                                      }
                                  }
                              });
                          }
                       })
                    }
                    
                }
            }
        });
       
    }
    function add_career(){
        var option = "";
           $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "get_career_advertisement_no"},
            success: function (data) {
               
                var result = JSON.parse(data);
                if (result.status == 1) {
                     for (var i = result['user_details'].length - 1; i >= 0; i--) {

                             var  data=result['user_details'][i] ;

                            option=option+"<option value='"+data['Advertisement_No']+"' data-id='"+data['Curentjobid']+"'>"+data['Advertisement_No']+"</option>";
                           
                      
                      
                         
                    }
             
        $(".modal-title").html("Add Career Details");
        $(".modal-body").html('<form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data"style="display: inline-block;">                   <input type="hidden" id="action" name="action" value="add_career_result_info"> <input type="hidden" id="curentjobid" name="curentjobid" >      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                <label   class="col-form-label">Status:</label>              <select name="type" required   class="form-control">                            <option selected="selected" value="">-Select-</option>                          <option value="Shortlisted">Shortlisted</option>                          <option value="Closed">Closed</option>                        </select>                           </div>     <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                <label   class="col-form-label">Select Advertisement No:</label>              <select name="adv_no" required   class="form-control  adv_no">                            <option selected="selected" value="">Select Advertisement No</option>     '+option+'            </select>                           </div>                           <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                            <label  class="col-form-label">Job Title English:</label>           <input  class="form-control" type="text" id="job_titel_english"  name="job_titel_english">               </div>               <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                                           <label  class="col-form-label">Job Title Hindi:</label>             <input  class="form-control" type="text"   id="job_titel_hindi" name="job_titel_hindi" >               </div>              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                          <label   class="col-form-label">Date:<em style="color:#f00">*</em></label>                                <input type="text" name="start_date" class="form-control date"    id="start_date" required>                     </div>                       <div class="clone-inputes-eng">  <div class="form-group col-lg-11 col-md-11 col-sm-11 col-xs-12 ">                        <label  class="col-form-label">Document Name English:</label>                  <textarea  class="form-control" id="document_title" name="document_title_eng[]"></textarea>                     <label  class="col-form-label">Upload English File:</label>                        <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document_eng" name="tender_document_eng[]" >           </div> <div class="col-lg-1 col-xs-1" > <button type="button"  class="add-more" onClick="add_more_eng();">+</button></div> </div><div class="clone-data-eng"></div>       <div class="clone-inputes-hindi">  <div class="form-group col-lg-11 col-md-11 col-sm-11 col-xs-12 ">                        <label  class="col-form-label">Document Name Hindi:</label>                  <textarea  class="form-control" id="document_title" name="document_title_hindi[]"></textarea>                     <label  class="col-form-label">Upload Hindi File:</label>                        <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document_hindi" name="tender_document_hindi[]" >           </div> <div class="col-lg-1 col-xs-1" > <button type="button"  class="add-more" onClick="add_more_hindi();">+</button></div> </div><div class="clone-data-hindi"></div>          <input type="submit" class=" submit-btn" value="submit">  </form>');
            $('#AddUserModal').modal('show');
            jQuery(".adv_no").on("change",function(){
                jQuery("#curentjobid").val(jQuery('option:selected', this).attr("data-id"));
            })
        $(".date").datepicker();
       
      
         }
            }
        });
    }
    function add_more_eng(){
        var len=jQuery(".clone-inputes-eng").length;
             var html=' <div class="clone-inputes-eng clon-eng-'+len+'">  <div class="form-group col-lg-11 col-md-11 col-sm-11 col-xs-12 ">                        <label  class="col-form-label">Document Name English:</label>                  <textarea  class="form-control" id="document_title" name="document_title[]"></textarea>                     <label  class="col-form-label">Upload English File:<em style="color:#f00">*</em></label>                        <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document" name="tender_document[]" >           </div> <div class="col-lg-1 col-xs-1" > <button type="button" onClick="add_more();" class="add-more">+</button><button type="button" onClick="remove_more_eng('+len+');"  class="remove-more">-</button></div> </div>';
            jQuery('.clone-data-eng').append(html);
    }
    
    function remove_more_eng(len){
           
            jQuery(".clon-eng-"+len).remove();
    }

     function add_more_hindi(){
        var len=jQuery(".clone-inputes-hindi").length;
             var html=' <div class="clone-inputes-hindi clon-hindi-'+len+'">  <div class="form-group col-lg-11 col-md-11 col-sm-11 col-xs-12 ">                        <label  class="col-form-label">Document Name English:</label>                  <textarea  class="form-control" id="document_title" name="document_title[]"></textarea>                     <label  class="col-form-label">Upload Hindi File:</label>                        <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document" name="tender_document[]" >           </div> <div class="col-lg-1 col-xs-1" > <button type="button" onClick="add_more_hindi();" class="add-more">+</button><button type="button" onClick="remove_more_hindi('+len+');"  class="remove-more">-</button></div> </div>';
            jQuery('.clone-data-hindi').append(html);
    }
    
    function remove_more_hindi(len){
           
            jQuery(".clon-hindi-"+len).remove();
    }

 
    
    function insert_data() {   

        if($( "#UserUpdateform" ).valid()){
          
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