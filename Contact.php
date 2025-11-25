<?php 
require 'DB.php';

// --- 保持和首页一样的季节逻辑 ---
$currentMonth = date('n'); 
$seasonIcon = "🌿";
// 简单的季节图标判断
if ($currentMonth == 12) { $seasonIcon = "🎄"; } 
elseif ($currentMonth == 1) { $seasonIcon = "✨"; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Nordic Taste</title>

    <style>
        /* --- 1. 全局与基础样式 (保持一致) --- */
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

        /* --- Header --- */
        header {
            background-color: #0b2222; 
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

        /* --- Contact 页面主要内容 --- */
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .contact-container {
            background: #163f3f;
            width: 100%;
            max-width: 900px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            display: flex;
            flex-wrap: wrap; /* 手机端自动换行 */
        }

        /* 左边：图片区域 */
        .contact-image {
            flex: 1;
            min-width: 300px;
            position: relative;
            overflow: hidden;
        }

        .contact-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
            filter: brightness(0.8);
        }

        .contact-image:hover img {
            transform: scale(1.05);
            filter: brightness(1);
        }

        /* 右边：信息区域 */
        .contact-info {
            flex: 1;
            min-width: 300px;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .contact-info h1 {
            color: #d0f0d0;
            margin-top: 0;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .contact-info p {
            color: #8faaa5;
            margin-bottom: 30px;
            font-size: 16px;
        }

        /* 联系方式列表 */
        .contact-links {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-btn {
            display: flex;
            align-items: center;
            text-decoration: none;
            background: rgba(0,0,0,0.2);
            padding: 15px 20px;
            border-radius: 10px;
            color: #fff;
            transition: 0.3s;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .contact-btn:hover {
            background: #2e7d6f;
            transform: translateX(5px);
            border-color: #2e7d6f;
        }

        .icon {
            font-size: 20px;
            margin-right: 15px;
            width: 30px;
            text-align: center;
        }

        .label {
            font-size: 14px;
            color: #aebcb9;
            display: block;
            margin-bottom: 2px;
        }

        .value {
            font-size: 16px;
            font-weight: bold;
            color: #fff;
        }

        /* --- Footer (极简版) --- */
        footer {
            background-color: #081a1a;
            padding: 40px 20px;
            border-top: 1px solid #1f4f4f;
            text-align: center;
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
            header { flex-direction: column; gap: 10px; }
            .nav-links a { margin: 0 10px; }
            .contact-info { padding: 30px; }
        }
    </style>
</head>

<body>

    <header>
        <div class="brand"><?php echo $seasonIcon; ?> My Restaurant</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="location.php">Location</a>
            <a href="contact.php" style="color: #fff; border-bottom: 1px solid #fff;">Contact Us</a>
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