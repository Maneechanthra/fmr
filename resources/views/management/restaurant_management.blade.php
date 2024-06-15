@extends('layouts.app')

@section('content')
<section class="main">
      <div class="main-top">
            <h1>จัดการข้อมูลร้านอาหาร</h1>
            <i class="fas fa-user">
                  {{ $userData->name }} || {{ $userData->email }}
            </i>
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
                              @foreach ($restaurants as $index => $restaurant)
                              <tr>
                                    <td data-label="ลำดับ">{{ $index + 1 }}</td>
                                    <td data-label="ชื่อร้าน"> {{ $restaurant->restaurant_name }}</td>
                                    <td data-label="ที่อยู่">
                                          {{ $restaurant->address }}
                                    </td>
                                    <td data-label="หมายเลขโทรศัพท์ 1"> {{ $restaurant->telephone_1 }}</td>
                                    <td data-label="หมายเลขโทรศัพท์ 2">{{ $restaurant->telephone_2 }}</td>
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

                                    <td>
                                          <form id="delete-form-{{ $restaurant->id }}" action="{{ route('delete-restaurant', ['id' => $restaurant->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="button-delete" onclick="confirmDelete('{{ $restaurant->id }}')">ลบข้อมูล</button>
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
      function confirmDelete(id) {
            Swal.fire({
                  title: 'คุณแน่ใจหรือไม่?',
                  text: "คุณต้องการลบร้านอาหารนี้ใช่หรือไม่?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'ใช่, ลบเลย!',
                  cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                  if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                  }
            });


      }
</script>
@endsection