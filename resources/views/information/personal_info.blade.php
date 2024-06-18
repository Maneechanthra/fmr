@extends('layouts.app')

@section('content')
<section class="main">
      <div class="main-top">
            <h1>ข้อมูลส่วนตัว</h1>
      </div>
      <section class="attendance">
            <div class="attendance-list">
                  <div class="form-update">
                        <h3 style="text-align: center;  padding: 0px;">ข้อมูลส่วนตัว</h3>
                        <div class="form-box">

                              @csrf
                              <!-- <div class="form-group">
                                    <h1>Welcome, {{ $userData->name }}</h1>
                                    <p>Email: {{ $userData->email }}</p>
                              </div> -->

                              <div class="box-content1">
                                    <p style="margin-top: 10px;">รูปโปรไฟล์</p>
                                    <div class="img-profile">
                                          <img src="{{ asset('assets/images/profile.jpg') }}">
                                    </div>
                                    <div class="text-form">
                                          <h1>Welcome</h1>
                                          <div class="text-name"> {{ $userData->name }}</div>
                                    </div>
                                    <div class="text-form">
                                          <h1>Email</h1>
                                          <div class="text-name"> {{ $userData->email }}</div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </section>
</section>
@endsection