<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Connexion Admin</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 40px auto; }
        label, input { display: block; width: 100%; margin-bottom: 10px; }
        input { padding: 8px; font-size: 1em; }
        button { padding: 10px; width: 100%; font-size: 1em; }
        #message { margin-top: 15px; color: red; }
    </style>
</head>
<body>

<h2>Connexion Admin</h2>

<form id="loginForm">
    <label for="mail">Email</label>
    <input type="email" id="mail" name="mail" required placeholder="Entrez votre email" />

    <label for="motdepasse">Mot de passe</label>
    <input type="password" id="motdepasse" name="motdepasse" required placeholder="Entrez votre mot de passe" />

    <button type="submit">Se connecter</button>
</form>

<div id="message"></div>

<script>
const apiUrl = 'http://localhost/ETU3209-ETU3111-ETU3118/ws/Admin/login';

async function handleLogin(e) {
    e.preventDefault();
    const messageDiv = document.getElementById('message');
    messageDiv.textContent = 'Connexion en cours...';
    messageDiv.style.color = 'blue';

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

        // Afficher plus d'informations de débogage
        console.log('Status:', response.status);
        console.log('Headers:', [...response.headers.entries()]);
        
        const data = await response.json();
        console.log('Response data:', data);

        if (!response.ok) {
            throw new Error(data.message || `Erreur HTTP ${response.status}`);
        }

        if (data.success) {
            window.location.href = '/dashboard';
        } else {
            messageDiv.style.color = 'red';
            messageDiv.textContent = data.message || 'Identifiants incorrects';
        }
    } catch (error) {
        console.error('Erreur complète:', error);
        messageDiv.style.color = 'red';
        messageDiv.textContent = `Erreur: ${error.message}`;
    }
}

document.getElementById('loginForm').addEventListener('submit', handleLogin);
</script>

</body>
</html>