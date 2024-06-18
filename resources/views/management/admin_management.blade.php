@extends('layouts.app')

@section('content')
<section class="main">
      <div class="main-top">
            <h1>จัดการข้อมูลสมาชิก</h1>
            <i class="fas fa-user">
                  {{ $userData->name }} || {{ $userData->email }}
            </i>
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


                                    <td>
                                          <form id="delete-form-{{ $admin->id }}" action="{{ route('delete-user', ['id' => $admin->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                @if ($admin->role == 1 && $userData->updated_at < $admin->created_at)
                                                      <button type="button" class="button-delete" onclick="confirmDelete('{{ $admin->id }}')">ลบข้อมูล</button>
                                                      @endif
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
      function confirmDelete(userId) {
            Swal.fire({
                  title: 'คุณแน่ใจหรือไม่?',
                  text: "คุณต้องการลบผู้ใช้นี้หรือไม่?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'ใช่, ลบเลย!',
                  cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                  if (result.isConfirmed) {
                        document.getElementById('delete-form-' + userId).submit();
                  }
            });


      }
</script>

@endsection