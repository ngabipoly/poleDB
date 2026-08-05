<?php echo view('template'.DIRECTORY_SEPARATOR.'partial-header', ['title' => 'Leasor Detail Management']); ?>
<div class="content-wrapper">
    <!-- Page Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-broadcast-tower"></i> Leasor Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('dashboard'); ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Leasor Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">    
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leasor Details</h3>
                    <button class="btn btn-primary btn-sm float-right" id="btn-add-leasor" data-toggle="modal" data-target="#leasorModal"><i class="fas fa-plus"></i> Add Leasor</button>
                </div>
                <div class="card-body">
                    <table id="leasorTable" class="table table-bordered table-striped dataTable dtr-inline">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Contact Person</th>
                                <th>Contact Number</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leasors as $leasor): ?>
                                <tr>
                                    <td><?= $leasor['id']; ?></td>
                                    <td><?= $leasor['name']; ?></td>
                                    <td><?= $leasor['contact_person']; ?></td>
                                    <td><?= $leasor['contact_number']; ?></td>
                                    <td><?= $leasor['contact_email']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info btn-edit-leasor" data-toggle="modal" data-target="#leasorModal" data-leasor-id="<?php echo $leasor['id']; ?>" data-leasor-name="<?php echo $leasor['name']; ?>" data-contact-person="<?php echo $leasor['contact_person']; ?>" data-contact-number="<?php echo $leasor['contact_number']; ?>" data-contact-email="<?php echo $leasor['contact_email']; ?>" data-lease-start="<?php echo $leasor['contract_start_date']; ?>" data-lease-end="<?php echo $leasor['contract_end_date']; ?>"><i class="fas fa-edit"></i> Edit</button>
                                        <button class="btn btn-sm btn-danger deleteLeasorBtn" data-toggle="modal" data-target="#deleteLeasorModal" data-delete-leasor-id="<?php echo $leasor['id']; ?>" data-delete-leasor-name="<?php echo $leasor['name']; ?>"><i class="fas fa-trash"></i> Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Leasor Modal -->
<div class="modal fade" id="leasorModal" tabindex="-1" role="dialog" aria-labelledby="leasorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="leasorForm" method="post" class="db-submit leasor-form" action="<?php echo base_url('infrastructure/leasor-management/save'); ?>" data-initmsg="Adding Leasor">
                 <?= csrf_field(); ?>
                 <input type="hidden" name="_method" value="ADD" id="_method">
                 <input type="hidden" id="leasorId" name="leasorId">
                <div class="modal-header">
                    <h5 class="modal-title" id="leasorModalLabel">Add Leasor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="leasorName">Leasor Name</label>
                        <input type="text" class="form-control" id="leasorName" name="leasorName" required>
                    </div>
                    <div class="form-group">
                        <label for="contractStatus">Contract Status</label>
                        <select class="form-control select2" id="contractStatus" name="contractStatus">
                            <option value="A">Available</option>
                            <option value="U">Unavailable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="leaseStart">Contract Start</label>
                        <input type="date" class="form-control" id="leaseStart" name="leaseStart" required>
                    </div>
                    <div class="form-group">
                        <label for="leaseEnd">Contract End</label>
                        <input type="date" class="form-control" id="leaseEnd" name="leaseEnd" required>
                    </div>
                    <div class="form-group">
                        <label for="contactPerson">Contact Person</label>
                        <input type="text" class="form-control" id="contactPerson" name="contactPerson" >
                    </div>
                    <div class="form-group">
                        <label for="contactNumber">Contact Number</label>
                        <input type="text" class="form-control" id="contactNumber" name="contactNumber" >
                    </div>
                    <div class="form-group">
                        <label for="contactEmail">Contact Email</label>
                        <input type="email" class="form-control" id="contactEmail" name="contactEmail" >
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
<!-- End Leasor Modal -->
 <!-- Delete Leasor Modal -->
 <div class="modal fade" id="deleteLeasorModal" tabindex="-1" role="dialog" aria-labelledby="deleteLeasorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteLeasorForm" method="post" class="leasor-delete-form db-submit" action="<?php echo base_url('infrastructure/leasor-management/delete'); ?>" data-initmsg="Deleting Leasor">
                 <?= csrf_field(); ?>
                 <input type="hidden" name="_method" value="DELETE">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteLeasorModalLabel">Delete Leasor <span class="deleteLeasorName"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="deleteLeasorId" name="deleteLeasorId">
                    <p>Are you sure you want to delete leasor <span id="spn-delete-leasor-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Delete Leasor Modal -->
<?php echo view('template'.DIRECTORY_SEPARATOR.'partial-footer'); ?>