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
                  <img style="margin-top: 30" src="{{ asset('assets/icons/petition.png') }}" />
                  <h4 style="margin-top: 10; font-size: 30">คำร้องขอสถานะรับรองร้านอาหาร</h4>
                  <p style="margin-top: 10; font-size: 30">จำนวน {{ $verifCount }} ร้าน</p>
            </div>
      </div>

      <section class="attendance">
            <div class="attendance-list">
                  <h1 style="margin-bottom: 10px;">คำร้องขอสถานะรับรองร้านอาหาร</h1>
                  <table id="data-table" style="padding-top: 10px;">
                        <thead>
                              <tr>
                                    <th scope="col">ลำดับ</th>
                                    <th scope="col">ชื่อร้าน</th>
                                    <th scope="col">ผู้ส่งคำร้อง</th>
                                    <th scope="col">หมายเลขโทรศัพท์ 1</th>
                                    <th scope="col">หมายเลขโทรศัพท์ 2</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">สถานะรับรอง</th>
                                    <th scope="col">จัดการ</th>
                              </tr>
                        </thead>
                        <tbody>
                              @foreach ($restaurants as $index => $restaurant)


                              <tr>
                                    <td data-label="ลำดับ">{{ $index + 1}}</td>
                                    <td data-label="ชื่อร้าน">{{ $restaurant-> restaurant_name}}</td>
                                    <td data-label="ผู้ส่งคำร้อง">{{ $restaurant-> user_name}}</td>
                                    <td data-label="หมายเลขโทรศัพท์ 1">{{ $restaurant-> telephone_1}}</td>
                                    <td data-label="หมายเลขโทรศัพท์ 2">{{ $restaurant-> telephone_2}}</td>
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
                                          @endif
                                    </td>


                                    <td>
                                          <form id="adjust-form-{{ $restaurant->id }}"
                                                action="{{ route('update-verify', ['id' => $restaurant->id, 'userId' => $userData['userId']]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="button" class="button-status"
                                                      onclick="confirmAdjust('{{ $restaurant->id }}')">ยืนยัน</button>
                                          </form>

                                    </td>
                              </tr>
                              @endforeach
                        </tbody>
                  </table>
            </div>
      </section>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmAdjust(id) {
      Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการปรับสถานะผู้ใช้นี้หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, แน่นอน!',
            cancelButtonText: 'ยกเลิก'
      }).then((result) => {
            if (result.isConfirmed) {
                  document.getElementById('adjust-form-' + id).submit();
            }
      });
}
</script>
@endsection