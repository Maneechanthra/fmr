@extends('layouts.app')

@section('content')

<section class="main">
      <div class="main-top">
            <h1>FMRestaurant</h1>
            <i class="fas fa-user-cog"></i>
      </div>
      <div class="users1">

            <div class="card1">
                  <img style="margin-top: 30" src="{{ asset('assets/icons/store-block.png') }}" />
                  <h4 style="margin-top: 10; font-size: 30">รายงานความไม่เหมาะสมของร้านอาหาร</h4>
                  <p style="margin-top: 10; font-size: 30">จำนวน {{ $reportCount }} ร้าน</p>
            </div>
      </div>

      <section class="attendance">
            <div class="attendance-list">
                  <h1 style="margin-bottom: 10px;">รายงานความไม่เหมาะสมของร้านอาหาร</h1>
                  <table id="data-table" style="padding-top: 10px;">
                        <thead>
                              <tr>
                                    <th scope="col">ลำดับ</th>
                                    <th scope="col">ชื่อร้าน</th>
                                    <th scope="col">เหตุผล</th>
                                    <th scope="col">รายละเอียดเพิ่มเติม</th>
                                    <th scope="col">สถานะรายงาน</th>
                                    <th scope="col">สถานะร้านอาหาร</th>
                                    <th scope="col">จำนวนครั้งที่ถูกรายงาน</th>
                                    <th scope="col">จัดการ</th>
                              </tr>
                        </thead>
                        <tbody>
                              @foreach ($reports as $index => $report)
                              <tr>
                                    <td data-label="ลำดับ">{{ $index + 1 }}</td>
                                    <td data-label="ชื่อร้าน">{{ $report->restaurant_name}}</td>
                                    <td data-label="เหตุผล">{{ $report->report_title}}</td>
                                    <td data-label="รายละเอียดเพิ่มเติม">{{ $report->report_description}}</td>
                                    <td data-label="สถานะ">
                                          @if ($report->report_status == 0)
                                          รอตรวจสอบ
                                          @else
                                          ตรวจสอบแล้ว
                                          @endif
                                    </td>
                                    <td data-label="สถานะรับรอง">รับรองแล้ว</td>
                                    <td data-label="สถานะรับรอง">
                                          @if (isset($reportCountByRestaurant[$index]))
                                          {{ $reportCountByRestaurant[$index] }}/3
                                          @endif
                                    </td>
                                    <td data-label="จัดการ">
                                          @if (isset($reportCountByRestaurant[$index]) &&
                                          $reportCountByRestaurant[$index] < 3) <button class="button-delete" disabled>
                                                ยกเลิกการเข้าถึงข้อมูล</button>
                                                @else
                                                <button class="button-delete">ยกเลิกการเข้าถึงข้อมูล</button>
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