<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <style>
    * { box-sizing: border-box; }

    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #f4f6f9;
      color: #333;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      padding: 40px 20px;
    }

    h1, h2 {
      text-align: center;
      color: #2c3e50;
    }

    .card {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      margin-bottom: 40px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .add-btn {
      display: inline-block;
      padding: 10px 16px;
      margin-bottom: 20px;
      background: #007bff;
      color: white;
      border-radius: 6px;
      text-decoration: none;
      font-size: 14px;
      transition: background 0.3s;
    }

    .add-btn:hover {
      background: #0056b3;
    }

    .btn-edit {
      background: #ffc107;
      color: black;
    }

    .btn-delete {
      background: #dc3545;
      color: white;
      border: none;
      cursor: pointer;
    }

    .btn-message {
      background: #17a2b8;
      color: white;
    }

    .success-message {
      background-color: #d4edda;
      color: #155724;
      padding: 10px 15px;
      margin-bottom: 20px;
      border-left: 5px solid #28a745;
      border-radius: 5px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 14px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background: #2c3e50;
      color: white;
      font-size: 14px;
    }

    tr:hover {
      background: #f9f9f9;
    }

    img {
      width: 100px;
      border-radius: 6px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .badge-admin {
      background-color: #28a745;
      color: white;
      padding: 5px 10px;
      border-radius: 12px;
      font-size: 12px;
    }

    .badge-user {
      background-color: #6c757d;
      color: white;
      padding: 5px 10px;
      border-radius: 12px;
      font-size: 12px;
    }

    .notif-badge {
      background: red;
      color: white;
      font-size: 11px;
      padding: 2px 7px;
      border-radius: 10px;
      margin-left: 5px;
    }

    .action-buttons a,
    .action-buttons form {
      display: inline-block;
      margin-right: 5px;
    }

    @media (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      thead {
        display: none;
      }

      tr {
        margin-bottom: 15px;
        background: white;
        border-radius: 8px;
        padding: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      }

      td {
        padding: 10px;
        border: none;
        position: relative;
      }

      td::before {
        content: attr(data-label);
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
        color: #6c757d;
      }
    }
  </style>
</head>
<body>

  <div class="container">

    <div class="card">
      <h1>Daftar Produk</h1>

      <a href="{{ route('admin.products.create') }}" class="add-btn">+ Tambah Produk</a>

      @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
      @endif

      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>Harga</th>
            <th>Kategori</th>
            <th>Stok</th> <!-- ✅ Tambahan kolom Stok -->
            <th>Deskripsi</th>
            <th>Gambar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($products as $p)
          <tr>
            <td data-label="Nama">{{ $p->name }}</td>
            <td data-label="Harga">Rp{{ number_format($p->price, 0, ',', '.') }}</td>
            <td data-label="Kategori">{{ $p->category->name ?? '-' }}</td>
            <td data-label="Stok">{{ $p->stock }}</td> <!-- ✅ Menampilkan stok -->
            <td data-label="Deskripsi">{{ $p->description }}</td>
            <td data-label="Gambar">
              @if($p->image_url)
                <img src="{{ $p->image_url }}" alt="{{ $p->name }}">
              @else
                <em>Tidak ada gambar</em>
              @endif
            </td>
            <td class="action-buttons" data-label="Aksi">
              <a href="{{ route('admin.products.edit', $p->id) }}" class="add-btn btn-edit">✏️ Edit</a>
              <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="add-btn btn-delete">🗑️ Hapus</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="card">
      <h2>Daftar Pengguna</h2>

      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Terdaftar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td data-label="Nama">{{ $user->name }}</td>
            <td data-label="Email">{{ $user->email }}</td>
            <td data-label="Role">
              @if($user->role === 'admin')
                <span class="badge-admin">Admin</span>
              @else
                <span class="badge-user">User</span>
              @endif
            </td>
            <td data-label="Terdaftar">{{ $user->created_at->format('d M Y') }}</td>
            <td data-label="Aksi">
              <a href="{{ route('admin.chat', $user->id) }}" class="add-btn btn-message">
                💬 Pesan
                @if(isset($unreadByUser[$user->id]))
                  <span class="notif-badge">{{ $unreadByUser[$user->id] }}</span>
                @endif
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>

</body>
</html>
