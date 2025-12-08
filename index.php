<?php 
require 'DB.php';
//
$currentMonth = date('n'); 
$greetingTitle = "Welcome to Our Space";
$subTitle = "Experience the taste of nature";
$seasonIcon = "🌿";

$particleType = ($currentMonth == 12) ? 'snow' : 'sakura';

switch ($currentMonth) {
    case 1: $greetingTitle = "Happy New Year"; $subTitle = "New beginnings, fresh tastes"; $seasonIcon = "✨"; break;
    case 2: $greetingTitle = "Love is in the Air"; $subTitle = "Celebrate Valentine's Day"; $seasonIcon = "❤️"; break;
    case 3: $greetingTitle = "Welcome Spring"; $subTitle = "Fresh flavors bloom"; $seasonIcon = "🌸"; break;
    case 4: $greetingTitle = "Happy Easter"; $subTitle = "Egg-citing treats await"; $seasonIcon = "🥚"; break;
    case 5: $greetingTitle = "Celebrate Mom"; $subTitle = "Treat the special lady"; $seasonIcon = "💐"; break;
    case 6: $greetingTitle = "Hello Summer"; $subTitle = "Cool drinks and fresh bites"; $seasonIcon = "☀️"; break;
    case 7: $greetingTitle = "Summer Vibes"; $subTitle = "Grill, chill, and enjoy"; $seasonIcon = "🔥"; break;
    case 8: $greetingTitle = "Late Summer"; $subTitle = "Delicious flavors"; $seasonIcon = "🍉"; break;
    case 9: $greetingTitle = "Hello Autumn"; $subTitle = "Warm dishes, cozy evenings"; $seasonIcon = "🍂"; break;
    case 10: $greetingTitle = "Spooky Halloween"; $subTitle = "Trick or treat menu"; $seasonIcon = "🎃"; break;
    case 11: $greetingTitle = "Happy Thanksgiving"; $subTitle = "Feast and gratitude"; $seasonIcon = "🦃"; break;
    case 12: $greetingTitle = "Merry Christmas"; $subTitle = "Celebrate the season"; $seasonIcon = "🎄"; break;
    default: $greetingTitle = "Welcome to Our Space"; $subTitle = "Experience the taste of nature"; $seasonIcon = "🌿"; break;
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
    
    <style>
        .particle { position: fixed; top: -10px; z-index: 9999; pointer-events: none; }
        .sakura { animation: fall linear infinite, rotate ease-in-out infinite; }
        .sakura::after { content: ''; position: absolute; width: 100%; height: 100%; background-color: #ffc0cb; border-radius: 10px 0 10px 0; box-shadow: 0 0 5px rgba(255, 192, 203, 0.7); }
        .snow { background: white; border-radius: 50%; box-shadow: 0 0 5px white; animation: fall linear infinite; }
        @keyframes fall { 0% { top: -10%; opacity: 0.9; } 100% { top: 110%; opacity: 0.2; } }
        @keyframes rotate { 0% { transform: translateX(0) rotate(0deg); } 50% { transform: translateX(50px) rotate(180deg); } 100% { transform: translateX(0) rotate(360deg); } }
    </style>
</head>

<body>

    <header>
        <div class="brand">
            <img src="image/logo.png" alt="Logo" class="logo">
            <?php echo $seasonIcon; ?> Yume (梦 - ゆめ)
        </div>
        
        <div class="nav-links">
            <a href="location.php">📍 Location</a>
            <a href="Contact.php">📞 Contact</a>
            <a href="about.php">👥 About</a>
            
            <div class="lang-dropdown">
                <button class="lang-btn" onclick="toggleLangMenu()">🌐 Language ▼</button>
                <div class="lang-menu" id="langMenu">
                    <a href="javascript:void(0)" onclick="changeLang('en')">🇬🇧 English</a>
                    <a href="javascript:void(0)" onclick="changeLang('zh-CN')">🇨🇳 Chinese</a>
                    <a href="javascript:void(0)" onclick="changeLang('ja')">🇯🇵 Japanese</a>
                    <a href="javascript:void(0)" onclick="changeLang('ko')">🇰🇷 Korean</a>
                </div>
            </div>
        </div>
    </header>

    <div id="google_translate_element" style="display:none;"></div>

    <div class="main-content">
        
        <div class="season-greeting">
            <h1><?php echo $greetingTitle; ?></h1>
            <p><?php echo $subTitle; ?></p>
        </div>

        <div class="scenery-gallery">
            <a href="location.php" class="scenery-card">
                <img src="https://images.unsplash.com/photo-1531366936337-7c912a4589a7?q=80&w=600" alt="Seafood">
                <div class="scenery-label">🦀 Premium Seafood</div>
            </a>
            <a href="location.php" class="scenery-card">
                <img src="https://images.unsplash.com/photo-1473448912268-2022ce9509d8?q=80&w=400" alt="Japanese">
                <div class="scenery-label">🍣 Fresh Japanese</div>
            </a>
            <a href="location.php" class="scenery-card">
                <img src="https://images.unsplash.com/photo-1483683804023-6ccdb62f86ef?q=80&w=600" alt="Beer">
                <div class="scenery-label">🍺 Chilled Beers</div>
            </a>
        </div>

        <div class="action-area">
            <h2 style="font-size: 18px; margin-bottom: 25px; color: #aebcb9; font-weight: normal;">Please select your dining preference</h2>
            <a href="Order/product.php?type=dinein" class="btn">🍽️ Dine In</a>
            <a href="Order/product.php?type=takeaway" class="btn">🛍️ Take Away</a>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Yume (梦 - ゆめ). All Rights Reserved.</p>
        <p class="fade-text">Osaka • Nature • Soul</p>
    </footer>

    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({ pageLanguage: 'en', includedLanguages: 'en,zh-CN,ja,ko', autoDisplay: false }, 'google_translate_element');
        }
    </script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <script>
        // Custom Language Logic: Sets cookie and reloads page
        function toggleLangMenu() {
            document.getElementById("langMenu").classList.toggle("show-lang");
        }
        function changeLang(lang) {
            var date = new Date();
            date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000)); // 30 Days
            document.cookie = "googtrans=/auto/" + lang + "; expires=" + date.toUTCString() + "; path=/";
            document.cookie = "googtrans=/auto/" + lang + "; expires=" + date.toUTCString() + "; path=/Order/"; // Ensure subfolder access
            location.reload();
        }
        window.onclick = function(event) {
            if (!event.target.matches('.lang-btn')) {
                var dropdowns = document.getElementsByClassName("lang-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    if (dropdowns[i].classList.contains('show-lang')) {
                        dropdowns[i].classList.remove('show-lang');
                    }
                }
            }
        }

        // Particle Animation
        const particleType = "<?php echo $particleType; ?>";
        function createParticle() {
            const particle = document.createElement('div');
            particle.classList.add('particle', particleType);
            let size = Math.random() * 10 + 5; 
            if(particleType === 'sakura') size += 5; 
            particle.style.width = size + 'px'; particle.style.height = size + 'px';
            particle.style.left = Math.random() * window.innerWidth + 'px';
            let duration = Math.random() * 5 + 5; 
            particle.style.animationDuration = duration + 's, ' + (duration * 2) + 's';
            document.body.appendChild(particle);
            setTimeout(() => { particle.remove(); }, duration * 1000); 
        }
        setInterval(createParticle, (particleType === 'snow') ? 200 : 400);
    </script>
</body>
</html>