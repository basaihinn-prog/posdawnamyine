<!DOCTYPE html>
<html>
<head>
    <title>404 Not Found</title>
    <style>
        /* Add your custom CSS here */
        body {
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            text-align: center;
        }
        img {
            width: 100%;
            height: auto;
        }
        a {
            margin-top: 80px;
            margin-bottom: 40px;
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            background: #FF8400;
            font-weight: 600;
            color: #FFF;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ url('/') }}">← {{ __('Back To Home') }}</a>
        <img src="{{ asset('assets/images/errors/404.jpg') }}" alt="Not found">
    </div>
</body>
</html>
