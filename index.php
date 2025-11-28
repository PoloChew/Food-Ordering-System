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
    <link rel="shortcut icon" href="/image/logo.png">
    <title>Food Order System - <?php echo $greetingTitle; ?></title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>

    <header>
        <div class="brand">
            <img src="image/logo.png" alt="Logo" class="logo">
            <?php echo $seasonIcon; ?> My Restaurant
        </div>
        <div id="google_translate_element" style="margin: 20px;"></div>
        <div class="nav-links">
            <a href="location.php">Location</a>
            <a href="Contact.php">Contact Us</a>
            <a href="about.php">About</a>
        </div>
    </header>

    <div class="main-content">
        
        <div class="season-greeting">
            <h1><?php echo $greetingTitle; ?></h1>
            <p><?php echo $subTitle; ?></p>
        </div>

        <div class="scenery-gallery">
            <div class="scenery-card">
                <img src="https://images.unsplash.com/photo-1531366936337-7c912a4589a7?q=80&w=600&auto=format&fit=crop" alt="Aurora Borealis">
            </div>
            <div class="scenery-card">
                <img src="https://images.unsplash.com/photo-1473448912268-2022ce9509d8?q=80&w=400&auto=format&fit=crop" alt="Forest">
            </div>
            <div class="scenery-card">
                <img src="https://images.unsplash.com/photo-1483683804023-6ccdb62f86ef?q=80&w=600&auto=format&fit=crop" alt="Winter Mood">
            </div>
        </div>

        <div class="action-area">
            <h2 style="font-size: 18px; margin-bottom: 25px; color: #aebcb9; font-weight: normal;">Please select your dining preference</h2>
            <a href="Order/product.php?type=dinein" class="btn">Dine In</a>
            <a href="Order/product.php?type=takeaway" class="btn">Take Away</a>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> My Restaurant. All Rights Reserved.</p>
        <p class="fade-text">Osaka • Nature • Soul</p>
    </footer>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement(
            {
                pageLanguage: 'en',
                includedLanguages: 'en,zh-CN,ja,ko', 
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            },
            'google_translate_element'
            );
        }
    </script>

<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>
</html>