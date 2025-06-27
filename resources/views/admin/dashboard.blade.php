<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 40px;
    }

    h1, h2 {
      text-align: center;
      color: #333;
    }

    .section {
      max-width: 1000px;
      margin: 30px auto;
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .add-btn {
      display: inline-block;
      margin-bottom: 20px;
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      transition: background-color 0.3s;
    }

    .add-btn:hover {
      background-color: #0056b3;
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
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
      vertical-align: top;
    }

    th {
      background-color: #007bff;
      color: white;
    }

    tr:hover {
      background-color: #f1f1f1;
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
  </style>
</head>
<body>

  <div class="section">
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
          <th>Deskripsi</th>
          <th>Gambar</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $p)
        <tr>
          <td>{{ $p->name }}</td>
          <td>Rp{{ number_format($p->price, 0, ',', '.') }}</td>
          <td>{{ $p->category->name ?? '-' }}</td>
          <td>{{ $p->description }}</td>
          <td>
            @if($p->image_url)
              <img src="{{ $p->image_url }}" alt="{{ $p->name }}">
            @else
              <em>Tidak ada gambar</em>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="section">
    <h2>Daftar Pengguna Terdaftar</h2>

    <table>
      <thead>
        <tr>
          <th>Nama</th>
          <th>Email</th>
          <th>Role</th>
          <th>Terdaftar Sejak</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>
            @if($user->role === 'admin')
              <span class="badge-admin">Admin</span>
            @else
              <span class="badge-user">User</span>
            @endif
          </td>
          <td>{{ $user->created_at->format('d M Y') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</body>
</html>
