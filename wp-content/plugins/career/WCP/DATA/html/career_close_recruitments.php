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
	td:first-child,td:last-child,th{
		text-align:center;
	}
	a:focus, a:hover{
		text-decoration:none;
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
	.dataTables_filter{
		position:relative;
		right:20px;
	}
	.dataTables_filter input{
		    width: auto !important;
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
 
 
 
<div class="container  investor-page-acf " style="padding-top:20px;">
    <div class="col-sm-12">
        <div class="col-sm-12 closure_of_trading_window">
            <h2>Closed Recruitments</h2>
        </div>
     
        

        
        <div class="table-responsive" style="display: block;width: 98%;margin: 0 auto;">
            <table id='corr-table' class="table table-bordered">
                <thead>
                    <tr>
                         <th class="all" >Sl No.</th>
                        <th class="all">Advertisement No.</th>
                        <th class="all" style="width:40%;">Job Description</th>
                        <th class="all">Last Date</th> 
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
 
<script>
 
    $(document).ready(function () {
      
        $ = jQuery;
        reload_table();
        var error_count=0;
       
    });
    
 

 
  function reload_table(loc=null) {
  
        $('#corr-table').dataTable({
                    "paging": true,
                    "pageLength": 20,
                    "bProcessing": false,
                     "bLengthChange": false,
                    "serverSide": false,
                     "bDestroy": true,
                     "searching": true,
					 "oLanguage": {

					"sSearch": "Search By Advertisement No.:"

					},
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {"action": "career_close_recruitments"}

                    },
                    "aoColumns": [
                      
                        {mData: 'id'},
                        {mData: 'Advertisement_No'}, 
                        {mData: 'Description'},
                        {mData: 'CloseingDate'},
                    ],
            "ordering": true,        
                     "order": [[3, 'desc']],
                    "columnDefs": [{
                        "targets": [3],
                        "orderable": true
                    }],
                    "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                    
                  }
            });  
    }
  
  
 
</script>