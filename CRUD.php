<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CRUD</title>
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f7fa;
      margin: 0;
      padding: 20px;
      display: flex;
      justify-content: center;
    }
    .container {
      max-width: 700px;
      width: 100%;
      background: white;
      padding: 20px 30px;
      border-radius: 8px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }
    form {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
      flex-wrap: wrap;
    }
    form input[type="text"] {
      flex: 1;
      padding: 10px 12px;
      font-size: 16px;
      border: 1.5px solid #ccc;
      border-radius: 6px;
      transition: border-color 0.3s ease;
    }
    form input[type="text"]:focus {
      outline: none;
      border-color: #007bff;
    }
    form button {
      background-color: #007bff;
      border: none;
      padding: 12px 20px;
      color: white;
      font-weight: 600;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    form button:hover {
      background-color: #0056b3;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }
    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #eee;
    }
    th {
      background-color: #007bff;
      color: white;
      font-weight: 600;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .btn-edit, .btn-delete {
      padding: 6px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 600;
      transition: background-color 0.3s ease;
      color: white;
    }
    .btn-edit {
      background-color: #28a745;
      margin-right: 8px;
    }
    .btn-edit:hover {
      background-color: #1e7e34;
    }
    .btn-delete {
      background-color: #dc3545;
    }
    .btn-delete:hover {
      background-color: #a71d2a;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>CRUD</h1>
    <form id="crud-form">
      <input type="text" name="nama" placeholder="Nama" required />
      <input type="file" name="foto" accept="image/*" required />
      <input type="text" name="kelas" placeholder="Kelas" required />
      <input type="text" name="jurusan" placeholder="Jurusan" required />
      <input type="text" name="email" placeholder="Email" required />
      <input type="text" name="no_hp" placeholder="No HP" required />
      <button type="submit" id="submit-btn">Tambah</button>
    </form>

    <table id="data-table">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Kelas</th>
          <th>Jurusan</th>
          <th>Email</th>
          <th>No HP</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
  </div>

  <script>
  
    const form = document.getElementById('crud-form');
    const inputnama = document.getElementById('input-nama');
    const inputkelas = document.getElementById('input-kelas');
    const inputjurusan = document.getElementById('input-jurusan');
    const inputemail = document.getElementById('input-email');
    const inputno_hp = document.getElementById('input-no_hp');
    const submitBtn = document.getElementById('submit-btn');
    const tbody = document.querySelector('#data-table tbody');

   
    let dataList = [];
    let editIndex = -1;

  
   
    function renderTable() {
      tbody.innerHTML = ''; 

      dataList.forEach((item, index) => {
        const row = document.createElement('tr');

        row.innerHTML = `
          <td>${item.name}</td>
          <td>${item.email}</td>
          <td>
            <button class="btn-edit" data-index="${index}">Edit</button>
            <button class="btn-delete" data-index="${index}">Hapus</button>
          </td>
        `;

        tbody.appendChild(row);
      });
    }

    
    function createData(index, name, kelas, jurusan, email, no_hp) {
      dataList.push({ name, kelas, jurusan, email, no_hp });
      renderTable();
    }

    
    function updateData(index, name, kelas, jurusan, email, no_hp) {
      if (index >= 0 && index < dataList.length) {
        dataList[index] = {index, name, kelas, jurusan, email, no_hp};
        renderTable();
      }
    }

    
    function deleteData(index) {
      if (index >= 0 && index < dataList.length) {
        dataList.splice(index, 1);
        renderTable();
      }
    }

   
    function resetForm() {
      inputName.value = '';
      inputEmail.value = '';
      editIndex = -1;
      submitBtn.textContent = 'Tambah';
    }

   
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      const namaVal = inputnama.value.trim();
      const kelasVal = inputkelas.value.trim();
      const jurusanVal = inputjurusan.value.trim();
      const emailVal = inputemail.value.trim();
      const no_hpVal = inputno_hp.value.trim();

      if (!nameVal || !emailVal) {
        alert('Harap isi semua field!');
        return;
      }

      if (editIndex === -1) {
       
        createData(namaVal, kelasVal, jurusanVal, emailVal, no_hpVal);
      } else {
       
        updateData(editIndex, namaVal, kelasVal, jurusanVal, emailVal, no_hpVal);
      }

      resetForm();
    });

   
    tbody.addEventListener('click', function(e) {
      const target = e.target;
      const index = target.getAttribute('data-index');

      if (target.classList.contains('btn-edit')) {
        
        const item = dataList[index];
        inputName.value = item.name;
        inputEmail.value = item.email;
        editIndex = Number(index);
        submitBtn.textContent = 'Update';
      } else if (target.classList.contains('btn-delete')) {
        if (confirm('Yakin ingin menghapus data ini?')) {
          deleteData(Number(index));
          if (editIndex === Number(index)) {
            resetForm();
          }
        }
      }
    });

    renderTable();

  </script>
</body>
</html>