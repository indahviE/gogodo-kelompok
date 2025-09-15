<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Siswa</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center p-8">
  <div class="w-full max-w-6xl bg-white shadow-xl rounded-xl p-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Data Siswa</h2>
    <a href="create.php" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">+ Tambah Data</a>

    <div class="overflow-x-auto mt-6">
      <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-200 text-gray-700">
          <tr>
            <th class="px-4 py-2">No</th>
            <th class="px-4 py-2">Foto</th>
            <th class="px-4 py-2">Nama</th>
            <th class="px-4 py-2">Kelas</th>
            <th class="px-4 py-2">Jurusan</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">No HP</th>
            <th class="px-4 py-2">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php
          $no = 1;
          $result = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY id DESC");
          while ($row = mysqli_fetch_assoc($result)) {
          ?>
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-2 text-center"><?= $no++ ?></td>
            <td class="px-4 py-2 text-center">
              <img src="uploads/<?= $row['foto'] ?>" class="w-12 h-12 rounded-full mx-auto">
            </td>
            <td class="px-4 py-2"><?= $row['nama'] ?></td>
            <td class="px-4 py-2"><?= $row['kelas'] ?></td>
            <td class="px-4 py-2"><?= $row['jurusan'] ?></td>
            <td class="px-4 py-2"><?= $row['email'] ?></td>
            <td class="px-4 py-2"><?= $row['nohp'] ?></td>
            <td class="px-4 py-2 text-center space-x-2">
              <a href="update.php?id=<?= $row['id'] ?>" class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">Edit</a>
              <a href="deleto.php?id=<?= $row['id'] ?>" onclick="return confirm('Apakah yakin data ingin dihapus?')" class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">Hapus</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>

<!-- www -->