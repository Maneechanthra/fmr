@extends('layouts.app')

@section('content')

<section class="main">
      <div class="main-top">
            <h1>FMRestaurant</h1>
            <i class="fas fa-user-cog"></i>
      </div>
      <div class="users1">
            <!-- <div class="card">
            <img style="margin-top: 30" src="{{ asset('assets/images/user.png') }}" />
            <h4 style="margin-top: 10; font-size: 30">จำนวนสมาชิกทั้งหมด</h4>
            <p style="margin-top: 10; font-size: 30">จำนวน 5 คน</p>
        </div> -->
            <div class="card1">
                  <img style="margin-top: 30" src="{{ asset('assets/images/restaurant.png') }}" />
                  <h4 style="margin-top: 10; font-size: 30">จำนวนร้านอาหารทั้งหมด</h4>
                  <p style="margin-top: 10; font-size: 30">จำนวน {{ $restaurantsCount }} ร้าน</p>
            </div>
      </div>

      <section class="attendance">
            <div class="attendance-list">
                  <h1 style="margin-bottom: 10px;">รายงานข้อมูลร้านอาหาร</h1>
                  <table id="data-table" class="table" style="padding-top: 10px;">
                        <thead>
                              <tr>
                                    <th scope="col">ลำดับ</th>
                                    <th scope="col">ชื่อร้าน</th>
                                    <th scope="col">ที่อยู่</th>
                                    <th scope="col">หมายเลขโทรศัพท์ 1</th>
                                    <th scope="col">หมายเลขโทรศัพท์ 2</th>
                                    <th scope="col">จำนวนรีวิว</th>
                                    <th scope="col">จำนวนการเข้าชม</th>
                                    <th scope="col">จำนวนผู้ถูกใจ</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">สถานะรับรอง</th>
                              </tr>
                        </thead>
                        <tbody>
                              @foreach ($data as $index => $restaurant)
                              <tr>
                                    <td data-label="ลำดับ">{{ $index + 1 }}</td>
                                    <td data-label="ชื่อร้าน">{{ $restaurant->restaurant_name }}</td>
                                    <td data-label="ที่อยู่">
                                          {{ $restaurant->address }}
                                    </td>
                                    <td data-label="หมายเลขโทรศัพท์ 1">{{ $restaurant->telephone_1 }}</td>
                                    <td data-label="หมายเลขโทรศัพท์ 2">{{ $restaurant->telephone_2 }}</td>
                                    <td data-label="จำนวนรีวิว">{{ $restaurant->review_count }}</td>
                                    <td data-label="จำนวนการเข้าชม">{{ $restaurant->view_count }}</td>
                                    <td data-label="จำนวนผู้ถูกใจ">{{ $restaurant->favorites_count }}</td>
                                    <td data-label="สถานะ">
                                          @if ($restaurant->status == 1)
                                          ปกติ
                                          @else
                                          ยกเลิกการเข้าถึงข้อมูล
                                          @endif
                                    </td>
                                    <td data-label="สถานะรับรอง">
                                          @if ($restaurant->verified == 0)
                                          ยังไม่ได้รับการรับรอง
                                          @elseif ($restaurant->verified == 1)
                                          รอตรวจรอบข้อมูล
                                          @else
                                          ได้รับการรับรองแล้ว
                                          @endif</td>
                              </tr>

                              @endforeach

                        </tbody>
                  </table>
            </div>
      </section>
</section>
@endsection