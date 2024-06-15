@extends('layouts.app')

@section('content')
<section class="main">
      <div class="main-top">
            <h1>ข้อมูลส่วนตัว</h1>
      </div>
      <section class="attendance">
            <div class="attendance-list">
                  <div class="form-update">
                        <h3>ข้อมูลส่วนตัว</h3>
                        <div class="form-box">

                              @csrf
                              <div class="form-group">
                                    <h1>Welcome, {{ $userData->name }}</h1>
                                    <p>Email: {{ $userData->email }}</p>
                              </div>
                        </div>
                  </div>
            </div>
      </section>
</section>
@endsection