<?php echo view('template/partial-header'); ?>
<style type="text/css">
        .marker-icon {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.4);
        }
        .marker-icon.good {
            background-color: green;
        }
        .marker-icon.reused {
            background-color: blue;
        }
        .marker-icon.damaged {
            background-color: orange;
        }
        .marker-icon.stolen {
            background-color: red;
        }
        .leaflet-popup-content-wrapper {
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        padding: 8px;
    }

    .leaflet-popup-custom {
        padding: 5px;
        border-radius: 5px;
        color: white;
        font-size: 0.9rem;
    }

    .popup-good {
        background-color: #28a745; /* Bootstrap green */
    }

    .popup-damaged {
        background-color: #ffc107; /* Bootstrap yellow */
        color: #212529; /* dark text for readability */
    }

    .popup-stolen {
        background-color: #dc3545; /* Bootstrap red */
    }

    .popup-replanted {
        background-color: #007bff; /* Bootstrap blue */
    }

    .popup-default {
        background-color: #6c757d; /* Bootstrap gray */
    }

</style>   
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
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fas fa-broadcast-tower"></i> Manage Infrastructure</h3>
                            <div class="card-tools">
                                <div class="btn-group float-right mr-2 mt-1 mb-1">
                                    <button type="button" class="btn btn-xs bg-gray-dark btn-infra pr-2 pl-2 border-right border-white" id="add-pole" title="Add a Pole" data-action="Add Pole" data-infra-type="Pole" data-toggle="modal" data-target="#infrastructure-modal" onclick="getLocation();">
                                        <i class="fas fa-plus-circle"></i> Pole
                                    </button>
                                    <button type="button" class="btn btn-xs bg-gray-dark btn-infra pr-2 pl-2 border-right border-white" id="add-manhole" title="Add a Manhole" data-action="Add Manhole" data-infra-type="Manhole" data-toggle="modal" data-target="#infrastructure-modal" onclick="getLocation();">
                                        <i class="fas fa-plus-circle"></i> Manhole
                                    </button>                                
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- add tabs for map view and table view -->
                            <ul class="nav nav-tabs" id="poleTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="table-tab" data-toggle="tab" title="View as Tables" href="#table-view" role="tab" aria-controls="table-view" aria-selected="true"><strong>List View</strong></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="map-tab" data-toggle="tab" href="#map-view" title="View on Map" role="tab" aria-controls="map-view" aria-selected="false"><strong>Map View</strong></a>
                                </li>
                            </ul>
                            <div class="tab-content" id="poleTabContent">
                                <div class="tab-pane fade show active" id="table-view" role="tabpanel" aria-labelledby="table-tab">
                                    <div class="row mb-2 border-bottom pt-2 pr-2 pb-2">
                                        <div class="col-md-12 text-right">
                                            <!-- Bootstrap switch for Manhole and Pole view -->
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-sm btn-outline-primary active" id="pole-view-switch">
                                                    <input type="radio" class="list-switch" name="infra-view" titlte="View Pole Listing" id="pole-view-radio" autocomplete="off" data-show="#pole-view" data-hide="#manhole-view" checked> Poles
                                                </label>
                                                <label class="btn btn-sm btn-outline-primary" id="manhole-view-switch">
                                                    <input type="radio" class="list-switch" name="infra-view" titlte="View Manhole Listing" id="manhole-view-radio" data-show="#manhole-view" data-hide="#pole-view" autocomplete="off"> Manholes
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive" id="pole-view">
                                        <h4>Pole Listing</h4>
                                        <table id="poles-table" class="table table-bordered table-striped table-hover table-sm display data-table nowrap" width="100%" data-order='[[ 8, "desc" ]]' aria-label="Pole Listing Table">
                                            <caption class="sr-only">Pole Listing Table</caption>
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th class="text-sm"><strong>Pole Code</strong></th>
                                                    <th class="text-sm"><strong>Pole Size</strong></th>
                                                    <th class="text-sm"><strong>Region</strong></th>
                                                    <th class="text-sm"><strong>District</strong></th>
                                                    <th class="text-sm"><strong>Latitude</strong></th>
                                                    <th class="text-sm"><strong>Longitude</strong></th>
                                                    <th class="text-sm"><strong>Pole Condition</strong></th>
                                                    <th class="text-sm"><strong>Added By</strong></th>
                                                    <th class="text-sm"><strong>Date Added</strong></th>
                                                    <th class="text-sm text-left"><strong>Actions</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($poles as $pole): ?>
                                                    <tr>
                                                        <td class="text-sm"><?php echo esc($pole['elmCode']) ?></td>
                                                        <td class="text-sm"><?php echo esc($pole['SizeLabel']) ?></td>
                                                        <td class="text-sm"><?php echo esc($pole['RegionName']) ?></td>
                                                        <td class="text-sm"><?php echo esc($pole['districtName']) ?></td>
                                                        <td class="text-sm"><?php echo esc($pole['latitude']) ?></td>
                                                        <td class="text-sm"><?php echo esc($pole['longitude']) ?></td>
                                                        <td class="text-sm"><?php echo esc($pole['elmCondition']) ?></td>
                                                        <td class="text-sm"><?php echo esc($pole['firstname'] . ' ' . $pole['lastname']) ?></td>
                                                        <td class="text-sm"><?php echo esc($pole['elmCreatedAt']) ?></td>
                                                        <td class="text-sm">
                                                            <div class="d-flex flex-row" style="gap:2px;">
                                                                <button class="btn btn-info btn-xs edit-infra rounded-circle d-flex align-items-center justify-content-center p-0"  
                                                                    style="width:25px; height:25px;"
                                                                    title="Edit Pole"
                                                                    data-toggle="modal" 
                                                                    data-target="#infrastructure-modal" 
                                                                    data-infra-type="Pole"
                                                                    data-infra-title="Edit Pole"
                                                                    data-element-id="<?php echo esc($pole['elmId']) ?>"
                                                                    data-element-code="<?php echo esc($pole['elmCode']) ?>"
                                                                    data-latitude="<?php echo esc($pole['latitude']) ?>"
                                                                    data-longitude="<?php echo esc($pole['longitude']) ?>"
                                                                    data-district-id="<?php echo esc($pole['districtId']) ?>" 
                                                                    data-pole-size="<?php echo esc($pole['poleSizeId']) ?>"
                                                                    data-element-condition="<?php echo esc($pole['elmCondition']) ?>" data-pole-type="<?php echo esc($pole['poleType']) ?>"
                                                                >
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-success btn-xs link-infra rounded-circle d-flex align-items-center justify-content-center p-0" 
                                                                    style="width:25px; height:25px;"
                                                                    title="Add Media to Pole"
                                                                    data-toggle="modal" 
                                                                    data-target="#media-link-modal" 
                                                                    data-infra-type="Pole"
                                                                    data-infra-title="Link Pole"
                                                                    data-element-id="<?php echo esc($pole['elmId']) ?>"
                                                                    data-element-code="<?php echo esc($pole['elmCode']) ?>"
                                                                    data-latitude="<?php echo esc($pole['latitude']) ?>"
                                                                    data-longitude="<?php echo esc($pole['longitude']) ?>"
                                                                    data-district-id="<?php echo esc($pole['districtId']) ?>" 
                                                                    data-pole-size="<?php echo esc($pole['poleSizeId']) ?>">
                                                                    <i class="fas fa-link"></i>
                                                                </button>
                                                                <button class="btn btn-danger btn-xs delete-pole rounded-circle d-flex align-items-center justify-content-center p-0" 
                                                                    style="width:25px; height:25px;" 
                                                                    title="Delete Pole"
                                                                    data-toggle="modal" 
                                                                    data-target="#delete-modal"
                                                                    data-pole-id="<?php echo esc($pole['elmId']) ?>"
                                                                    data-name="<?php echo esc($pole['elmCode']) ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive" id="manhole-view" style="display: none;">
                                        <h4>Manhole Listing</h4>
                                        <table id="manhole-table" class="table table-bordered table-striped table-hover table-sm display data-table nowrap" width="100%" data-order='[[ 10, "desc" ]]' >
                                            <caption class="sr-only">Manhole Listing</caption>
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th class="text-sm"><strong>Manhole Code</strong></th>
                                                    <th class="text-sm"><strong>Region</strong></th>
                                                    <th class="text-sm"><strong>District</strong></th>
                                                    <th class="text-sm"><strong>Latitude</strong></th>
                                                    <th class="text-sm"><strong>Longitude</strong></th>
                                                    <th class="text-sm" title="Depth in meters"><strong>Manhole Depth</strong></th>
                                                    <th class="text-sm" title="Length in meters"><strong>Manhole Length</strong></th>
                                                    <th class="text-sm" title="Width in meters"><strong>Manhole Width</strong></th>
                                                    <th class="text-sm"><strong>Manhole Condition</strong></th>
                                                    <th class="text-sm"><strong>Added By</strong></th>
                                                    <th class="text-sm"><strong>Date Added</strong></th>
                                                    <th class="text-sm"><strong>Actions</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($manholes as $manhole): ?>
                                                    <tr>
                                                        <td class="text-sm"><?php echo esc($manhole['elmCode']) ?></td>
                                                        <td class="text-sm"><?php echo esc($manhole['RegionName']) ?></td>
                                                        <td class="text-sm"><?php echo esc($manhole['districtName']) ?></td>
                                                        <td class="text-sm"><?php echo esc($manhole['latitude']) ?></td>
                                                        <td class="text-sm"><?php echo esc($manhole['longitude']) ?></td>
                                                        <td class="text-sm"><?php echo esc($manhole['manholeDepth']) ?></td>
                                                        <td class="text-sm"><?php echo esc($manhole['manholeLength']) ?></td>
                                                        <td class="text-sm"><?php echo esc($manhole['manholeWidth']) ?></td>
                                                        <td class="text-sm"><?php echo esc($manhole['elmCondition']) ?></td>
                                                        <td class="text-sm"><?php echo esc($manhole['lastname'].', '.$manhole['firstname']) ?></td>
                                                        <td class="text-sm"><?php echo esc($manhole['elmCreatedAt']) ?></td>
                                                        <td class="text-sm">
                                                            <div class="d-flex flex-row" style="gap:2px;">
                                                                <button class="btn btn-info btn-xs edit-infra rounded-circle d-flex align-items-center justify-content-center p-0"
                                                                        style="width:25px; height:25px;"
                                                                        data-toggle="modal" 
                                                                        data-target="#infrastructure-modal"
                                                                        data-infra-type="Manhole"
                                                                        data-infra-title="Edit Manhole"
                                                                        data-element-id="<?php echo $manhole['elmId'] ?>"
                                                                        data-element-code="<?php echo esc($manhole['elmCode']) ?>"
                                                                        data-latitude="<?php echo esc($manhole['latitude']) ?>"
                                                                        data-longitude="<?php echo esc($manhole['longitude']) ?>"
                                                                        data-district-id="<?php echo esc($manhole['districtId']) ?>" 
                                                                        data-manhole-depth="<?php echo $manhole['manholeDepth'] ?>"
                                                                        data-manhole-length="<?php echo $manhole['manholeLength'] ?>"
                                                                        data-manhole-width="<?php echo $manhole['manholeWidth'] ?>"
                                                                        data-manhole-diameter="<?php echo $manhole['manholeDiameter'] ?>"
                                                                        data-element-condition="<?php echo esc($manhole['elmCondition']) ?>">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-secondary btn-xs media-link rounded-circle d-flex align-items-center justify-content-center p-0"
                                                                        style="width:25px; height:25px;"
                                                                        data-toggle="modal" 
                                                                        data-target="#media-link-modal"
                                                                        data-element-id="<?php echo $manhole['elmId'] ?>"
                                                                        data-element-code="<?php echo esc($manhole['elmCode']) ?>">
                                                                    <i class="fas fa-link"></i>
                                                                </button>
                                                                <button class="btn btn-danger btn-xs delete-manhole rounded-circle d-flex align-items-center justify-content-center p-0"
                                                                        style="width:25px; height:25px;"
                                                                        data-toggle="modal" 
                                                                        data-target="#delete-modal"
                                                                        data-manhole-id="<?php echo $manhole['elmId'] ?>"
                                                                        data-name="<?php echo esc($manhole['elmCode']) ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
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
<div class="modal fade" id="infrastructure-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="infrastructure/save" method="post" class="db-submit infra-form" id="element-form" data-initmsg="Adding new pole">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title"><span id="action-title">Add Pole </span><i class="fas fa-tower"></i></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="elmId" id="elm-id">
                    <input type="hidden" name="elmType" id="elm-type">
                    <div class="form-group">
                        <label for="district-code">District</label>
                        <select name="districtId" id="district-id" class="form-control select2">
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
                                    echo '<option value="' . htmlspecialchars($district['districtId']) . '" ' .
                                        'data-region-code="' . htmlspecialchars($district['RegionCode']) . '" ' .
                                        'data-region-name="' . htmlspecialchars($district['RegionName']) . '" ' .
                                        'data-district-code="' . htmlspecialchars($district['code']) . '">' .
                                        htmlspecialchars($district['districtName']) .
                                        '</option>';
                                }
                                echo '</optgroup>';
                            }
                            ?>
                        </select>

                    </div>
                        <input type="hidden" class="form-control" id="infra-code" name="infra_code" readonly >
                   <small> <a href="#" onclick="getLocation(); return false;" class="btn btn-primary btn-xs" ><i class="fas fa-map-marker-alt"></i> Refresh Location</a> </small>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="longitude">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="elmLongitude" required readonly>                        
                            </div>                            
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="latitude">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="elmLatitude" required readonly>
                            </div>                            
                        </div>
                    </div>

                    <div class="form-group pole-data">
                        <label for="pole-type">Pole Type</label>
                        <select name="poleTypeId" id="pole-type" class="form-control select2">
                            <option value="">--Select Type--</option>
                            <?php foreach ($pole_types as $pole_type) { echo '<option value="' . $pole_type['TypeId'] . '">' . $pole_type['TypeName'] . '</option>'; } ?>
                        </select>
                    </div>

                    <div class="form-group pole-data">
                        <label for="pole-size">Pole Size</label>
                        <select name="poleSizeId" id="pole-size" class="form-control select2">
                            <option value="">--Select Size--</option>
                            <?php foreach ($sizes as $size) { echo '<option value="' . $size['poleSizeId'] . '">' . $size['SizeLabel'] . '</option>'; } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="elm-condition">Condition</label>
                        <select name="elmCondition" id="elm-condition" class="form-control select2">
                            <option value="">--Select Condition--</option>
                            <option value="Good">Good</option>
                            <option value="Re-Used">Re-Used</option>
                            <option value="Damaged">Damaged</option>
                        </select>
                    </div>
                    <div class="manhole-data">
                        <div class="form-group d-flex align-items-center">
                            <label for="manhole-circular" class="mb-0 mr-4">Is the manhole circular?</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="manhole-circular" name="manholeCircular">
                                <label class="custom-control-label" for="manhole-circular">Yes</label>
                            </div>
                        </div>                        
                    </div>
                    
                    <div class="row manhole-data">
                        <div class="col-md-4 col-sm-4 col-xs-3 non-circular-data">
                            <div class="form-group">
                                <label for="manhole-width">Width</label>
                                <input type="number" class="form-control" id="manhole-width" name="manholeWidth" value="0">
                            </div>                            
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-3 non-circular-data">
                            <div class="form-group">
                                <label for="manhole-length">Length</label>
                                <input type="number" class="form-control" id="manhole-length" name="manholeLength" value="0">
                            </div>                            
                        </div>
                        
                        <div class="col-md-4 col-sm-4 col-xs-3 manhole-data" id="circular-data">
                            <div class="form-group">
                                <label for="manhole-diameter">Diameter</label>
                                <input type="number" class="form-control" id="manhole-diameter" name="manholeDiameter" value="0">
                            </div>                            
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-3 manhole-data">
                            <div class="form-group">
                                <label for="manhole-depth">Depth</label>
                                <input type="number" class="form-control" id="manhole-depth" name="manholeDepth" value="0">
                            </div>
                        </div>
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
            <form action="<?= base_url('infrastructre/delete') ?>" method="post" class="db-submit" id="delete-form" data-initmsg="Deleting pole">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-danger">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Delete Pole</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="delete-pole-name"></strong>?</p>
                    <input type="hidden" name="delete_pole_id" id="delete-pole-id">
                    <input type="hidden" name="delete_pole_code" id="delete-pole-code">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Media link Modal -->
<div class="modal fade" id="media-link-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-link"></i> Link Media to <span id="destination-media-code"></span> </h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?php echo base_url('infrastructre/linkMedia') ?>" method="post" class="db-submit" id="media-link-form" data-initmsg="Linking media">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="media-destination-element" id="media-destination-element" value="">
                <input type="hidden" name="media-destination-code" id="media-destination-code" value="">
                <input type="hidden" name="carryId" id="carry-id" value="0">
                <input type="hidden" name="formType" value="linkMedia" class="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="media-type">Cable Type</label>
                        <select class="form-control" id="media-type" name="media_type">
                            <option value="">--Select Cable Type--</option>
                            <?php foreach ($media_types as $media_type): ?>
                                <option value="<?php echo esc($media_type->carryTypeId); ?>"><?php echo esc($media_type->carryTypeName); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="media-capacity">Cable Capacity</label>
                        <select class="form-control" id="media-capacity" name="media_capacity">
                            
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="media-source-type">Source Type</label>
                        <select class="form-control" id="media-source-type" name="media_source_type">
                            <option value="">--Select Source Element Type--</option>
                            <option value="Pole">Pole</option>
                            <option value="Manhole">Manhole</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="source-element">Source Element</label>
                        <select class="form-control" id="source-element" name="source_element">
                            <option value="">--Select Origin Element--</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Link</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php echo view('template/partial-footer'); ?>
