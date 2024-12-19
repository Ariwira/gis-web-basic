<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Geografis - Tugas Praktikum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2193b0;
            --secondary-color: #6dd5ed;
            --accent-color: #f85032;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
        }

        body {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: #fff;
            font-family: 'Arial', sans-serif;
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            display: flex;
            justify-content: center
        }


        .hero-content {
            text-align: center;
            max-width: 800px;
            padding: 0 15px;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: bold;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
            color: #fff;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 40px;
            color: rgba(255, 255, 255, 0.8);
        }

        .btn-custom {
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            margin: 0 10px;
            display: inline-block;
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border: none;
            color: #fff;
        }

        .btn-primary-custom:hover {
            background-color: #007acc;
            transform: scale(1.05);
        }

        .btn-secondary-custom {
            background-color: var(--accent-color);
            border: none;
            color: #fff;
        }

        .btn-secondary-custom:hover {
            background-color: #c41c00;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .btn-custom {
                margin: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Sistem Informasi Geografis</h1>
                <p class="hero-subtitle">Universitas Udayana</p>
                <div class="mt-4">
                    <a href="/map" class="btn btn-custom btn-primary-custom shadow-lg">LATIHAN</a>
                    <a href="/tugas1" class="btn btn-custom btn-secondary-custom shadow-lg">TUGAS 1</a>
                    <a href="/interactive" class="btn btn-custom btn-primary-custom shadow-lg">LATIHAN 2</a>
                    <a href="/tugas2" class="btn btn-custom btn-secondary-custom shadow-lg">TUGAS 2</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
