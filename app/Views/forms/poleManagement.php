<?php echo view('template\partial-header'); ?>
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Manage Poles</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pole-modal">
                                        <i class="fas fa-plus"></i> Add Pole
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="poles-table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Pole Name</th>
                                                    <th>Location</th>
                                                    <th>Pole Type</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12 map-container">
                                    <div id="map" style="width: 100%; height: 400px;"></div>
                                </div>  
                            </div>
                        </div>  
                    </div>                     
                </div>
                
                <script>
                    function initMap() {
                        var uluru = {lat: 0.317, lng: 32.582};
                        var map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 12,
                            center: uluru
                        });
                        var marker = new google.maps.Marker({
                            position: uluru,
                            map: map
                        });
                    }
                </script>
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4nE4b5VZ4Zl5qV3f4VxI3Zl8jV5jJ2b3&callback=initMap"></script>
            </div>
        </section>
    </div>
        <div class="modal fade" id="pole-modal" tabindex="-1" role="dialog" aria-labelledby="poleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="poleModalLabel">Capture Pole Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="pole-name" class="col-form-label">Pole Name</label>
                                <input type="text" class="form-control" id="pole-name">
                            </div>
                            <div class="form-group">
                                <label for="pole-location" class="col-form-label">Pole Location</label>
                                <input type="text" class="form-control" id="pole-location">
                            </div>
                            <div class="form-group">
                                <label for="pole-type" class="col-form-label">Pole Type</label>
                                <select id="pole-type" class="form-control">
                                    <option value="">Select Pole Type</option>
                                    <option value="1">Pole A</option>
                                    <option value="2">Pole B</option>
                                    <option value="3">Pole C</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save Pole Details</button>
                    </div>
                </div>
            </div>
        </div>
<?php echo view('template\partial-footer'); ?>