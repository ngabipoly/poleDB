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
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#district-modal">
                                        <i class="fas fa-plus"></i> Add District
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="districts-table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>District Code</th>
                                                    <th>District Name</th>
                                                    <th>Region Name</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
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
                        <h4 class="modal-title" id="district-mgr-h">New District</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?php echo base_url('districts/save');?>" id="frm-district-mgt" method="post" class="db-submit" data-initmsg="Saving District...">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="region-id" class="col-form-label">Region:</label>
                            <select id="region-id" name="region_id" class="form-control" required>
                                <?php 
                                    $regionObj = new \App\Models\RegionModel();
                                    $regions = $regionObj->findAll();
                                    foreach ($regions as $r) {
                                        echo "<option value='$r->id'>$r->region_name</option>";
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
    <?php echo view('template\partial-footer'); ?>