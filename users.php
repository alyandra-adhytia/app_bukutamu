<?php
include_once('templates/header.php');

//pengecekan user role bukan admin maka tidak boleh mengakses halaman
if($_SESSION['role'] != 'admin'){
    echo"<script>alert('anda tidak memiliki akses')</script>";
    echo"<script>window.location.href='index.php'</script>";
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <?php
    require_once('function.php');
    ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Data User</h1>

    <?php
    // jika ada tombol simpan
    if (isset($_POST['simpan'])) {
        if (tambah_user($_POST) > 0) {
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
    // jika ada tombol ganti password
    else if (isset($_POST['ganti_password'])) {
        if (ganti_password($_POST) > 0) {
            ?>
                <div class="alert alert-success" role="alert">
                    Password berhasil diubah!
                </div>
            <?php
        } else {
            ?>
                <div class="alert alert-danger" role="alert">
                    Password gagal diubah!
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
                <span class="text">Tambah User</span>
            </button>
            <?php
            // mengambil data user dari tabel dengan kode terbesar
            $query = mysqli_query($koneksi, "SELECT max(id_user) as kodeTerbesar FROM users");
            $data = mysqli_fetch_array(result: $query);
            $kodeuser = $data['kodeTerbesar'];
            // mengambil angka dari kode user terbesar, menggunakan fungsi substr dan diubah ke integer dengan (int)
            $urutan = (int) substr($kodeuser, 3);
            // nomor yang diambil akan tambah 1 untuk menentukan nomor urut berikutnya
            $urutan++;

            // membuat kode user baru
            $huruf = "usr";
            $kodeuser = $huruf . sprintf("%02d", $urutan); // using %02d to pad with zeros
            ?>
            <!-- Modal -->
            <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="">
                                <input type="hidden" name="id_user" id="id_user" value="<?= $kodeuser ?>">
                                <div class="form-group row">
                                    <label for="nama_user" class="col-sm-3 col-form-label">Username</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="username" name="username">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama_user" class="col-sm-3 col-form-label">Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="alamat" class="col-sm-3 col-form-label">User Role</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="user_role" id="user_role">
                                            <option value="admin">Administrator</option>
                                            <option value="operator">Operator</option>
                                        </select>
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
            <!-- Modal Ganti Password -->
            <div class="modal fade" id="gantiPassword" tabindex="-1" aria-labelledby="gantiPasswordLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="gantiPasswordLabel">Ganti Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="">
                                <input type="hidden" name="id_user" id="id_user">
                                <div class="form-group row">
                                    <label for="nama_user" class="col-sm-3 col-form-label">Password Baru</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sencondary"
                                        data-dismiss="modal">Keluar</button>
                                    <button type="submit" name="ganti_password" class="btn btn-primary">Simpan</button>
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
                                        class="form-control form-control-sm" placeholder=""
                                        aria-controls="dataTable"></label></div>
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
                                            style="width: 154.2px;">Username</th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Office: activate to sort column ascending"
                                            style="width: 73.2px;">User Role</th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Salary: activate to sort column ascending"
                                            style="width: 106.2px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $users = query("SELECT * FROM users");
                                    foreach ($users as $user):
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $user['username'] ?></td>
                                            <td><?= $user['user_role'] ?></td>
                                            <td class="d-flex justify-content-center align-items-center">
                                                <button type="button" class="btn btn-info btn-icon-split m-1"
                                                    data-toggle="modal" data-target="#gantiPassword"
                                                    data-id="<?= $user['id_user'] ?>">
                                                    <span class="text">Ganti Password</span>
                                                </button>
                                                <a class="btn btn-success"
                                                    href="edit-user.php?id=<?= $user['id_user'] ?>">Ubah</a>
                                                <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                                    href="hapus-user.php?id=<?= $user['id_user'] ?>"
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
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</div>


<!-- /.container-fluid -->

<?php
include_once('templates/footer.php');
?>