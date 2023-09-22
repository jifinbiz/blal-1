<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/css/bootstrap.min.css">
  <script src="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/js/jquery.min.js"></script>
  <script src="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/js/bootstrap.min.js"></script>
 <link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/js/datatables.min.js"></script>
 <script src="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/js/jquery.validate.min.js" ></script>
<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/css/jquery-ui.css">
 
 
  <script src="<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/js/jqueryui.js"></script>



<style type="text/css">
.spinner img{
        position: absolute;
    top: 40%;
    left: 45%;
}
.spinner{
    position: absolute;
    text-align: center;
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
    background: #00000057;
}
	a:focus, a:hover{
		text-decoration:none;
	}
  .f-r{
    float: right;
  }
  .f-l{
    float: left;
  }
  .t-c{
    text-align: center;
  }
  .borderless td, .borderless th {
    border: none;
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
table{
    width: 99% !important;
}
</style>
 
<div class="container investor-page-acf" style="padding-top:20px;">
    <div class="col-sm-12">
        
    

        <!-- <div class="col-sm-6" style="margin:15px 0 20px;">
           		<label for="location_old"  style="color:#000000;font-weight:bold;font-size:18px;">Select Location :</label>
            <select name="location"   id="location" style="color:#000000;font-weight:bold;height:30px;width:200px;display: inline">
                <option selected="selected" value="CORPORATE OFFICE">CORPORATE OFFICE</option>
                <option value="KGF COMPLEX">KGF COMPLEX</option>
                <option value="BANGALORE COMPLEX">BANGALORE COMPLEX</option>
                <option value="MYSORE COMPLEX">MYSORE COMPLEX</option>
                <option value="PALAKKAD COMPLEX">PALAKKAD COMPLEX</option>
                <option value="REGIONAL / DISTRICT (MKTG) OFFICES">REGIONAL / DISTRICT (MKTG) OFFICES</option>
                <option value="UNITY BUILDINGS">UNITY BUILDINGS</option>
                <option value="VIGNYAN INDUSTRIES LTD., TARIKERE">VIGNYAN INDUSTRIES LTD., TARIKERE</option>

            </select>
        </div> -->
        <div class="col-sm-12"  style="margin:10px 0;">
            <h4 style="text-align:right;"><a href="http://crm-doctor.com/blal/archived-tender/" >CHECK ARCHIVED TENDERS</a></h3>
        </div>
       
        <div class="table-responsive" style="display: block;width: 98%;margin: 0 auto;">
            <table id='service-table' class="table table-bordered">
                <thead>
                    <tr>
                          <th class="all" style="width:40%;">Description</th>
                        <th class="all">Last Date</th>
                        <th class="all">Opening Date</th>
                        <th class="all">Inviting Officer</th>
                        <th class="all">Type</th>
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
     

    function reload_table(loc=null) {
       
        $('#service-table').dataTable({
                    "paging": true,
                    "pageLength": 20,
                    "bLengthChange": false,
                    "bProcessing": false,
                    "serverSide": false,
                     "bDestroy": true,
                     "searching": false,
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {"action": "tender_get_frontend","location":loc}

                    },
                    "aoColumns": [
                      
                        {mData: 'Description'},
                        {mData: 'End_date'},
                        {mData: 'Opening_date'},
                        {mData: 'Inviting_Officer'},
                        {mData: 'Type'},
                    ],
                    "ordering": false,        

                    "columnDefs": [{
                        "targets": [4],
                        "orderable": false
                    }],
                    "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                    
                  }
            });	 
    }

  jQuery("#location").change(function(){
 
        var loc=jQuery(this).val();
        console.log(loc);
        reload_table(loc);
         
    });
 
    function download_tender(id,pos,type){
         
        $(".modal-title").html("Download Tender");
        $(".modal-body").html('<form method="POST" name="UserUpdateform" id="UserUpdateform"  enctype="multipart/form-data"style="display: inline-block;">      <input type="hidden" id="action" name="action" value="Add_tender_download">    <input type="hidden" id="tender_no" name="tender_no" value="'+id+'">  <input type="hidden" id="pos" name="pos" value="'+pos+'">  <input type="hidden" id="type" name="type" value="'+type+'">             <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Name:<em style="color:#f00">*</em></label>               <input type="text" name="name" class="form-control"  id="name" required>          </div>        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label   class="col-form-label">Organisation Name:<em style="color:#f00">*</em></label>               <input type="text" name="organisation_name" id="organisation_name" class="form-control" required>          </div>        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                        <label for="no_of_coins" class="col-form-label">Mobile Number:<em style="color:#f00">*</em></label>                        <input type="text" class="form-control" maxlength="10"   id="phone_number" name="phone_number" required>        </div>              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                         <label for="email" class="col-form-label">Email Id:<em style="color:#f00">*</em></label>             <input type="email" name="email" id="email" class="form-control" required>            </div>     <input type="submit" class="submit-btn" value="submit">  </form>');
            $('#AddUserModal').modal('show');
			$("#btnAddUser").removeAttr("disabled");
                   jQuery("#phone_number").keyup(function(){
                    var inputVal = jQuery(this).val();
                    var numericReg = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;
                    if(!numericReg.test(inputVal)) {
                         jQuery(".error-keyup-1").remove();
                        jQuery(this).after('<label class="error error-keyup-1">Numeric characters only.</label>');
                    }else if(inputVal.length != 10){
                         jQuery(".error-keyup-1").remove();
                        jQuery(this).after('<label class="error error-keyup-1">Maximum 10 characters.</label>');
                    }else{
                        jQuery(".error-keyup-1").remove();
                    }
                           
                             
                });
                
      
    }

 


 
  function insert_data() {   

        if($( "#UserUpdateform" ).valid() && jQuery( ".error" ).length == 0){
            
            var data=jQuery('#UserUpdateform').serialize() 
            var form_data = new FormData(document.getElementById('UserUpdateform'));                


 $(".modal-body").append("<div class='spinner'><img src='<?php echo WP_PLUGIN_URL;?>/tenders/WCP/DATA/spinner.gif'></div>");
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
                               var element = document.createElement('a');
  
                              
                                element.href = result.file_path;
                                element.id = "download-pdf-custom-link";
                                element.target = "_blank";
                      
                                
                                document.documentElement.appendChild(element);
                         
                                element.click();
                       
                                jQuery("#download-pdf-custom-link").remove();
                          // alert(result.msg);
                          reload_table();
                          if(jQuery(".corr_details").is(":visible")){
                               var  id=jQuery("#btn_add_corr").attr("data-id");
                              tenders_corr(id);
                          }
                      }else{
						   $("#btnAddUser").removeAttr("disabled");
                         $(".modal-body .spinner").remove();
                          alert(result.msg);
                      }
                  }
              });
           }else{
            $("#btnAddUser").removeAttr("disabled");
          }
        
           
    }     
    $( document ).ready( function () {
          $("#btnAddUser").on("click",function(){
            console.log("test");
			$(this).attr("disabled","disabled");
            insert_data();
            // $( ".submit-btn" ).trigger("click");
        })
               
   });
 
  
 
</script>