<!DOCTYPE html>
<html>
<head>
  <title>Chat dengan {{ $user->name }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      padding: 30px;
    }

    .chat-box {
      max-width: 700px;
      margin: auto;
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .message {
      margin-bottom: 15px;
      padding: 10px 15px;
      border-radius: 12px;
      max-width: 70%;
      line-height: 1.5;
    }

    .me {
      background-color: #007bff;
      color: white;
      margin-left: auto;
      text-align: right;
    }

    .other {
      background-color: #e9ecef;
      color: #333;
      margin-right: auto;
    }

    .message p {
      margin: 0;
    }

    form {
      margin-top: 20px;
      display: flex;
      gap: 10px;
    }

    input[type="text"] {
      flex: 1;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    button {
      padding: 10px 20px;
      border: none;
      background-color: #28a745;
      color: white;
      border-radius: 6px;
      cursor: pointer;
    }

    .success {
      background-color: #d4edda;
      padding: 10px;
      border-left: 5px solid #28a745;
      margin-bottom: 15px;
      border-radius: 5px;
      color: #155724;
    }
  </style>
</head>
<body>
  <div class="chat-box">
    <h2>Chat dengan {{ $user->name }}</h2>

    @if(session('success'))
      <div class="success">{{ session('success') }}</div>
    @endif

    @foreach($messages as $msg)
      <div class="message {{ $msg->sender_id == auth()->id() ? 'me' : 'other' }}">
        <p>{{ $msg->message }}</p>
        <small>{{ $msg->created_at->format('H:i d M Y') }}</small>
      </div>
    @endforeach

    <form method="POST" action="{{ route('admin.chat.send', $user->id) }}">
      @csrf
      <input type="text" name="message" placeholder="Tulis pesan..." required>
      <button type="submit">Kirim</button>
    </form>
  </div>
</body>
</html>
