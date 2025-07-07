<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil | Horizon Banque</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.14.2/dist/full.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        .bg-gradient-bank {
            background: linear-gradient(135deg, #317AC1 0%, #1E3A8A 100%);
        }
        .btn-bank {
            background-color: #317AC1;
            transition: all 0.3s;
        }
        .btn-bank:hover {
            background-color: #2563A8;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .floating {
            animation: floating 6s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .wave {
            animation: wave 8s linear infinite;
        }
        @keyframes wave {
            0% { background-position-x: 0; }
            100% { background-position-x: 1000px; }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 overflow-x-hidden">
    <!-- Vague décorative animée -->
    <div class="absolute bottom-0 left-0 w-full h-32 bg-repeat-x bg-auto wave" style="background-image: url('data:image/svg+xml;utf8,<svg viewBox=\'0 0 1200 120\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z\' fill=\'%23317AC1\' opacity=\'.25\'/><path d=\'M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z\' fill=\'%23317AC1\' opacity=\'.5\'/><path d=\'M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z\' fill=\'%23317AC1\'/></svg>');"></div>

    <div class="container mx-auto px-4 py-12 flex flex-col items-center justify-center min-h-screen relative z-10">
        <!-- Logo animé -->
        <div class="floating mb-8">
            <div class="w-32 h-32 rounded-full bg-gradient-bank flex items-center justify-center shadow-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Titre principal -->
        <h1 class="text-4xl md:text-5xl font-bold text-center text-gray-800 mb-4 animate__animated animate__fadeIn">
            Bienvenue chez <span class="text-blue-600">Horizon Banque</span>
        </h1>
        <p class="text-xl text-gray-600 text-center mb-12 max-w-2xl animate__animated animate__fadeIn animate__delay-1s">
            Votre partenaire financier pour toutes les étapes de votre vie
        </p>

        <!-- Cartes de sélection -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl animate__animated animate__fadeInUp animate__delay-1s">
            <!-- Carte Client -->
            <div class="login-card card bg-white shadow-md transition-all duration-300 hover:shadow-xl border-t-4 border-blue-500">
                <div class="card-body p-8 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="card-title text-gray-800 mb-2">Espace Client</h2>
                    <p class="text-gray-600 mb-6">Accédez à votre compte personnel pour gérer vos finances au quotidien</p>
                    <a href="Login.php" class="btn btn-bank text-white w-full py-3 rounded-lg font-semibold shadow-md transform transition hover:-translate-y-1">
                        Se connecter
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Carte Administrateur -->
            <div class="login-card card bg-white shadow-md transition-all duration-300 hover:shadow-xl border-t-4 border-blue-500">
                <div class="card-body p-8 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 7a4 4 0 018 0v1m-8 0a4 4 0 018 0v1m-9 4H5a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6a2 2 0 00-2-2h-1m-1 0h-1" />
                        </svg>
                    </div>
                    <h2 class="card-title text-gray-800 mb-2">Espace Administrateur</h2>
                    <p class="text-gray-600 mb-6">Accès sécurisé au back-office de gestion des services bancaires</p>
                    <a href="login-admin.php" class="btn btn-outline btn-primary w-full py-3 rounded-lg font-semibold shadow-sm transform transition hover:-translate-y-1">
                        Connexion Admin
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Section supplémentaire -->
       
    </div>

    <!-- Effets de bulles décoratives -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-10 left-1/4 w-16 h-16 rounded-full bg-blue-100 opacity-20 animate-float" style="animation-delay: 0s; animation-duration: 15s;"></div>
        <div class="absolute top-1/3 left-3/4 w-24 h-24 rounded-full bg-blue-200 opacity-10 animate-float" style="animation-delay: 2s; animation-duration: 20s;"></div>
        <div class="absolute top-2/3 left-1/3 w-20 h-20 rounded-full bg-blue-100 opacity-15 animate-float" style="animation-delay: 5s; animation-duration: 18s;"></div>
        <div class="absolute bottom-20 right-1/4 w-12 h-12 rounded-full bg-blue-200 opacity-10 animate-float" style="animation-delay: 3s; animation-duration: 12s;"></div>
    </div>

    <script>
        // Animation des bulles
        document.addEventListener('DOMContentLoaded', () => {
            // Créer des bulles supplémentaires de manière aléatoire
            const bubblesContainer = document.querySelector('.fixed');
            for (let i = 0; i < 5; i++) {
                const bubble = document.createElement('div');
                const size = Math.random() * 30 + 10;
                const delay = Math.random() * 5;
                const duration = Math.random() * 15 + 10;
                const left = Math.random() * 100;
                const top = Math.random() * 100;
                const opacity = Math.random() * 0.1 + 0.05;
                
                bubble.className = 'absolute rounded-full bg-blue-100 animate-float';
                bubble.style.width = `${size}px`;
                bubble.style.height = `${size}px`;
                bubble.style.left = `${left}%`;
                bubble.style.top = `${top}%`;
                bubble.style.opacity = opacity;
                bubble.style.animationDelay = `${delay}s`;
                bubble.style.animationDuration = `${duration}s`;
                
                bubblesContainer.appendChild(bubble);
            }

            // Effet de parallaxe sur les vagues
            window.addEventListener('scroll', () => {
                const scrollPosition = window.pageYOffset;
                const wave = document.querySelector('.wave');
                wave.style.backgroundPositionX = `${-scrollPosition * 0.5}px`;
            });
        });

        // Animation au survol des cartes
        const cards = document.querySelectorAll('.login-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.classList.add('animate__animated', 'animate__pulse');
            });
            
            card.addEventListener('mouseleave', () => {
                card.classList.remove('animate__animated', 'animate__pulse');
            });
        });
    </script>

    <style>
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
        .animate-float {
            animation: float linear infinite;
        }
    </style>
</body>
</html>