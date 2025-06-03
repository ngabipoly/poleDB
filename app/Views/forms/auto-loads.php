<?php echo view('template\partial-header'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="callout callout-info mt-2">
              <h5><i class="fas fa-info"></i> Note:</h5>
              For in bulk Loading, please download and fill the template to minimize errors!
              <?php 
                $list_group=[];
                $list = array_filter($lists = $user['lists'] , function($item){
                    return $item['menu_category'] != 'auto-load' &&  $item['menu_category'] != 'bulk-auto-load'  ;
                });

                $has_number_auto_load = array_filter($lists = $user['lists'] , function($item){
                    return $item['menu_category'] == 'auto-load';
                });

                $has_bulk_auto_load = array_filter($lists = $user['lists'] , function($item){
                    return $item['menu_category'] == 'bulk-auto-load';
                });

                if($list){
                    foreach ($list as $item ){
                        # group list items
                        $list_group[$item['menu_category']][]=$item;
                    }                    
                }
              ?>
            </div>

            
            
        <div class="row">
            <div class="col-md-4">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Actions</strong> </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!-- Begin Collapsible Loadings here -->
                        <div id="loadings">
                        <?php
                            if($has_number_auto_load){ ?>
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title w-100"> <a class="d-block w-100" data-toggle="collapse" href="#loadNumbers"><strong>Auto Load Numbers</strong> </a>  </h3>                                              
                                </div>
                                <div id="loadNumbers" class="collapse" data-parent="#loadings">
                                    <form id="frm-loadings-m" method="post" action="<?php echo base_url('front-office/wb-add-loading');?>">
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 lg-6" id="load-status">

                                                </div>
                                            </div>
                                            <div class="row" id="num-add">
                                                <div class="col-md-12 col-lg-6">
                                                <!-- text input -->
                                                    <div class="form-group">
                                                        <label>MSISDN</label>
                                                        <input type="number" id="msisdn" class="form-control form-control-sm required entries" placeholder="Enter Phone Number" data-emptymsg="Please Enter an MSISDN">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-lg-6">
                                                    <div class="form-group">
                                                        <label>Amount</label>
                                                        <div class="input-group">
                                                            <input type="number" id="load-amt" class="form-control form-control-sm  required entries" placeholder="Enter Amount" data-emptymsg="Please Enter an Amount">
                                                            <div class="input-group-append">
                                                                <button id="btn-add-number" data-amount="#load-amt" data-parent='#frm-loadings-m' data-source ="#msisdn" data-target="#loader-area"  class="btn btn-sm btn-primary btn-add-number">Add</button>
                                                            </div>
                                                        </div>                                                    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row p-0">
                                                <div class="col-md-12">
                                                <!-- textarea -->
                                                    <div class="form-group">
                                                        <label>Load Area</label>
                                                        <textarea class="form-control form-control-sm " name="loader-data" id="loader-area" rows="" readonly></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button class="btn btn-sm btn-danger float-left" id="btn-post-loading" type="reset"> <strong> Cancel </strong></button>
                                            <button class="btn btn-sm btn-success float-right" id="btn-post-loading"  data-url="<?php echo base_url('front-office/wb-add-loading');?>"> <strong> Post Loading </strong></button>
                                        </div>
                                    </form>                    
                                </div>
                            </div>                            

                         <?php   } 

                         if($has_bulk_auto_load){ ?>
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title w-100">
                                            <a href="#bulk-uploads" data-toggle="collapse" class="d-block w-100">
                                                <strong>Bulk Auto Load</strong>
                                            </a>
                                        </h3>
                                    </div>
                                    <div id="bulk-uploads" class="collapse" data-parent="#loadings">
                                        <div class="card-body">
                                            <div id="upload-status"></div>
                                            <form id="upload-form" method="post" action="<?php echo base_url('front-office/wb-bulk-loading');?>" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label for="load-input">Choose csv File</label>                                                    
                                                        
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="load-input" class="custom-file-input" id="load-input" accept=".csv" >
                                                            <label class="custom-file-label form-control-sm " for="load-input">Choose file</label>  
                                                            <input type="hidden" name="action-type" value="auto_load" id="auto-ld">
                                                        </div>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-success" id="upload-details" type="submit">Upload</button>
                                                        </div>
                                                    </div>
                                                </div>                                      
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } 
                            
                            if($list){ ?>
                                <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <a data-toggle="collapse" href="#other-actions">
                                                    <strong> Other Actions </strong>
                                                </a>                                            
                                            </h3>
                                        </div>
                                        <div id="other-actions" class="collapse" data-parent="#loadings">
                                            <div class="card-body p-0" id="misc-actions">
                                                <div id="messages"></div>
                                                <form action="<?php echo base_url('front-office/bulk-loading-msc');?> " method="post" id="frm-misc-actions" class="form" enctype="multipart/form-data">
                                                    <div class="row">
                                                            <div class="col-sm-11 m-3">
                                                                <div class="form-group">
                                                                    <label for="o-type">Operation Type</label>
                                                                    <select name="o-type" id="o-type" class="form-control form-control-sm">
                                                                        <option value="I">Single Entry</option>
                                                                        <option value="B">Bulk Upload</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="action-key"> Action Type </label>
                                                                    <select name="action-key" id="action-key" name="action-key" class="form-control form-control-sm ">
                                                                        <option value="">---Select Action---</option>
                                                                    <?php
                                                                            foreach ($list_group as $category => $items) {
                                                                                $category = explode('-',$category);
                                                                                $category = ucwords(implode(" ",$category));
                                                                                echo '<optgroup label="' . htmlspecialchars($category) . '">';
                                                                                foreach ($items as $item) {
                                                                                    echo '<option value="' . htmlspecialchars($item['url']) . '">' . htmlspecialchars($item['menu_name']) . '</option>';
                                                                                }
                                                                                echo '</optgroup>';
                                                                            }
                                                                    ?>
                                                                    </select>
                                                                </div>
                                                                <div id="single-entry-ops">
                                                                    <div class="form-group">
                                                                        <label for="o-msisdn">MSISDN</label>
                                                                        <div class="input-group">
                                                                            <input type="o-number" id="o-msisdn" name="o-msisdn" class="form-control form-control-sm required entries" placeholder="Enter Phone Number" data-emptymsg="Please Enter an MSISDN">
                                                                            
                                                                            <div class="input-group-append">
                                                                                <button id="btn-o-queue" data-parent="#frm-misc-actions" data-source ="#o-msisdn" data-target="#o-queue" class="btn btn-sm btn-primary btn-add-number">Add to Queue</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="o-queue">Number Queue</label>
                                                                        <textarea class="form-control o-queue" name="o-queue" id="o-queue" rows="6" aria-readonly="readonly" readonly></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <button class="btn btn-sm btn-success" id="misc-exec" data-url="<?php echo base_url('front-office/single-loading-msc');?>">Execute</button>
                                                                        <button class="btn btn-sm btn-default float-right" type="reset">Reset</button>
                                                                    </div>   
                                                                </div>
                                                            <div class="o-bulk-upload" id="bulk-ops" hidden>
                                                                <div class="form-group">
                                                                    <label for="action-file">Upload File</label>
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input type="file" name="action-file" class="custom-file-input form-control-sm " id="action-file" accept=".csv" >  
                                                                            <label class="custom-file-label" for="action-file">Choose file</label>                                                      
                                                                        </div>
                                                                        <div class="input-group-append">
                                                                            <button class="btn btn-success" id="upload-act-file" type="submit">Upload</button>
                                                                        </div>
                                                                    </div>
                                                                </div>                                             
                                                            </div>                                                                                                     
                                                            </div>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>

                                    </div>
                          <?php  }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><strong> Loadings Status </strong></h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-2" id="file-status-contents">
                    <div class="table-responsive">
                        <table id="data-table" class="table table-bordered table-striped table-hover table-sm" width="100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th><strong><small>RefNo</small></strong></th>
                                    <th><strong><small>Source File</small></strong></th>
                                    <th><strong><small>IN File</small></strong></th>
                                    <th><strong><small>PF Number</small></strong></th>
                                    <th><strong><small>Submitted</small></strong></th>
                                    <th><strong><small>Status</small></strong></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot class="thead-dark">
                                <tr>
                                    <th><strong><small>RefNo</small></strong></th>
                                    <th><strong><small>Source File</small></strong></th>
                                    <th><strong><small>IN File</small></strong></th>
                                    <th><strong><small>PF Number</small></strong></th>
                                    <th><strong><small>Submitted</small></strong></th>
                                    <th><strong><small>Status</small></strong></th> 
                                </tr>                           
                            </tfoot>
                        </table>                        
                    </div>

                </div>
                <!-- /.card-body -->
                </div>
            </div>
        </div>


          </div><!-- /.col -->
        </div><!-- /.row -->
            <div class="modal fade" id="details-modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Load Report</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="file-details">

                            </div>
                        </div>
                        <!-- /.end body -->
                        <!-- this row will not appear when printing -->
                        <div class="modal-footer">
                            <div class="col-12">
                                <a href="#" id="print-report" data-report="#file-details" class="btn btn-success float-right"><i class="fas fa-print"></i> Print</a>
                            </div>
                        </div>
                    </div>
                <!-- /.modal-content -->
                </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
      </div><!-- /.container-fluid -->
    </section>
    <?php echo view('template\partial-footer'); ?>