<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline — AIMpro Care</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;

            font-family: 'Poppins', sans-serif;
            color: #172640;

            background:
                radial-gradient(circle at 12% 15%, rgba(200, 223, 219, 0.72), transparent 30%),
                radial-gradient(circle at 88% 82%, rgba(102, 163, 191, 0.28), transparent 32%),
                #F2EFE7;
        }

        .card {
            max-width: 420px;
            padding: 40px 32px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.55);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            box-shadow: 0 24px 60px rgba(34, 57, 111, 0.10);
        }

        .icon {
            width: 72px;
            height: 72px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            border-radius: 24px;
            background: rgba(51, 104, 160, 0.10);
            color: #22396F;
            font-size: 32px;
        }

        h1 {
            margin: 0 0 10px;
            font-size: 22px;
            font-weight: 700;
        }

        p {
            margin: 0 0 20px;
            color: #53657D;
            font-size: 14px;
            line-height: 1.7;
        }

        button {
            min-height: 44px;
            padding: 10px 22px;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(145deg, #22396F, #3368A0);
            color: #fff;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="icon">
            <i class="bi bi-wifi-off"></i>
        </div>

        <h1>Anda Sedang Offline</h1>

        <p>
            Koneksi internet tidak tersedia. Periksa koneksi Anda
            dan coba lagi.
        </p>

        <button onclick="window.location.reload()">
            <i class="bi bi-arrow-clockwise"></i>
            Coba Lagi
        </button>
    </div>

</body>

</html>