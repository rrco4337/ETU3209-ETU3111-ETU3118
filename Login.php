<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion Client | Horizon Banque</title>
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
    .wave {
      animation: wave 8s linear infinite;
    }
    @keyframes wave {
      0% { background-position-x: 0; }
      100% { background-position-x: 1000px; }
    }
  </style>
</head>
<body class="min-h-screen bg-gray-50">
  <!-- Vague décorative animée -->
  <div class="absolute bottom-0 left-0 w-full h-32 bg-repeat-x bg-auto wave" style="background-image: url('data:image/svg+xml;utf8,<svg viewBox=\'0 0 1200 120\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z\' fill=\'%23317AC1\' opacity=\'.25\'/><path d=\'M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z\' fill=\'%23317AC1\' opacity=\'.5\'/><path d=\'M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z\' fill=\'%23317AC1\'/></svg>');"></div>

  <div class="container mx-auto px-4 py-12 flex flex-col items-center justify-center min-h-screen relative z-10">
    <!-- Logo -->
    

    <!-- Carte de connexion -->
    <div class="card bg-white shadow-xl w-full max-w-md">
      
      <div class="card-body p-8">
        <div class="mb-8">
      <div class="w-20 h-20 rounded-full bg-gradient-bank flex items-center justify-center shadow-xl mx-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-center text-gray-800 mt-4">Horizon Banque</h1>
    </div>
        <h2 class="card-title text-2xl font-bold text-gray-800 mb-2 text-center">Connexion Client</h2>
        <p class="text-center text-gray-600 mb-6">Accédez à votre espace bancaire sécurisé</p>
        
        <div>
          <div class="form-control mb-4">
            <label class="label" for="nom">
              <span class="label-text text-gray-700 font-medium">Nom d'utilisateur</span>
            </label>
            <input 
              type="text" 
              id="nom" 
              placeholder="Entrez votre nom d'utilisateur" 
              class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
          </div>
          
          <div class="form-control mb-6">
            <label class="label" for="password">
              <span class="label-text text-gray-700 font-medium">Mot de passe</span>
            </label>
            <input 
              type="password" 
              id="password" 
              placeholder="••••••••" 
              class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
          </div>
          
          <button 
            onclick="login()" 
            class="btn btn-bank text-white w-full py-3 rounded-lg font-semibold shadow-md mb-4"
          >
            Se connecter
          </button>
          
          <p id="message" class="text-center"></p>
        </div>
      </div>
      
      <div class="px-8 py-4 bg-gray-50 rounded-b-lg text-center border-t">
        <p class="text-sm text-gray-600">
          Nouveau client ? <a href="#" class="text-blue-600 font-medium hover:underline">Ouvrir un compte</a>
        </p>
      </div>
    </div>
  </div>

  <script>
    const apiBase = "http://localhost/tp-flightphp-crud/ws";

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

      // Reset message style
      message.className = 'text-center';

      if (!nom || !password) {
        message.textContent = 'Veuillez remplir tous les champs.';
        message.classList.add('text-red-600');
        return;
      }

      // Show loading state
      message.textContent = 'Connexion en cours...';
      message.classList.add('text-blue-600');

      const data = `nom=${encodeURIComponent(nom)}&password=${encodeURIComponent(password)}`;

      ajax('POST', '/clients/login', data,
        (res) => {
          message.classList.remove('text-blue-600');
          message.classList.add('text-green-600');
          message.textContent = res.message || 'Connexion réussie !';

          // Stockage local si besoin
          if (res.data) {
            localStorage.setItem('user', JSON.stringify(res.data));
          }

          setTimeout(() => {
            window.location.href = 'Accueil.php';
          }, 1500);
        },
        (err) => {
          message.classList.remove('text-blue-600');
          message.classList.add('text-red-600');
          message.textContent = err.erreur || err.error || 'Erreur inconnue.';
        }
      );
    }
  </script>
</body>
</html>