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
    <title>About Us - The Creators</title>
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/751/751621.png">
    <link rel="stylesheet" href="css/about.css">

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
            <a href="location.php">📍 Location</a>
            <a href="contact.php">📞 Contact Us</a>
            <a href="about.php" style="color: #fff; border-bottom: 1px solid #fff;">👥 About</a>
        </div>
    </header>

    <div class="about-hero">
        <img src="https://images.unsplash.com/photo-1531366936337-7c912a4589a7?q=80&w=1600&auto=format&fit=crop" alt="Aurora Tech">
        <div class="hero-content">
            <h1>Innovation Meets Tradition</h1>
            <p>We combine the warmth of fine dining with the precision of modern technology.</p>
        </div>
    </div>

    <div class="content-container">

        <div class="system-intro">
            <h2>About The System</h2>
            <p>
                This <strong>Food Ordering System</strong> is designed to simplify your dining experience. Whether you want to enjoy an immersive <strong>Dine-in</strong> experience or quickly <strong>Delivery</strong>, our platform ensures a smooth, efficient, and convenient ordering experience.
            </p>
            <p style="margin-top: 15px; font-size: 14px; opacity: 0.7;">
                Developed as part of the Diploma in Information Technology curriculum, blending coding skills with user experience design.
            </p>
        </div>

        <h2 class="team-title">Meet The Creators</h2>
        
        <div class="team-grid">
            
            <div class="dev-card">
                <div class="avatar-circle">C</div> 
                <div class="dev-name">Chew Chun Xian</div>
                <div class="dev-role">Developer</div>
                <div class="uni-badge">TARUMT • DFT Y2S2</div>
            </div>

            <div class="dev-card">
                <div class="avatar-circle">W</div>
                <div class="dev-name">Wong Jia Cheng</div>
                <div class="dev-role">Developer</div>
                <div class="uni-badge">TARUMT • DFT Y2S2</div>
            </div>

            <div class="dev-card">
                <div class="avatar-circle">N</div>
                <div class="dev-name">Ng Hong Liang</div>
                <div class="dev-role">Developer</div>
                <div class="uni-badge">TARUMT • DFT Y2S2</div>
            </div>

        </div>

    </div>

    <footer><p>© <?php echo date('Y'); ?> TAR UMT Cafe. All Rights Reserved.</p><p class="fade-text">Beyond Education</p></footer>
</body>
</html>