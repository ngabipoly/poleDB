<?php echo view('/template/partial-header'); ?>
    <div class="content-wrapper">
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
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Media Type Management</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-xs float-rightbtn-sm mt-2 mb-2 rounded-25" id="add-media-type" data-toggle="modal" data-target="#media-type-modal" data-action="add"><i class="fas fa-plus-circle"></i> Add Media Type</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
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
                                                    <?php foreach ($carryTypes as $media_type): ?>
                                                        <tr>
                                                            <td><?php echo esc($media_type->carryTypeId); ?></td>
                                                            <td><?php echo esc($media_type->carryTypeName); ?></td>
                                                            <td><?php echo esc($media_type->carryTypeDescription); ?></td>
                                                            <td>
                                                                <button class="btn btn-primary btn-sm edit-media-type rounded-circle p-1"
                                                                    style="height: 28px; width: 28px;"
                                                                    data-id="<?php echo esc($media_type->carryTypeId); ?>"
                                                                    data-name="<?php echo esc($media_type->carryTypeName); ?>"
                                                                    data-description="<?php echo esc($media_type->carryTypeDescription); ?>" data-action="edit"
                                                                    data-toggle="modal"
                                                                    data-target="#media-type-modal"
                                                                    title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <!-- Add delete button if needed -->
                                                                <button class="btn btn-danger btn-sm delete-media-type rounded-circle p-1"
                                                                    style="height: 28px; width: 28px;"
                                                                    data-delete-media-type-id="<?php echo esc($media_type->carryTypeId); ?>"
                                                                    data-delete-media-type-name="<?php echo esc($media_type->carryTypeName); ?>"
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
                    <h5 class="modal-title" id="media-type-modalLabel">Add/Edit Media Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="media-type-form" class="form-horizontal needs-validation db-submit" method="post" action="<?php echo base_url('infrastructure/save-media'); ?>" data-action="add" data-initmsg="Saving Media Type Details...">
                    <div class="modal-body">
                        <input type="hidden" name="carryTypeId" id="carryTypeId" value="">
                        <input type="hidden" name="formType" value="carryType">
                        <div class="form-group">
                            <label for="carryTypeName">Media Type Name</label>
                            <input type="text" class="form-control" id="carryTypeName" name="carryTypeName" required>
                        </div>
                        <div class="form-group">
                            <label for="carryTypeDescription">Description</label>
                            <textarea class="form-control" id="carryTypeDescription" name="carryTypeDescription"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Media Type Modal -->
    <div class="modal fade" id="delete-type-modal" tabindex="-1" role="dialog" aria-labelledby="delete-type-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="delete-type-modalLabel">Delete Media Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="delete-type-form" class="form-horizontal db-submit" method="post" action="<?php echo base_url('infrastructure/delete-media-type'); ?>" data-initmsg="Deleting Media Type...">
                    <div class="modal-body">
                        <p>Are you sure you want to delete <span id="delMediaTypeName"></span> media type?</p>
                        <input type="hidden" name="deleteCarryTypeId" id="deleteCarryTypeId" value="">
                        <input type="hidden" name="deleteCarryTypeName" id="deleteCarryTypeName" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php echo view('template/partial-footer'); ?>
