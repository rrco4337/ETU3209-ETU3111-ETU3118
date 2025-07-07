<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin | Banque Horizon</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.14.2/dist/full.css" rel="stylesheet" type="text/css" />
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
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600"></div>
    
    <div class="w-full max-w-md mx-4">
        <div class="card shadow-2xl bg-white">
            <div class="card-body p-8">
                <!-- Logo Banque -->
                <div class="flex justify-center mb-8">
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 rounded-full bg-gradient-bank flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-800">Horizon Banque</span>
                    </div>
                </div>
                
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Connexion Administrateur</h2>
                <p class="text-center text-gray-600 mb-8">Accédez à l'interface d'administration</p>
                
                <form id="loginForm">
                    <div class="form-control mb-4">
                        <label class="label" for="mail">
                            <span class="label-text text-gray-700 font-medium">Email</span>
                        </label>
                        <input type="email" id="mail" name="mail" placeholder="Entrez votre email" 
                               class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div class="form-control mb-6">
                        <label class="label" for="motdepasse">
                            <span class="label-text text-gray-700 font-medium">Mot de passe</span>
                        </label>
                        <input type="password" id="motdepasse" name="motdepasse" placeholder="••••••••" 
                               class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-bank text-white w-full py-3 rounded-lg font-semibold shadow-md mb-4">
                        Se connecter
                    </button>
                    
                    <div id="message" class="text-center text-sm font-medium"></div>
                </form>
            </div>
            
            <div class="px-8 py-4 bg-gray-50 rounded-b-lg text-center border-t">
                <p class="text-sm text-gray-600">
                    Accès réservé au personnel autorisé
                </p>
            </div>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                &copy; 2023 Horizon Banque. Tous droits réservés.
            </p>
        </div>
    </div>

    <script>
    const apiUrl = 'http://localhost/tp-flightphp-crud/ws/Admin/login';

    async function handleLogin(e) {
        e.preventDefault();
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = 'Connexion en cours...';
        messageDiv.className = 'text-center text-sm font-medium text-blue-600';

        try {
            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    mail: document.getElementById('mail').value.trim(),
                    motdepasse: document.getElementById('motdepasse').value
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || `Erreur HTTP ${response.status}`);
            }

            if (data.success) {
                window.location.href = '/tp-flightphp-crud/TypePretConfig.php';
            } else {
                messageDiv.className = 'text-center text-sm font-medium text-red-600';
                messageDiv.textContent = data.message || 'Identifiants incorrects';
            }
        } catch (error) {
            console.error('Erreur:', error);
            messageDiv.className = 'text-center text-sm font-medium text-red-600';
            messageDiv.textContent = `Erreur: ${error.message}`;
        }
    }

    document.getElementById('loginForm').addEventListener('submit', handleLogin);
    </script>
</body>
</html>