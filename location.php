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
    case 5: // 五月 - 母亲节
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
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/751/751621.png">
    <title>Our Location - TAR UMT Cafe</title>
    <link rel="stylesheet" href="css/location.css">

    <style>
        .brand img { height: 35px; width: auto; margin-right: 10px; vertical-align: middle; }
        .brand { display: flex; align-items: center; }
    </style>
</head>
<body>
    <header>
        <div class="brand">
            <img src="image/logo.png" alt="TAR UMT">
            TAR UMT Cafe
        </div>
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
                <div class="info-item"><strong>📍 Address</strong>Ground Floor, Bangunan Tan Sri Khaw Kai Boh (Block A),<br>Jalan Genting Kelang, Setapak, 53300 Kuala Lumpur<br><span style="font-size:14px; color:#6c8c8c;">(Inside TAR UMT Main Campus)</span></div>
                <div class="info-item"><strong>🕐 Opening Hours</strong>Mon - Sun: 24 Hours</div>
                <div class="info-item"><strong>📞 Contact</strong>+60 11 3772 1966<br>infoFoodUs@gmail.com</div>
                <div style="margin-top: 40px;"><a href="#" class="btn" style="display: inline-block; padding: 15px 40px; background: #2e7d6f; color: #fff; text-decoration: none; border-radius: 30px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">Get Directions</a></div>
            </div>
            
            <div class="right-column">
                
                <div class="map-container">
                    

[Image of google map iframe]

                    <iframe src="https://www.google.com/maps/embed?pb=!4v1765257965801!6m8!1m7!1smeDqMgcKtU8ZYMDJDuf-vg!2m2!1d3.217365224687878!2d101.7284337870789!3f259.240128064341!4f-3.2837938933896!5f0.7820865974627469" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

                <div class="arena-container">
                    <img src="image/arena.jpg" alt="Arena Scene" onerror="this.src='https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1000&auto=format&fit=crop'">
                    <div class="arena-caption">The Grand Arena View</div>
                </div>

            </div>
        </div>
    </div>
    <footer><p>© <?php echo date('Y'); ?> TAR UMT Cafe. All Rights Reserved.</p><p class="fade-text">Beyond Education</p></footer>
</body>
</html>