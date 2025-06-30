<!DOCTYPE html>
<html>
<head>
  <title>Tambah Produk</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 40px;
    }

    .container {
      max-width: 600px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }

    .back-button {
      display: inline-block;
      margin-bottom: 20px;
      background: #6c757d;
      color: white;
      padding: 10px 16px;
      text-decoration: none;
      border-radius: 6px;
      font-size: 14px;
      transition: background 0.3s;
    }

    .back-button:hover {
      background-color: #5a6268;
    }

    label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
      color: #555;
    }

    input[type="text"],
    input[type="number"],
    textarea,
    select {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 5px;
      border: 1px solid #ccc;
      transition: 0.3s;
    }

    input:focus,
    textarea:focus,
    select:focus {
      border-color: #007bff;
      outline: none;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .alert-success {
      background: #d4edda;
      color: #155724;
      padding: 10px 15px;
      border-left: 5px solid #28a745;
      margin-bottom: 20px;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="{{ route('admin.products.index') }}" class="back-button">🔙 Kembali</a>

    <h1>Tambah Produk</h1>

    @if(session('success'))
      <div class="alert-success">
        {{ session('success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('admin.products.store') }}">
      @csrf

      <label for="name">Nama Produk:</label>
      <input type="text" id="name" name="name" required>

      <label for="price">Harga:</label>
      <input type="number" id="price" name="price" required>

      <!-- ✅ Tambahan stok -->
      <label for="stock">Stok:</label>
      <input type="number" id="stock" name="stock" required>

      <label for="description">Deskripsi:</label>
      <textarea id="description" name="description" rows="3"></textarea>

      <label for="image_url">URL Gambar:</label>
      <input type="text" id="image_url" name="image_url">

      <label for="category_id">Kategori:</label>
      <select id="category_id" name="category_id" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
      </select>

      <button type="submit">Simpan Produk</button>
    </form>
  </div>
</body>
</html>
