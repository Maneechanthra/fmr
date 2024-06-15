@extends('layouts.app')

@section('content')
<section class="main">
      <div class="main-top">
            <h1>จัดการข้อมูลสมาชิก</h1>
      </div>

      <section class="attendance">
            <div class="attendance-list">
                  <h2 style="margin-bottom: 10px;">รายชื่อสมาชิก</h2>
                  <table id="data-table" style="width: 100%; padding-top: 10px;">
                        <thead>
                              <tr>
                                    <th scope="col">ลำดับ</th>
                                    <th scope="col">ชื่อ-นามกุล</th>
                                    <th scope="col">อีเมล</th>
                                    <th scope="col">สถานะ</th>
                                    <!-- <th scope="col">ปรับสถานะ</th> -->
                                    <th scope="col">ลบ</th>
                              </tr>
                        </thead>
                        <tbody>
                              @foreach ($dataAdmin as $index => $admin)
                              <tr>
                                    <td data-label="ลำดับ">{{ $index + 1 }}</td>
                                    <td data-label="ชื่อ-นามกุล">{{ $admin->name }}</td>
                                    <td data-label="อีเมล">{{ $admin->email }}</td>
                                    <td data-label="สถานะ">
                                          @if ($admin->role == 1)
                                          แอดมิน
                                          @endif
                                    </td>
                                    <td data-label="ลบ">
                                          @if ($admin->role == 1 && $admin->updated_at > $admin->created_at)
                                          <button class="button-delete">ลบข้อมูล</button>
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