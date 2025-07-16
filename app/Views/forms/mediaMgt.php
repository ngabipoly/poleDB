<?php 
    echo view('template/partial-header');
?>
<div class="content-wraper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Media Type Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Media Type Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Media Type Listing Gibs</h3>
                        </div>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool float-right btn-primary btn-sm rounded-25" data-card-widget="collapse"><i class="fas fa-plus-circle"></i> Add Media Type</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                        User: <?php echo json_encode($user); ?>
                                    <div class="table-responsive">
                                        <table id="media-type-table" class="table table-bordered table-striped table-hover table-sm display data-table nowrap" width="100%">
                                            <caption class="sr-only">Media Type Listing</caption>
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($media_types as $media_type): ?>
                                                    <tr>
                                                        <td><?php echo esc($media_type['carryTypeId']); ?></td>
                                                        <td><?php echo esc($media_type['carryTypeName']); ?></td>
                                                        <td><?php echo esc($media_type['carryTypeDescription']); ?></td>
                                                        <td>
                                                            <button class="btn btn-primary btn-sm edit-media-type rounded-circle p-2"
                                                                data-id="<?php echo esc($media_type['carryTypeId']); ?>"
                                                                data-name="<?php echo esc($media_type['carryTypeName']); ?>"
                                                                data-description="<?php echo esc($media_type['carryTypeDescription']); ?>" data-action="edit"
                                                                data-toggle="modal"
                                                                data-target="#media-type-modal"
                                                                title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-sm delete-media-type rounded-circle p-2"
                                                                data-id="<?php echo esc($media_type['carryTypeId']); ?>"
                                                                data-action="delete"
                                                                data-toggle="modal"
                                                                data-target="#delete-type-modal"
                                                                title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Media Type Modal -->
<div class="modal fade" id="media-type-modal" tabindex="-1" role="dialog" aria-labelledby="media-type-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="media-type-modalLabel">
                    <span class="modal-action">Add</span> Media Type
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="media-type-form" method="post" action="<?php echo base_url('media-management/save'); ?>">
                    <input type="hidden" name="carryTypeId" id="carryTypeId" value="">
                    <div class="form-group">
                        <label for="carryTypeName">Media Type Name</label>
                        <input type="text" class="form-control" id="carryTypeName" name="carryTypeName" placeholder="Enter Media Type Name" required>
                    </div>
                    <div class="form-group">
                        <label for="carryTypeDescription">Media Type Description</label>
                        <textarea class="form-control" id="carryTypeDescription" name="carryTypeDescription" rows="3" placeholder="Enter Media Type Description" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Media Type Modal -->
<div class="modal fade" id="delete-type-modal" tabindex="-1" role="dialog" aria-labelledby="delete-type-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="delete-type-modalLabel">Delete Media Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this media type?</p>
            </div>
            <div class="modal-footer">
                <form id="delete-media-type-form" method="post" action="<?php echo base_url('media-management/delete'); ?>">
                    <input type="hidden" name="carryTypeId" id="deleteCarryTypeId" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->

<?php 
    echo view('template/partial-footer');
?>