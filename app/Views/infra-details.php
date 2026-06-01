<?php echo view('template'.DIRECTORY_SEPARATOR.'partial-header', ['title' => 'Infrastructure Details']); 

$links = array_merge(
    array_map(fn($link) => $link['carryId'], $downstreamElements),
    array_map(fn($link) => $link['carryId'], $upstreamElements)
);

?>

<div class="content-wrapper">
    <!-- Page Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-broadcast-tower"></i> Infrastructure Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('dashboard'); ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Infrastructure Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <?php
                $conditionClass = '';
                switch ($element['elmCondition']) {
                    case 'Good':
                        $conditionClass = 'success';
                        break;
                    case 'Re-used':
                        $conditionClass = 'primary';
                        break;
                    case 'Damaged':
                        $conditionClass = 'warning';
                        break;
                    default:
                        $conditionClass = 'secondary';
                }
                ?>

            <!-- Pole Information -->
            <div class="card text-sm">
                <div class="card-header bg-<?= esc($conditionClass) ?>">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> <?= esc($element['elmType']) ?> Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">

                        <!-- Left Column -->
                        <div class="col-md-4">
                            <ul class="list-unstyled text-small mb-0">
                                <?php 
                                    if($element['elmType']=='Pole') {
                                    ?>                            
                                    <li><strong>Pole Code:</strong> <?= esc($element['elmCode']) ?></li>
                                    <li><strong>Pole Size:</strong> <?= esc($element['poleSize']) ?></li>
                                    <li><strong>Pole Type:</strong> <?= esc($element['poleType']) ?></li>
                                <?php }?>
                                <?php 
                                    if($element['elmType']=='Manhole') { ?>
                                     <li><strong>Manhole Code:</strong> <?= esc($element['elmCode']) ?></li>
                                     <li><strong>Cover Type:</strong> <?= esc($element['coverType']) ?></li>
                                     <li><strong>Construction Material:</strong> <?= esc($element['constructionMaterial']) ?></li>
                                     <li><strong>Operating Status:</strong> <?= esc($element['operatingStatus']) ?></li>
                                    <li><strong>Manhole Shape:</strong> <?= esc($element['manholeDiameter'] > 0 ? 'Circular' : 'Square/Rectangular') ?></li>
                                <?php } ?>
                            </ul>
                        </div>

                        <!-- Middle Column -->
                        <div class="col-md-4">
                            <ul class="list-unstyled text-small mb-0">
                               <?php if($element['elmType']=='Manhole') { ?>
                                <li><strong>Location:</strong> <?= esc($element['manholeLocation']) ?></li> <?php } ?>
                                <li><strong>Region:</strong> <?= esc($element['region']) ?></li>
                                <li><strong>District:</strong> <?= esc($element['district']) ?></li>
                                <li><strong>Latitude:</strong> <?= esc($element['latitude']) ?></li>
                                <li><strong>Longitude:</strong> <?= esc($element['longitude']) ?></li>
                            </ul>
                        </div>
                        <?php 
                        if($element['elmType']=='Manhole') { ?>
                        <!-- Right Column -->
                         <div class="col-md-4">
                            <ul class="list-unstyled text-small mb-0">
                                <li><strong>Diameter:</strong> <?= esc($element['manholeDiameter']) ?></li>
                                <li><strong>Depth:</strong> <?= esc($element['manholeDepth']) ?></li>
                                <li><strong>Length:</strong> <?= esc($element['manholeLength']) ?></li>
                                <li><strong>Width:</strong> <?= esc($element['manholeWidth'])?></li> 
                            </ul>
                         </div>
                        <?php } ?>

                    </div>
                    <textarea name="all_ids" id="all-ids" hidden><?php echo implode(',', $links); ?></textarea>
                </div>
                <div class="footer p-3">
                    <small class="text-muted float-left"><strong> <button type="button" id="unlink-all" class="btn btn-xs btn-danger round" data-toggle="modal" data-target="#delink-modal"><i class="fas fa-unlink"></i> Unlink From All</button> </strong> </small>
                    <small class="text-muted float-right"><strong> Created By </strong><?= esc($element['createdBy']) ?> | <strong> Created At</strong> <?= esc($element['createdAt']) ?></small>
                </div>
            </div>

            <!-- Media Linkages -->
            <div class="card text-sm">
                <div class="card-header bg-secondary">
                    <h3 class="card-title"><i class="fas fa-link"></i> Media Linkages</h3>
                </div>
                <div class="card-body">
                    <!-- Downstream Linkages -->
                    <h4 class="mt-1">Downstream Linkages</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table table-striped table-hover table-sm text-sm">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th>Link Id</th>
                                    <th>Media Type</th>
                                    <th>Media Category</th>
                                    <th>Origin Type</th>
                                    <th>Origin Code</th>
                                    <th>District</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Condition</th>
                                    <th>Distance(Meters)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($downstreamElements as $link): 
                                    $url = base_url('infrastructure/element-details/' . $link['elmId']);?>
                                    <tr>
                                        <td><?= esc($link['carryId']) ?></td>
                                        <td><?= esc($link['carryTypeName']) ?></td>
                                        <td><?= esc($link['capacityLabel']) ?></td>
                                        <td><?= esc($link['elmType']) ?></td>
                                        <td><a class="text-primary" title="View Element Details" href="<?= esc($url) ?>"><?= esc($link['elmCode']) ?></a></td>
                                        <td><?= esc($link['district']) ?></td>
                                        <td><?= esc($link['latitude']) ?></td>
                                         <td><?= esc($link['longitude']) ?></td>
                                        <td><?= esc($link['elmCondition']) ?></td>
                                        <td><?= esc($link['distance']) ?></td>
                                        <td>
                                            <button class="btn-danger unlink-element rounded-circle d-flex align-items-center justify-content-center p-0" title="Unlink Element" 
                                                                        style="width:25px; height:25px;"
                                                                        data-toggle="modal" 
                                                                        data-target="#delink-modal">
                                                <i class="fas fa-unlink"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Upstream Linkages -->
                    <h4 class="mb-3">Upstream Linkages</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table table-striped table-hover table-sm text-sm">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th>Link Id</th>
                                    <th>Media Type</th>
                                    <th>Media Category</th>
                                    <th>Destination Type</th>
                                    <th>Destination Code</th>
                                    <th>District</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Condition</th>
                                    <th>Distance(Meters)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($upstreamElements as $link): 
                                    $url = base_url('infrastructure/element-details/' . $link['elmId']);
                                    ?>
                                    <tr>
                                        <td><?= esc($link['carryId']) ?></td>
                                        <td><?= esc($link['carryTypeName']) ?></td>
                                        <td><?= esc($link['capacityLabel']) ?></td>
                                        <td><?= esc($link['elmType']) ?></td>
                                        <td><a href="<?= esc($url) ?>"><?= esc($link['elmCode']) ?></a></td>
                                        <td><?= esc($link['district']) ?></td>
                                        <td><?= esc($link['latitude']) ?></td>
                                        <td><?= esc($link['longitude']) ?></td>
                                        <td><?= esc($link['elmCondition']) ?></td>
                                        <td><?= esc($link['distance']) ?></td>
                                        <td>
                                            <button class="btn-danger unlink-element rounded-circle d-flex align-items-center justify-content-center p-0" title="Unlink Element" 
                                                                        style="width:25px; height:25px;"
                                                                        data-toggle="modal" 
                                                                        data-target="#delink-modal">
                                                <i class="fas fa-unlink"></i>
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
    </section>
</div>

<!-- Delink Modal -->
 <div class="modal fade" id="delink-modal" tabindex="-1" role="dialog" aria-labelledby="delinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="delinkModalLabel">Confirm Unlink</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to unlink this element from all its linkages? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <form action="<?php echo base_url('infrastructure/delink');?>" class="db-submit" method="post">
                    <textarea name="delink_element_ids" id="delink-element-ids" class="d-none"></textarea>
                <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" id="confirm-delink">Unlink</button>
                </form>
            </div>
        </div>
    </div>

<?php echo view('template'.DIRECTORY_SEPARATOR.'partial-footer'); ?>
