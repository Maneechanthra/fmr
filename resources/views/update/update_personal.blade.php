@extends('layouts.app')

@section('content')
<section class="main">
      <div class="main-top">
            <h1>แก้ไขข้อมูลส่วนตัว</h1>
      </div>
      <section class="attendance">
            <div class="attendance-list">
                  <div class="form-update">
                        <h3>แก้ไขข้อมูลส่วนตัว</h3>
                        <div class="form-box">

                              @csrf
                              <div class="form-group">
                                    <label for="email">อีเมล *</label>
                                    <input id="email" name="email" type="email" required>
                              </div>
                              <div class="form-group">
                                    <label for="password">รหัสผ่าน *</label>
                                    <input id="password" name="password" type="password" placeholder="Password..." required>
                              </div>
                              <button type="submit">บันทึกการเปลี่ยนแปลง</button>

                        </div>
                  </div>
            </div>
      </section>
</section>
@endsection