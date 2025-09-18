<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h3 class="mb-4 text-center">Login</h3>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <div class="position-relative">
                    <input type="password" id="password" name="password" class="form-control pe-5"
                        placeholder="Masukkan password" required>
                    <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y pe-3" id="togglePassword"
                        style="cursor: pointer;"></i>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input">
                <label class="form-check-label">Remember Me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>

            <div class="mt-3 text-center">
                <a href="{{ route('forgot.form') }}">Lupa Password?</a>
            </div>
        </form>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        const passwordField = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        // toggle password
        togglePassword.addEventListener('click', () => {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            togglePassword.classList.toggle('bi-eye');
            togglePassword.classList.toggle('bi-eye-slash');
        });
    </script>
</body>

</html>
