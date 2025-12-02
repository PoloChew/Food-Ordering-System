<?php 
require 'DB.php';

$currentMonth = date('n'); 
$greetingTitle = "Welcome to Our Space";
$subTitle = "Experience the taste of nature";
$seasonIcon = "🌿";


switch ($currentMonth) {
    case 1: 
        $greetingTitle = "Happy New Year";
        $subTitle = "New beginnings, fresh tastes";
        $seasonIcon = "✨";
        break;
    case 2: 
        $greetingTitle = "Love is in the Air";
        $subTitle = "Celebrate Valentine's Day with us";
        $seasonIcon = "❤️";
        break;
    case 3: 
        $greetingTitle = "Welcome Spring";
        $subTitle = "Fresh flavors bloom like spring flowers";
        $seasonIcon = "🌸";
        break;
    case 4: 
        $greetingTitle = "Happy Easter";
        $subTitle = "Egg-citing treats await you";
        $seasonIcon = "🥚";
        break;
    case 5: 
        $greetingTitle = "Celebrate Mom";
        $subTitle = "Treat the special lady in your life";
        $seasonIcon = "💐";
        break;
    case 6: 
        $greetingTitle = "Hello Summer";
        $subTitle = "Cool drinks and fresh bites";
        $seasonIcon = "☀️";
        break;
    case 7: 
        $greetingTitle = "Summer Vibes";
        $subTitle = "Grill, chill, and enjoy";
        $seasonIcon = "🔥";
        break;
    case 8: 
        $greetingTitle = "Late Summer Treats";
        $subTitle = "Delicious flavors before autumn";
        $seasonIcon = "🍉";
        break;
    case 9: 
        $greetingTitle = "Hello Autumn";
        $subTitle = "Warm dishes for cozy evenings";
        $seasonIcon = "🍂";
        break;
    case 10: 
        $greetingTitle = "Spooky Halloween";
        $subTitle = "Trick or treat with our special menu";
        $seasonIcon = "🎃";
        break;
    case 11: 
        $greetingTitle = "Happy Thanksgiving";
        $subTitle = "Feast and gratitude";
        $seasonIcon = "🦃";
        break;
    case 12: 
        $greetingTitle = "Merry Christmas";
        $subTitle = "Celebrate the joy of the season with us";
        $seasonIcon = "🎄";
        break;
    default:
        $greetingTitle = "Welcome to Our Space";
        $subTitle = "Experience the taste of nature";
        $seasonIcon = "🌿";
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/image/logo.png">
    <title>Our Location - Osaka</title>
    <link rel="stylesheet" href="css/location.css">
</head>
<body>
    <header>
        <div class="brand"><?php echo $seasonIcon; ?>Yume (梦 - ゆめ)</div>
        <div class="nav-links">
            <a href="index.php">🏠 Home</a>
            <a href="location.php" style="color: #fff; border-bottom: 1px solid #fff;">📍 Location</a> 
            <a href="Contact.php">📞 Contact Us</a>
            <a href="about.php">👥 About</a>
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
                    +60 11 3772 1966<br>
                    infoFoodUs@gmail.com
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
        <p>© <?php echo date('Y'); ?> Yume (梦 - ゆめ). All Rights Reserved.</p>
        <p class="fade-text">Osaka • Nature • Soul</p>
    </footer>
</body>
</html>