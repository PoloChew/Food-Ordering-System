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
    <title>Contact Us - Nordic Taste</title>
    <link rel="stylesheet" href="css/contact.css">
    <link rel="shortcut icon" href="/image/logo.png">
</head>

<body>

    <header>
        <div class="brand"><?php echo $seasonIcon; ?> My Restaurant</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="location.php">Location</a>
            <a href="contact.php" style="color: #fff; border-bottom: 1px solid #fff;">Contact Us</a>
            <a href="about.php">About</a>
        </div>
    </header>

    <div class="main-content">
        
        <div class="contact-container">
            <div class="contact-image">
                <img src="image/osaka_vibe.jpg" onerror="this.src='https://images.unsplash.com/photo-1590559899731-a3828392a2bb?q=80&w=800&auto=format&fit=crop'" alt="Osaka Vibes">
            </div>

            <div class="contact-info">
                <h1>Get in Touch</h1>
                <p>Have a question or want to book a table? We are here for you.</p>

                <div class="contact-links">
                    
                    <a href="tel:+601137721966" class="contact-btn">
                        <span class="icon">📞</span>
                        <div>
                            <span class="label">Call Us</span>
                            <span class="value">+60 11 3772 1966</span>
                        </div>
                    </a>

                    <a href="https://wa.me/601137721966" target="_blank" class="contact-btn">
                        <span class="icon">💬</span>
                        <div>
                            <span class="label">WhatsApp Us</span>
                            <span class="value">Chat Now</span>
                        </div>
                    </a>

                    <a href="sms:+601137721966" class="contact-btn">
                        <span class="icon">✉️</span>
                        <div>
                            <span class="label">Send SMS</span>
                            <span class="value">+60 11 3772 1966</span>
                        </div>
                    </a>

                    <a href="mailto:infoFoodUs@gmail.com" class="contact-btn">
                        <span class="icon">📧</span>
                        <div>
                            <span class="label">Email Us</span>
                            <span class="value">infoFoodUs@gmail.com</span>
                        </div>
                    </a>

                </div>
            </div>
        </div>

    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> My Restaurant. All Rights Reserved.</p>
        <p class="fade-text">Osaka • Nature • Soul</p>
    </footer>
</body>
</html>