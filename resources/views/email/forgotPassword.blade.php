<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f7f7f7; padding: 30px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">Reset Your Password</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-4">Hello {{$user->first_name}},</h5>
                        <p>It seems like you requested to reset your password. You can reset your password by clicking the button below:</p>
                        
                        <a href="{{ route('resetPassword', $token) }}" class="btn btn-primary btn-lg btn-block" role="button">
                            Reset Password
                        </a>
                        
                        <p class="mt-4">If you did not request a password reset, please ignore this email.</p>
                        <p>Thanks,<br>The Team</p>
                    </div>
                    <div class="card-footer text-muted text-center">
                        &copy; {{ date('Y') }} Your Company Name. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
