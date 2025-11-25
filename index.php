<?php 
require 'DB.php';

// --- 宝贝，这里是自动判断季节的逻辑 ---
$currentMonth = date('n'); // 获取当前月份 (1-12)
$greetingTitle = "Welcome to Our Space";
$subTitle = "Experience the taste of nature";
$seasonIcon = "🌿";

// 如果是12月，显示圣诞主题
if ($currentMonth == 12) {
    $greetingTitle = "Merry Christmas";
    $subTitle = "Celebrate the joy of the season with us";
    $seasonIcon = "🎄";
} 
// 如果是1月，显示跨年主题
elseif ($currentMonth == 1) {
    $greetingTitle = "Happy New Year";
    $subTitle = "New beginnings, fresh tastes";
    $seasonIcon = "✨";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Order System - <?php echo $greetingTitle; ?></title>

    <style>
        /* --- 全局设置 --- */
        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            background-color: #0f2f2f; /* 深绿色背景 */
            margin: 0;
            padding: 0;
            color: #e8f5e9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* --- 顶部导航栏 (Header) --- */
        header {
            background-color: #0b2222; /* 比背景更深一点 */
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
            color: #d0f0d0;
            letter-spacing: 1px;
        }

        .nav-links a {
            color: #aebcb9;
            text-decoration: none;
            margin-left: 25px;
            font-size: 14px;
            transition: color 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-links a:hover {
            color: #ffffff;
            border-bottom: 1px solid #fff;
        }

        /* --- 主要内容区域 --- */
        .main-content {
            flex: 1; /* 让这部分撑开高度 */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            text-align: center;
        }

        /* 节日标题 */
        .season-greeting {
            margin-bottom: 30px;
        }
        
        .season-greeting h1 {
            font-size: 48px;
            margin: 0;
            color: #d0f0d0;
            text-shadow: 0 0 10px rgba(208, 240, 208, 0.2);
            font-weight: 300;
        }
        
        .season-greeting p {
            color: #8faaa5;
            font-size: 16px;
            margin-top: 10px;
            font-style: italic;
        }

        /* --- 中间的风景照片展示区 --- */
        .scenery-gallery {
            display: flex;
            gap: 15px;
            margin-bottom: 50px;
            justify-content: center;
            flex-wrap: wrap;
            max-width: 900px;
        }

        .scenery-card {
            width: 200px;
            height: 140px;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
            opacity: 0.8; /* 默认稍微暗一点 */
            transition: all 0.4s ease;
            border: 1px solid #1f4f4f;
        }

        .scenery-card:hover {
            opacity: 1;
            transform: scale(1.05);
            border-color: #d0f0d0;
        }

        .scenery-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.7) sepia(0.2); /* 滤镜：让照片看起来暗绿复古 */
        }

        /* --- 按钮区域 --- */
        .action-area {
            background: #163f3f;
            padding: 40px 60px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.05);
        }

        .btn {
            display: inline-block;
            padding: 18px 50px;
            margin: 15px;
            border-radius: 50px; /* 更加圆润 */
            background: linear-gradient(145deg, #2e7d6f, #25665a);
            color: #ffffff;
            text-decoration: none;
            font-size: 20px;
            font-weight: 600;
            transition: 0.3s ease-in-out;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn:hover {
            background: linear-gradient(145deg, #3fa58d, #338773);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.4);
            color: #fff;
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
            .scenery-gallery {
                display: none; /* 手机上如果太挤可以隐藏照片，或者改小 */
            }
            header {
                flex-direction: column;
                gap: 10px;
            }
            .nav-links a {
                margin: 0 10px;
            }
        }
    </style>
</head>

<body>

    <header>
        <div class="brand"><?php echo $seasonIcon; ?> My Restaurant</div>
        <div class="nav-links">
            <a href="location.php">Location</a>
            <a href="Contact.php">Contact Us</a>
            <a href="#">About</a>
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
</body>
</html>