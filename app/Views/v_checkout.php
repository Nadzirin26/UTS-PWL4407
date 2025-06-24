<?= $this->extend('layout') ?>
<?= $this->section('content') ?> 

<?php
$total_berat = $total_berat ?? 0;
?>

<div class="row">
    <div class="col-lg-6">
        <?= form_open('buy', 'class="row g-3"') ?>
        <?= form_hidden('username', session()->get('username')) ?>
        <?= form_input(['type' => 'hidden', 'name' => 'total_harga', 'id' => 'total_harga', 'value' => '']) ?>
        <?= form_input(['type' => 'hidden', 'name' => 'total_berat', 'id' => 'total_berat', 'value' => $total_berat]) ?>

        <div class="col-12">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" value="<?= session()->get('username'); ?>" readonly>
        </div>
        <div class="col-12">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" required>
        </div> 
        <div class="col-12">
            <label for="kelurahan" class="form-label">Kelurahan</label>
            <select class="form-control" id="kelurahan" name="kelurahan" required>
            </select>
        </div>
        <div class="col-12">
            <label for="layanan" class="form-label">Layanan</label>
            <select class="form-control" id="layanan" name="layanan" required>
                <option value="selected" selected> - Pilih jenis pengiriman - </option>
            </select>
        </div>
        <div class="col-12">
            <label for="ongkir" class="form-label">Ongkir</label>
            <input type="text" class="form-control" id="ongkir" name="ongkir" readonly>
        </div>
    </div>
    <div class="col-lg-6">
        <!-- Table -->
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Berat</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= $item['name'] ?></td> 
                            <td><?= $item['weight'] ?></td> 
                            <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3"></td>
                        <td>Total Berat Barang</td>
                        <td><?= $total_berat?></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td>Subtotal</td>
                        <td><?= number_to_currency($total, 'IDR') ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td>Total</td>
                        <td><span id="total"><?= number_to_currency($total, 'IDR') ?></span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Buat Pesanan</button>
        </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    let ongkir = 0;
    let total = 0;
    let total_berat = <?= $total_berat ?>;
    $("#total_berat").val(total_berat);

    hitungTotal();

    function hitungTotal() {
        total = ongkir + <?= $total ?>;
        $("#ongkir").val(ongkir);
        $("#total").html("IDR " + total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
        $("#total_harga").val(total);
    }

    $('#kelurahan').select2({
        placeholder: 'Ketik nama kelurahan...',
        ajax: {
            url: '<?= base_url('get-location') ?>',
            dataType: 'json',
            delay: 1500,
            data: function (params) {
                return { search: params.term };
            },
            processResults: function (data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.subdistrict_name + ", " + item.district_name + ", " + item.city_name + ", " + item.province_name + ", " + item.zip_code
                        };
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 3
    });

    $("#kelurahan").on('change', function() {
        const id_kelurahan = $(this).val(); 
        const selectedOption = $("#layanan").val();
        $("#layanan").empty().append('<option selected disabled>- Pilih jenis pengiriman -</option>');
        ongkir = 0;

        $.ajax({
            url: "<?= site_url('get-cost') ?>",
            type: 'GET',
            data: { 
                destination: id_kelurahan,
                weight: total_berat
            },
            dataType: 'json',
            success: function(data) { 
                data.forEach(function(item) {
                    let cost = item["cost"];
                    let text = item["description"] + " (" + item["service"] + ") : estimasi " + item["etd"];
                    let option = $('<option>', {
                        value: cost,
                        text: text,
                        selected: cost == selectedOption
                    });
                    $("#layanan").append(option);
                });

                if ($("#layanan").val()) {
                    ongkir = parseInt($("#layanan").val());
                }

                hitungTotal(); 
            },
        });
    });

    $("#layanan").on('change', function() {
        ongkir = parseInt($(this).val());
        hitungTotal();
    });  
});
</script>
<?= $this->endSection() ?>