<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-5+1I1bVb+gOQe6ZyP9xkUQwPflAnY7w1fNwQpQFQOaQ0GZpD0S1QqVvQpG+qVb5F" crossorigin="anonymous">
    <style>
        body { background-color: #f8f9fa; }
        .content-wrapper { padding: 1rem; }
    </style>
</head>
<body>
    
    <!-- Content -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdSb9g6TjG6eVbV2HhZ8jvQvS+O3yZfS9F6QaD9q0Qj2Q+o1KQ+zB+V+5N5T2E" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc2e9kr4eG+rtW5VfR0C2nV7K2eMVbW1kQ9o1E2Gd" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        if (window.feather) { feather.replace(); }
    </script>
</body>
</html>