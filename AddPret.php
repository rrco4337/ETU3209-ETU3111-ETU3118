<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nouvelle Demande de Prêt | Banque Horizon</title>
  <script src="https://unpkg.com/htmlincludejs"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@1.14.2/dist/full.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
    .glass-card {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .input-bank {
      transition: all 0.3s;
      border: 1px solid #e2e8f0;
    }
    .input-bank:focus {
      border-color: #317AC1;
      box-shadow: 0 0 0 3px rgba(49, 122, 193, 0.2);
    }
    .table-bank th {
      background-color: #f8fafc;
    }
    .table-bank tr:hover {
      background-color: #f8fafc;
    }
    .animate-fade-in {
      animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="min-h-screen bg-gray-50">
  <!-- Header -->
  <?php include('sidebar.php'); ?>

  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">
    <div class="card glass-card shadow-xl mb-8 animate-fade-in">
      <div class="card-body">
        <h2 class="card-title text-xl mb-4">
          <i class="fas fa-hand-holding-usd mr-2 text-blue-600"></i>
          Nouvelle Demande de Prêt
        </h2>
        
        <form onsubmit="ajouterPret(); return false;" class="space-y-4">
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">Client</span>
            </label>
            <select id="idClient" class="select select-bordered input-bank w-full"></select>
          </div>
          
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">Type de prêt</span>
            </label>
            <select id="idTypePret" class="select select-bordered input-bank w-full"></select>
          </div>
          
          <div class="form-control pt-4">
            <button type="submit" class="btn btn-bank text-white">
              <i class="fas fa-paper-plane mr-2"></i>
              Soumettre
            </button>
          </div>
        </form>
        
        <div id="message" class="mt-4 p-4 rounded-lg hidden"></div>
      </div>
    </div>
  </main>

  <?php include('footer.php'); ?>

  <script>
    function ajax(method, url, data, callback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, "http://localhost/ETU3209-ETU3111-ETU3118/ws" + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            callback(JSON.parse(xhr.responseText));
          } else {
            const messageDiv = document.getElementById("message");
            messageDiv.innerText = "Vous ne pouvez pas souscrire au prêt choisi";
            messageDiv.className = "mt-4 p-4 rounded-lg bg-red-100 text-red-800";
            messageDiv.classList.remove("hidden");
          }
        }
      };
      xhr.send(data);
    }

    function chargerClients() {
      ajax("GET", "/clients", null, data => {
        const select = document.getElementById("idClient");
        data.forEach(c => {
          select.innerHTML += `<option value="${c.idClient}">${c.nom}</option>`;
        });
      });
    }

    function chargerTypesPret() {
      ajax("GET", "/type-pret", null, data => {
        const select = document.getElementById("idTypePret");
        data.forEach(p => {
          select.innerHTML += `<option value="${p.idType}">${p.libelle} (${p.montant} Ar / ${p.duree_mois_max} mois)</option>`;
        });
      });
    }

    function ajouterPret() {
      const idClient = document.getElementById("idClient").value;
      const idTypePret = document.getElementById("idTypePret").value;
      const messageDiv = document.getElementById("message");

      const data = `idClient=${idClient}&idTypePret=${idTypePret}`;

      ajax("POST", "/pret", data, (res) => {
        messageDiv.innerText = res.message;
        messageDiv.className = "mt-4 p-4 rounded-lg bg-green-100 text-green-800";
        messageDiv.classList.remove("hidden");
      });
    }

    // Initialisation
    document.addEventListener('DOMContentLoaded', () => {
      chargerClients();
      chargerTypesPret();
    });

    function showNotification(message, type) {
      const types = {
        success: { icon: 'fa-check-circle', color: 'bg-green-500' },
        error: { icon: 'fa-times-circle', color: 'bg-red-500' },
        warning: { icon: 'fa-exclamation-circle', color: 'bg-yellow-500' }
      };
      
      const notif = document.createElement('div');
      notif.className = `fixed top-4 right-4 ${types[type].color} text-white px-4 py-2 rounded shadow-lg flex items-center animate-fade-in`;
      notif.innerHTML = `
        <i class="fas ${types[type].icon} mr-2"></i>
        <span>${message}</span>
      `;
      
      document.body.appendChild(notif);
      
      setTimeout(() => {
        notif.classList.add('opacity-0', 'transition-opacity', 'duration-500');
        setTimeout(() => notif.remove(), 500);
      }, 3000);
    }
  </script>
</body>
</html>