<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style-login.css') }}">

      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400&display=swap" rel="stylesheet">
</head>

<body>
      <form action="{{ route('/') }}">
            <div class="content">
                  <div class="icon-round">
                        <img src="{{ asset('assets/icons/lock.png') }}" alt="" width="100%">
                  </div>
                  <div class="box">
                        <h2>ลงชื่อเข้าใช้งาน</h2>
                        <div class="form-box">
                              <label>อีเมล *</label>
                              <input style="margin-bottom: 10px;" type="text" placeholder="Username...">
                              <label>รหัสผ่าน *</label>
                              <input type="password" placeholder="Password...">
                        </div>
                        <button type="submit">Login</button>
                  </div>

                  <div class="text-footer"><a href="#">ลืมรหัสผ่าน?</a></div>
            </div>
      </form>

</body>

</html>