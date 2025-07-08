<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Fonds Financiers | Banque Horizon</title>
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
    .solde-positive {
      color: #10B981;
      font-weight: 500;
    }
    .solde-negative {
      color: #EF4444;
      font-weight: 500;
    }
  </style>
</head>
<body class="min-h-screen bg-gray-50">

  <?php include('sidebar.php'); ?>

  <main class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-wallet mr-2 text-blue-600"></i>
        Gestion des Fonds Financiers
      </h1>
      <div class="stats shadow bg-white">
        <div class="stat">
          <div class="stat-title">Total des fonds</div>
          <div class="stat-value text-blue-600" id="total-fonds">0 Ar</div>
          <div class="stat-desc">Solde global</div>
        </div>
      </div>
    </div>

    <!-- Form Card -->
    <div class="card bg-white shadow-xl mb-8 animate-fade-in">
      <div class="card-body">
        <h2 class="card-title text-xl mb-4">
          <i class="fas fa-plus-circle mr-2 text-blue-600"></i>
          Ajouter un nouveau fond
        </h2>
        
        <form onsubmit="ajouterFondFinancier(); return false;">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-control">
              <label class="label">
                <span class="label-text font-medium">Date de création</span>
              </label>
              <input type="date" id="date_creation" required 
                     class="input input-bordered input-bank w-full">
            </div>
            
            <div class="form-control">
              <label class="label">
                <span class="label-text font-medium">Solde initial</span>
              </label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Ar</span>
                <input type="number" id="solde_initiale" step="0.01" required 
                       class="input input-bordered input-bank w-full pl-10">
              </div>
            </div>
          </div>
          
          <div class="form-control mt-6">
            <button type="submit" class="btn btn-bank text-white">
              <i class="fas fa-save mr-2"></i>
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Table Card -->
    <div class="card bg-white shadow-xl">
      <div class="card-body">
        <div class="flex justify-between items-center mb-4">
          <h2 class="card-title text-xl">
            <i class="fas fa-list-ul mr-2 text-blue-600"></i>
            Liste des fonds financiers
          </h2>
          <div class="form-control">
            <div class="input-group">
              <input type="text" placeholder="Rechercher..." class="input input-bordered" id="search-input">
              <button class="btn btn-square">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="table w-full table-bank">
            <thead>
              <tr>
                <th class="bg-gray-50">Année</th>
                <th class="bg-gray-50">Solde Initial</th>
                <th class="bg-gray-50">Solde en Cours</th>
                <th class="bg-gray-50">Date de Création</th>
              </tr>
            </thead>
            <tbody id="table-fond">
              <!-- Les données seront chargées ici -->
            </tbody>
          </table>
        </div>
        
        <div class="flex justify-between items-center mt-4">
          <div class="text-sm text-gray-600">
            Affichage de <span id="start-item">1</span> à <span id="end-item">10</span> sur <span id="total-items">0</span> entrées
          </div>
          <div class="btn-group">
            <button class="btn btn-sm">«</button>
            <button class="btn btn-sm btn-active">1</button>
            <button class="btn btn-sm">2</button>
            <button class="btn btn-sm">3</button>
            <button class="btn btn-sm">»</button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <?php include('footer.php'); ?>
  

  <script>
    const apiBase = "http://localhost/tp-flightphp-crud/ws";

    function ajax(method, url, data, callback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            callback(JSON.parse(xhr.responseText));
          } else {
            console.error("Erreur AJAX", xhr.status, xhr.responseText);
            showNotification('Erreur lors de la requête', 'error');
          }
        }
      };
      xhr.send(data);
    }

    function ajouterFondFinancier() {
      const date = document.getElementById("date_creation").value;
      const initiale = document.getElementById("solde_initiale").value;

      if (!date || !initiale) {
        showNotification('Veuillez remplir tous les champs', 'warning');
        return;
      }

      const data = `date_creation=${encodeURIComponent(date)}&solde_initiale=${encodeURIComponent(initiale)}`;

      ajax("POST", "/fond-financier", data, () => {
        showNotification('Fond financier ajouté avec succès', 'success');
        chargerFonds();
        document.getElementById("date_creation").value = '';
        document.getElementById("solde_initiale").value = '';
      });
    }

    function chargerFonds() {
      ajax("GET", "/fond-financier", null, (fonds) => {
        const tbody = document.getElementById("table-fond");
        tbody.innerHTML = "";
        
        let totalFonds = 1;
        
        fonds.forEach(f => {
          const row = document.createElement("tr");
          row.className = "hover:bg-gray-50 transition animate-fade-in";
          row.innerHTML = `
            <td class="font-semibold">${f.annee}</td>
            <td>${formatMoney(f.solde_initiale)}</td>
            <td class="${f.solde_en_cours >= 0 ? 'solde-positive' : 'solde-negative'}">${formatMoney(f.solde_en_cours || 0)}</td>
            <td>${formatDate(f.date_creation)}</td>
          `;
          tbody.appendChild(row);
          
          // Calcul du total
          totalFonds += parseFloat(f.solde_en_cours) || 0;
        });
        
        // Mettre à jour les statistiques
        document.getElementById('total-fonds').textContent = formatMoney(totalFonds);
        document.getElementById('total-items').textContent = fonds.length;
        
        // Si pas de données, afficher un message
        if (fonds.length === 0) {
          tbody.innerHTML = `
            <tr>
              <td colspan="5" class="text-center py-8 text-gray-500">
                <i class="fas fa-database fa-2x mb-2"></i>
                <p>Aucun fond financier enregistré</p>
              </td>
            </tr>
          `;
        }
      });
    }

    function formatMoney(amount) {
      return new Intl.NumberFormat('fr-FR', {
        style: 'decimal',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }).format(amount) + ' Ar';
    }

    function formatDate(dateString) {
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString('fr-FR', options);
    }

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

    // Initialisation
    document.addEventListener('DOMContentLoaded', () => {
      chargerFonds();
      
      // Ajouter un effet de chargement
      const tbody = document.querySelector("#table-fond");
      tbody.innerHTML = `
        <tr>
          <td colspan="5" class="text-center py-8">
            <div class="flex justify-center">
              <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
            </div>
            <p class="mt-2 text-gray-500">Chargement des données...</p>
          </td>
        </tr>
      `;
    });
  </script>
</body>
</html>