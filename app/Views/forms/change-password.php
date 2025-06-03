<?php echo view('template\partial-header'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
<?php 

?>
<div class="h-100 d-flex align-items-center justify-content-center">
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-sm-12">
                    <div class="card card-warning">
                        <div class="card-header" >
                           <strong> Change Password</strong> 
                        </div>
                        <form class="form-horizontal db-submit" action="<?php echo base_url('administration/save-new-pwd');?>" method="post" data-initmsg="Changing Password...">
                        <?php echo csrf_field() ?>
                        <input type="hidden" name="uid" id="uid" value="<?php echo $user['id'];?>">                        
                        <input type="hidden" name="forced" id="forced" value="<?php echo $user['force_change'];?>">                        
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="u-phrase" class="col-form-label">Current Password</label>
                                    <div class="col-sm-12">
                                    <input type="password" class="form-control" id="u-old-phrase" name="u-old-phrase" placeholder="Current Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="u-phrase" class="col-form-label">New Password</label>
                                    <div class="col-sm-12">
                                    <input type="password" class="form-control" id="u-phrase" name="u-phrase" placeholder="New Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="repeat-phrase" class="col-form-label">Repeat Password</label>
                                    <div class="col-sm-12">
                                    <input type="password" class="form-control" id="repeat-phrase" name="repeat-phrase" placeholder="Repeat Password">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info folat-right mr-2">Change Password</button>
                                <button type="reset" class="btn btn-default float-left mr-4">Clear Entries</button>
                            </div>
                            <!-- /.card-footer -->
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

    <?php echo view('template\partial-footer'); ?>