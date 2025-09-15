<?php 
include 'koneksi.php';

// ambil id siswa dari url
$id = $_GET['id'];

// ambil data siswa lama
$siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE id=$id"));

if(isset($_POST['update'])) {
    $nama     = $_POST['nama'];
    $kelas    = $_POST['kelas'];
    $jurusan  = $_POST['jurusan'];
    $email    = $_POST['email'];
    $no_hp    = $_POST['no_hp'];
    $foto_lama = $siswa['foto'];

    // cek apakah ada file baru
    if($_FILES['foto']['name'] != "") {
        $nama_file = $_FILES['foto']['name'];
        $tmp_file  = $_FILES['foto']['tmp_name'];
        $path      = "uploads/" . $nama_file;

        if(move_uploaded_file($tmp_file, $path)){
            // hapus foto lama kalau ada
            if(file_exists("uploads/" . $foto_lama) && $foto_lama != ""){
                unlink("uploads/" . $foto_lama);
            }
            $foto = $nama_file;
        } else {
            $foto = $foto_lama;
        }
    } else {
        $foto = $foto_lama;
    }

    // update ke database
    mysqli_query($conn, "UPDATE siswa
        SET nama='$nama', foto='$foto', kelas='$kelas', jurusan='$jurusan', email='$email', no_hp='$no_hp'
        WHERE id=$id");

    // redirect supaya tidak reload form dan muncul warning
    header("Location: index.php");
    exit;
}
?>

<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl p-8">
  <h2 class="text-2xl font-bold text-gray-700 mb-6">Update Data Siswa</h2>
  
  <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
    <div>
      <label class="block text-sm font-medium text-gray-600">Nama</label>
      <input type="text" name="nama" value="<?php echo $siswa['nama']; ?>" 
             class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-600">Kelas</label>
      <input type="text" name="kelas" value="<?php echo $siswa['kelas']; ?>" 
             class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-600">Jurusan</label>
      <input type="text" name="jurusan" value="<?php echo $siswa['jurusan']; ?>" 
             class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-600">Email</label>
      <input type="email" name="email" value="<?php echo $siswa['email']; ?>" 
             class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-600">No HP</label>
      <input type="text" name="no_hp" value="<?php echo $siswa['no_hp']; ?>" 
             class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-600">Foto</label>
      <div class="flex items-center gap-4 mt-2">
        <img src="uploads/<?php echo $siswa['foto']; ?>" 
             alt="Foto Siswa" 
             class="w-20 h-20 object-cover rounded-lg border">
        <input type="file" name="foto" 
               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                      file:rounded-lg file:border-0
                      file:text-sm file:font-semibold
                      file:bg-blue-50 file:text-blue-600
                      hover:file:bg-blue-100">
      </div>
    </div>

    <div class="flex justify-end gap-3 pt-4">
      <a href="index.php" 
         class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition">
        Batal
      </a>
      <button type="submit" name="update" 
              class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
        Update
      </button>
    </div>
  </form>
</div>
