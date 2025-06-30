<!DOCTYPE html>
<html>
<head>
  <title>Edit Produk</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f9;
      padding: 40px;
    }

    .form-container {
      max-width: 600px;
      margin: auto;
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    button {
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
    }

    button:hover {
      background-color: #0056b3;
    }

    .back-link {
      display: block;
      margin-top: 20px;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h1>Edit Produk</h1>

  <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Nama Produk</label>
    <input type="text" name="name" value="{{ old('name', $product->name) }}" required>

    <label>Harga</label>
    <input type="number" name="price" value="{{ old('price', $product->price) }}" required>

    <label>Stok</label> <!-- ✅ Kolom tambahan untuk stok -->
    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>

    <label>Kategori</label>
    <select name="category_id" required>
      @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
          {{ $category->name }}
        </option>
      @endforeach
    </select>

    <label>Deskripsi</label>
    <textarea name="description">{{ old('description', $product->description) }}</textarea>

    <label>URL Gambar</label>
    <input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}">

    <button type="submit">💾 Simpan Perubahan</button>
  </form>

  <a href="{{ route('admin.products.index') }}" class="back-link">← Kembali ke Daftar Produk</a>
</div>

</body>
</html>
