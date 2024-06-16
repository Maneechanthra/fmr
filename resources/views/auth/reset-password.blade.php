<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Reset Password</title>
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style-login.css') }}">

      <!-- Include Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

      <!-- Google Fonts -->
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400&display=swap" rel="stylesheet">
</head>

<body>
      <div class="container">

            <form action="{{ route('reset-password') }}" method="POST">
                  @csrf
                  <!-- CSRF token -->

                  <h2>Reset Password</h2>
                  <div class="content">

                        <!-- Token Input -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Input -->
                        <div class="mb-3">
                              <label for="email" class="form-label">Email address</label>
                              <input type="email" class="form-control" id="email" name="email" value="{{ $request->email }}" readonly>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                              <label for="password" class="form-label">New Password</label>
                              <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="mb-3">
                              <label for="password_confirmation" class="form-label">Confirm New Password</label>
                              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                  </div>
            </form>
      </div>

      <!-- Bootstrap JS Bundle (needed for Bootstrap components) -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
      </script>
</body>

</html>