<?php echo view('template/partial-header'); ?>
 <div class="content-wrapper">
    <!-- Page header and breadcrumb -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Media Capacity Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?php echo base_url('dashboard'); ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Media Capacity Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main page content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Media Capacity Listing</h3>
                            <div class="card-tools">
                                <button type="button"
                                        class="btn btn-primary btn-xs mt-2 mb-2 rounded-25"
                                        id="add-media-capacity"
                                        data-toggle="modal"
                                        data-target="#media-capacity-modal"
                                        data-action="add">
                                    <i class="fas fa-plus-circle"></i> Add Media Capacity
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="media-capacity-table"
                                       class="table table-bordered table-striped table-hover table-sm display data-table nowrap"
                                       width="100%">
                                    <caption class="sr-only">Media Capacity Listing</caption>
                                    <thead class="thead-dark">
                                        <tr class="text-sm">
                                            <th>ID</th>
                                            <th>Media Type</th>
                                            <th>Capacity</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($carryCapacities as $media_capacity): ?>
                                            <tr class="text-sm">
                                                <td><?php echo esc($media_capacity->carryCapacityId); ?></td>
                                                <td><?php echo esc($media_capacity->carryTypeName); ?></td>
                                                <td><?php echo esc($media_capacity->capacityLabel); ?></td>
                                                <td><?php echo esc($media_capacity->CapacityDescription); ?></td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm edit-media-capacity rounded-circle p-1"
                                                            data-id="<?php echo esc($media_capacity->carryCapacityId); ?>"
                                                            data-label="<?php echo esc($media_capacity->capacityLabel); ?>"
                                                            data-type-id="<?php echo esc($media_capacity->carryTypeId); ?>"
                                                            data-description="<?php echo esc($media_capacity->CapacityDescription); ?>"
                                                            data-action="edit" data-toggle="modal"
                                                            data-target="#media-capacity-modal" title="Edit Media Capacity">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-media-capacity rounded-circle p-1"
                                                            data-id="<?php echo esc($media_capacity->carryCapacityId); ?>"
                                                            data-label="<?php echo esc($media_capacity->capacityLabel); ?>"
                                                            data-toggle="modal"
                                                            data-target="#delete-capacity-modal" title="Delete Media Capacity">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div> <!-- /.table-responsive -->
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div> <!-- /.col-12 -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->


    <!-- Media Capacity Modal -->
    <div class="modal fade" id="media-capacity-modal" tabindex="-1" role="dialog" aria-labelledby="media-capacity-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="media-capacity-modalLabel">Media Capacity Management</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="media-capacity-form" class="form-horizontal db-submit" method="post" action="<?php echo base_url('infrastructure/save-media-capacity'); ?>" data-initmsg="Saving Media Capacity Details...">
                    <input type="hidden" name="formType" value="carryCapacity">
                    <input type="hidden" name="carryCapacityId" id="carryCapacityId" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="carryTypeId">Media Type</label>
                            <select class="form-control" id="carryTypeId" name="carryTypeId" required>
                                <option value="">Select Media Type</option>
                                <?php foreach ($carryTypes as $type): ?>
                                    <option value="<?php echo esc($type->carryTypeId); ?>"><?php echo esc($type->carryTypeName); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="carryCapacityLabel">Capacity Label</label>
                            <input type="text" class="form-control" id="carryCapacityLabel" name="carryCapacityLabel" required>
                        </div>
                        <div class="form-group">
                            <label for="carryCapacity">Media Capacity</label>
                            <input type="number" class="form-control" id="carryCapacity" name="carryCapacity" required>
                        </div>
                        <div class="form-group">
                            <label for="carryCapacityDescription">Description</label>
                            <textarea class="form-control" id="carryCapacityDescription" name="carryCapacityDescription"></textarea>
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

    <!-- Delete Media Capacity Modal -->
    <div class="modal fade" id="delete-capacity-modal" tabindex="-1" role="dialog" aria-labelledby="delete-capacity-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="delete-capacity-modalLabel">Delete Media Capacity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="delete-capacity-form" class="form-horizontal db-submit" method="post" action="<?php echo base_url('infrastructure/delete-media-capacity'); ?>" data-initmsg="Delete Media Capacity">
                    <div class="modal-body">
                        <p>Are you sure you want to delete <span class="text-bold" id="delCapacityLabel"></span> media capacity?</p>
                        <input type="hidden" name="deleteCarryCapacityId" id="deleteCarryCapacityId" value="">
                        <input type="hidden" name="deleteCarryCapacityLabel" id="deleteCarryCapacityLabel" value="">
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