<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Atur Password Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .valid {
            color: green;
        }

        .invalid {
            color: red;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h3 class="mb-4 text-center">Atur Password Baru</h3>

        <form method="POST" action="{{ route('set.password') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label>Password Baru</label>
                <div class="position-relative">
                    <input type="password" id="password" name="password" class="form-control pe-5" placeholder="Masukkan password"
                        required>
                    <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y pe-3" id="togglePassword"
                        style="cursor: pointer;"></i>
                </div>
                <ul class="mt-2" id="password-rules">
                    <li id="rule-length" class="invalid">Minimal 6 karakter</li>
                    <li id="rule-uppercase" class="invalid">Mengandung huruf besar</li>
                    <li id="rule-number" class="invalid">Mengandung angka</li>
                    <li id="rule-special" class="invalid">Mengandung karakter spesial</li>
                </ul>
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <div class="position-relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control pe-5"
                        placeholder="Konfirmasi password" required>
                    <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y pe-3" id="toggleConfirm"
                        style="cursor: pointer;"></i>
                        <div id="confirm-feedback" class="invalid-feedback">Password tidak cocok</div>
                </div>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-success w-100" disabled>
                Simpan Password
            </button>
        </form>
    </div>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        const passwordField = document.getElementById('password');
        const confirmField = document.getElementById('password_confirmation');
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirm = document.getElementById('toggleConfirm');
        const submitBtn = document.getElementById('submitBtn');

        // rules
        const ruleLength = document.getElementById('rule-length');
        const ruleUppercase = document.getElementById('rule-uppercase');
        const ruleNumber = document.getElementById('rule-number');
        const ruleSpecial = document.getElementById('rule-special');

        // toggle password
        togglePassword.addEventListener('click', () => {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            togglePassword.classList.toggle('bi-eye');
            togglePassword.classList.toggle('bi-eye-slash');
        });

        toggleConfirm.addEventListener('click', () => {
            const type = confirmField.type === 'password' ? 'text' : 'password';
            confirmField.type = type;
            toggleConfirm.classList.toggle('bi-eye');
            toggleConfirm.classList.toggle('bi-eye-slash');
        });

        // validasi
        function validatePassword() {
            const password = passwordField.value;
            let valid = true;

            if (password.length >= 6) {
                ruleLength.classList.replace('invalid', 'valid');
            } else {
                ruleLength.classList.replace('valid', 'invalid');
                valid = false;
            }
            if (/[A-Z]/.test(password)) {
                ruleUppercase.classList.replace('invalid', 'valid');
            } else {
                ruleUppercase.classList.replace('valid', 'invalid');
                valid = false;
            }
            if (/\d/.test(password)) {
                ruleNumber.classList.replace('invalid', 'valid');
            } else {
                ruleNumber.classList.replace('valid', 'invalid');
                valid = false;
            }
            if (/[\W_]/.test(password)) {
                ruleSpecial.classList.replace('invalid', 'valid');
            } else {
                ruleSpecial.classList.replace('valid', 'invalid');
                valid = false;
            }
            return valid;
        }

        function validateConfirmation() {
            const match = confirmField.value === passwordField.value && confirmField.value !== '';
            if (!match && confirmField.value !== '') {
                confirmField.classList.add('is-invalid');
            } else {
                confirmField.classList.remove('is-invalid');
            }
            return match;
        }

        function checkFormValidity() {
            const validPassword = validatePassword();
            const validConfirm = validateConfirmation();
            submitBtn.disabled = !(validPassword && validConfirm);
        }

        passwordField.addEventListener('input', checkFormValidity);
        confirmField.addEventListener('input', checkFormValidity);
    </script>
</body>

</html>
