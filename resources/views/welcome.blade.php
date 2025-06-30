<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang di Aplikasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }

        h1 {
            font-weight: 600;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        p {
            font-weight: 300;
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .btn {
            background-color: #ffffff;
            color: #4facfe;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #e3e3e3;
            color: #0077ff;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 1.8rem;
            }

            p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>👋 Selamat Datang!</h1>
        <p>Ini adalah halaman utama dari aplikasi Laravel kamu. Silakan login untuk mengelola data.</p>
        <a href="{{ route('login') }}" class="btn">🔐 Login Admin</a>
    </div>
</body>
</html>
