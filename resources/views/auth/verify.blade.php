<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification email - OURATABLE</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #fef3c7 0%, #fff9f0 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .verify-container {
            max-width: 500px;
            width: 90%;
            margin: 20px;
        }
        .verify-card {
            background: white;
            border-radius: 32px;
            padding: 48px 32px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid #fde68a;
        }
        .verify-icon {
            width: 80px;
            height: 80px;
            background: #fef3c7;
            border-radius: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        .verify-icon i {
            width: 48px;
            height: 48px;
            color: #f97316;
        }
        h1 {
            font-size: 24px;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 12px;
        }
        .message {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .btn {
            background: #f97316;
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 60px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn:hover {
            background: #ea580c;
            transform: translateY(-2px);
        }
        .resend-link {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }
        .resend-link a {
            color: #f97316;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }
        .resend-link a:hover {
            text-decoration: underline;
        }
        .footer {
            margin-top: 20px;
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-card">
            <div class="verify-icon">
                <i data-lucide="mail-check"></i>
            </div>
            <h1>Vérifiez votre email</h1>
            <div class="message">
                <p>Un email de vérification a été envoyé à <strong>{{ Auth::user()->email }}</strong></p>
                <p>Veuillez cliquer sur le lien dans l'email pour activer votre compte.</p>
            </div>
            
            <form action="{{ route('verification.resend') }}" method="POST">
                @csrf
                <button type="submit" class="btn">
                    <i data-lucide="mail" style="width: 16px; height: 16px;"></i>
                    Renvoyer l'email
                </button>
            </form>
            
            <div class="resend-link">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-lucide="log-out" style="width: 14px; height: 14px; display: inline;"></i>
                    Se déconnecter
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
        <div class="footer">
            <p>© 2024 OURATABLE - Le partage culinaire</p>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>