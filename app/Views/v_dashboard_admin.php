<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Welcome, <?= esc($username) ?></h2>
<p>Role: <?= esc($role) ?></p>

<!-- Card displaying the total user count -->
<div class="row">
    <!-- Total Users Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Jumlah Unit</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= esc($userCount) ?> <!-- Menampilkan jumlah unit -->
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Users Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Unit Kosong</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= esc($adminCount) ?> <!-- Menampilkan jumlah unit kosong -->
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Regular Users Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Unit Di Huni</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= esc($userRoleCount) ?> <!-- Menampilkan jumlah unit yang di huni -->
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add more cards or content as needed -->
<?= $this->endSection() ?>
