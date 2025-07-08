<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client | Banque Horizon</title>
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
                
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Connexion Client</h2>
                <p class="text-center text-gray-600 mb-8">Accédez à votre espace personnel</p>
                
                <div>
                    <div class="form-control mb-4">
                        <label class="label" for="nom">
                            <span class="label-text text-gray-700 font-medium">Nom d'utilisateur</span>
                        </label>
                        <input type="text" id="nom" placeholder="Entrez votre nom d'utilisateur" 
                               class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div class="form-control mb-6">
                        <label class="label" for="password">
                            <span class="label-text text-gray-700 font-medium">Mot de passe</span>
                        </label>
                        <input type="password" id="password" placeholder="••••••••" 
                               class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <button onclick="login()" class="btn btn-primary btn-bank text-white w-full py-3 rounded-lg font-semibold shadow-md mb-4">
                        Se connecter
                    </button>
                    
                    <div id="message" class="text-center text-sm font-medium"></div>
                </div>
            </div>
            
            <div class="px-8 py-4 bg-gray-50 rounded-b-lg text-center border-t">
                <p class="text-sm text-gray-600">
                    Vous n'avez pas de compte ? <a href="#" class="text-blue-600 hover:underline">Contactez votre conseiller</a>
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
        const apiBase = "http://localhost/ETU3209-ETU3111-ETU3118/ws";

        function ajax(method, url, data, callback, errorCallback) {
            const xhr = new XMLHttpRequest();
            xhr.open(method, apiBase + url, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (xhr.status >= 200 && xhr.status < 300) {
                            callback(response);
                        } else {
                            errorCallback(response);
                        }
                    } catch (e) {
                        errorCallback({ erreur: "Erreur de communication avec le serveur." });
                    }
                }
            };
            xhr.send(data);
        }

        function login() {
            const nom = document.getElementById('nom').value.trim();
            const password = document.getElementById('password').value.trim();
            const message = document.getElementById('message');

            if (!nom || !password) {
                message.textContent = 'Veuillez remplir tous les champs.';
                message.style.color = 'red';
                return;
            }

            const data = `nom=${encodeURIComponent(nom)}&password=${encodeURIComponent(password)}`;

            message.textContent = 'Connexion en cours...';
            message.style.color = 'blue';

            ajax('POST', '/clients/login', data,
                (res) => {
                    message.style.color = 'green';
                    message.textContent = res.message || 'Connexion réussie !';

                    // Stockage local si besoin
                    if (res.data) {
                        localStorage.setItem('user', JSON.stringify(res.data));
                    }

                    setTimeout(() => {
                        window.location.href = '/ETU3209-ETU3111-ETU3118/dashboard_client.php';
                    }, 1500);
                },
                (err) => {
                    message.style.color = 'red';
                    message.textContent = err.erreur || err.error || 'Erreur inconnue.';
                }
            );
        }
    </script>
</body>
</html>