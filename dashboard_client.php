<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Client | Banque Horizon</title>
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
    .stats-card {
      background: white;
      border-radius: 0.5rem;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }
    .stats-card:hover {
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
      transform: translateY(-2px);
    }
    .summary-card {
      border-left: 4px solid #317AC1;
    }
    .loan-type-card {
      border-top: 3px solid #317AC1;
    }
    .progress-bar {
      height: 8px;
      border-radius: 4px;
    }
    .progress-fill {
      height: 100%;
      border-radius: 4px;
      background-color: #317AC1;
      transition: width 0.5s ease;
    }
  </style>
</head>
<body class="min-h-screen bg-gray-50">
  <!-- Header -->


  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">
          <i class="fas fa-tachometer-alt mr-2 text-blue-600"></i>
          Tableau de bord client
        </h1>
        <p class="text-gray-600" id="welcome">Bienvenue !</p>
      </div>
      <div class="flex space-x-2">
        
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <!-- Solde Card -->
      <div class="stats-card summary-card p-6 animate-fade-in">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="text-lg font-semibold text-gray-600">Solde disponible</h3>
            <p class="text-2xl font-bold text-gray-800 mt-2" id="solde">... Ar</p>
          </div>
          <div class="bg-blue-100 p-3 rounded-full">
            <i class="fas fa-wallet text-blue-600 text-xl"></i>
          </div>
        </div>
        <div class="mt-4">
          <button id="btn-ajout-solde" class="btn btn-sm btn-bank text-white">
            <i class="fas fa-plus mr-2"></i> Ajouter du solde
          </button>
        </div>
      </div>

      <!-- Prêt Restant Card -->
      <div class="stats-card summary-card p-6 animate-fade-in">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="text-lg font-semibold text-gray-600">Montant restant</h3>
            <p class="text-2xl font-bold text-gray-800 mt-2" id="reste">... Ar</p>
          </div>
          <div class="bg-blue-100 p-3 rounded-full">
            <i class="fas fa-hand-holding-usd text-blue-600 text-xl"></i>
          </div>
        </div>
        <div class="mt-4">
          <div class="text-sm text-gray-500 mb-1">Progression globale</div>
          <div class="progress-bar bg-gray-200 w-full">
            <div id="progress-global" class="progress-fill" style="width: 0%"></div>
          </div>
        </div>
      </div>

      <!-- Total Prêt Card -->
      <div class="stats-card summary-card p-6 animate-fade-in">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="text-lg font-semibold text-gray-600">Montant total prêt</h3>
            <p class="text-2xl font-bold text-gray-800 mt-2" id="total">... Ar</p>
          </div>
          <div class="bg-blue-100 p-3 rounded-full">
            <i class="fas fa-chart-pie text-blue-600 text-xl"></i>
          </div>
        </div>
        <div class="mt-4">
          <div class="text-sm text-gray-500">Total des prêts contractés</div>
        </div>
      </div>
    </div>

    <!-- Loans Section -->
    <div class="card bg-white shadow-xl mb-8 animate-fade-in">
      <div class="card-body">
        <h2 class="card-title text-xl mb-6">
          <i class="fas fa-list-alt mr-2 text-blue-600"></i>
          Mes prêts en cours
        </h2>
        
        <div id="prets-container" class="space-y-8">
          <!-- Les prêts seront chargés ici -->
          <div class="text-center py-12">
            <div class="flex justify-center">
              <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
            </div>
            <p class="mt-4 text-gray-500">Chargement de vos prêts...</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Balance Modal -->
    <div class="modal" id="modal-ajout-solde">
      <div class="modal-box">
        <h3 class="font-bold text-lg">
          <i class="fas fa-plus-circle mr-2 text-blue-600"></i>
          Ajouter du solde
        </h3>
        <form id="form-solde" class="mt-4">
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">Montant à ajouter (Ar)</span>
            </label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500"></span>
              <input type="number" id="montant-solde" placeholder="Ar" step="1000" 
                     class="input input-bordered input-bank w-full pl-10">
            </div>
          </div>
          
          <div class="modal-action">
            <button type="button" id="btn-annuler" class="btn btn-outline">Annuler</button>
            <button type="submit" class="btn btn-bank text-white">
              <i class="fas fa-check-circle mr-2"></i> Confirmer
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <?php include('footer.php'); ?>

  <script>
    const apiBase = "http://localhost/ETU3209-ETU3111-ETU3118/ws";

    const user = JSON.parse(localStorage.getItem("user"));
    if (!user || !user.idClient) {
      alert("Non connecté !");
      window.location.href = "Login.php";
    }

    // Mettre à jour le message de bienvenue
    document.getElementById("welcome").textContent = `Bienvenue, ${user.nom} !`;

    // Initialiser les éléments modaux
    const btnAjoutSolde = document.getElementById("btn-ajout-solde");
    const modalAjoutSolde = document.getElementById("modal-ajout-solde");
    const btnAnnuler = document.getElementById("btn-annuler");

    btnAjoutSolde.onclick = function() {
      modalAjoutSolde.classList.add('modal-open');
      document.getElementById("montant-solde").focus();
    };

    btnAnnuler.onclick = function() {
      modalAjoutSolde.classList.remove('modal-open');
      document.getElementById("form-solde").reset();
    };

    // Gérer le formulaire d'ajout de solde
    document.getElementById("form-solde").onsubmit = function(event) {
      event.preventDefault();

      const montant = parseFloat(document.getElementById("montant-solde").value);
      if (isNaN(montant) || montant <= 0) {
        showNotification('Veuillez entrer un montant valide', 'warning');
        return;
      }

      // Afficher un indicateur de chargement
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
      submitBtn.disabled = true;

      fetch(`${apiBase}/dashboard/update-solde`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ idClient: user.idClient, montant })
      })
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          showNotification('Erreur : ' + data.message, 'error');
        } else {
          showNotification('Solde ajouté avec succès !', 'success');
          modalAjoutSolde.classList.remove('modal-open');
          document.getElementById("form-solde").reset();
          chargerDashboard();
        }
      })
      .catch(err => {
        console.error(err);
        showNotification('Une erreur est survenue', 'error');
      })
      .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      });
    };

    // Charger les données du dashboard
    function chargerDashboard() {
      // Afficher un indicateur de chargement
      const container = document.getElementById("prets-container");
      container.innerHTML = `
        <div class="text-center py-12">
          <div class="flex justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
          </div>
          <p class="mt-4 text-gray-500">Chargement de vos données...</p>
        </div>
      `;

      fetch(`${apiBase}/dashboard/client/${user.idClient}`)
        .then(res => res.json())
        .then(data => {
          // Mettre à jour les cartes de résumé
          const soldeDisponible = parseFloat(data.resume.solde_disponible || 0);
          document.getElementById("solde").innerText = formatCurrency(soldeDisponible);

          let montantRestant = 0, montantTotalPret = 0;
          if (Array.isArray(data.pretsParType)) {
            data.pretsParType.forEach(p => {
              montantRestant += parseFloat(p.montant_restant || 0);
              montantTotalPret += parseFloat(p.montant_restant || 0) + parseFloat(p.montant_paye || 0);
            });
          } else if (typeof data.pretsParType === 'object') {
            Object.values(data.pretsParType).forEach(arr => {
              arr.forEach(p => {
                montantRestant += parseFloat(p.montant_restant || 0);
                montantTotalPret += parseFloat(p.montant_restant || 0) + parseFloat(p.montant_paye || 0);
              });
            });
          }

          document.getElementById("reste").innerText = formatCurrency(montantRestant);
          document.getElementById("total").innerText = formatCurrency(montantTotalPret);

          // Mettre à jour la barre de progression
          const progressPercent = montantTotalPret > 0 ? 
            ((montantTotalPret - montantRestant) / montantTotalPret * 100) : 0;
          document.getElementById("progress-global").style.width = `${progressPercent}%`;

          // Afficher les prêts
          afficherPrets(data.pretsParType);
        })
        .catch(err => {
          console.error(err);
          showNotification('Erreur lors du chargement des données', 'error');
        });
    }

    // Afficher les prêts par type
    function afficherPrets(prets) {
      const container = document.getElementById("prets-container");
      
      if (!prets || (typeof prets === 'object' && Object.keys(prets).length === 0)) {
        container.innerHTML = `
          <div class="text-center py-12">
            <i class="fas fa-info-circle text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-500">Aucun prêt en cours</p>
          </div>
        `;
        return;
      }

      container.innerHTML = "";
      const types = {};

      // Organiser les prêts par type
      if (Array.isArray(prets)) {
        prets.forEach(pret => {
          if (!types[pret.type_pret]) types[pret.type_pret] = [];
          types[pret.type_pret].push(pret);
        });
      } else if (typeof prets === 'object') {
        Object.entries(prets).forEach(([type, pretsArray]) => {
          types[type] = pretsArray;
        });
      }

      // Créer une carte pour chaque type de prêt
      for (const type in types) {
        const card = document.createElement("div");
        card.className = "loan-type-card bg-white rounded-lg shadow p-6 animate-fade-in";
        
        // Calculer le total pour ce type de prêt
        const totalType = types[type].reduce((acc, p) => {
          return acc + parseFloat(p.montant_restant || 0) + parseFloat(p.montant_paye || 0);
        }, 0);
        
        const restantType = types[type].reduce((acc, p) => acc + parseFloat(p.montant_restant || 0), 0);
        const progressPercent = totalType > 0 ? ((totalType - restantType) / totalType * 100) : 0;

        card.innerHTML = `
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">
              <i class="fas fa-home mr-2 text-blue-600"></i>
              ${type}
            </h3>
            <div class="text-right">
              <div class="text-sm text-gray-500">Total: ${formatCurrency(totalType)}</div>
              <div class="text-sm font-medium">Restant: ${formatCurrency(restantType)}</div>
            </div>
          </div>
          
          <div class="mb-4">
            <div class="flex justify-between text-sm text-gray-500 mb-1">
              <span>Progression</span>
              <span>${progressPercent.toFixed(1)}%</span>
            </div>
            <div class="progress-bar bg-gray-200 w-full">
              <div class="progress-fill" style="width: ${progressPercent}%"></div>
            </div>
          </div>
          
          <div class="overflow-x-auto">
            <table class="table w-full table-bank">
              <thead>
                <tr>
                  <th class="bg-gray-50">Date début</th>
                  <th class="bg-gray-50">Payé</th>
                  <th class="bg-gray-50">Restant</th>
                  <th class="bg-gray-50">Statut</th>
                  <th class="bg-gray-50">Actions</th>
                </tr>
              </thead>
              <tbody>
                ${types[type].map(p => `
                  <tr class="hover:bg-gray-50 transition">
                    <td>${formatDate(p.date_debut_pret)}</td>
                    <td class="text-right">${formatCurrency(p.montant_paye)}</td>
                    <td class="text-right">${formatCurrency(p.montant_restant)}</td>
                    <td>
                      <span class="badge ${parseFloat(p.montant_restant) === 0 ? 'badge-success' : 'badge-warning'}">
                        ${parseFloat(p.montant_restant) === 0 ? 'Terminé' : 'En cours'}
                      </span>
                    </td>
                    <td class="space-x-1">
  <button onclick="ouvrirForm(${p.idPret}, '${type}')" 
          class="btn btn-xs btn-bank text-white" 
          ${parseFloat(p.montant_restant) === 0 ? 'disabled' : ''}>
    <i class="fas fa-money-bill-wave mr-1"></i> Payer
  </button>
  <button onclick="exporterSuiviPDF(${p.idPret})" class="btn btn-xs btn-outline">
    <i class="fas fa-file-pdf mr-1 text-red-600"></i> PDF
  </button>
</td>

                  </tr>
                `).join("")}
              </tbody>
            </table>
          </div>
        `;
        container.appendChild(card);
      }
    }
    // Fonction pour exporter le suivi en PDF
    function exporterSuiviPDF(idPret) {
        if (!user || !user.idClient) return;

  // Rediriger vers un script PHP qui génère le PDF
  const url = `${apiBase}/suivi/export-pdf.php?idPret=${idPret}&idClient=${user.idClient}`;
  window.open(url, "_blank");
}
    // Fonctions utilitaires
    function formatCurrency(amount) {
      return parseFloat(amount || 0).toLocaleString('fr-FR') + ' Ar';
    }

    function formatDate(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      return date.toLocaleDateString('fr-FR');
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

    function ouvrirForm(idPret, typeLibelle) {
      window.location.href = `Payer.php?idPret=${idPret}&type=${encodeURIComponent(typeLibelle)}`;
    }

    // Charger le dashboard au démarrage
    document.addEventListener('DOMContentLoaded', chargerDashboard);
  </script>
</body>
</html>