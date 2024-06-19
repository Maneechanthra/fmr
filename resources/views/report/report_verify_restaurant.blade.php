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
                                    <th scope="col">รูปภาพ</th>
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

                                    <td data-label="รูปภาพ">
                                          @foreach($restaurant->image_paths as $image_path)
                                          <img src="{{ url('public/' . $image_path) }}" alt="Restaurant Image assets" style="max-width: 100px; max-height: 100px; margin-right: 10px;">
                                          @endforeach
                                    </td>


                                    <td data-label="หมายเลขโทรศัพท์ 1">{{ $restaurant-> telephone_1}}</td>
                                    <td data-label=" หมายเลขโทรศัพท์ 2">{{ $restaurant-> telephone_2}}
                                    </td>
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
                                          <form id="adjust-form-{{ $restaurant->id }}" action="{{ route('update-verify', ['id' => $restaurant->id, 'userId' => $userData->id]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="button" class="button-status" onclick="confirmAdjust('{{ $restaurant->id }}')">ยืนยัน</button>
                                          </form>


                                          <form id="adjust-form-{{ $restaurant->id }}" action="{{ route('reject-verify', ['id' => $restaurant->id, 'userId' => $userData->id]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="button" class="button-delete" onclick="rejectAdjus('{{ $restaurant->id }}')">ปฏิเสธ</button>
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
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


<script>
      function confirmAdjust(id) {
            Swal.fire({
                  title: 'คุณแน่ใจหรือไม่?',
                  text: "คุณต้องการปรับสถานะรับรองร้านอาหารใช่หรือไม่?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'ใช่, แน่นอน!',
                  cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                  if (result.isConfirmed) {
                        document.getElementById('adjust-form-' + id).submit();
                  }
            });
      }
</script>
<script>
      function rejectAdjus(restaurantId) {
            var promptBox = document.createElement("div");
            promptBox.classList.add("prompt-box");
            promptBox.innerHTML = `
        <h4>กรุณากรอกเหตุผลที่ปฏิเสธ:</h4>
        <input class="reason" type="text" id="reject-reason" />
        <input type="hidden" id="restaurant-id" value="${restaurantId}" />
        <input type="hidden" id="user-id" value="{{ $userData->id }}" />
        <button onclick="submitReject()">ตกลง</button>
        <button onclick="closePrompt()">ยกเลิก</button>
    `;
            document.body.appendChild(promptBox);
      }


      function submitReject() {
            var reasonElement = document.getElementById("reject-reason");
            var restaurantIdElement = document.getElementById("restaurant-id");
            var userIdElement = document.getElementById("user-id");

            if (reasonElement && restaurantIdElement && userIdElement) {
                  var reason = reasonElement.value;
                  var restaurantId = restaurantIdElement.value;
                  var userId = userIdElement.value;

                  console.log("เหตุผล: " + reason, "restaurant_id: " + restaurantId, " || userId: " + userId);

                  if (reason.trim() !== "") {
                        Swal.fire({
                              title: 'คุณแน่ใจหรือไม่?',
                              text: "คุณต้องการที่จะปฏิเสธร้านอาหารนี้หรือไม่?",
                              icon: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'ใช่, ปฏิเสธ!',
                              cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                              if (result.isConfirmed) {
                                    axios.put(`/reject-verify/${restaurantId}/${userId}`, {
                                                id: restaurantId,
                                                updated_by: userId,
                                                reject_detail: reason
                                          })
                                          .then(response => {
                                                console.log(response.data);
                                                Swal.fire(
                                                      'ปฏิเสธ!',
                                                      'การปฏิเสธร้านอาหารสำเร็จแล้ว',
                                                      'success'
                                                ).then(() => {
                                                      window.history
                                                            .back();
                                                });
                                          })
                                          .catch(error => {
                                                console.error(error);
                                                Swal.fire(
                                                      'ผิดพลาด!',
                                                      'เกิดข้อผิดพลาดในการปฏิเสธร้านอาหาร',
                                                      'error'
                                                );
                                          });
                              }
                        });
                  } else {
                        alert("กรุณากรอกเหตุผลที่ปฏิเสธ");
                  }
            } else {
                  console.error("One or more elements not found.");
            }
      }



      function closePrompt() {
            var promptBox = document.querySelector(".prompt-box");
            promptBox.parentNode.removeChild(promptBox);
      }
</script>






@endsection