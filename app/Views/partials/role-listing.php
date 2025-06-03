<?php echo view('template\partial-header'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
<?php 
$roles=json_decode(json_encode($roles));
$status = ['A'=>'Active','I'=>'Inactive'];
?>
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-sm-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <strong><h4>Manage Roles</h4></strong>
                        </div>
                        <div class="card-body">
                            <a href="#" id="new-role" data-toggle="modal" data-target="#role-modal" class="btn btn-xs btn-success">Create New Role</a>
                            <div class="table-responsive">
                                <table class="table data-table table-bordered table-striped table-hover table-sm" width="100%">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><strong><small>Role Name</small></strong></th>
                                            <th><strong><small>Role Status</small></strong></th>
                                            <th><strong><small>Created</small></strong></th>
                                            <th><strong><small>Modified</small></strong></th>
                                            <th><strong><small>Deletion</small></strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (!$roles){
                                                # code...
                                            }else{ 
                                            foreach ($roles as $role) {?>
                                                <tr>
                                                    <td>
                                                        <small> 
                                                            <a href="#" data-url="<?php echo base_url('administration/fetch-rights'); ?>" data-roleid="<?php echo $role->role_id;?>" data-rolename="<?php echo $role->role_name ;?>" data-rolestatus="<?php echo $role->role_status;?>" data-desc="<?php echo $role->role_desc;?>" data-toggle="modal" data-target="#role-modal" class="get-role-rights" >
                                                                <?php echo $role->role_name ;?>
                                                            </a>
                                                        </small>
                                                    </td>
                                                    <td><small><?php echo $status[$role->role_status];?></small></td>
                                                    <td><small><?php echo $role->create_date ;?></small></td>
                                                    <td><small><?php echo $role->modify_date;?></small> </td>
                                                    <td><small><?php echo $role->delete_date ;?></small></td>
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

<div class="modal fade" id="role-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="roleModalLabel">New Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
           
        <div class="modal-body">
            <div class="card card-warning card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-create-role" role="tab" aria-controls="custom-tabs-create-role" aria-selected="true">Role Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-role-rights" role="tab" aria-controls="custom-tabs-role-rights" aria-selected="false">Role Rights</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-create-role" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                            <form method="post" class="db-submit" action="<?php echo base_url('administration/role-save');?>" data-initmsg="Sending Role Data...">   
                                <?php echo csrf_field() ?>
                                <input type="hidden" name="role-id" id="role-id">      
                                <div class="card">
                                    <div class="card-header">
                                        <h4 id="role-action">Create Role</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="role-name" class="col-form-label">Role Name:</label>
                                            <input type="text" class="form-control" id="role-name" name="role-name">
                                        </div>
                                        <div class="form-group">
                                            <label for="role-status" class="col-form-label">Role Status:</label>
                                            <select type="text" class="form-control" id="role-status" name="role-status">
                                                <option value="A">Active</option>
                                                <option value="I">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="role-desc" class="col-form-label">Description:</label>
                                            <textarea class="form-control" id="role-desc" name="role-desc"></textarea>
                                        </div>        
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary float -right">Add Role</button>
                                    </div> 
                                </div>
                            </form>    
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-role-rights" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                            <form action="<?php echo base_url('administration/save-role-rights');?>" class="db-submit"  method="post" data-initmsg="Changing Rights...">
                                <div class="card">                                
                                    <div class="card-body">
                                        <h4>Change Rights for: <span id="spn-role-name"></span></h4>
                                            <input type="hidden" id="entity-id" name="entity-id">
                                            <input type="hidden" id="entity-type" name="entity-type" value="R">                                  
                                        <div id="roles">
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary float-right">Save Settings</button>
                                    </div> 
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
                <!-- /.card -->
                </div>
        </div>
    </div>
</div>
</div>
<?php echo view('template\partial-footer'); ?>