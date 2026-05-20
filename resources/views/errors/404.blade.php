<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ไม่พบหน้าที่ต้องการ</title>
    <!-- FAVICON ICON -->
    <link rel="shortcut icon" href="{{ assetImage(readconfig('favicon_icon')) }}" type="image/svg+xml">
    <style>
        .content {
            display: flex;
            justify-content: center;
            margin: 20px;
        }
    </style>
</head>

<body>
    <div class="content">
        <img class="img-fluid" src="{{ asset('assets/images/demo/errors/404.svg') }}" alt="ไม่พบหน้าที่ต้องการ">
    </div>
</body>

</html>
