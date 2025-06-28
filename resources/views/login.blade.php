<!DOCTYPE html>
<html>
  
<head>
  <title>Login Admin</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #6a11cb, #2575fc);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .login-box {
      background-color: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
    }

    .login-box h2 {
      margin-bottom: 25px;
      text-align: center;
      color: #333;
    }

    label {
      font-weight: 600;
      display: block;
      margin-top: 15px;
      margin-bottom: 5px;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    button[type="submit"] {
      width: 100%;
      padding: 12px;
      margin-top: 25px;
      background-color: #007bff;
      border: none;
      color: white;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button[type="submit"]:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Login Admin</h2>
    <form method="POST" action="/login">
      @csrf
      <label for="email">Email</label>
      <input type="email" name="email" required>

      <label for="password">Password</label>
      <input type="password" name="password" required>

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
