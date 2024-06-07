@extends('layouts.app')

@section('content')
<section class="main">
    <div class="main-top">
        <h1>จัดการข้อมูลร้านอาหาร</h1>
        <i class="fas fa-user-cog"></i>
    </div>

    <section class="attendance">
        <div class="attendance-list">
            <h1 style="margin-bottom: 10px;">ข้อมูลร้านอาหารทั้งหมด</h1>
            <table id="data-table" style="width:100%; padding-top: 10px;">
                <thead>
                    <tr>
                        <th scope="col">ลำดับ</th>
                        <th scope="col">ชื่อร้าน</th>
                        <th scope="col">ที่อยู่</th>
                        <th scope="col">หมายเลขโทรศัพท์ 1</th>
                        <th scope="col">หมายเลขโทรศัพท์ 2</th>
                        <th scope="col">สถานะ</th>
                        <th scope="col">สถานะรับรอง</th>
                        <th scope="col">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="ลำดับ">1</td>
                        <td data-label="ชื่อร้าน">ร้านแม่หยา</td>
                        <td data-label="ที่อยู่">
                            79-81 ถนน ตลาดธนารักษ์ อำเภอเมืองอุดรธานี อุดรธานี 41000
                        </td>
                        <td data-label="หมายเลขโทรศัพท์ 1">042221590</td>
                        <td data-label="หมายเลขโทรศัพท์ 2">042221590</td>
                        <td data-label="สถานะ">ปกติ</td>
                        <td data-label="สถานะรับรอง">รับรองแล้ว</td>
                        <td data-label="จัดการ">
                            <button class="button-delete">
                                ลบข้อมูล
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td data-label="ลำดับ">1</td>
                        <td data-label="ชื่อร้าน">ร้านแม่หยา</td>
                        <td data-label="ที่อยู่">
                            79-81 ถนน ตลาดธนารักษ์ อำเภอเมืองอุดรธานี อุดรธานี 41000
                        </td>
                        <td data-label="หมายเลขโทรศัพท์ 1">042221590</td>
                        <td data-label="หมายเลขโทรศัพท์ 2">042221590</td>
                        <td data-label="สถานะ">ปกติ</td>
                        <td data-label="สถานะรับรอง">รับรองแล้ว</td>
                        <td data-label="จัดการ">
                            <button class="button-delete">
                                ลบข้อมูล
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td data-label="ลำดับ">1</td>
                        <td data-label="ชื่อร้าน">ร้านแม่หยา</td>
                        <td data-label="ที่อยู่">
                            79-81 ถนน ตลาดธนารักษ์ อำเภอเมืองอุดรธานี อุดรธานี 41000
                        </td>
                        <td data-label="หมายเลขโทรศัพท์ 1">042221590</td>
                        <td data-label="หมายเลขโทรศัพท์ 2">042221590</td>
                        <td data-label="สถานะ">ปกติ</td>
                        <td data-label="สถานะรับรอง">รับรองแล้ว</td>
                        <td data-label="จัดการ">
                            <button class="button-delete">
                                ลบข้อมูล
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </section>
</section>
@endsection