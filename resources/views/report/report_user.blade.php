@extends('layouts.app')

@section('content')
<section class="main">
      <div class="main-top">
            <h1>รายงานข้อมูลสมาชิก</h1>
            <i class="fas fa-user">
                  {{ $userData->name }} || {{ $userData->email }}
            </i>
      </div>
      <div class="users1">
            <div class="card1">
                  <img style="margin-top: 30" src="{{ asset('assets/images/user.png') }}" />
                  <h4 style="margin-top: 10; font-size: 30">จำนวนสมาชิกทั้งหมด</h4>
                  <p style="margin-top: 10; font-size: 30">จำนวน {{ $users_count }} คน</p>
            </div>
            <!-- <div class="card">
            <img style="margin-top: 30" src="{{ asset('assets/images/restaurant.png') }}" />
            <h4 style="margin-top: 10; font-size: 30">จำนวนร้านอาหารทั้งหมด</h4>
            <p style="margin-top: 10; font-size: 30">จำนวน 4 ร้าน</p>
        </div> -->
      </div>

      <section class="attendance">
            <div class="attendance-list">
                  <h1 style="margin-bottom: 10px;">รายงานข้อมูลสมาชิก</h1>
                  <table id="data-table" style="width:100%; padding-top: 10px;">
                        <thead>
                              <tr>
                                    <th scope="col">ลำดับ</th>
                                    <th scope="col">ชื่อ-นามกุล</th>
                                    <th scope="col">อีเมล</th>
                                    <th scope="col">สถานะ</th>

                              </tr>
                        </thead>
                        <tbody>
                              @foreach ($data as $index => $users)
                              <tr>
                                    <td data-label="ลำดับ">{{ $index + 1 }}</td>
                                    <td data-label="ชื่อ-นามกุล">{{ $users->user_name }}</td>
                                    <td data-label="อีเมล">{{ $users->user_email }}</td>
                                    <td data-label="สถานะ">
                                          @if ($users->role == 0)
                                          สมาชิก
                                          @else
                                          แอดมิน
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