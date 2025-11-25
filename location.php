<?php 
require 'DB.php';
$currentMonth = date('n'); 
$seasonIcon = "🌿";
if ($currentMonth == 12) { $seasonIcon = "🎄"; } 
elseif ($currentMonth == 1) { $seasonIcon = "✨"; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Location - Osaka</title>

    <style>
        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            background-color: #0f2f2f; 
            margin: 0;
            padding: 0;
            color: #e8f5e9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* --- Header 样式 --- */
        header {
            background-color: #0b2222; 
            padding: 25px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            border-bottom: 1px solid #1f4f4f;
        }

        .brand {
            font-size: 28px;
            font-weight: 700;
            color: #d0f0d0;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .nav-links a {
            color: #aebcb9;
            text-decoration: none;
            margin-left: 40px;
            font-size: 18px;
            font-weight: 500;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-links a:hover {
            color: #fff;
        }

        /* --- Location 页面专属样式 --- */
        .location-hero {
            position: relative;
            height: 400px;
            width: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .location-hero img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.6); /* 稍微变暗，为了突出文字 */
            z-index: 1;
        }

        .hero-text {
            position: relative;
            z-index: 2;
            padding: 20px;
        }

        .hero-text h1 {
            font-size: 60px;
            margin: 0;
            color: #fff;
            text-shadow: 0 0 20px rgba(46, 125, 111, 0.8);
            letter-spacing: 3px;
        }

        .hero-text p {
            font-size: 24px;
            color: #d0f0d0;
            margin-top: 15px;
            font-weight: 300;
        }

        /* 内容容器 */
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 40px;
            flex: 1;
        }

        .grid-layout {
            display: flex;
            gap: 50px;
            flex-wrap: wrap;
        }

        .info-card {
            flex: 1;
            min-width: 350px;
            background: rgba(22, 63, 63, 0.6);
            padding: 40px;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }

        .info-card h2 {
            font-size: 32px;
            color: #d0f0d0;
            margin-top: 0;
            border-bottom: 2px solid #2e7d6f;
            display: inline-block;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .info-item {
            margin-bottom: 30px;
            font-size: 18px;
            line-height: 1.6;
            color: #aebcb9;
        }

        .info-item strong {
            color: #fff;
            display: block;
            font-size: 20px;
            margin-bottom: 5px;
        }

        /* 地图容器 */
        .map-container {
            flex: 1.5;
            min-width: 350px;
            height: 500px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.5);
            border: 2px solid #2e7d6f;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
            filter: brightness(0.95);
        }

        footer {
            background-color: #081a1a;
            padding: 40px 20px; /* 减少内边距 */
            border-top: 1px solid #1f4f4f;
            text-align: center; /* 内容居中 */
            color: #6c8c8c;
        }

        footer p {
            margin: 5px 0;
            font-size: 14px;
            letter-spacing: 1px;
        }

        footer .fade-text {
            font-size: 12px;
            opacity: 0.5;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 10px;
        }

        /* 手机适配 */
        @media (max-width: 768px) {
            header { flex-direction: column; padding: 20px; }
            .nav-links a { margin: 10px; font-size: 16px; }
            .hero-text h1 { font-size: 36px; }
            .grid-layout { flex-direction: column; }
            .map-container { height: 300px; }
        }
    </style>
</head>

<body>

    <header>
        <div class="brand"><?php echo $seasonIcon; ?> Nordic Taste</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="location.php" style="color: #fff; border-bottom: 1px solid #fff;">Location</a> 
            <a href="Contact.php">Contact Us</a>
        </div>
    </header>

    <div class="location-hero">
        <img src="https://images.unsplash.com/photo-1579033461380-adb47c3eb938?q=80&w=1600&auto=format&fit=crop" alt="Aurora Finland">
        <div class="hero-text">
            <h1>Dine Under The Lights</h1>
            <p>The World's Northernmost Dining Experience</p>
        </div>
    </div>

    <div class="content-container">
        
        <div class="grid-layout">
            
            <div class="info-card">
                <h2>Visit Us</h2>
                
                <div class="info-item">
                    <strong>📍 Address</strong>
                    3 Chome-6-10 Fukushima,<br>
                    AFukushima Ward, Osaka, 553-0003日本<br>
                    <span style="font-size:14px; color:#6c8c8c;">(Just next to Santa Claus Village)</span>
                </div>
                <div class="info-item">
                    <strong>🕐 Opening Hours</strong>
                    Mon - Sun: 24 Hours<br>
                    <span style="color: #d4af37;">★ Best Aurora Viewing: 10 PM - 2 AM</span>
                </div>

                <div class="info-item">
                    <strong>📞 Contact</strong>
                    +358 40 123 4567<br>
                    hello@nordic-mcd.fi
                </div>

                <div style="margin-top: 40px;">
                    <a href="#" class="btn" style="
                        display: inline-block;
                        padding: 15px 40px;
                        background: #2e7d6f;
                        color: #fff;
                        text-decoration: none;
                        border-radius: 30px;
                        font-weight: bold;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                    ">Get Directions</a>
                </div>
            </div>

            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!4v1764050147754!6m8!1m7!1sSJoTmvdTUUrLPml2Ao8RUg!2m2!1d34.69261180385783!2d135.4841551029057!3f53.2817223342599!4f1.965464408982129!5f0.7820865974627469" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> My Restaurant. All Rights Reserved.</p>
        <p class="fade-text">Osaka • Nature • Soul</p>
    </footer>
</body>
</html>