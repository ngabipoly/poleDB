<?php echo view('template\partial-header'); ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>District Management</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
                            <li class="breadcrumb-item active">District Management</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Manage Districts</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-xs btn-radius-5 btn-primary" id="add-district" data-toggle="modal" data-target="#district-modal">
                                        <i class="fas fa-plus"></i> Add District
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="districts-table" class="table table-bordered table-striped table-hover data-table">
                                            <thead class="text-sm text-dark">
                                                <tr>
                                                    <th class="text-sm">ID</th>
                                                    <th class="text-sm">Region Code</th>
                                                    <th class="text-sm">Region Name</th>
                                                    <th class="text-sm">District Code</th>
                                                    <th class="text-sm">District Name</th>
                                                    <th class="text-sm">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    foreach ($districts as $district) {
                                                        echo "<tr>
                                                                <td class='text-sm'>{$district['districtId']}</td>
                                                                <td class='text-sm'>{$district['RegionCode']}</td>
                                                                <td class='text-sm'>{$district['RegionName']}</td>
                                                                <td class='text-sm'>{$district['code']}</td>
                                                                <td class='text-sm'>{$district['districtName']}</td>
                                                                <td class='text-sm'>
                                                                    <button class='btn btn-info btn-xs edit-district' data-toggle='modal' data-target='#district-modal'  data-district-id='{$district['districtId']}' data-district-region-code='{$district['RegionCode']}' data-district-region-name='{$district['RegionName']}' data-district-code='{$district['code']}' data-district-name='{$district['districtName']}' data-region-id='{$district['region_id']}'><i class='fas fa-edit'></i></button>
                                                                    <button class='btn btn-danger btn-xs delete-district' data-district-id='{$district['districtId']}' data-district-name='{$district['districtName']}' data-region-name='{$district['RegionName']}' data-toggle='modal' data-target='#delete-modal'> <i class='fas fa-trash'></i></button>
                                                                </td>
                                                              </tr>";
                                                    }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Region Code</th>
                                                    <th>Region Name</th>
                                                    <th>District Code</th>
                                                    <th>District Name</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
        <div class="modal fade" id="district-modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="district-mgr-h"><span id="district-action">Add</span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="district/save" id="frm-district-mgt" method="post" class="db-submit" data-initmsg="Saving District...">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <input type="hidden" name="district_id" id="district-id">
                        <div class="form-group">
                            <label for="region-id" class="col-form-label">Region:</label>
                            <select id="region-id" name="region_id" class="form-control" required>
                                <?php 
                                    foreach ($regions as $region) {
                                        echo "<option value='$region->RegionId'>$region->RegionName</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="district-name" class="col-form-label">District Name:</label>
                            <input type="text" class="form-control" id="district-name" name="district_name" required>
                        </div>
                        <div class="form-group">
                            <label for="district-code" class="col-form-label">District Code:</label>
                            <input type="text" class="form-control" id="district-code" name="district_code" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save District</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="delete-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete District</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="district/delete" method="post" class="db-submit" data-initmsg="Deleting District..." id="frm-delete-district">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <p><i class="fas fa-exclamation-triangle text-danger"></i> Are you sure you want to delete <span id="spn-district-name"></span> district in  <span id="spn-region-name"></span> Region?</p>
                            <input type="hidden" name="delete_district_id" id="delete-district-id">
                            <input type="hidden" name="delete_district_name" id="delete-district-name">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php echo view('template\partial-footer'); ?>