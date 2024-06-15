@extends('layouts.app')

@section('content')
<section class="main">
      <div class="main-top">
            <h1>FMRestaurant</h1>
            <i class="fas fa-user">
                  {{ $userData->name }} || {{ $userData->email }}
            </i>

      </div>
      <div class="users">
            <div class="card">
                  <img style="margin-top: 30" src="{{ asset('assets/images/user.png')}}" />
                  <h4 style="margin-top: 10; font-size: 30">จำนวนสมาชิกทั้งหมด</h4>
                  <p style="margin-top: 10; font-size: 30">จำนวน {{ $total_users }} คน</p>
            </div>
            <div class="card">
                  <img style="margin-top: 30" src="{{ asset('assets/images/restaurant.png')}}" />
                  <h4 style="margin-top: 10; font-size: 30">
                        จำนวนร้านอาหารทั้งหมด
                  </h4>
                  <p style="margin-top: 10; font-size: 30">จำนวน {{ $restaurantsCount  }} ร้าน</p>
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
                              @foreach($topRestaurants as $index => $restaurant)
                              <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $restaurant->restaurant_name }}</td>
                                    <td>{{ $restaurant->address }}</td>
                                    <td>{{ $restaurant->telephone_1 }}</td>
                                    <td>{{ $restaurant->telephone_2 }}</td>
                                    <td>{{ $restaurant->review_count }}</td>
                                    <td>{{ $restaurant->view_count }}</td>
                                    <td>{{ $restaurant->favorites_count }}</td>
                                    <td>
                                          @if($restaurant->status == 1)
                                          ปกติ
                                          @else
                                          ยกเลิกการเข้าถึงข้อมูล
                                          @endif
                                    </td>
                                    <td>
                                          @if ($restaurant->verified == 0)
                                          ยังไมได้รับการรับรอง
                                          @elseif($restaurant->verified == 1)
                                          รอตรวจสอบ
                                          @else
                                          รับรองแล้ว
                                          @endif
                                    </td>
                              </tr>
                              @endforeach
                        </tbody>
                  </table>
            </div>
      </section>
</section>
@endsection