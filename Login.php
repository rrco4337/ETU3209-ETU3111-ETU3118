<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion Client</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 50px;
      max-width: 400px;
      margin: auto;
    }
    input {
      display: block;
      margin-bottom: 10px;
      padding: 8px;
      width: 100%;
    }
    button {
      padding: 10px;
      width: 100%;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
    #message {
      margin-top: 10px;
      color: red;
    }
  </style>
</head>
<body>
  <h2>Connexion Client</h2>
  <div>
    <input type="text" id="nom" placeholder="Nom d'utilisateur">
    <input type="password" id="password" placeholder="Mot de passe">
    <button onclick="login()">Se connecter</button>
    <p id="message"></p>
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
        return;
      }

      const data = `nom=${encodeURIComponent(nom)}&password=${encodeURIComponent(password)}`;

      ajax('POST', '/clients/login', data,
        (res) => {
          message.style.color = 'green';
          message.textContent = res.message || 'Connexion réussie !';

          // Stockage local si besoin
          if (res.data) {
            localStorage.setItem('user', JSON.stringify(res.data));
          }

          setTimeout(() => {
          window.location.href = 'Accueil.html';
 // change cette URL selon ton projet
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
