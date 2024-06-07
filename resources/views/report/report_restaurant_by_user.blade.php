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
                  <img style="margin-top: 30" src="{{ asset('assets/icons/store-block.png') }}" />
                  <h4 style="margin-top: 10; font-size: 30">รายงานความไม่เหมาะสมของร้านอาหาร</h4>
                  <p style="margin-top: 10; font-size: 30">จำนวน 4 ร้าน</p>
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
                                    <th scope="col">หมายเลขโทรศัพท์ 1</th>
                                    <th scope="col">หมายเลขโทรศัพท์ 2</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">สถานะรับรอง</th>
                                    <th scope="col">จำนวนครั้งที่ถูกรายงาน</th>
                                    <th scope="col">จัดการ</th>
                              </tr>
                        </thead>
                        <tbody>
                              <tr>
                                    <td data-label="ลำดับ">01</td>
                                    <td data-label="ชื่อร้าน">ร้านแม่หยา</td>
                                    <td data-label="หมายเลขโทรศัพท์ 1">042221590</td>
                                    <td data-label="หมายเลขโทรศัพท์ 2">042221590</td>
                                    <td data-label="สถานะ">ปกติ</td>
                                    <td data-label="สถานะรับรอง">รับรองแล้ว</td>
                                    <td data-label="สถานะรับรอง">1/3</td>
                                    <td data-label="จัดการ">
                                          <button class="button-delete">
                                                ยกเลิกการเข้าถึงข้อมูล
                                          </button>
                                    </td>
                              </tr>
                              <tr>
                                    <td data-label="ลำดับ">01</td>
                                    <td data-label="ชื่อร้าน">ร้านแม่หยา</td>
                                    <td data-label="หมายเลขโทรศัพท์ 1">042221590</td>
                                    <td data-label="หมายเลขโทรศัพท์ 2">042221590</td>
                                    <td data-label="สถานะ">ปกติ</td>
                                    <td data-label="สถานะรับรอง">รับรองแล้ว</td>
                                    <td data-label="สถานะรับรอง">1/3</td>
                                    <td data-label="จัดการ">
                                          <button class="button-delete">
                                                ยกเลิกการเข้าถึงข้อมูล
                                          </button>
                                    </td>
                              </tr>
                              <tr>
                                    <td data-label="ลำดับ">01</td>
                                    <td data-label="ชื่อร้าน">ร้านแม่หยา</td>
                                    <td data-label="หมายเลขโทรศัพท์ 1">042221590</td>
                                    <td data-label="หมายเลขโทรศัพท์ 2">042221590</td>
                                    <td data-label="สถานะ">ปกติ</td>
                                    <td data-label="สถานะรับรอง">รับรองแล้ว</td>
                                    <td data-label="สถานะรับรอง">1/3</td>
                                    <td data-label="จัดการ">
                                          <button class="button-delete">
                                                ยกเลิกการเข้าถึงข้อมูล
                                          </button>
                                    </td>
                              </tr>
                              <tr>
                                    <td data-label="ลำดับ">01</td>
                                    <td data-label="ชื่อร้าน">ร้านแม่หยา</td>
                                    <td data-label="หมายเลขโทรศัพท์ 1">042221590</td>
                                    <td data-label="หมายเลขโทรศัพท์ 2">042221590</td>
                                    <td data-label="สถานะ">ปกติ</td>
                                    <td data-label="สถานะรับรอง">รับรองแล้ว</td>
                                    <td data-label="สถานะรับรอง">1/3</td>
                                    <td data-label="จัดการ">
                                          <button class="button-delete">
                                                ยกเลิกการเข้าถึงข้อมูล
                                          </button>
                                    </td>
                              </tr>
                              <tr>
                                    <td data-label="ลำดับ">01</td>
                                    <td data-label="ชื่อร้าน">ร้านแม่หยา</td>
                                    <td data-label="หมายเลขโทรศัพท์ 1">042221590</td>
                                    <td data-label="หมายเลขโทรศัพท์ 2">042221590</td>
                                    <td data-label="สถานะ">ปกติ</td>
                                    <td data-label="สถานะรับรอง">รับรองแล้ว</td>
                                    <td data-label="สถานะรับรอง">1/3</td>
                                    <td data-label="จัดการ">
                                          <button class="button-delete">
                                                ยกเลิกการเข้าถึงข้อมูล
                                          </button>
                                    </td>
                              </tr>
                        </tbody>
                  </table>
            </div>
      </section>
</section>
@endsection