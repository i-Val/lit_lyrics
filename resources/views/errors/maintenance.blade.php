<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode - Lit Lyrics</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.css') }}">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f8f8;
        }
        .maintenance-container {
            text-align: center;
            max-width: 600px;
            padding: 2rem;
        }
        .maintenance-img {
            max-width: 100%;
            height: auto;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <img src="{{ asset('app-assets/images/pages/maintenance.svg') }}" alt="Maintenance" class="maintenance-img" style="max-height: 300px;">
        <h1 class="mb-1">Under Maintenance 🛠</h1>
        <p class="mb-3">Sorry for the inconvenience but we're performing some maintenance at the moment. We'll be back shortly!</p>
        
        @if(Request::is('admin/*') || Request::is('login'))
            <a href="{{ route('dashboard.index') }}" class="btn btn-primary">Go to Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary">Admin Login</a>
        @endif
    </div>
</body>
</html>
