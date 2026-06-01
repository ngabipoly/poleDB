<?php echo view('template'.DIRECTORY_SEPARATOR.'partial-header'); ?>
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
                                        <table id="poles-table" class="table table-bordered table-striped table-hover table-sm text-sm display data-table nowrap" width="100%" data-order='[[ 8, "desc" ]]' aria-label="Pole Listing Table">
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
                                                                <?php $url = base_url('infrastructure/element-details/' . $pole['elmId']); ?>
                                                                <a class="btn bg-navy btn-xs view-infra rounded-circle d-flex align-items-center justify-content-center p-0"  
                                                                    style="width:25px; height:25px;"
                                                                    title="View Pole Data"
                                                                    href="<?php echo $url; ?>"
                                                                >
                                                                    <i class="fas fa-clipboard-list"></i>
                                                                </a>
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
                                                                    data-utel-owned="<?php echo esc($pole['utel_owned']) ?>"
                                                                    data-leasor-id="<?php echo esc($pole['leasor_id']) ?>"
                                                                    data-usage-start-date="<?php echo esc($pole['usageStartDate']) ?>"
                                                                    data-usage-end-date="<?php echo esc($pole['usageEndDate']) ?>"
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
                                                                <button class="btn btn-danger btn-xs delete-element rounded-circle d-flex align-items-center justify-content-center p-0" 
                                                                    style="width:25px; height:25px;" 
                                                                    data-toggle="modal" 
                                                                    data-target="#delete-modal"
                                                                    title="Delete <?php echo esc($pole['elmType']); ?>"
                                                                    data-element-type="<?php echo esc($pole['elmType']) ?>"
                                                                    data-element-id="<?php echo esc($pole['elmId']) ?>"
                                                                    data-element-name="<?php echo esc($pole['elmCode']) ?>">
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
                                        <table id="manhole-table" class="table table-bordered table-striped table-hover table-sm text-sm display data-table nowrap" width="100%" data-order='[[ 8, "desc" ]]' >
                                            <caption class="sr-only">Manhole Listing</caption>
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th class="text-sm"><strong>Manhole Code</strong></th>
                                                    <th class="text-sm"><strong>Region</strong></th>
                                                    <th class="text-sm"><strong>District</strong></th>
                                                    <th class="text-sm"><strong>Latitude</strong></th>
                                                    <th class="text-sm"><strong>Longitude</strong></th>
                                                    <th class="text-sm" title="Depth in meters"><strong>Manhole Depth</strong></th>
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
                                                        <td class="text-sm"><?php echo esc($manhole['elmCondition']) ?></td>
                                                        <td class="text-sm"><?php echo esc($manhole['lastname'].', '.$manhole['firstname']) ?></td>
                                                        <td class="text-sm"><?php echo esc($manhole['elmCreatedAt']) ?></td>
                                                        <td class="text-sm">
                                                            <div class="d-flex flex-row" style="gap:2px;">
                                                                <?php $url = base_url('infrastructure/element-details/' . $manhole['elmId']); ?>
                                                                <a class="btn bg-navy btn-xs media-view rounded-circle d-flex align-items-center justify-content-center p-0"
                                                                        style="width:25px; height:25px;"
                                                                        title="View Manhole Data"
                                                                        href="<?php echo $url; ?>">
                                                                    <i class="fas fa-clipboard-list"></i>
                                                                </a>
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
                                                                        data-element-condition="<?php echo esc($manhole['elmCondition']) ?>"
                                                                        data-cover-type="<?php echo esc($manhole['coverType']) ?>"
                                                                        data-manhole-location="<?php echo esc($manhole['manholeLocation']) ?>"
                                                                        data-operating-status="<?php echo esc($manhole['operatingStatus']) ?>"
                                                                        data-construction-material="<?php echo esc($manhole['constructionMaterial']) ?>"
                                                                        data-access-restriction="<?php echo esc($manhole['accessRestriction']) ?>"
                                                                        >
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-success btn-xs link-infra media-link rounded-circle d-flex align-items-center justify-content-center p-0"
                                                                        style="width:25px; height:25px;"
                                                                        data-toggle="modal" 
                                                                        data-target="#media-link-modal"
                                                                        data-element-id="<?php echo $manhole['elmId'] ?>"
                                                                        data-element-code="<?php echo esc($manhole['elmCode']) ?>"
                                                                        data-latitude="<?php echo esc($manhole['latitude']) ?>"
                                                                        data-longitude="<?php echo esc($manhole['longitude']) ?>"
                                                                        data-infra-type="Manhole"
                                                                        data-infra-title="Link Manhole"
                                                                        title="Add Upstream Link">
                                                                    <i class="fas fa-link"></i>
                                                                </button>
                                                                <button class="btn btn-danger btn-xs delete-element rounded-circle d-flex align-items-center justify-content-center p-0"
                                                                        style="width:25px; height:25px;"
                                                                        data-toggle="modal" 
                                                                        data-target="#delete-modal"
                                                                        title="Delete <?php echo esc($manhole['elmType']); ?>"
                                                                        data-element-type="<?php echo esc($manhole['elmType']) ?>"
                                                                        data-element-id="<?php echo esc($manhole['elmId']) ?>"
                                                                        data-element-name="<?php echo esc($manhole['elmCode']) ?>">
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
<div class="modal text-sm fade" id="infrastructure-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="infrastructure/save" method="post" class="db-submit infra-form" id="element-form" data-initmsg="Adding new pole">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title"><span id="action-title">Add Pole </span><i class="fas fa-tower"></i></h5>
                    <div class="modal-subtitle"><small id="infra-coordinates" class="form-text text-muted"></small></div>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="elmId" id="elm-id">
                    <input type="hidden" name="elmType" id="elm-type">                    
                    <input type="hidden" class="form-control" id="infra-code" name="infra_code" readonly >
                    <input type="hidden" class="form-control form-control-sm" id="longitude" name="elmLongitude" required readonly>   
                    <input type="hidden" class="form-control form-control-sm" id="latitude" name="elmLatitude" required readonly>                        
                        <small class="align-middle">
                            <a href="#" onclick="getLocation(); return false;" class="btn btn-primary btn-xs">
                                <i class="fas fa-map-marker-alt"></i> Refresh Location
                            </a>
                        </small>                        
                    <div class="row mt-0 mb-0">
                        <div class="col-sm-6 mb-0 mt-0">
                            <div class="form-group mb-0 mt-o">
                                <label for="district-code" class="col-form-label-sm">District</label>
                                <select name="districtId" id="district-id" class="form-control select2 form-control-sm">
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
                        </div>
                        <div class="col-sm-6 mb-0 mt-0">
                            <div class="form-group mb-0 mt-0">
                                <label for="elm-condition" class="col-form-label-sm">Condition</label>
                                <select name="elmCondition" id="elm-condition" class="form-control select2 form-control-sm">
                                    <option value="">--Select Condition--</option>
                                    <option value="Good">Good</option>
                                    <option value="Re-Used">Re-Used</option>
                                    <option value="Damaged">Damaged</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-0 mb-0" style="display: none;">
                        <div class="col-sm-6 mb-0 mt-0">
                            <div class="form-group">
                                <label for="longitude" class="col-form-label-sm">Longitude</label>                     
                            </div>                            
                        </div>
                        <div class="col-sm-6 mb-0 mt-0">
                            <div class="form-group">
                                <label for="latitude" class="col-form-label-sm">Latitude</label>
                            </div>                            
                        </div>
                    </div>
                <div class="row pole-data mt-0 mb-0">
                    <div class="col-sm-6 mb-0 mt-0">
                        <div class="form-group mb-0 mt-0">
                            <label for="pole-type" class="col-form-label-sm">Pole Type</label>
                            <select name="poleTypeId" id="pole-type" class="form-control select2 form-control-sm">
                                <option value="">--Select Type--</option>
                                <?php foreach ($pole_types as $pole_type) { echo '<option value="' . $pole_type['TypeId'] . '">' . $pole_type['TypeName'] . '</option>'; } ?>
                            </select>
                        </div>                        
                    </div>
                    <div class="col-sm-6 mb-0 mt-0">
                        <div class="form-group mb-0 mt-0">
                            <label for="pole-size" class="col-form-label-sm">Pole Size</label>
                            <select name="poleSizeId" id="pole-size" class="form-control select2 form-control-sm">
                                <option value="">--Select Size--</option>
                                <?php foreach ($sizes as $size) { echo '<option value="' . $size['poleSizeId'] . '">' . $size['SizeLabel'] . '</option>'; } ?>
                            </select>
                        </div>                        
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-check form-switch pole-data">
                            <input type="hidden" name="utelOwned" id="utelOwned" value="N">
                            <input class="form-check-input"
                                type="checkbox"
                                checked
                                id="utel-owned"
                                value="Y">

                            <label class="form-check-label col-form-label-sm" for="utel-owned">
                                UTel-Owned
                            </label>
                        </div>                        
                    </div>
                    <div class="col-sm-6">
                        
                    </div>
                </div>


                    <div class="form-group pole-data mt-0 mb-0 leasor-fields" style="display: none;">
                        <label for="leasor" class="col-form-label-sm">Leasor</label>
                        <select name="leasorId" id="leasor" class="form-control form-control-sm select2">
                            <option value="">--Select Leasor--</option>
                            <?php foreach ($leasors as $leasor) { echo '<option value="' . $leasor['id'] . '">' . $leasor['name'] . '</option>'; } ?>
                        </select>
                    </div>

                    <div class="row pole-data mt-0 mb-0 leasor-fields" style="display: none;">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lease-start-date" class="col-form-label sm">Lease Start Date</label>
                                <input type="date" class="form-control form-control-sm" id="lease-start-date" name="leaseStartDate">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lease-end-date" class="col-form-label sm">Lease End Date</label>
                                <input type="date" class="form-control form-control-sm" id="lease-end-date" name="leaseEndDate">
                            </div>
                        </div>                        
                    </div>

                    
                    <div class="manhole-data mt-0 mb-0">
                        <div class="form-group mt-0 mb-0">
                            <label for="manhole-location" class="col-form-label-sm">Location</label>
                            <input type="text" class="form-control form-control-sm" id="manhole-location" name="manholeLocation" placeholder="Enter street/Road Name" value="">
                        </div>
                        <div class="row mt-0 mb-0">
                            <div class="col-sm-6 mb-0 mt-0">
                                <div class="form-group">
                                    <label for="construction-material" class="col-form-label-sm">Construction Material</label>
                                    <select name="constructionMaterial" id="construction-material" class="form-control select2 form-control-sm">
                                        <option value="">--Select Material--</option>
                                        <option value="Concrete">Concrete</option>
                                        <option value="Brick">Brick</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-0 mt-0">
                                <div class="form-group">
                                    <label for="cover-type" class="col-form-label-sm">Cover Type</label>
                                    <select name="coverType" id="cover-type" class="form-control select2 form-control-sm">
                                        <option value="">--Select Cover Type--</option>
                                        <option value="Casted">Casted</option>
                                        <option value="Metal">Metal</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 mb-0 mt-0">
                                <div class="form-group mb-0 mt-0">
                                    <label for="access-restriction" class="col-form-label-sm">Access Restriction</label>
                                    <select name="accessRestriction" id="access-restriction" class="form-control select2 form-control-sm">
                                        <option value="">--Select Restriction--</option>
                                        <option value="L">Locked</option>
                                        <option value="U">Unlocked</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-0 mt-0">
                                <div class="form-group mb-0 mt-0">
                                    <label for="operating-status" class="col-form-label-sm">Operating Status</label>
                                    <select name="operatingStatus" id="operating-status" class="form-control select2 form-control-sm">
                                        <option value="">--Select Status--</option>
                                        <option value="Active">Active</option>
                                        <option value="Sealed by Road">Sealed by Road</option>
                                        <option value="Under Maintenance">Under Maintenance</option>
                                        <option value="Damaged">Damaged</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex align-items-center mb-0">
                            <label for="manhole-circular" class="col-form-label-sm mb-0 mt-0 mr-4">Is the manhole circular?</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="manhole-circular" name="manholeCircular">
                                <label class="custom-control-label" for="manhole-circular">Yes</label>
                            </div>
                        </div>                        
                    </div>
                    
                    <div class="row manhole-data mt-0 mb-0">
                        <div class="col-md-4 col-sm-4 col-xs-3 non-circular-data mt-0 mb-0">
                            <div class="form-group">
                                <label for="manhole-width" class="form-label col-form-label-sm">Width</label>
                                <input type="number" class="form-control form-control-sm" id="manhole-width" name="manholeWidth" value="0">
                            </div>                            
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-3 non-circular-data mt-0 mb-0">
                            <div class="form-group mt-0 mb-0">
                                <label for="manhole-length" class="form-label col-form-label-sm">Length</label>
                                <input type="number" class="form-control form-control-sm" id="manhole-length" name="manholeLength" value="0">
                            </div>                            
                        </div>
                        
                        <div class="col-md-4 col-sm-4 col-xs-3 manhole-data mt-0 mb-0" id="circular-data">
                            <div class="form-group mt-0 mb-0">
                                <label for="manhole-diameter" class="col-form-label-sm">Diameter</label>
                                <input type="number" class="form-control form-control-sm" id="manhole-diameter" name="manholeDiameter" value="0">
                            </div>                            
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-3 manhole-data mt-0 mb-0">
                            <div class="form-group mt-0 mb-0">
                                <label for="manhole-depth" class="col-form-label-sm">Depth</label>
                                <input type="number" class="form-control form-control-sm" id="manhole-depth" name="manholeDepth" value="0">
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
<div class="modal fade text-sm" id="delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('infrastructure/delete') ?>" method="post" class="db-submit" id="delete-form" data-initmsg="Deleting pole">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-danger">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Delete  <span id="spn-delete-element-type"></span></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="delete-element-name"></strong>?</p>
                    <input type="hidden" name="delete_element_id" id="delete-element-id">
                    <input type="hidden" name="delete_element_code" id="delete-element-code">
                    <input type="hidden" name="delete_element_type" id="delete-element-type">
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
<div class="modal fade text-sm" id="media-link-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title"><i class="fas fa-link"></i> Link Media to <span id="destination-media-code"></span> </h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?php echo base_url('infrastructure/linkMedia') ?>" method="post" class="db-submit" id="media-link-form" data-initmsg="Linking media">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="media-destination-element" id="media-destination-element" value="">
                <input type="hidden" name="media-destination-code" id="media-destination-code" value="">
                <input type="hidden" name="carryId" id="carry-id" value="0">
                <input type="hidden" name="element-longitude" id="element-longitude" value="0">
                <input type="hidden" name="element-latitude" id="element-latitude" value="0">
                <input type="hidden" name="src-element-longitude" id="src-element-longitude" value="0">
                <input type="hidden" name="src-element-latitude" id="src-element-latitude" value="0">
                <input type="hidden" name="formType" value="linkMedia" class="">
                <div class="modal-body">
                    <div class="row mb-0">
                        <div class="col-md-6 mb-0">
                            <div class="form-group">
                                <label for="media-type" class="col-form-label-sm pb-0">Cable Type</label>
                                <select class="form-control form-control-sm" id="media-type" name="media_type">
                                    <option value="">--Select Cable Type--</option>
                                    <?php foreach ($media_types as $media_type): ?>
                                        <option value="<?php echo esc($media_type->carryTypeId); ?>"><?php echo esc($media_type->carryTypeName); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>                        
                        </div>  
                        <div class="col-md-6 mb-0">
                            <div class="form-group">
                                <label for="media-capacity" class="col-form-label-sm pb-0">Cable Capacity</label>
                                <select class="form-control form-control-sm" id="media-capacity" name="media_capacity">
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-0 mb-0">
                        <div class="col-md-6 mb-0 mt-0">
                            <div class="form-group">
                                <label for="media-source-type" class="col-form-label-sm pb-0">Source Type</label>
                                <select class="form-control form-control-sm" id="media-source-type" name="media_source_type">
                                    <option value="">--Select Source Element Type--</option>
                                    <option value="Pole">Pole</option>
                                    <option value="Manhole">Manhole</option>
                                </select>
                            </div>                            
                        </div>
                        <div class="col-md-6 mb-0 mt-0">
                            <div class="form-group">
                                <label for="source-element" class="col-form-label-sm pb-0">Source Element</label>
                                <select class="form-control form-control-sm" id="source-element" name="source_element">
                                    <option value="">--Select Origin Element--</option>
                                </select>
                            </div>                            
                        </div>
                    </div>
                    <small id="media-distance-label" class="form-text text-muted"></small>
                    <input type="hidden" class="form-control form-control-sm" id="distance" name="distance" value="0" readonly>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success rounded-6">Create Link</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php echo view('template'.DIRECTORY_SEPARATOR.'partial-footer'); ?>
