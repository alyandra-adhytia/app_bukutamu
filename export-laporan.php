<?php
include('koneksi.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Menetapkan baris header
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'TANGGAL');
$sheet->setCellValue('C1', 'NAMA TAMU');
$sheet->setCellValue('D1', 'ALAMAT');
$sheet->setCellValue('E1', 'NO TELEPON/HP');
$sheet->setCellValue('F1', 'BERTEMU DENGAN');
$sheet->setCellValue('G1', 'KEPENTINGAN');

// Memeriksa apakah 'cari' diatur dan parameter tanggal valid
if (isset($_GET['cari']) && !empty($_GET['p_awal']) && !empty($_GET['p_akhir'])) {
    $p_awal = $_GET['p_awal'];
    $p_akhir = $_GET['p_akhir'];
    
    // Memvalidasi format tanggal (opsional tapi direkomendasikan)
    if (DateTime::createFromFormat('Y-m-d', $p_awal) && DateTime::createFromFormat('Y-m-d', $p_akhir)) {
        $data = mysqli_query($koneksi, "SELECT * FROM buku_tamu WHERE tanggal BETWEEN '$p_awal' AND '$p_akhir'");
    } else {
        // Jika tanggal tidak valid, fallback ke semua data
        $data = mysqli_query($koneksi, "SELECT * FROM buku_tamu");
    }
} else {
    // Jika tidak ada pencarian atau tanggal kosong, ambil semua data
    $data = mysqli_query($koneksi, "SELECT * FROM buku_tamu");
}

$i = 2; // Mulai dari baris 2 (setelah header)
$no = 1; // Inisialisasi nomor baris
while ($d = mysqli_fetch_array($data)) {
    $sheet->setCellValue('A' . $i, $no++);
    $sheet->setCellValue('B' . $i, $d['tanggal']);
    $sheet->setCellValue('C' . $i, $d['nama_tamu']);
    $sheet->setCellValue('D' . $i, $d['alamat']);
    $sheet->setCellValue('E' . $i, $d['no_hp']);
    $sheet->setCellValue('F' . $i, $d['bertemu']);
    $sheet->setCellValue('G' . $i, $d['kepentingan']);
    $i++;
}

$writer = new Xlsx($spreadsheet);
$filename = 'Laporan_Buku_Tamu.xlsx';
$writer->save($filename);

// Berikan pengguna link unduhan
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"$filename\"");
readfile($filename);

// Hapus file setelah diunduh untuk mencegah penumpukan di server
unlink($filename);
?>
