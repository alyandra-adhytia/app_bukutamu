<?php
require_once('function.php');
include_once('templates/header.php');
?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET[
        'id'];
    if (hapus_tamu($id) > 0) {
        echo "<script>alert('Data berhasil di hapus!');</script>";
        echo "<script>window.location.href='./buku-tamu.php'</script>";
    } else {
        echo "<script>window.alert('Data gagal dihapus!');</script>";
    }
}
?>

<?= require_once("templates/header.php") ?>;