<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home Page with Slider and Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body,
        html {
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100%;
            margin: 0;
        }

        .full-height-center {
            min-height: calc(100vh - 320px);
            /* slider (200) + card (approx 120) height minus kiya */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            text-align: center;
        }

        h1 {
            font-size: 1.8rem;
        }

        @media (min-width: 768px) {
            h1 {
                font-size: 2.5rem;
            }
        }

        button.btn {
            min-width: 120px;
        }

        /* Slider height fix karne ke liye */
        .carousel {
            max-height: 200px;
            /* aap apni zarurat ke hisab se height change kar sakte hain */
            overflow: hidden;
        }

        .carousel-item img {
            object-fit: cover;
            height: 200px;
            /* same height as carousel */
            width: 100%;
        }

        .carousel-inner {
            width: 93%;
            border-radius: 7px;
        }

        .ticker-wrapper {
            width: 100%;
            overflow: hidden;

            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            white-space: nowrap;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .ticker {
            display: inline-block;
            padding: 10px 0;
            animation: scroll-left 15s linear infinite;
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .com-card {
            border: 1px solid #ccc;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
    </style>
</head>

<body>

    <div class="container mt-3">
        <!-- Bootstrap Carousel slider -->
        <div id="homeSlider" class="carousel slide d-flex justify-content-center" data-bs-ride="carousel"
            data-bs-interval="3000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <a href="/plan-game">
                    <img src="{{ asset('homeallimage/image1.jpg') }}" class="d-block w-100" alt="Image 1" />
                    </a>
                </div>
                <div class="carousel-item">
                    <a href="" target="_blank">
                    <img src="{{ asset('homeallimage/image2.jpg') }}" class="d-block w-100" alt="Image 2" />
                    </a>
                </div>
                <div class="carousel-item">
                    <a href="" target="_blank">
                    <img src="{{ asset('homeallimage/image3.jpg') }}" class="d-block w-100" alt="Image 3" />
                    </a>
                </div>
            </div>
        </div>

        <!-- Card below the slider -->
        <div class="card com-card mt-4 mx-auto" style="max-width: 350px;">
            <div class="card-body">
                <p class="card-text mb-1"><strong>Balance:</strong> 0.0</p>
                <p class="card-text"><strong>ID:</strong> 002132</p>
            </div>
        </div>
    </div>
    <div class="ticker-wrapper mt-3">
        <div class="ticker">
            <span>🚀 Welcome to the Home Page! Mot spalling mistake shambhak lena - Balance: 0.0 | ID: 002132 🚀</span>
        </div>
    </div>
    
    <div class="container full-height-center">
        <h1>Welcome, {{ auth()->user()->name }}</h1>

        <form method="POST" action="{{ route('logout') }}" class="mt-4 w-100" style="max-width: 300px;">
            @csrf
            <button type="submit" class="btn btn-danger w-100">Logout</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
