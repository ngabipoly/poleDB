<?php echo view('template\partial-header'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
<?php 
$users=json_decode(json_encode($users));
$activity = ['1'=>'Inactive', '2'=>'Active','3'=>'Deactivated']
?>
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-sm-12">
                    <div class="card card-warning">
                        <div class="card-header" >
                           <strong> Management</strong> 
                        </div>
                        <div class="card-body">
                        <a href="#" id="new-user" data-toggle="modal" data-target="#user-modal" class="btn btn-xs btn-success">Add User</a>
                        
                            <div class="table-responsive mt-2">
                                <table class="table data-tables table-bordered table-striped table-hover table-sm" width="100%">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><strong><small>User PF</small></strong></th>
                                            <th><strong><small>First Name</small></strong></th>
                                            <th><strong><small>Last Name</small></strong></th>
                                            <th><strong><small>Email</small></strong></th>
                                            <th><strong><small>Role</small></strong></th>
                                            <th><strong><small>Status</small></strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (!$users){
                                                # code...
                                            }else{ 
                                            foreach ($users as $user) {?>
                                                <tr>
                                                    <td><small>
                                                        <a href="#" class="user-edit" data-toggle="modal" data-target="#user-modal" data-uid="<?php echo $user->id;?>" data-pf="<?php echo $user->user_pf ;?>" data-fname="<?php echo $user->firstname ;?>" data-lname="<?php echo $user->lastname ;?>" data-uemail="<?php echo $user->email;?>" data-urole="<?php echo $user->role_id;?>" data-ustatus="<?php echo $user->active;?>">
                                                           <?php echo $user->user_pf ;?> 
                                                        </a>                                                        
                                                    </small></td>
                                                    <td><small style="text-transform:capitalize;"><?php echo strtolower( $user->firstname) ;?></small></td>
                                                    <td><small style="text-transform:capitalize;"><?php echo strtolower($user->lastname) ;?></small></td>
                                                    <td><small><?php echo strtolower($user->email);?></small> </td>
                                                    <td><small><?php echo $user->role_name ;?></small></td>
                                                    <td><small><?php echo isset($activity[$user->active])? $activity[$user->active] : $user->active ;?></small></td>
                                                </tr>                                   
                                            <?php   
                                            } 
                                        } ?>
                                            
                                    </tbody>
                                </table>
                            </div>                            
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php echo view('template\partial-footer'); ?>
    
    <div class="modal fade" id="user-modal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="user-mgr-h">New User</h4>
              <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url('administration/usr-admin');?>" id="frm-user-mgt" method="post" class="db-submit" data-initmsg="Saving User...">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="uid" id="uid">
                <div class="modal-body">
                    <div class="form-group row">
                       <label for="pf-number" class="col-sm-3 col-form-label">PF Number</label>
                       <div class="col-sm-6">
                            <input type="text" class="form-control required" name="pf-number" id="pf-number" placeholder="PF Number">
                       </div> 
                    </div>
                    <div class="form-group row">
                       <label for="first-name" class="col-sm-3 col-form-label">First Name</label>
                       <div class="col-sm-6">
                            <input type="text" class="form-control required" name="first-name" id="first-name" placeholder="First Name">
                       </div> 
                    </div>
                    <div class="form-group row">
                       <label for="last-name" class="col-sm-3 col-form-label">Last Name</label>
                       <div class="col-sm-6">
                            <input type="text" class="form-control" name="last-name" id="last-name" placeholder="Last Name">
                       </div> 
                    </div>                
                    <div class="form-group row">
                        <label for="user-email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control required" id="user-email" name="user-email" placeholder="User Email">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user-role" class="col-sm-3 col-form-label">Role</label>
                        <div class="col-sm-6">
                            <select class="form-control required" name="user-role" id="user-role">
                                <option value="" selected>Select User Role</option>
                                <?php 
                                    if ($roles) {
                                        foreach ($roles as $role) { ?>
                                            <option value="<?php echo $role['role_id'];?>"><?php echo $role['role_name'] ;?></option>
                                     <?php   }
                                    }
                                ?>
                            </select>
                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <label for="user-status" class="col-sm-3 col-form-label">User Status</label>
                        <div class="col-sm-6">
                            <select name="user-status" id="user-status" class="form-control required">
                                <option value="" selected>Set User Status</option>
                                <option value="1">Inactive</option>
                                <option value="2">Active</option>
                                <option value="3">Deactive</option>
                            </select>
                        </div>
                    </div>
                </div>  
                <div id="rtn-errors"></div>              
                <div class="modal-footer">
                    <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                    <button type="button" id="reset-pwd" class="btn btn-danger">Reset Password</button>
                    <button type="submit" class="btn btn-primary float-right">Save changes</button>
                </div>            
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->