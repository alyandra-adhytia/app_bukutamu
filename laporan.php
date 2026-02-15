<?php
include_once('templates/header.php');
require_once('function.php');

if (isset($_POST['tampilkan'])) {
    $p_awal = $_POST['p_awal'];
    $p_akhir = $_POST['p_akhir'];

    $link = "export-laporan.php?cari=true&p_awal=$p_awal&p_akhir=$p_akhir";
    //query sesuai dengan periode yang dipilih
    $buku_tamu = query("SELECT * FROM buku_tamu WHERE tanggal BETWEEN '$p_awal' AND '$p_akhir' ");
} else {
    //query ambil semua data buku tamu
    $buku_tamu = query("SELECT * FROM buku_tamu ORDER BY tanggal DESC");
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Laporan Tamu</h1>

    <div class="row">
        <div class="col-lg-12">

            <!-- Filter Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 border-left-primary">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Periode Laporan</h6>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <div class="form-row align-items-end">
                            <div class="col-md-4 mb-3">
                                <label for="p_awal" class="font-weight-bold">Tanggal Awal</label>
                                <input type="date" class="form-control" id="p_awal" name="p_awal" required
                                    value="<?= isset($_POST['p_awal']) ? $_POST['p_awal'] : '' ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="p_akhir" class="font-weight-bold">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="p_akhir" name="p_akhir" required
                                    value="<?= isset($_POST['p_akhir']) ? $_POST['p_akhir'] : '' ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <button type="submit" name="tampilkan" class="btn btn-primary btn-block">
                                    <i class="fas fa-search fa-sm mr-1"></i> Tampilkan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Table Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Data Buku Tamu</h6>
                    <a href="<?= isset($_POST['tampilkan']) ? $link : 'export-laporan.php'; ?>" target='_blank'
                        class="btn btn-success btn-sm btn-icon-split shadow-sm">
                        <span class="icon text-white-50">
                            <i class="fas fa-file-excel"></i>
                        </span>
                        <span class="text">Export Excel</span>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Tamu</th>
                                    <th>Alamat</th>
                                    <th>No. HP</th>
                                    <th>Bertemu</th>
                                    <th>Kepentingan</th>
                                    <th width="10%">Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($buku_tamu as $tamu): ?>
                                    <tr>
                                        <td align="center"><?= $no++; ?></td>
                                        <td><?= date('d-m-Y', strtotime($tamu['tanggal'])); ?></td>
                                        <td><?= htmlspecialchars($tamu['nama_tamu']); ?></td>
                                        <td><?= htmlspecialchars($tamu['alamat']); ?></td>
                                        <td><?= htmlspecialchars($tamu['no_hp']); ?></td>
                                        <td><?= htmlspecialchars($tamu['bertemu']); ?></td>
                                        <td><?= htmlspecialchars($tamu['kepentingan']); ?></td>
                                        <td class="text-center">
                                            <?php if ($tamu['gambar']): ?>
                                                <img src="src/upload_gambar/<?= $tamu['gambar'] ?>" class="img-thumbnail"
                                                    style="height: 80px; width: 80px; object-fit: cover;" alt="Foto Tamu">
                                            <?php else: ?>
                                                <span class="badge badge-secondary p-2">Tidak ada</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php
include_once('templates/footer.php');
?>