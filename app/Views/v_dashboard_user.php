<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h2>Welcome, <?= esc($username) ?></h2>
<p>Role: <?= esc($role) ?></p>

<!-- Card untuk Menampilkan Informasi Langganan Gym -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title">Harga Unit Rumah</h5>
                <p><strong>Plan:</strong> 1 unit </p>
                <p><strong>Price:</strong> 1.500.000.000</p>
                <p><strong>Status:</strong> Pemilik Rumah di Green House </p>
                
                <!-- Tambahkan tombol untuk mengelola langganan (misalnya, pembaruan) -->
            </div>
        </div>
    </div>

        <!-- Card untuk Menampilkan Jumlah Pengunjung Gym -->
    
</div>

<?= $this->endSection() ?>
