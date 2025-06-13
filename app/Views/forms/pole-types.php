<?php echo view('template/partial-header'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pole Size Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Pole Size Management</li>
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
                            <h3 class="card-title">Pole Size List</h3>
                            <div class="card-tools">
                                <a href="#" class="btn btn-primary btn-sm float-right add-pole-size" data-toggle="modal" data-target="#poleSizeModal"><i class="fas fa-plus-circle"></i> Add Pole Size</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm " width="100%">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-sm">Size Label</th>
                                            <th class="text-sm">Size (Meters)</th>
                                            <th class="text-sm">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($poleSizes): ?>
                                            <?php foreach ($poleSizes as $size): ?>
                                                <tr>
                                                    <td class="text-sm"><?php echo $size['SizeLabel']; ?></td>
                                                    <td class="text-sm"><?php echo $size['SizeMtrs']; ?></td>
                                                    <td class="text-sm">
                                                        <button class="btn btn-sm btn-warning edit-pole-size" data-id="<?php echo $size['poleSizeId']; ?>" data-label="<?php echo $size['SizeLabel']; ?>" data-size="<?php echo $size['SizeMtrs']; ?>" data-toggle="modal" data-target="#poleSizeModal"><i class="fas fa-edit"></i> Edit</button>
                                                        <button class="btn btn-sm btn-danger delete-pole-size" data-id="<?php echo $size['poleSizeId']; ?>" data-label="<?php echo $size['SizeLabel']; ?>" data-size="<?php echo $size['SizeMtrs']; ?>" data-toggle="modal" data-target="#deletePoleSizeModal"><i class="fas fa-trash"></i> Delete</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">No Pole Sizes Found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="poleSizeModal" tabindex="-1" role="dialog" aria-labelledby="poleSizeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary disabled">
                    <h5 class="modal-title" id="poleSizeModalLabel">Add New Pole Size</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="poleSizeForm" method="POST" action="<?php echo base_url('pole-management/save-pole-type'); ?>" data-initmsg="Saving Pole Size Details..." class="db-submit">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="poleSizeId" name="poleSizeId">
                    <div class="modal-body px-3">
                        <div class="form-group">
                            <label for="poleType">Pole Type</label>
                            <input type="text" class="form-control" id="poleType" name="poleType" placeholder="Enter Pole Type" required>
                        </div>
                        <div class="form-group">
                            <label for="poleSize">Pole Size (Meters)</label>
                            <input type="number" class="form-control" id="size-meteres" name="sizeMeteres" placeholder="Enter Pole Size" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deletePoleSizeModal" tabindex="-1" role="dialog" aria-labelledby="deletePoleSizeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="deletePoleSizeModalLabel"><i class="fas fa-exclamation-circle"></i> Delete Pole Size</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deletePoleSizeForm" method="POST" action="<?php echo base_url('pole-management/delete-pole-type'); ?>" data-initmsg="Deleting Pole Size..." class="db-submit">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body px-3">
                        <p class="">
                            </i> Are you sure you want to delete this pole?
                            <p  class="text-center font-weight-bold"> 
                                ID: <span id="deletePoleId"></span> -
                                Size Label: <span id="deletePoleSizeLabel"></span> -  
                                Height: <span id="deletePoleSize"></span> Meters
                            </p>
                        </p>
                        <input type="hidden" id="delPoleSizeId" name="delPoleSizeId">
                        <input type="hidden" id="delPoleLabel" name="delPoleLabel">
                        <input type="hidden" id="delSizeMeteres" name="delSizeMeteres">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php echo view('template/partial-footer'); ?>