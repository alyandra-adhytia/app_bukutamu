<?php
include_once('templates/header.php');
//pengecekan user role bukan operator maka tidak boleh mengakses halaman
if($_SESSION['role'] != 'operator'){
    echo"<script>alert('anda tidak memiliki akses')</script>";
    echo"<script>window.location.href='index.php'</script>";
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <?php
    require_once('function.php');
    include_once('templates/header.php');
    ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Buku Tamu</h1>


    <?php
    // jika ada tombol simpan
    if (isset($_POST['simpan'])) {
        if (tambah_tamu($_POST) > 0) {
            ?>
            <div class="alert alert-success" role="alert">
                Data berhasil simpan!
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-danger" role="alert">
                Data gagal disimpan!
            </div>
            <?php
        }
    }
    ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#tambahModal">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Data Tamu</span>
            </button>
            <?php
            // mengambil data barang dari tabel dengan kode terbesar
            $query = mysqli_query($koneksi, "SELECT max(id_tamu) as kodeTerbesar FROM buku_tamu");
            $data = mysqli_fetch_array($query);
            $kodeTamu = $data['kodeTerbesar'] ?? '';
            // mengambil angka dari kode barang terbesar, menggunakan fungsi substr dan diubah ke integer dengan (int)
            if ($kodeTamu !== '' && strlen($kodeTamu) >= 3) {
                $urutan = (int) substr($kodeTamu, 2, 3);
            } else {
                $urutan = 0;
            }
            // nomor yang diambil akan tambah 1 untuk menentukan nomor urut berikutnya
            $urutan++;

            //membuat kode barang baru
            // string sprintf("%03s", $urutan); berfungsi untuk membuat string menjadi 3 karakter

            // angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misal zt
            $huruf = "zt";
            $kodeTamu = $huruf . sprintf("%03s", $urutan);
            ?>
            <!-- Modal -->
            <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data Tamu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="" enctype="multipart/form-data">
                                <input type="hidden" name="id_tamu" id="id_tamu" value="<?= $kodeTamu ?>">
                                <div class="form-group row">
                                    <label for="nama_tamu" class="col-sm-3 col-form-label">Nama Tamu</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nama_tamu" name="nama_tamu">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" id="alamat" name="alamat"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="no_hp" class="col-sm-3 col-form-label">No. Telepon</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="no_hp" name="no_hp">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bertemu" class="col-sm-3 col-form-label">Bertemu Dengan</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="bertemu" name="bertemu">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kepentingan" class="col-sm-3 col-form-label">Kepentingan</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="kepentingan" name="kepentingan">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="gambar" class="col-sm-3 col-form-label">Unggah Foto</label>
                                    <div class="custom-file col-sm-8">
                                        <input type="file" class="custom-file-input" id="gambar" name="gambar">
                                        <label class="custom-file-label" for="gambar">Choose file</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sencondary"
                                        data-dismiss="modal">Keluar</button>
                                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="dataTable_length"><label>Show <select
                                        name="dataTable_length" aria-controls="dataTable"
                                        class="custom-select custom-select-sm form-control form-control-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>entries</label></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="dataTable_filter" class="dataTables_filter"><label>Search:<input type="search"
                            class="form-control form-control-sm" placeholder="" aria-controls="dataTable"></label></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0"
                                role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="dataTable"
                                            rowspan="1" colspan="1" aria-sort="ascending"
                                            aria-label="Name: activate to sort column descending"
                                            style="width: 20.2px;">No</th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Position: activate to sort column ascending"
                                            style="width: 154.2px;">Tanggal</th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Office: activate to sort column ascending"
                                            style="width: 73.2px;">Nama Tamu</th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Age: activate to sort column ascending"
                                            style="width: 30.2px;">No. Telp/HP</th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Start date: activate to sort column ascending"
                                            style="width: 74.2px;">Alamat</th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Salary: activate to sort column ascending"
                                            style="width: 66.2px;">Kepentingan</th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Salary: activate to sort column ascending"
                                            style="width: 66.2px;">Bertemu Dengan</th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Salary: activate to sort column ascending"
                                            style="width: 66.2px;">Gambar</th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Salary: activate to sort column ascending"
                                            style="width: 106.2px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $buku_tamu = query("SELECT * FROM buku_tamu");
                                    foreach ($buku_tamu as $tamu):
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $tamu['tanggal'] ?></td>
                                            <td><?= $tamu['nama_tamu'] ?></td>
                                            <td><?= $tamu['no_hp'] ?></td>
                                            <td><?= $tamu['alamat'] ?></td>
                                            <td><?= $tamu['bertemu'] ?></td>
                                            <td><?= $tamu['kepentingan'] ?></td>
                                            <td>
                                                <?php if ($tamu['gambar']) : ?>
                                                    <img src="src/upload_gambar/<?= $tamu['gambar'] ?>" width="60" alt="Foto Tamu">
                                                <?php else : ?>
                                                    <span>Tidak ada foto</span>
                                                <?php endif; ?>
                                            </td> <!-- Tampilkan gambar -->
                                            <td class="d-flex justify-content-center align-items-center">
                                                <a class="btn btn-success"
                                                    href="edit-tamu.php?id=<?= $tamu['id_tamu'] ?>">Ubah</a>
                                                <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                                    href="hapus-tamu.php?id=<?= $tamu['id_tamu'] ?>"
                                                    class="btn btn-danger m-1" type="button">Hapus</a>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">Showing 1
                                to 10 of 57 entries</div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button page-item previous disabled" id="dataTable_previous"><a
                                            href="#" aria-controls="dataTable" data-dt-idx="0" tabindex="0"
                                            class="page-link">Previous</a></li>
                                    <li class="paginate_button page-item active"><a href="#" aria-controls="dataTable"
                                            data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
                                    <li class="paginate_button page-item "><a href="#" aria-controls="dataTable"
                                            data-dt-idx="2" tabindex="0" class="page-link">2</a></li>
                                    <li class="paginate_button page-item "><a href="#" aria-controls="dataTable"
                                            data-dt-idx="3" tabindex="0" class="page-link">3</a></li>
                                    <li class="paginate_button page-item "><a href="#" aria-controls="dataTable"
                                            data-dt-idx="4" tabindex="0" class="page-link">4</a></li>
                                    <li class="paginate_button page-item "><a href="#" aria-controls="dataTable"
                                            data-dt-idx="5" tabindex="0" class="page-link">5</a></li>
                                    <li class="paginate_button page-item "><a href="#" aria-controls="dataTable"
                                            data-dt-idx="6" tabindex="0" class="page-link">6</a></li>
                                    <li class="paginate_button page-item next" id="dataTable_next"><a href="#"
                                            aria-controls="dataTable" data-dt-idx="7" tabindex="0"
                                            class="page-link">Next</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page level plugins -->
    <script src="src/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="src/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="src/js/demo/datatables-demo.js"></script>

</div>


<!-- /.container-fluid -->

<?php
include_once('templates/footer.php');
?>