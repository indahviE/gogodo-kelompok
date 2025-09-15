<?php
include 'koneksi.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM user WHERE id='$id'");

echo "<script>alert('Data berhasil dihapus'); window.location='tabel.php';</script>";
