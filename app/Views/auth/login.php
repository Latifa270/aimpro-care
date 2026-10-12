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
    <link rel="stylesheet" href="<?= base_url('assets/css/pages/auth.css')  ?>">
</head>

<body>
    <main class="login-wrapper">
        <div class="login-card">
            <div class="login-card-body">

                <div class="login-brand">
                    <div class="login-logo">
                        AIMpro Care
                    </div>

                    <h1 class="login-title">
                        Selamat Datang
                    </h1>

                    <p class="login-subtitle">
                        Sistem AfterSales Quality Control
                    </p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="login-alert" role="alert">
                        <?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="login-alert" role="alert">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <div><?= esc($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="login-form-group">
                        <label for="email" class="login-label">
                            Email
                        </label>

                        <input
                            type="email"
                            class="login-input"
                            id="email"
                            name="email"
                            placeholder="Masukkan email"
                            autocomplete="email"
                            required>
                    </div>

                    <div class="login-form-group">
                        <label for="password" class="login-label">
                            Password
                        </label>

                        <input
                            type="password"
                            class="login-input"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required>
                    </div>

                    <button type="submit" class="btn-login">
                        Masuk
                    </button>
                </form>

                <div class="login-footer">
                    AIMpro Care - After-Sales & Quality Control
                </div>

            </div>
        </div>
    </main>

</body>

</html>