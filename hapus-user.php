<?php
require_once('function.php');
include_once('templates/header.php');
?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET[
        'id'];
    if (hapus_user($id) > 0) {
        // jika data berhasil di hapus maka akan muncul alert
        echo "<script>alert('Data berhasil di hapus!');</script>";
        // redirect ke halaman users.php
        echo "<script>window.location.href='./users.php'</script>";
    } else {
        // jika gagal di hapus
        echo "<script>window.alert('Data gagal dihapus!');</script>";
        echo "<script>window.location.href='./users.php'</script>";
    }
}
?>

<?= require_once("templates/header.php") ?>;