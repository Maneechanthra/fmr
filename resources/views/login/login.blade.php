<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Login</title>
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style-login.css') }}">

      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400&display=swap" rel="stylesheet">

      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
      <div class="container">
            <form action="{{ route('login-user') }}" method="POST">
                  @csrf
                  <!-- Add CSRF token -->
                  <div class="content">
                        <div class="icon-round">
                              <img src="{{ asset('assets/icons/lock.png') }}" alt="" width="100%">
                        </div>
                        <div class="box">
                              <h2>ลงชื่อเข้าใช้งาน</h2>

                              <!-- Display success or fail alerts -->
                              @if (Session::has('success'))
                              <div class="alert alert-success">
                                    {{ Session::get('success') }}
                              </div>
                              @endif
                              @if (Session::has('fail'))
                              <div class="alert alert-danger">
                                    {{ Session::get('fail') }}
                              </div>
                              @endif

                              <div class="form-box">
                                    <label for="email">อีเมล *</label>
                                    <input style="margin-bottom: 10px;" type="text" id="email" name="email" placeholder="Email...">

                                    <label for="password">รหัสผ่าน *</label>
                                    <input type="password" id="password" name="password" placeholder="Password...">
                              </div>
                              <button style="margin-top: 10px;" type="submit">เข้าสู่ระบบ</button>
                        </div>

                        <!-- Forgot password link -->
                        @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                              {{ __('Forgot your password?') }}
                        </a>
                        @endif

                        <!-- Registration link -->
                        <a href="registration">สมัครสมาชิก</a>
                  </div>
            </form>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
      </script>
</body>

</html>