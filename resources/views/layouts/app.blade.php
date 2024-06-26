<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <title>FMRestaurant</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style-update-personal.css') }}">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
      <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
      <link rel="stylesheet" href="{{ asset('assets/css/datatables.css') }}">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
      <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
      <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
      <div class="container">
            @include('navbar.navbar')
            @yield('content')
      </div>
      <!-- Include jQuery and DataTables JS libraries -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
      <script>
            $(document).ready(function() {
                  $('#data-table').DataTable();
            });
      </script>
</body>

</html>