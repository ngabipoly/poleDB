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
                                <button type="button" class="btn btn-xs btn-primary" id="add-pole" data-toggle="modal" data-target="#pole-modal" onclick="getLocation();">
                                    <i class="fas fa-plus"></i> Add Pole
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- add tabs for map view and table view -->
                            <ul class="nav nav-tabs" id="poleTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="table-tab" data-toggle="tab" href="#table-view" role="tab" aria-controls="table-view" aria-selected="true"><strong>List View</strong></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="map-tab" data-toggle="tab" href="#map-view" role="tab" aria-controls="map-view" aria-selected="false"><strong>Map View</strong></a>
                                </li>
                            </ul>
                            <div class="tab-content" id="poleTabContent">
                                <div class="tab-pane fade show active" id="table-view" role="tabpanel" aria-labelledby="table-tab">
                                    <div class="row mb-3 mt-3">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary btn-sm" id="refresh-table"><i class="fas fa-sync"></i> Refresh Table</button>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="poles-table" class="table table-bordered table-striped table-hover table-sm display compact nowrap" width="100%">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th class="text-sm"><strong>Pole Code</strong></th>
                                                    <th class="text-sm"><strong>Pole Size</strong></th>
                                                    <th class="text-sm"><strong>Region</strong></th>
                                                    <th class="text-sm"><strong>District</strong></th>
                                                    <th class="text-sm"><strong>Latitude</strong></th>
                                                    <th class="text-sm"><strong>Longitude</strong></th>
                                                    <th class="text-sm"><strong>Actions</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($poles as $pole): ?>
                                                    <tr>
                                                        <td><?php echo esc($pole['PoleCode']) ?></td>
                                                        <td><?php echo esc($pole['SizeLabel']) ?></td>
                                                        <td><?php echo esc($pole['RegionName']) ?></td>
                                                        <td><?php echo esc($pole['name']) ?></td>
                                                        <td><?php echo esc($pole['latitude']) ?></td>
                                                        <td><?php echo esc($pole['longitude']) ?></td>
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
                                <div class="tab-pane fade" id="map-view" role="tabpanel" aria-labelledby="map-tab">
                                        <!-- MAP VIEW -->
                                    <div id="pole-map" class="map mt-3" style="height: 400px; width: 100%;"></div>
                                </div>
                            </div>
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
                            <?php foreach ($sizes as $size) { echo '<option value="' . $size['id'] . '">' . $size['SizeLabel'] . '</option>'; } ?>
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
            .bindPopup("<strong><?php echo esc($pole['PoleCode']) ?></strong><br><strong>District: </strong><?php echo esc($pole['name']) ?><br><strong>Size: </strong><?php echo esc($pole['SizeLabel']) ?><br><strong>Condition: </strong><?php echo esc($pole['pole_condition']) ?>");
    <?php } ?>

    // Auto-detect device location and fill inputs
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
            }, function(error) {
                toastr.error('Error fetching location: ' + error.message);
            });
        } else {
            toastr.error('Geolocation is not supported by this browser.');
        }
    }
</script>

<?php echo view('template/partial-footer'); ?>
