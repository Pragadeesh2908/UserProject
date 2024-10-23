<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap4.css') }}">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alertSuccess = document.querySelector('.alert-success');
            const alertError = document.querySelector('.alert-danger');

            if (alertSuccess) {
                setTimeout(() => {
                    alertSuccess.style.display = 'none';
                }, 5000);
            }

            if (alertError) {
                setTimeout(() => {
                    alertError.style.display = 'none';
                }, 5000);
            }
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2>Forgot Your Password?</h2>

        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @elseif(session('status'))
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>
        @endif
      
        <form action="{{ route('forgot.password') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
        </form>
    </div>
</body>
</html>
