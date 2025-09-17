<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verifikasi OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .otp-input { width:50px; height:50px; text-align:center; font-size:24px; margin-right:10px; }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5 bg-white p-4 rounded shadow">
            <h4 class="text-center mb-3">Verifikasi OTP</h4>
            <p class="text-center text-muted">
                OTP telah dikirim ke email <b>{{ session('otp_email', $email ?? '') }}</b>.
            </p>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <input type="hidden" name="email" value="{{ session('otp_email', $email ?? '') }}">
                <div class="d-flex justify-content-center mb-3">
                    <input type="text" name="otp[0]" maxlength="1" class="otp-input form-control" required>
                    <input type="text" name="otp[1]" maxlength="1" class="otp-input form-control" required>
                    <input type="text" name="otp[2]" maxlength="1" class="otp-input form-control" required>
                    <input type="text" name="otp[3]" maxlength="1" class="otp-input form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Verifikasi Kode</button>
            </form>

            <form method="POST" action="{{ route('otp.resend') }}" class="mt-3 text-center">
                @csrf
                <input type="hidden" name="email" value="{{ session('otp_email', $email ?? '') }}">
                <button type="submit" id="resendBtn" class="btn btn-link">Kirim Ulang OTP</button>
            </form>

            <p id="countdown" class="text-muted"></p>
        </div>
    </div>
</div>

<script>
const inputs = document.querySelectorAll('.otp-input');
inputs.forEach((input, i) => {
    input.addEventListener('input', () => { if(input.value.length===1 && i<inputs.length-1) inputs[i+1].focus(); });
    input.addEventListener('keydown', (e) => { if(e.key==='Backspace' && input.value==='' && i>0) inputs[i-1].focus(); });
});

let sisa = {{ $sisa ?? 0 }};
const resendBtn = document.getElementById('resendBtn');
const countdown = document.getElementById('countdown');

if(sisa>0){
    resendBtn.disabled = true;
    const interval = setInterval(()=>{
        countdown.textContent=`Tunggu ${sisa} detik sebelum kirim ulang OTP.`;
        sisa--;
        if(sisa<0){
            clearInterval(interval);
            countdown.textContent='';
            resendBtn.disabled=false;
        }
    },1000);
}
</script>
</body>
</html>
