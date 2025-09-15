<?php
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $folder = "upload/";
    $nama_file = time() . "_" . basename($_FILES['foto']['name']);
    $target_file = $folder . $nama_file;
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];


    // Upload file
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
        // Simpan hanya nama file ke database
        $sql = "INSERT INTO siswa (nama, foto, kelas, jurusan, email, no_hp) 
                VALUES ('$nama','$nama_file','$kelas','$jurusan','$email','$no_hp')";
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Gagal menyimpan ke database: " . mysqli_error($conn);
        }
    } else {
        echo "Upload gambar gagal!";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="../css/crud.css">
</head>

<body>

    <div class="container">
        <h2>📝 Tambah Barang</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Nama</label>
            <input type="text" name="nama" required><br>

            <label>Foto</label>
            <input type="file" name="foto" accept="image/*" required><br>

            <label>Kelas</label>
            <input type="text" name="kelas" required><br>

            <label>Jurusan</label>
            <input type="text" name="jurusan" required><br>

            <label>Email</label>
            <input type="text" name="email" required><br>

            <label>No Handphone</label>
            <input type="text" name="no_hp" required><br>

            <button type="submit" name="submit">Simpan</button>
        </form>
    </div>

</body>

</html>