<?php
// update.php
session_start();
include 'koneksi.php';

// wajib ada id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['msg'] = "ID tidak valid.";
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];

// ambil data siswa
$stmt = mysqli_prepare($conn, "SELECT id, nama, foto, kelas, jurusan, email, no_hp FROM siswa WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$siswa) {
    $_SESSION['msg'] = "Data siswa tidak ditemukan.";
    header("Location: index.php");
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // ambil input dan bersihkan
    $nama    = trim($_POST['nama']);
    $kelas   = trim($_POST['kelas']);
    $jurusan = trim($_POST['jurusan']);
    $email   = trim($_POST['email']);
    $no_hp   = trim($_POST['no_hp']);
    $foto_lama = $siswa['foto'];

    // validasi sederhana
    if ($nama === '') $errors[] = "Nama wajib diisi.";
    if ($email === '') $errors[] = "Email wajib diisi.";

    // handle upload (jika ada)
    $foto = $foto_lama; // default tetap foto lama
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        // cek error upload
        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Terjadi kesalahan saat upload file.";
        } else {
            $tmp = $_FILES['foto']['tmp_name'];
            $originalName = basename($_FILES['foto']['name']);
            $size = $_FILES['foto']['size'];

            // validasi ukuran (2MB)
            if ($size > 2 * 1024 * 1024) {
                $errors[] = "Ukuran file tidak boleh lebih dari 2MB.";
            }

            // validasi mime type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp);
            finfo_close($finfo);

            $allowed = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($mime, $allowed)) {
                $errors[] = "Tipe file tidak diizinkan. Hanya JPG/PNG.";
            }

            if (empty($errors)) {
                // buat nama unik
                $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                $newName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
                $dest = __DIR__ . '/uploads/' . $newName;

                if (move_uploaded_file($tmp, $dest)) {
                    // hapus foto lama jika ada
                    if ($foto_lama && file_exists(__DIR__ . '/uploads/' . $foto_lama)) {
                        @unlink(__DIR__ . '/uploads/' . $foto_lama);
                    }
                    $foto = $newName;
                } else {
                    $errors[] = "Gagal memindahkan file upload.";
                }
            }
        }
    }

    if (empty($errors)) {
        // update menggunakan prepared statement
        $update = mysqli_prepare($conn, "UPDATE siswa SET nama = ?, foto = ?, kelas = ?, jurusan = ?, email = ?, no_hp = ? WHERE id = ?");
        mysqli_stmt_bind_param($update, "ssssssi", $nama, $foto, $kelas, $jurusan, $email, $no_hp, $id);
        $ok = mysqli_stmt_execute($update);
        mysqli_stmt_close($update);

        if ($ok) {
            $_SESSION['msg'] = "Data berhasil diupdate.";
            // reload data terbaru (redirect agar nilai $siswa update)
            header("Location: update.php?id=$id");
            exit;
        } else {
            $errors[] = "Gagal update data: " . mysqli_error($conn);
        }
    }
}

// reload data (agar tampilan menampilkan update terbaru)
$stmt2 = mysqli_prepare($conn, "SELECT id, nama, foto, kelas, jurusan, email, no_hp FROM siswa WHERE id = ?");
mysqli_stmt_bind_param($stmt2, "i", $id);
mysqli_stmt_execute($stmt2);
$res2 = mysqli_stmt_get_result($stmt2);
$siswa = mysqli_fetch_assoc($res2);
mysqli_stmt_close($stmt2);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Update Siswa - <?= htmlspecialchars($siswa['nama']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
  <div class="max-w-2xl mx-auto">
    <a href="index.php" class="text-sm text-gray-500 hover:underline mb-4 inline-block">← Kembali</a>
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-2xl font-semibold mb-4">Update Data Siswa</h2>

      <?php if(!empty($errors)): ?>
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
          <ul class="list-disc pl-5">
            <?php foreach($errors as $e): ?>
              <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if(isset($_SESSION['msg'])): ?>
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">
          <?= htmlspecialchars($_SESSION['msg']); unset($_SESSION['msg']); ?>
        </div>
      <?php endif; ?>

      <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-600">Nama</label>
          <input type="text" name="nama" value="<?= htmlspecialchars($siswa['nama']) ?>" class="w-full mt-1 px-4 py-2 border rounded-lg">
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-600">Kelas</label>
            <input type="text" name="kelas" value="<?= htmlspecialchars($siswa['kelas']) ?>" class="w-full mt-1 px-4 py-2 border rounded-lg">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600">Jurusan</label>
            <input type="text" name="jurusan" value="<?= htmlspecialchars($siswa['jurusan']) ?>" class="w-full mt-1 px-4 py-2 border rounded-lg">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-600">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($siswa['email']) ?>" class="w-full mt-1 px-4 py-2 border rounded-lg">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600">No HP</label>
            <input type="text" name="no_hp" value="<?= htmlspecialchars($siswa['no_hp']) ?>" class="w-full mt-1 px-4 py-2 border rounded-lg">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600">Foto</label>
          <div class="flex items-center gap-4 mt-2">
            <?php if(!empty($siswa['foto']) && file_exists(__DIR__ . '/uploads/' . $siswa['foto'])): ?>
              <img src="uploads/<?= htmlspecialchars($siswa['foto']) ?>" alt="Foto Siswa" class="w-24 h-24 object-cover rounded border">
            <?php else: ?>
              <div class="w-24 h-24 bg-gray-200 flex items-center justify-center rounded text-sm text-gray-500">No Photo</div>
            <?php endif; ?>

            <div class="w-full">
              <input type="file" name="foto" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
              <p class="text-xs text-gray-500 mt-2">Biarkan kosong jika tidak ingin mengganti foto. Maks 2MB. JPG/PNG.</p>
            </div>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <a href="index.php" class="bg-gray-500 text-white px-5 py-2 rounded hover:bg-gray-600">Batal</a>
          <button type="submit" name="update" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
