<!DOCTYPE html>
<html>
<head>
  <title>Chat dengan {{ $user->name }}</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f2f5;
      margin: 0;
      padding: 30px;
    }

    .chat-box {
      max-width: 800px;
      margin: auto;
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .back-button {
      background-color: #6c757d;
      color: white;
      padding: 8px 15px;
      border-radius: 20px;
      text-decoration: none;
      font-size: 14px;
      transition: background-color 0.3s;
    }

    .back-button:hover {
      background-color: #5a6268;
    }

    h2 {
      margin: 0;
      text-align: center;
      color: #333;
      flex-grow: 1;
    }

    .message {
      display: flex;
      flex-direction: column;
      max-width: 70%;
      padding: 10px 15px;
      border-radius: 20px;
      position: relative;
      word-wrap: break-word;
      line-height: 1.5;
    }

    .me {
      align-self: flex-end;
      background-color: #007bff;
      color: white;
      border-bottom-right-radius: 2px;
    }

    .other {
      align-self: flex-start;
      background-color: #e4e6eb;
      color: #111;
      border-bottom-left-radius: 2px;
    }

    .message small {
      font-size: 12px;
      margin-top: 4px;
      opacity: 0.7;
      align-self: flex-end;
    }

    form {
      display: flex;
      margin-top: 20px;
      gap: 10px;
    }

    input[type="text"] {
      flex-grow: 1;
      padding: 10px;
      border-radius: 20px;
      border: 1px solid #ccc;
      outline: none;
    }

    button {
      padding: 10px 20px;
      border: none;
      border-radius: 20px;
      background-color: #28a745;
      color: white;
      cursor: pointer;
    }

    .success {
      background-color: #d4edda;
      padding: 10px;
      border-left: 5px solid #28a745;
      border-radius: 5px;
      color: #155724;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

  <div class="chat-box" id="chatMessages">
    <div class="top-bar">
      <a href="{{ route('admin.products.index') }}" class="back-button">← Kembali</a>
      <h2>Chat dengan {{ $user->name }}</h2>
      <div style="width: 70px;"></div>
    </div>

    @if(session('success'))
      <div class="success">{{ session('success') }}</div>
    @endif

    @foreach($messages as $msg)
      <div class="message {{ $msg->sender_id == auth()->id() ? 'me' : 'other' }}">
        <p>{{ $msg->message }}</p>
        <small>{{ $msg->created_at->timezone('Asia/Jakarta')->format('H:i d M Y') }}</small>
      </div>
    @endforeach

    <form method="POST" action="{{ route('admin.chat.send', $user->id) }}">
      @csrf
      <input type="text" name="message" placeholder="Tulis pesan..." required autocomplete="off">
      <button type="submit">Kirim</button>
    </form>
  </div>

  <script>
    setInterval(() => {
      fetch(window.location.href)
        .then(response => response.text())
        .then(html => {
          const parser = new DOMParser();
          const newDoc = parser.parseFromString(html, 'text/html');
          const newContent = newDoc.querySelector('#chatMessages').innerHTML;
          document.querySelector('#chatMessages').innerHTML = newContent;
        })
        .catch(err => console.error('Error loading chat:', err));
    }, 5000);
  </script>

</body>
</html>
