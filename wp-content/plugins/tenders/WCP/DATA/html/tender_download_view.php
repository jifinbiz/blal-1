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
            <h3>DOWNLOADED TENDERS</h3>
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
                        <th class="all">Sl No.</th>
                        <th class="all">Tender no.</th>
                        <th class="all">Name</th>
                        <th class="all">Organisation Name</th>
                        <th class="all">Mobile no.</th>
                        <th class="all">Email Id</th> 
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

 
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
                        data: {"action": "tender_get_download_tenders"}

                    },
                    "aoColumns": [
                      
                        {mData: 'id'},
                        {mData: 'Tenderno'},
                        {mData: 'Name'},
                        {mData: 'Org_name'},
                        {mData: 'MobileNo'},
                        {mData: 'EmailId'}
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

  
 
 
</script>