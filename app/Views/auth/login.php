<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login AIMpro Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --background-color: #f5f7fb;
        }

        * {
            font-family: 'poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background: var(--background-color)
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            max-width: 420px;
            width: 100%;
            border: 0;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
        }

        .login-logo {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .login-subtitle {
            font-size: 14px;
            color: #6c757d;
        }

        .form-control {
            min-height: 48px;
            border-radius: 12px;
        }

        .btn-login {
            min-height: 48px;
            border-radius: 12px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <main class="login-wrapper">
        <div class="card login-card">
            <div class="card-body p-4 p-md-5">

                <div class="text-center mb-4">
                    <div class="login-logo">AIMpro Care</div>
                    <p class="login-subtitle mb-0">
                        Sistem After-Sales & Quality Control
                    </p>
                </div>

                <form action="<?= site_url('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="Masukkan email"
                            autocomplete="email"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary btn-login w-100">
                        Masuk
                    </button>
                </form>

            </div>
        </div>
    </main>

</body>

</html>