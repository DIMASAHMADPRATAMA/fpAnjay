<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Status Pesanan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 30px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
    }

    select {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    .btn-submit {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 12px 20px;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      width: 100%;
    }

    .btn-submit:hover {
      background-color: #218838;
    }

    .back-link {
      display: block;
      margin-top: 20px;
      text-align: center;
      color: #007bff;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Edit Status Pesanan</h2>

  <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label for="payment_status">Status Pembayaran</label>
    <select name="payment_status" id="payment_status" required>
      <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
      <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
    </select>

    <label for="shipping_status">Status Pengiriman</label>
    <select name="shipping_status" id="shipping_status" required>
      <option value="pending" {{ $order->shipping_status == 'pending' ? 'selected' : '' }}>Menunggu</option>
      <option value="processing" {{ $order->shipping_status == 'processing' ? 'selected' : '' }}>Diproses</option>
      <option value="shipped" {{ $order->shipping_status == 'shipped' ? 'selected' : '' }}>Terkirim</option>
    </select>

    <button type="submit" class="btn-submit">Simpan Perubahan</button>
  </form>

  <a href="{{ route('admin.dashboard') }}" class="back-link">← Kembali ke Dashboard</a>
</div>

</body>
</html>
