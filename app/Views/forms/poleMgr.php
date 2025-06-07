<?php echo view('template/partial-header'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pole Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Pole Management</li>
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
                            <h3 class="card-title">Manage Poles</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-xs btn-primary" id="add-pole" data-toggle="modal" data-target="#pole-modal">
                                    <i class="fas fa-plus"></i> Add Pole
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="poles-table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            <th>District</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($poles as $pole): ?>
                                            <tr>
                                                <td><?php echo $pole['PoleId'] ?></td>
                                                <td><?php echo esc($pole['PoleCode']) ?></td>
                                                <td><?php echo esc($pole['latitude']) ?></td>
                                                <td><?php echo esc($pole['longitude']) ?></td>
                                                <td><?php echo esc($pole['name']) ?></td>
                                                <td>
                                                    <button class="btn btn-info btn-xs edit-pole" 
                                                            data-toggle="modal" 
                                                            data-target="#pole-modal"
                                                            data-pole-id="<?php echo $pole['PoleId'] ?>"
                                                            data-pole-code="<?php echo esc($pole['PoleCode']) ?>"
                                                            data-latitude="<?php echo esc($pole['latitude']) ?>"
                                                            data-longitude="<?php echo esc($pole['longitude']) ?>"
                                                            data-district-id="<?php echo $pole['district_id'] ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-xs delete-pole" 
                                                            data-toggle="modal" 
                                                            data-target="#delete-modal"
                                                            data-pole-id="<?php echo $pole['PoleId'] ?>"
                                                            data-name="<?php echo esc($pole['PoleCode']) ?>">
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

                    <!-- MAP VIEW -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Map View</h3>
                        </div>
                        <div class="card-body">
                            <div id="pole-map" style="height: 400px; width: 100%;"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add/Edit Pole Modal -->
<div class="modal fade" id="pole-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="pole-management/store" method="post" class="db-submit" id="pole-form" data-innit-msg="Adding new pole">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Add Pole <i class="fas fa-tower"></i></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="pole_id" id="pole-id">
                    <div class="form-group">
                        <label for="district-code">District</label>
                        <select name="district_id" id="district-id" class="form-control select2">
                            <option value="">Select District</option>
                            <?php
                            $grouped = [];

                            // Group districts by RegionName
                            foreach ($districts as $district) {
                                $region = $district['RegionName'];
                                if (!isset($grouped[$region])) {
                                    $grouped[$region] = [];
                                }
                                $grouped[$region][] = $district;
                            }

                            // Output optgroups and options
                            foreach ($grouped as $regionName => $districtList) {
                                echo '<optgroup label="' . htmlspecialchars($regionName) . '">';
                                foreach ($districtList as $district) {
                                    echo '<option value="' . htmlspecialchars($district['id']) . '" ' .
                                        'data-region-code="' . htmlspecialchars($district['RegionCode']) . '" ' .
                                        'data-region-name="' . htmlspecialchars($district['RegionName']) . '" ' .
                                        'data-district-code="' . htmlspecialchars($district['code']) . '">' .
                                        htmlspecialchars($district['name']) .
                                        '</option>';
                                }
                                echo '</optgroup>';
                            }
                            ?>
                        </select>

                    </div>
                        <input type="hidden" class="form-control" id="pole-code" name="pole_code" readonly >
                   <small> <a href="#" onclick="getLocation(); return false;" class="btn btn-primary btn-xs" ><i class="fas fa-map-marker-alt"></i> Refresh Location</a> </small>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="longitude">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="pole_longitude" required>                        
                            </div>                            
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="pole_latitude" required>
                            </div>                            
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pole-size">Pole Size</label>
                        <select name="pole_size" id="pole-size" class="form-control select2">
                            <option value="">--Select Size--</option>
                            <?php foreach ($sizes as $size) { echo '<option value="' . $size['id'] . '">' . $size['size'] . '</option>'; } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pole-condition">Condition</label>
                        <select name="pole_condition" id="pole-condition" class="form-control select2">
                            <option value="">--Select Condition--</option>
                            <option value="good">Good</option>
                            <option value="fair">Fair</option>
                            <option value="poor">Poor</option>
                        </select>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('pole-management/delete') ?>" method="post" class="db-submit">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Delete Pole</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p><i class="fas fa-exclamation-triangle text-danger"></i> Are you sure you want to delete <strong id="delete-pole-name"></strong>?</p>
                    <input type="hidden" name="delete_pole_id" id="delete-pole-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Initialize Leaflet map
    var map = L.map('pole-map').setView([0.3476, 32.5825], 7); // Uganda default center
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    <?php foreach ($poles as $pole){ ?>
        L.marker([<?php echo $pole['latitude'] ?>, <?php echo $pole['longitude'] ?>])
            .addTo(map)
            .bindPopup("<strong><?php echo esc($pole['PoleCode']) ?></strong><br><strong>District: </strong><?php echo esc($pole['name']) ?><br><strong>Size: </strong><?php echo esc($pole['size']) ?><br><strong>Condition: </strong><?php echo esc($pole['pole_condition']) ?>");
    <?php } ?>

    // Auto-detect device location and fill inputs
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
            }, function(error) {
                alert('Error fetching location: ' + error.message);
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }
</script>

<?php echo view('template/partial-footer'); ?>
