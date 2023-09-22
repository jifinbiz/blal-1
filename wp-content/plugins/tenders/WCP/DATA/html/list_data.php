  <link rel="stylesheet" href="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/css/bootstrap.min.css">
  <script src="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/js/jquery.min.js"></script>
  <script src="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/js/bootstrap.min.js"></script>
 <link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/js/datatables.min.js"></script>
 <script src="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/js/jquery.validate.min.js" ></script>
<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/css/jquery-ui.css">
 
 
  <script src="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/js/jqueryui.js"></script>



<style type="text/css">
  .f-r{
    float: right;
  }
	.error{
		color:red;
	}
  .f-l{
    float: left;
  }
  .t-c{
    text-align: center;
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
 
<div class="flex_container tenders_details" style="padding-top:20px;">
    <div class="col-sm-12">
        <div class="col-sm-12">
            <h3>ACTIVE TENDERS</h3>
        </div>
        <div class="col-sm-12"  style="margin:0 0 20px 0; ">
            <input type="button" value="Add Tenders" name="btn_add_user" id="btn_add_user" class="btn btn-info btn-sm" onclick="add_tenders()"  />
            <input type="button" value="Import Tenders" name="btn_add_user" id="btn_add_user" class="btn btn-info btn-sm" onclick="import_tenders()"  />
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
                        <th class="all">TENDER NO</th>
                        <th class="all">TENDER TITLE</th>
                        <th class="all">Start Date</th>
                        <th class="all">End Date</th>
                        <th class="all">Opening Date</th>
                        <th class="all">Status</th>
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
            <h3>CORRIGENDUM</h3>
        </div>
        <div class="col-sm-12"  style="margin:0 0 20px 0; ">
            <input type="button" value="Add Corrigendum" name="btn_add_corr" id="btn_add_corr" class="btn btn-info btn-sm" onclick="add_corr()"  />
            <input type="button" value="Back Tenders" name="btn_back_corr" id="btn_back_corr" class="btn btn-info btn-sm" onclick="tenders_back()"  />
          
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
                        <th class="all">TENDER NO</th>
                        <th class="all">TENDER TITLE</th>
                        <th class="all">Start Date</th>
                        <th class="all">End Date</th>
                        <th class="all">Opening Date</th>
                        <th class="all">Status</th>
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
                        data: {"action": "tender_get_tenders"}

                    },
                    "aoColumns": [
                      
                        {mData: 'Tenderno'},
                        {mData: 'Workdetails_eng'},
                        {mData: 'Start_date'},
                        {mData: 'End_date'},
                        {mData: 'Opening_date'},
                        {mData: 'Status'},
                        {mData: 'action'}
                    ],
                    "order": [[ 0, "desc" ]],        

                    "columnDefs": [{
                        "targets": [5],
                        "orderable": false
                    }],
                    "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                    
                  }
            });	 
    }

    function tenders_back() {
        jQuery(".tenders_details").show();
        jQuery(".corr_details").hide();
         reload_table();

    }
    function tenders_corr(id) {
        jQuery(".tenders_details").hide();
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
                        data: {"action": "tender_get_corr","id":id}

                    },
                    "aoColumns": [
                      
                        {mData: 'Tenderno'},
                        {mData: 'Workdetails_eng'},
                        {mData: 'Start_date'},
                        {mData: 'End_date'},
                        {mData: 'Opening_date'},
                        {mData: 'Status'},
                        {mData: 'action'}
                    ],
                    "order": [[ 0, "desc" ]],        

                    "columnDefs": [{
                        "targets": [5],
                        "orderable": false
                    }],
                    "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                    
                  }
            });  
    }

    function tenders_delete(id) {
      if (confirm("Are you sure?")) {
          
       
          $.ajax({
              type: 'POST',
              url: '<?php echo admin_url('admin-ajax.php'); ?>',
              data: {"action": "tender_delete_tenders", id: id},
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
              data: {"action": "tender_delete_corr", id: id},
              success: function (data) {
                  var result = JSON.parse(data)
                  if (result.status == 1) {
                         var  id=jQuery("#btn_add_corr").attr("data-id");
                      tenders_corr(id);
                  }else{
                     alert(result.msg);
                      var  id=jQuery("#btn_add_corr").attr("data-id");
                      tenders_corr(id);
                     
                  }
              }
          });
      }
       return false;
    }
    function tenders_update(id) {
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "tender_get_detail_by_id", id: id},
            success: function (data) {
               
                var result = JSON.parse(data);
                if (result.status == 1) {
                     for (var i = result['user_details'].length - 1; i >= 0; i--) {

                             var  data=result['user_details'][i] ;

                             var Tenderid =data['Tenderid'];
                             var Tenderno =data['Tenderno'];
                             var Workdetails_eng =data['Workdetails_eng'];
                             var Start_date =data['Start_date'];
                             var Opening_date =data['Opening_date'];

                             var workdetails_hindi =data['Workdetails_hindi'].split('$');
                             var tenderfile =data['Tenderfile'].split('$');
                             var file_size =data['file_size'].split('$');


                              var wh =data['Workdetails_hindi'];
                             var tf =data['Tenderfile'];
                             var fs =data['file_size'];
                            


                             var Type =data['Type'];
                             var OpeningDate_Time =data['OpeningDate_Time'];
                             var Location =data['Location'];
                             var Inviting_Officer =data['Inviting_Officer'];
                             var End_date =data['End_date'];
                             var EndDate_Time =data['EndDate_Time'];
                         
                        var html="";
                      
                         for (let i =  0; i < tenderfile.length; ++i) {
                             html=html+"<tr><td>"+i+"</td><td>"+workdetails_hindi[i]+"</td><td>"+tenderfile[i]+"</td><td><a href='<?php echo home_url(); ?>/wp-content/plugins/tenders/WCP/DATA/uploads/"+tenderfile[i]+"'  >View</a></td><td>"+file_size[i]+"</td><td><a href='#' data-tenderfile='"+tenderfile[i]+"' class='delete-file' >Del</a></td></tr>";
                        }
                             
                    $(".modal-title").html("Edit Tender Details");
                    $(".modal-body").html('<form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data"style="display: inline-block;"> <input type="hidden" name="Tenderid" value="'+Tenderid+'">                    <input type="hidden" name="wh" value="'+wh+'">                    <input type="hidden" name="tf" value="'+tf+'">                    <input type="hidden" name="fs" value="'+fs+'">     <input type="hidden" id="action" name="action" value="tender_Update_tenders_info">            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Tender No:<em style="color:#f00">*</em></label>             <input type="text" name="tender_no" class="form-control"  id="tender_no" value="'+Tenderno+'" required>          </div>      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label for="price" class="col-form-label">Inviting Officer:</label>                 <textarea  class="form-control" id="inviting_officer" name="inviting_officer">'+Inviting_Officer+'</textarea>          </div>  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label for="price" class="col-form-label">Tender Description:</label>                   <textarea  class="form-control" id="tender_description" name="tender_description">'+Workdetails_eng+'</textarea>          </div><div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                      <label   class="col-form-label">Start Date:<em style="color:#f00">*</em></label>            <input type="text" name="start_date" class="form-control date"  id="start_date" value="'+Start_date+'" required>       </div>  <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">                     <label   class="col-form-label">End Date:<em style="color:#f00">*</em></label>          <input type="text" name="end_date" class="form-control date"  id="end_date" value="'+End_date+'" required>      </div>   <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">                     <label   class="col-form-label">&nbsp;</label>          <input type="text" name="end_date_time" class="form-control" value="'+EndDate_Time+'"  id="end_date_time" required>      </div> <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">                     <label   class="col-form-label">Opening Date:<em style="color:#f00">*</em></label>              <input type="text" name="opening_date" class="form-control date"  value="'+Opening_date+'" id="opening_date" required>       </div>  <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">                     <label   class="col-form-label">&nbsp;</label>              <input type="text" name="opening_date_time" class="form-control"  id="opening_date_time" value="'+OpeningDate_Time+'" required>         </div>  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Location:<em style="color:#f00">*</em></label>      <select name="location" required   class="form-control location">                <option   value="">-Select-</option>              <option value="CORPORATE OFFICE">CORPORATE OFFICE</option>              <option value="KGF COMPLEX">KGF COMPLEX</option>                <option value="BANGALORE COMPLEX">BANGALORE COMPLEX</option>                <option value="MYSORE COMPLEX">MYSORE COMPLEX</option>              <option value="PALAKKAD COMPLEX">PALAKKAD COMPLEX</option>              <option value="REGIONAL / DISTRICT (MKTG) OFFICES">REGIONAL / DISTRICT (MKTG) OFFICES</option>              <option value="UNITY BUILDINGS">UNITY BUILDINGS</option>                <option value="VIGNYAN INDUSTRIES LTD., TARIKERE">VIGNYAN INDUSTRIES LTD., TARIKERE</option>        </select>                       </div>      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Type:<em style="color:#f00">*</em></label>      <select name="type" required   class="form-control type">                <option   value="">-Select-</option>              <option value="E-Tender">E-Tender</option>              <option value="Manual Tender">Manual Tender</option>                <option value="EoI Enquiry">EoI Enquiry</option>        </select>                       </div>      <div class="clone-inputes">  <div class="form-group col-lg-11 col-md-11 col-sm-11 col-xs-12 ">                        <label  class="col-form-label">Document Title:</label>                  <textarea  class="form-control" id="document_title" name="document_title[]"></textarea>                     <label  class="col-form-label">Tender Document:<em style="color:#f00">*</em></label>                        <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document" name="tender_document[]" >           </div> <div class="col-lg-1 col-xs-1" > <button type="button"  class="add-more" onClick="add_more();">+</button></div> </div><div class="clone-data"></div>  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                 <table class="table table-bordered">                        <tr>                          <th>Slno</th>                          <th>Document Title</th>                          <th>Document Description</th>                          <th>View</th>                          <th>File Size</th>                          <th>Delete</th>                        </tr>     '+html+'                 </table>                    </div>   <input type="submit" class=" submit-btn" value="submit">  </form>  ');
                    

                                $('#AddUserModal').modal('show');
                                 

                        $('.location option[value="'+Location+'"]').attr('selected','selected');
                        $('.type option[value="'+Type+'"]').attr('selected','selected');
                     
                        $(".date").datepicker({
    dateFormat: 'yy-m-d',//check change
    changeMonth: true,
    changeYear: true
});
      $(".type").on("change",function(){

            var data=$(this).val();
            if(data == "EoI Enquiry"){
                $("#start_date").removeAttr("required").parent().find("em").remove();
                $("#end_date").removeAttr("required").parent().find("em").remove();
                $("#end_date_time");
                $("#opening_date").removeAttr("required").parent().find("em").remove();
                $("#opening_date_time").removeAttr("required"); 
            }else{
                $("#start_date").attr("required","required").parent().find("label").append('<em style="color:#f00">*</em>');
                $("#end_date").attr("required","required").parent().find("label").append('<em style="color:#f00">*</em>');
                $("#end_date_time").attr("required","required");
                $("#opening_date").attr("required","required").parent().find("label").append('<em style="color:#f00">*</em>');
                $("#opening_date_time").attr("required","required"); 
            }
         });

                       jQuery(".delete-file").click(function(){
                        if (confirm("Are you sure?")) {
                            var tenderfile =jQuery(this).attr('data-tenderfile');
                              $.ajax({
                                  type: 'POST',
                                  url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                  data: {"action": "tender_delete_file", id: id,'file_name':tenderfile},
                                  success: function (data) {
                                      var result = JSON.parse(data);
                                      if (result.status == 1) {
                                       
                                          jQuery("#AddUserModal").modal('hide');
                                          jQuery('#UserUpdateform')[0].reset();
                                          alert(result.msg);
                                          reload_table();
                                          if(jQuery(".corr_details").is(":visible")){
                                               var  id=jQuery("#btn_add_corr").attr("data-id");
                                              tenders_corr(id);
                                          }
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
    function add_tenders(){
       	 
        $(".modal-title").html("Add Tender Details");
        $(".modal-body").html('<form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data"style="display: inline-block;">      <input type="hidden" id="action" name="action" value="tender_Add_tenders_info">            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Tender No:<em style="color:#f00">*</em></label>             <input type="text" name="tender_no" class="form-control"  id="tender_no" required>          </div>      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label for="price" class="col-form-label">Inviting Officer:</label>                 <textarea  class="form-control" id="inviting_officer" name="inviting_officer"></textarea>          </div>  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label for="price" class="col-form-label">Tender Description:</label>                   <textarea  class="form-control" id="tender_description" name="tender_description"></textarea>          </div><div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                      <label   class="col-form-label">Start Date:<em style="color:#f00">*</em></label>            <input type="text" name="start_date" class="form-control date"  id="start_date" required>       </div>  <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">                     <label   class="col-form-label">End Date:<em style="color:#f00">*</em></label>          <input type="text" name="end_date" class="form-control date"  id="end_date" required>      </div>   <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">                     <label   class="col-form-label">&nbsp;</label>          <input type="text" name="end_date_time" class="form-control"  id="end_date_time" required>      </div> <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">                     <label   class="col-form-label">Opening Date:<em style="color:#f00">*</em></label>              <input type="text" name="opening_date" class="form-control date"  id="opening_date" required>       </div>  <div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">                     <label   class="col-form-label">&nbsp;</label>              <input type="text" name="opening_date_time" class="form-control"  id="opening_date_time" required>         </div>  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Location:<em style="color:#f00">*</em></label>      <select name="location" required   class="form-control">                <option selected="selected" value="">-Select-</option>              <option value="CORPORATE OFFICE">CORPORATE OFFICE</option>              <option value="KGF COMPLEX">KGF COMPLEX</option>                <option value="BANGALORE COMPLEX">BANGALORE COMPLEX</option>                <option value="MYSORE COMPLEX">MYSORE COMPLEX</option>              <option value="PALAKKAD COMPLEX">PALAKKAD COMPLEX</option>              <option value="REGIONAL / DISTRICT (MKTG) OFFICES">REGIONAL / DISTRICT (MKTG) OFFICES</option>              <option value="UNITY BUILDINGS">UNITY BUILDINGS</option>                <option value="VIGNYAN INDUSTRIES LTD., TARIKERE">VIGNYAN INDUSTRIES LTD., TARIKERE</option>        </select>                       </div>      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Type:<em style="color:#f00">*</em></label>      <select name="type" required   class="form-control type">                <option selected="selected" value="">-Select-</option>              <option value="E-Tender">E-Tender</option>              <option value="Manual Tender">Manual Tender</option>                <option value="EoI Enquiry">EoI Enquiry</option>        </select>                       </div>    <div class="clone-inputes">  <div class="form-group col-lg-11 col-md-11 col-sm-11 col-xs-12 ">                        <label  class="col-form-label">Document Title:</label>                  <textarea  class="form-control" id="document_title" name="document_title[]"></textarea>                     <label  class="col-form-label">Tender Document:<em style="color:#f00">*</em></label>                        <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document" name="tender_document[]" >           </div> <div class="col-lg-1 col-xs-1" > <button type="button"  class="add-more" onClick="add_more();">+</button></div> </div><div class="clone-data"></div> <input type="submit" class=" submit-btn" value="submit">  </form>');
            $('#AddUserModal').modal('show');
            
       $(".date").datepicker({
    dateFormat: 'yy-m-d',//check change
    changeMonth: true,
    changeYear: true
});
       
      $(".type").on("change",function(){

            var data=$(this).val();
            if(data == "EoI Enquiry"){
                $("#start_date").removeAttr("required").parent().find("em").remove();
                $("#end_date").removeAttr("required").parent().find("em").remove();
                $("#end_date_time");
                $("#opening_date").removeAttr("required").parent().find("em").remove();
                $("#opening_date_time").removeAttr("required"); 
            }else{
                $("#start_date").attr("required","required").parent().find("label").append('<em style="color:#f00">*</em>');
                $("#end_date").attr("required","required").parent().find("label").append('<em style="color:#f00">*</em>');
                $("#end_date_time").attr("required","required");
                $("#opening_date").attr("required","required").parent().find("label").append('<em style="color:#f00">*</em>');
                $("#opening_date_time").attr("required","required"); 
            }
         });
      
    }
    function add_more(){
        var len=jQuery(".clone-inputes").length;
             var html=' <div class="clone-inputes clon-'+len+'">  <div class="form-group col-lg-11 col-md-11 col-sm-11 col-xs-12 ">                        <label  class="col-form-label">Document Title:</label>                  <textarea  class="form-control" id="document_title" name="document_title[]"></textarea>                     <label  class="col-form-label">Tender Document:<em style="color:#f00">*</em></label>                        <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document" name="tender_document[]" >           </div> <div class="col-lg-1 col-xs-1" > <button type="button" onClick="add_more();" class="add-more">+</button><button type="button" onClick="remove_more('+len+');"  class="remove-more">-</button></div> </div>';
            jQuery('.clone-data').append(html);
    }
    
    function remove_more(len){
           
            jQuery(".clon-"+len).remove();
    }

    function import_tenders(){
         
        $(".modal-title").html("Import Tenders");
        $(".modal-body").html('<form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data"style="display: inline-block;">      <input type="hidden" id="action" name="action" value="tender_import_tenders_info">               <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label  class="col-form-label">Tender:<em style="color:#f00">*</em></label>                        <input type="file"  accept=".xlsx,.xls"  class="form-control" id="tender_document" name="tender_document" required>           </div>    <a href="https://bemlproto.crm-doctor.com/wp-content/uploads/2022/06/For_Upload-2.xlsx" download>Sample Import</a> <input type="submit" class=" submit-btn" value="submit">  </form>');
            $('#AddUserModal').modal('show');
         
     
      
    }
    function add_corr(){
         var  id=jQuery("#btn_add_corr").attr("data-id");
        
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "tender_get_detail_by_id", id: id},
            success: function (data) {
               
                var result = JSON.parse(data);
                if (result.status == 1) {
                     for (var i = result['user_details'].length - 1; i >= 0; i--) {

                             var  data=result['user_details'][i] ;

                             var Tenderid =data['Tenderid'];
                             var Tenderno =data['Tenderno'];
                             var Workdetails_eng =data['Workdetails_eng'];
                             var Tenderfile =data['Tenderfile'];
                             var file_size =data['file_size'];
                             var Start_date =data['Start_date'];
                             var Opening_date =data['Opening_date'];

                             var Type =data['Type'];
                             var OpeningDate_Time =data['OpeningDate_Time'];
                             var Location =data['Location'].toUpperCase();
                             var Inviting_Officer =data['Inviting_Officer'];
                             var End_date =data['End_date'];
                             var EndDate_Time =data['EndDate_Time'];
                         

                             
                    $(".modal-title").html("Edit Corrigendum Detail");
                    $(".modal-body").html('<form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data"style="display: inline-block;"> <input type="hidden" name="Tenderid" value="'+Tenderid+'">     <input type="hidden" id="action" name="action" value="tender_add_corr_info">            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Tender No:<em style="color:#f00">*</em></label>             <input type="text" name="tender_no" class="form-control"  id="tender_no" readonly value="'+Tenderno+'" required>          </div>      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label for="price" class="col-form-label">Inviting Officer:</label>                 <textarea  class="form-control" id="inviting_officer" name="inviting_officer" readonly>'+Inviting_Officer+'</textarea>          </div>  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label for="price" class="col-form-label">Corrigendum Title English:</label>                   <textarea  class="form-control" id="corrigendum_title_english" name="corrigendum_title_english"></textarea>          </div> <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label for="price" class="col-form-label">Corrigendum Title Hindi:</label>                   <textarea  class="form-control" id="corrigendum_title_hindi" name="corrigendum_title_hindi"></textarea>          </div><div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                      <label   class="col-form-label">Start Date:<em style="color:#f00">*</em></label>            <input type="text" name="start_date" class="form-control date"  id="start_date" value="'+Start_date+'" required>       </div>  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                     <label   class="col-form-label">End Date:<em style="color:#f00">*</em></label>          <input type="text" name="end_date" class="form-control date"  id="end_date" value="'+End_date+'" required>      </div>    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                     <label   class="col-form-label">Opening Date:<em style="color:#f00">*</em></label>              <input type="text" name="opening_date" class="form-control date"  value="'+Opening_date+'" id="opening_date" required>       </div>     <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Location:<em style="color:#f00">*</em></label>      <select name="location" readonly required   class="form-control location">                <option   value="">-Select-</option>              <option value="CORPORATE OFFICE">CORPORATE OFFICE</option>              <option value="KGF COMPLEX">KGF COMPLEX</option>                <option value="BANGALORE COMPLEX">BANGALORE COMPLEX</option>                <option value="MYSORE COMPLEX">MYSORE COMPLEX</option>              <option value="PALAKKAD COMPLEX">PALAKKAD COMPLEX</option>              <option value="REGIONAL / DISTRICT (MKTG) OFFICES">REGIONAL / DISTRICT (MKTG) OFFICES</option>              <option value="UNITY BUILDINGS">UNITY BUILDINGS</option>                <option value="VIGNYAN INDUSTRIES LTD., TARIKERE">VIGNYAN INDUSTRIES LTD., TARIKERE</option>        </select>                       </div>      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Type:<em style="color:#f00">*</em></label>      <select readonly name="type" required   class="form-control type">                <option   value="">-Select-</option>              <option value="E-Tender">E-Tender</option>              <option value="Manual Tender">Manual Tender</option>                <option value="EoI Enquiry">EoI Enquiry</option>        </select>                       </div>             <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label  class="col-form-label">Tender Document:<em style="color:#f00">*</em></label>                        <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document" name="tender_document" >           </div>   <input type="submit" class=" submit-btn" value="submit">  </form>');
                                $('#AddUserModal').modal('show');
                                 

                        $('.location option[value="'+Location+'"]').attr('selected','selected');
                        $('.type option[value="'+Type+'"]').attr('selected','selected');
                     
                        $(".date").datepicker({
    dateFormat: 'yy-m-d',//check change
    changeMonth: true,
    changeYear: true
});
      $(".type").on("change",function(){

            var data=$(this).val();
            if(data == "EoI Enquiry"){
                $("#start_date").removeAttr("required").parent().find("em").remove();
                $("#end_date").removeAttr("required").parent().find("em").remove();
                $("#end_date_time");
                $("#opening_date").removeAttr("required").parent().find("em").remove();
                $("#opening_date_time").removeAttr("required"); 
            }else{
                $("#start_date").attr("required","required").parent().find("label").append('<em style="color:#f00">*</em>');
                $("#end_date").attr("required","required").parent().find("label").append('<em style="color:#f00">*</em>');
                $("#end_date_time").attr("required","required");
                $("#opening_date").attr("required","required").parent().find("label").append('<em style="color:#f00">*</em>');
                $("#opening_date_time").attr("required","required"); 
            }
         });

                       
                    }
                    
                }
            }
        });
      
    }

    function corr_update(id){
            
            
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "tender_get_detail_corr_by_id", id: id},
            success: function (data) {
               
                var result = JSON.parse(data);
                if (result.status == 1) {
                     for (var i = result['user_details'].length - 1; i >= 0; i--) {

                             var  data=result['user_details'][i] ;

                             var CG_ID =data['CG_ID'];
                             var Tenderno =data['CG_TenderNo'];
                             var CG_NameEng =data['CG_NameEng'];
                             var CG_NameHnd =data['CG_NameHnd']; 
                             var CG_StratDate =data['CG_StratDate'];
                             var CG_OpeningDate =data['CG_OpeningDate'];

                             var CG_Type =data['CG_Type']; 
                             var CG_Location =data['CG_Location'].toUpperCase();
                             var Invitinfficer =data['Invitinfficer'];
                             var CG_EndDate =data['CG_EndDate'];
                        
                         

                             
                    $(".modal-title").html("Edit Corrigendum Detail");
                    $(".modal-body").html('<form method="POST" name="UserUpdateform" id="UserUpdateform" onsubmit="return false;" enctype="multipart/form-data"style="display: inline-block;"> <input type="hidden" name="CG_ID" value="'+CG_ID+'">     <input type="hidden" id="action" name="action" value="tender_update_corr_info">            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Tender No:<em style="color:#f00">*</em></label>             <input type="text" name="tender_no" class="form-control"  id="tender_no" readonly value="'+Tenderno+'" required>          </div>      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label for="price" class="col-form-label">Inviting Officer:</label>                 <textarea  class="form-control" id="inviting_officer" name="inviting_officer" readonly>'+Invitinfficer+'</textarea>          </div>  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label for="price" class="col-form-label">Corrigendum Title English:</label>                   <textarea  class="form-control" id="corrigendum_title_english" name="corrigendum_title_english">'+CG_NameEng+'</textarea>          </div> <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label for="price" class="col-form-label">Corrigendum Title Hindi:</label>                   <textarea  class="form-control" id="corrigendum_title_hindi" name="corrigendum_title_hindi">'+CG_NameHnd+'</textarea>          </div><div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                      <label   class="col-form-label">Start Date:<em style="color:#f00">*</em></label>            <input type="text" name="start_date" class="form-control date"  id="start_date" value="'+CG_StratDate+'" required>       </div>  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                     <label   class="col-form-label">End Date:<em style="color:#f00">*</em></label>          <input type="text" name="end_date" class="form-control date"  id="end_date" value="'+CG_EndDate+'" required>      </div>    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                     <label   class="col-form-label">Opening Date:<em style="color:#f00">*</em></label>              <input type="text" name="opening_date" class="form-control date"  value="'+CG_OpeningDate+'" id="opening_date" required>       </div>     <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Location:<em style="color:#f00">*</em></label>      <select name="location" readonly required   class="form-control location">                <option   value="">-Select-</option>              <option value="CORPORATE OFFICE">CORPORATE OFFICE</option>              <option value="KGF COMPLEX">KGF COMPLEX</option>                <option value="BANGALORE COMPLEX">BANGALORE COMPLEX</option>                <option value="MYSORE COMPLEX">MYSORE COMPLEX</option>              <option value="PALAKKAD COMPLEX">PALAKKAD COMPLEX</option>              <option value="REGIONAL / DISTRICT (MKTG) OFFICES">REGIONAL / DISTRICT (MKTG) OFFICES</option>              <option value="UNITY BUILDINGS">UNITY BUILDINGS</option>                <option value="VIGNYAN INDUSTRIES LTD., TARIKERE">VIGNYAN INDUSTRIES LTD., TARIKERE</option>        </select>                       </div>      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Type:<em style="color:#f00">*</em></label>      <select readonly name="type" required   class="form-control type">                <option   value="">-Select-</option>              <option value="E-Tender">E-Tender</option>              <option value="Manual Tender">Manual Tender</option>                <option value="EoI Enquiry">EoI Enquiry</option>        </select>                       </div>             <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label  class="col-form-label">Tender Document:<em style="color:#f00">*</em></label>                        <input type="file"  accept=".pdf,.zip,.rar"  class="form-control" id="tender_document" name="tender_document" >           </div>   <input type="submit" class=" submit-btn" value="submit">  </form>');
                                $('#AddUserModal').modal('show');
                                 

                        $('.location option[value="'+CG_Location+'"]').attr('selected','selected');
                        $('.type option[value="'+CG_Type+'"]').attr('selected','selected');
                     
                        $(".date").datepicker({
    dateFormat: 'yy-m-d',//check change
    changeMonth: true,
    changeYear: true
});
      $(".type").on("change",function(){

            var data=$(this).val();
            if(data == "EoI Enquiry"){
                $("#start_date").removeAttr("required").parent().find("em").remove();
                $("#end_date").removeAttr("required").parent().find("em").remove();
                $("#end_date_time");
                $("#opening_date").removeAttr("required").parent().find("em").remove();
                $("#opening_date_time").removeAttr("required"); 
            }else{
                $("#start_date").attr("required","required").parent().find("label").append('<em style="color:#f00">*</em>');
                $("#end_date").attr("required","required").parent().find("label").append('<em style="color:#f00">*</em>');
                $("#end_date_time").attr("required","required");
                $("#opening_date").attr("required","required").parent().find("label").append('<em style="color:#f00">*</em>');
                $("#opening_date_time").attr("required","required"); 
            }
         });

                       
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
                              tenders_corr(id);
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