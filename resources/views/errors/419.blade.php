<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 3rem;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 90%;
            width: 400px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            opacity: 0;
        }

        .container.show {
            opacity: 1;
        }

        h1 {
            color: #5469d4;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: 600;
        }

        .icon {
            font-size: 4rem;
            color: #5469d4;
            margin-bottom: 1rem;
            animation: pulse 1.5s infinite;
        }

        p {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .refresh-btn {
            padding: 0.8rem 2rem;
            font-size: 1rem;
            color: #fff;
            background-color: #5469d4;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .refresh-btn:hover {
            background-color: #4557b7;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(84, 105, 212, 0.25);
        }

        .refresh-btn:active {
            transform: translateY(0);
        }

        .refresh-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.3s ease;
        }

        .refresh-btn:hover::before {
            left: 100%;
        }

        /* Animation */
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 2rem;
                width: 90%;
            }

            h1 {
                font-size: 1.8rem;
            }

            p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon">⌛</div>
        <h1>Sesi Berakhir</h1>
        <p>Sesi Anda telah berakhir untuk alasan keamanan. Klik tombol di bawah untuk menyegarkan dan melanjutkan dari
            tempat Anda sebelumnya.</p>
        <button class="refresh-btn" onclick="window.location.href='/'">
            Kembali ke Halaman Login
        </button>
    </div>

    <script>
        // Smooth fade-in animation on load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelector('.container').classList.add('show');
            }, 100);
        });
    </script>
</body>

</html>
