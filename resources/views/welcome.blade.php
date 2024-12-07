<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas Sistem Informasi Geografis</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        html {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {

            background: linear-gradient(135deg, #6dd5ed, #2193b0);
            background-size: cover;
            background: linear-gradient(135deg, #6dd5ed, #2193b0);
            color: #fff;
            font-family: 'Arial', sans-serif;
            ;
        }

        .container {
            margin-top: 5%;
        }

        h1 {
            font-size: 3rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .btn {
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #00c6ff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #007acc;
            transform: scale(1.1);
        }

        .btn-secondary {
            background-color: #f85032;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #c41c00;
            transform: scale(1.1);
        }

        .mt-4 {

            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="container text-center">
        <h1>Tugas Sistem Informasi Geografis</h1>
        <div class="mt-5 d-flex justify-content-center  ">
            <a href="/map" class="mx-2 btn btn-primary shadow-lg">LATIHAN</a>
            <a href="/tugas1" class="mx-2 btn btn-secondary shadow-lg">TUGAS 1</a>
        </div>
    </div>
</body>

</html>
