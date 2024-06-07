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
                    @foreach($user as $index => $userInfo)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $userInfo->user_name }}</td>
                        <td>{{ $userInfo->user_email }}</td>
                        <td>
                            @if ($userInfo->role == 0)
                            สมาชิก
                            @else
                            แอดมิน
                            @endif

                        </td>
                        <td>
                            <form action="{{ route('update-status', ['id' => $userInfo->user_id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="button-status">ปรับสถานะ</button>
                            </form>
                        </td>

                        <td>
                            <form id="delete-form-{{ $userInfo->user_id }}" action="{{ route('delete-user', ['id' => $userInfo->user_id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="button-delete" onclick="confirmDelete('{{ $userInfo->user_id }}')">ลบข้อมูล</button>
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