<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur OURATABLE</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #fde68a;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            background: #f97316;
            width: 70px;
            height: 70px;
            border-radius: 60px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .logo svg {
            width: 40px;
            height: 40px;
            color: white;
        }
        h1 {
            color: #1f2937;
            font-size: 24px;
            font-weight: 800;
            margin: 0 0 10px;
        }
        .subtitle {
            color: #f97316;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .content {
            color: #4b5563;
            line-height: 1.6;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            background: #f97316;
            color: white;
            padding: 14px 32px;
            border-radius: 60px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(249,115,22,0.3);
        }
        .btn:hover {
            background: #ea580c;
            transform: translateY(-2px);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #9ca3af;
        }
        .warning {
            background: #fef3c7;
            padding: 12px;
            border-radius: 12px;
            font-size: 13px;
            margin: 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .warning svg {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            color: #d97706;
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e5e7eb, transparent);
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="logo">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 13.87A4 4 0 0 1 7.5 6.5 4 4 0 0 1 11 4c.67 0 1.3.16 1.87.45 1.64 1.06 1.89 3.22.74 4.68A4.5 4.5 0 0 1 17 14v1"/>
                        <path d="M9 22h6"/>
                        <path d="M12 22v-4"/>
                        <path d="M3 15h18"/>
                        <path d="M6 15v3"/>
                        <path d="M18 15v3"/>
                    </svg>
                </div>
                <h1>Bienvenue sur <span style="color:#f97316">OURATABLE</span></h1>
                <p class="subtitle">La table des gourmets</p>
            </div>
            
            <div class="content">
                <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
                <p>Merci d'avoir rejoint <strong>OURATABLE</strong>, la communauté des passionnés de cuisine !</p>
                <p>Pour profiter pleinement de toutes les fonctionnalités et partager vos créations culinaires, veuillez vérifier votre adresse email.</p>
                
                <div class="warning">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span><strong>Important :</strong> Votre compte ne sera actif qu'après vérification de votre email.</span>
                </div>
                
                <div style="text-align: center; margin: 35px 0;">
                    <a href="{{ $verificationUrl }}" class="btn">Vérifier mon email</a>
                </div>
                
                <p style="font-size: 13px;">Si vous n'avez pas créé de compte sur OURATABLE, ignorez simplement cet email.</p>
                <p style="font-size: 13px;">Ce lien de vérification expirera dans 60 minutes.</p>
                
                <div class="divider"></div>
                
                <p style="font-size: 13px; text-align: center;">Des questions ? <a href="#" style="color: #f97316;">Contactez-nous</a></p>
            </div>
            
            <div class="footer">
                <p>© 2024 OURATABLE - Le partage culinaire</p>
                <p>Cette table est faite pour vous, rejoignez la communauté !</p>
            </div>
        </div>
    </div>
</body>
</html>