@extends('layouts.app')

@section('content')

<section class="main">
      <div class="main-top">
            <h1>FMRestaurant</h1>
            <i class="fas fa-user">
                  {{ $userData->name }} || {{ $userData->email }}
            </i>
      </div>
      <div class="users1">
            <div class="card1">
                  <img style="margin-top: 30px;" src="{{ asset('assets/icons/store-block.png') }}" />
                  <h4>รายงานความไม่เหมาะสมของร้านอาหาร</h4>
                  <p>จำนวน {{ $reportCount }} ร้าน</p>
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
                                    <th scope="col">จำนวนครั้งที่ถูกรายงาน</th>
                                    <th scope="col">สถานะร้านอาหาร</th>
                                    <th scope="col">จัดการ</th>
                              </tr>
                        </thead>
                        <tbody>
                              @foreach ($groupedReports as $restaurantId => $restaurantData)
                              <tr>
                                    <td data-label="ลำดับ">{{ $loop->iteration }}</td>
                                    <td data-label="ชื่อร้าน">{{ $restaurantData['restaurant']['restaurant_name'] }}
                                    </td>
                                    <td data-label="เหตุผล">
                                          @foreach ($restaurantData['report_titles'] as $title)
                                          <p>{{ $title }}</p>
                                          @endforeach
                                    </td>
                                    <td data-label="รายละเอียดเพิ่มเติม">
                                          @foreach ($restaurantData['report_descriptions'] as $description)
                                          <p>{{ $description }}</p>
                                          @endforeach
                                    </td>
                                    <td data-label="จำนวนครั้งที่ถูกรายงาน">{{ $restaurantData['reportCount'] }} / 3
                                    </td>
                                    <td data-label="สถานะร้านอาหาร">
                                          @if ($restaurantData['restaurant']['verified'])
                                          รับรองแล้ว
                                          @else
                                          ยังไม่รับรอง
                                          @endif
                                    </td>
                                    <td data-label="จัดการ">
                                          @if ($restaurantData['reportCount'] < 3) <button class="button-delete" disabled>ยกเลิกการเข้าถึงข้อมูล</button>
                                                @else
                                                <form id="updated-status-restaurant-{{ $restaurantId }}" action="{{ route('update-status-restaurant', ['id' => $restaurantId, 'userId' => $userData['userId']]) }}" method="POST">
                                                      @csrf
                                                      @method('PUT')
                                                      <button type="button" class="button-delete" onclick="updatedStatus('{{ $restaurantId }}')">ยกเลิกการเข้าถึงข้อมูล</button>
                                                </form>
                                                @endif
                                    </td>
                              </tr>
                              @endforeach
                        </tbody>
                  </table>
            </div>
      </section>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
      function updatedStatus(id) {
            console.log(id);
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
                        document.getElementById('updated-status-restaurant-' + id).submit();
                  }
            });
      }
</script>

@endsection