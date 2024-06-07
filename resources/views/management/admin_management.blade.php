@extends('layouts.app')

@section('content')
<section class="main">
    <div class="main-top">
        <h1>จัดการข้อมูลสมาชิก</h1>

    </div>

    <section class="attendance">
        <div class="attendance-list">
            <h1 style="margin-bottom : 10px">จัดการข้อมูลสมาชิก</h1>
            <table id="data-table" style="width:100%; padding-top: 10px;">
                <thead>
                    <tr>
                        <th scope="col">ลำดับ</th>
                        <th scope="col">ชื่อ-นามกุล</th>
                        <th scope="col">อีเมล</th>
                        <th scope="col">สถานะ</th>
                        <th scope="col">ปรับสถานะ</th>
                        <th scope="col">ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="ลำดับ">1</td>
                        <td data-label="ชื่อ-นามกุล">นายสุเมธ มณีจันทรา</td>
                        <td data-label="อีเมล">sumet.ma@ku.th</td>
                        <td data-label="สถานะ">สมาชิก</td>
                        <td data-label="ปรับสถานะ"><button class="button-status">ปรับสถานะ</button></td>
                        <td data-label="ลบ"><button class="button-delete">ลบข้อมูล</button></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </section>
</section>
@endsection