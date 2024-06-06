<span style="font-family: verdana, geneva, sans-serif">
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <title>FMRestaurant</title>
        <!-- <link rel='stylesheet' href="{{ asset('css/style.css') }}"> -->
        <link rel='stylesheet' href="../css/style.css">
        <!-- Font Awesome Cdn Link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    </head>

    <body>
        <div class="container">
            <nav>
                <ul>
                    <li>
                        <a href="index.html" class="logo">
                            <img src="/img/logo.png" />
                            <span class="nav-item">FMR</span>
                        </a>
                    </li>
                    <li>
                        <a href="dashboard.html">
                            <i class="fas fa-menorah"></i>
                            <span class="nav-item">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="report_restaurant.html">
                            <i class="fas fa-flag"></i>
                            <span class="nav-item">รายงานความไม่เหมาะสม</span>
                        </a>
                    </li>
                    <li>
                        <a href="user.html">
                            <i class="fas fa-chart-bar"></i>
                            <span class="nav-item">รายงานสมาชิก</span>
                        </a>
                    </li>
                    <li>
                        <a href="show_restaurant.html">
                            <i class="fas fa-chart-bar"></i>
                            <span class="nav-item">รายงานข้อมูลร้านอาหาร</span>
                        </a>
                    </li>
                    <li>
                        <a href="restaurant_management_.html">
                            <i class="fas fa-utensils"></i>
                            <span class="nav-item">จัดการข้อมูลร้านอาหาร</span>
                        </a>
                    </li>
                    <li>
                        <a href="verify_restaurant.html">
                            <i class="fas fa-utensils"></i>
                            <span class="nav-item">จัดการข้อมูลยืนยันตัวตน</span>
                        </a>
                    </li>
                    <li>
                        <a href="user_,management.html">
                            <i class="fas fa-user"></i>
                            <span class="nav-item">จัดการข้อมูลสมาชิก</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_,management.html">
                            <i class="fas fa-user"></i>
                            <span class="nav-item">จัดการข้อมูลผู้ดูแลระบบ</span>
                        </a>
                    </li>
                    <li>
                        <a href="edit_personal.html">
                            <i class="fas fa-edit"></i>
                            <span class="nav-item">แก้ไขข้อมูลส่วนตัว</span>
                        </a>
                    </li>
                    <li>
                        <a href="login.html" class="logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="nav-item">Log out</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <section class="main">
                <div class="main-top">
                    <h1>FMRestaurant</h1>
                    <i class="fas fa-user-cog"></i>
                </div>
                <div class="users">
                    <div class="card">
                        <img style="margin-top: 30" src="/img/user.png" />
                        <h4 style="margin-top: 10; font-size: 30">จำนวนสมาชิกทั้งหมด</h4>
                        <p style="margin-top: 10; font-size: 30">จำนวน 5 คน</p>
                    </div>
                    <div class="card">
                        <img style="margin-top: 30" src="/img/restaurant.png" />
                        <h4 style="margin-top: 10; font-size: 30">
                            จำนวนร้านอาหารทั้งหมด
                        </h4>
                        <p style="margin-top: 10; font-size: 30">จำนวน 4 ร้าน</p>
                    </div>
                </div>

                <section class="attendance">
                    <div class="attendance-list">
                        <h1>รายการร้านอาหารที่มีจำนวนการเข้าชมมากที่สุด</h1>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>ชื่อร้าน</th>
                                    <th>ที่อยู่</th>
                                    <th>หมายเลขโทรศัพท์ 1</th>
                                    <th>หมายเลขโทรศัพท์ 2</th>
                                    <th>จำนวนรีวิว</th>
                                    <th>จำนวนการเข้าชม</th>
                                    <th>จำนวนผู้ถูกใจ</th>
                                    <th>สถานะ</th>
                                    <th>สถานะรับรอง</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>01</td>
                                    <td>ร้านแม่หยา</td>
                                    <td>
                                        79-81 ถนน ตลาดธนารักษ์ อำเภอเมืองอุดรธานี อุดรธานี 41000
                                    </td>
                                    <td>042221590</td>
                                    <td></td>
                                    <td>51</td>
                                    <td>620</td>
                                    <td>750</td>
                                    <td>ปกติ</td>
                                    <td>รับรองแล้ว</td>
                                </tr>
                                <tr>
                                    <td>02</td>
                                    <td>ท่าบ่อปลาเผา</td>
                                    <td>
                                        CQ2F+376 สินชัยธานี ตำบล บ้านเลื่อม อำเภอเมืองอุดรธานี
                                        อุดรธานี 41000
                                    </td>
                                    <td>0819743641</td>
                                    <td></td>
                                    <td>12</td>
                                    <td>435</td>
                                    <td>23</td>
                                    <td>ปกติ</td>
                                    <td>รับรองแล้ว</td>
                                </tr>
                                <tr>
                                    <td>03</td>
                                    <td>ร้านอาหารฟาโรห์เฮาส์</td>
                                    <td>CQCC+JH ตำบล บ้านเลื่อม อำเภอเมืองอุดรธานี อุดรธานี</td>
                                    <td>0654017432</td>
                                    <td>0630038428</td>
                                    <td>35</td>
                                    <td>295</td>
                                    <td>123</td>
                                    <td>ปกติ</td>
                                    <td>อยู่ระหว่างตรวจสอบข้อมูล</td>
                                </tr>
                                <tr>
                                    <td>04</td>
                                    <td>PaMaHeng The Eatery พามาเฮง</td>
                                    <td>
                                        420 ถ. สุขุมวิท 71 แขวงพระโขนงเหนือ เขตวัฒนา กรุงเทพมหานคร
                                        10110
                                    </td>
                                    <td>0969788897</td>
                                    <td></td>
                                    <td>20</td>
                                    <td>268</td>
                                    <td>98</td>
                                    <td>ยกเลิกการเข้าถึงข้อมูล</td>
                                    <td>อยู่ระหว่างตรวจสอบข้อมูล</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </section>
        </div>
    </body>

    </html>
</span>