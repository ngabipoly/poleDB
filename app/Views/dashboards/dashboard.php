<?php echo view('template/partial-header'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pole Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Pole Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <!-- KPI Cards -->
            <div class="row text-white">
                <div class="col-md-3">
                <div class="card bg-primary">
                    <div class="card-body">
                    <h5>Total Poles</h5>
                    <h3 id="totalPoles">0</h3>
                    </div>
                </div>
                </div>
                <div class="col-md-3">
                <div class="card bg-success">
                    <div class="card-body">
                    <h5>Good Condition</h5>
                    <h3 id="goodPoles">0</h3>
                    </div>
                </div>
                </div>
                <div class="col-md-3">
                <div class="card bg-warning">
                    <div class="card-body">
                    <h5>Replanted</h5>
                    <h3 id="replanted">0</h3>
                    </div>
                </div>
                </div>
                <div class="col-md-3">
                <div class="card bg-info">
                    <div class="card-body">
                    <h5>Added This Month</h5>
                    <h3 id="monthlyAddition">0</h3>
                    </div>
                </div>
                </div>
            </div>
            <!-- Charts Row -->
            <div class="row my-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Poles Condition</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="conditionChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h3 class="card-title">Pole Size Distribution</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="sizeChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">Regional Pole Distribution</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="regionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>

</script>
<?php echo view('template/partial-footer'); ?>