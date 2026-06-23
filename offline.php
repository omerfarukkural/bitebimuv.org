<?php
/**
 * Offline Sayfası – PWA için
 * Bite Bi Muv Derneği Teması v4.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html( get_bloginfo( 'name' ) ); ?> – Çevrimdışı</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bbm-primary: #6366f1;
            --bbm-text: #1f2937;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        .offline-card {
            background: #fff;
            border-radius: 24px;
            padding: 48px 40px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,.25);
        }
        .offline-icon {
            font-size: 80px;
            line-height: 1;
            margin-bottom: 24px;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--bbm-text);
            margin-bottom: 12px;
        }
        p {
            color: #6b7280;
            line-height: 1.7;
            margin-bottom: 32px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bbm-primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px 28px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            text-decoration: none;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,.4); }
        .offline-tips {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #f3f4f6;
            font-size: 13px;
            color: #9ca3af;
        }
        .offline-tips ul { list-style: none; display: flex; flex-direction: column; gap: 6px; }
    </style>
</head>
<body>
    <div class="offline-card">
        <div class="offline-icon">📡</div>
        <h1>İnternet Bağlantısı Yok</h1>
        <p>
            Şu anda internet bağlantınız bulunmuyor.<br>
            Bağlantınızı kontrol edip tekrar deneyin.
        </p>
        <button class="btn" onclick="window.location.reload()">
            🔄 Tekrar Dene
        </button>
        <div class="offline-tips">
            <ul>
                <li>📶 Wi-Fi bağlantınızı kontrol edin</li>
                <li>📱 Mobil veri aktif mi?</li>
                <li>✈️ Uçak modu kapalı mı?</li>
            </ul>
        </div>
    </div>
    <script>
        window.addEventListener('online', () => {
            document.querySelector('.btn').textContent = '✅ Bağlantı Sağlandı – Yükleniyor…';
            setTimeout(() => window.location.reload(), 800);
        });
    </script>
</body>
</html>
