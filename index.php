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
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/751/751621.png">
    <title>TAR UMT Cafe - <?php echo $greetingTitle; ?></title>
    <link rel="stylesheet" href="css/index.css">
    
    <style>
        .particle { position: fixed; top: -10px; z-index: 9999; pointer-events: none; }
        .sakura { animation: fall linear infinite, rotate ease-in-out infinite; }
        .sakura::after { content: ''; position: absolute; width: 100%; height: 100%; background-color: #ffc0cb; border-radius: 10px 0 10px 0; box-shadow: 0 0 5px rgba(255, 192, 203, 0.7); }
        .snow { background: white; border-radius: 50%; box-shadow: 0 0 5px white; animation: fall linear infinite; }
        @keyframes fall { 0% { top: -10%; opacity: 0.9; } 100% { top: 110%; opacity: 0.2; } }
        @keyframes rotate { 0% { transform: translateX(0) rotate(0deg); } 50% { transform: translateX(50px) rotate(180deg); } 100% { transform: translateX(0) rotate(360deg); } }

        .intro-modal {
            display: none; 
            position: fixed; 
            z-index: 10000; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            background-color: rgba(0,0,0,0.85); 
            justify-content: center; 
            align-items: center;
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s;
        }

        .intro-content {
            background: linear-gradient(145deg, #163f3f, #0f2f2f);
            padding: 40px; 
            border-radius: 20px; 
            border: 2px solid #d4af37; 
            width: 90%; 
            max-width: 500px; 
            text-align: center; 
            position: relative;
            box-shadow: 0 0 30px rgba(212, 175, 55, 0.3);
            color: #fff;
        }

        .intro-title {
            color: #d4af37; 
            font-size: 32px; 
            margin-bottom: 20px; 
            font-weight: bold; 
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .intro-text {
            font-size: 16px; 
            line-height: 1.8; 
            color: #aebcb9; 
            margin-bottom: 30px;
        }

        .intro-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .intro-btn {
            padding: 12px 25px; 
            border-radius: 30px; 
            text-decoration: none; 
            font-weight: bold; 
            transition: 0.3s;
            flex: 1;
            font-size: 14px;
            text-transform: uppercase;
        }

        .btn-dine { background: #2e7d6f; color: #fff; border: 1px solid #2e7d6f; }
        .btn-dine:hover { background: #3fa58d; transform: translateY(-2px); }

        .btn-take { background: transparent; color: #d4af37; border: 1px solid #d4af37; }
        .btn-take:hover { background: #d4af37; color: #000; transform: translateY(-2px); }

        .close-intro {
            position: absolute; 
            top: 15px; 
            right: 20px; 
            color: #6c8c8c; 
            font-size: 28px; 
            cursor: pointer;
            transition: 0.3s;
        }
        .close-intro:hover { color: #fff; transform: rotate(90deg); }
    </style>
</head>

<body>

    <header>
        <div class="brand">
            <img src="image/logo.png" alt="TAR UMT Logo" class="logo">
            TAR UMT Cafe
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
            <a href="javascript:void(0)" class="scenery-card" 
               onclick="showIntro('Seafood', '🦀 Premium Seafood', 'Dive into the ocean\'s finest offerings. Our fresh seafood selection features giant King Crabs, succulent Tiger Prawns, and imported Oysters, all prepared to highlight their natural sweetness.', 1)">
                <img src="../image/seafood.png" alt="Seafood">
                <div class="scenery-label">🦀 Premium Seafood</div>
            </a>

            <a href="javascript:void(0)" class="scenery-card" 
               onclick="showIntro('Japanese', '🍣 Fresh Japanese', 'Experience the art of Japanese cuisine. From delicate Salmon Sashimi to rich Beef Ramen and crispy Tempura, every dish is crafted with precision and tradition in mind.', 2)">
                <img src="../image/japanese_food.png" alt="Japanese">
                <div class="scenery-label">🍣 Fresh Japanese</div>
            </a>

            <a href="javascript:void(0)" class="scenery-card" 
               onclick="showIntro('Beer', '🍺 Chilled Beers', 'Relax with our curated selection of premium Japanese beers. Whether you prefer the crisp taste of Asahi or the rich aroma of Suntory Premium Malts, we have the perfect brew for you.', 4)">
                <img src="../image/beer.png" alt="Beer">
                <div class="scenery-label">🍺 Chilled Beers</div>
            </a>
        </div>

        <div class="action-area">
            <h2 style="font-size: 18px; margin-bottom: 25px; color: #aebcb9; font-weight: normal;">Please select your dining preference</h2>
            <a href="Order/product.php?type=dinein" class="btn">🍽️ Dine In</a>
            <a href="Order/product.php?type=delivery" class="btn">🛵 Delivery</a>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> TAR UMT Cafe. All Rights Reserved.</p>
        <p class="fade-text">Beyond Education</p>
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

        function showIntro(category, title, description, categoryId) {
            document.getElementById('introModal').style.display = 'flex';
            document.getElementById('introTitle').innerText = title;
            document.getElementById('introText').innerText = description;
            
            // Set links to go directly to that category on the menu
            document.getElementById('linkDine').href = "Order/product.php?type=dinein&category_id=" + categoryId;
            // CHANGED: Link type to delivery
            document.getElementById('linkTake').href = "Order/product.php?type=delivery&category_id=" + categoryId;
        }

        function closeIntro() {
            document.getElementById('introModal').style.display = 'none';
        }

        // Close modal if clicking outside the box
        window.onclick = function(event) {
            var modal = document.getElementById('introModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <div id="introModal" class="intro-modal">
        <div class="intro-content">
            <span class="close-intro" onclick="closeIntro()">×</span>
            <div id="introTitle" class="intro-title"></div>
            <p id="introText" class="intro-text"></p>
            
            <p style="font-size: 14px; color: #fff; margin-bottom: 15px;">— How would you like to order? —</p>
            
            <div class="intro-buttons">
                <a id="linkDine" href="#" class="intro-btn btn-dine">🍽️ Dine In</a>
                <a id="linkTake" href="#" class="intro-btn btn-take">🛵 Delivery</a>
            </div>
        </div>
    </div>

</body>
</html>