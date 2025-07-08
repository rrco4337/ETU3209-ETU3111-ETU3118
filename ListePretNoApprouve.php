<?php
session_start();

$value = false; // Par défaut

if (isset($_SESSION['idDepartement'])) {
    if ($_SESSION['idDepartement'] == 1) {
        $value = true; // Département Finance
    }
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prêts en attente | Banque Horizon</title>
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
    .pending-row {
      background-color: #fffaf0;
    }
    .status-badge {
      padding: 0.25rem 0.5rem;
      border-radius: 0.25rem;
      font-size: 0.75rem;
      font-weight: 600;
    }
    .status-pending {
      background-color: #fef3c7;
      color: #92400e;
    }
    .status-approved {
      background-color: #d1fae5;
      color: #065f46;
    }
  </style>
</head>
<body class="min-h-screen bg-gray-50">
  <!-- Header -->
  <?php include('sidebar.php'); ?>

  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-clock mr-2 text-blue-600"></i>
        Prêts en attente d'approbation
      </h1>
      <div class="flex space-x-2">
        <button class="btn btn-sm btn-outline" onclick="chargerPrets()">
          <i class="fas fa-sync-alt mr-2"></i> Actualiser
        </button>
      </div>
    </div>

    <div class="card bg-white shadow-xl animate-fade-in">
      <div class="card-body">
        <div class="overflow-x-auto">
          <table class="table w-full table-bank" id="tablePrets">
            <thead>
              <tr>
                <th class="bg-gray-50">Client</th>
                <th class="bg-gray-50">Type de prêt</th>
                <th class="bg-gray-50 text-right">Montant (Ar)</th>
                <th class="bg-gray-50">Date début</th>
                <th class="bg-gray-50">Statut</th>
                <th class="bg-gray-50">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6" class="text-center py-8">
                  <div class="flex justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
                  </div>
                  <p class="mt-2 text-gray-500">Chargement des prêts...</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmation-modal">
      <div class="modal-box">
        <h3 class="font-bold text-lg">
          <i class="fas fa-check-circle mr-2 text-blue-600"></i>
          Confirmer l'approbation
        </h3>
        <p class="py-4">Êtes-vous sûr de vouloir approuver ce prêt ? Cette action est irréversible.</p>
        <div class="modal-action">
          <button id="btn-cancel" class="btn btn-outline">Annuler</button>
          <button id="btn-confirm" class="btn btn-bank text-white">
            <i class="fas fa-check mr-2"></i> Confirmer
          </button>
        </div>
      </div>
    </div>
  </main>

  <?php include('footer.php'); ?>

  <script>
    const apiBase = "http://localhost/ETU3209-ETU3111-ETU3118/ws";
    const roleAdminFinance = <?= json_encode($value) ?>;
    let currentPretId = null;

    function ajax(method, url, data, callback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            try {
              callback(JSON.parse(xhr.responseText));
            } catch (e) {
              showNotification('Erreur de parsing JSON : ' + e.message, 'error');
            }
          } else {
            showNotification('Erreur AJAX ' + xhr.status + ': ' + xhr.responseText, 'error');
          }
        }
      };
      xhr.send(data);
    }

    function chargerPrets() {
      // Afficher l'indicateur de chargement
      const tbody = document.querySelector("#tablePrets tbody");
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="text-center py-8">
            <div class="flex justify-center">
              <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
            </div>
            <p class="mt-2 text-gray-500">Chargement des prêts...</p>
          </td>
        </tr>
      `;

      ajax("GET", "/pret/non-approuves", null, (data) => {
        tbody.innerHTML = "";

        if (data.length === 0) {
          tbody.innerHTML = `
            <tr>
              <td colspan="6" class="text-center py-8 text-gray-500">
                <i class="fas fa-check-circle text-3xl text-green-500 mb-4"></i>
                <p>Aucun prêt en attente d'approbation</p>
              </td>
            </tr>
          `;
          return;
        }

        data.forEach(pre => {
          const tr = document.createElement("tr");
          tr.className = "animate-fade-in pending-row";
          
          tr.innerHTML = `
            <td class="font-medium">${pre.client_nom}</td>
            <td>
              <div class="flex items-center">
                <i class="fas ${getPretIcon(pre.type_pret_libelle)} mr-2 text-blue-500"></i>
                ${pre.type_pret_libelle}
              </div>
            </td>
            <td class="text-right">${formatCurrency(pre.montant_restant)}</td>
            <td>${formatDate(pre.date_debut_pret)}</td>
            <td>
              <span class="status-badge status-pending">
                <i class="fas fa-clock mr-1"></i> En attente
              </span>
            </td>
            <td>
              ${roleAdminFinance ? `
                <button onclick="showConfirmationModal(${pre.idPret})" 
                        class="btn btn-xs btn-bank text-white">
                  <i class="fas fa-check mr-1"></i> Approuver
                </button>
              ` : `
                <span class="text-gray-400">En attente de validation</span>
              `}
            </td>
          `;
          tbody.appendChild(tr);
        });
      });
    }

    function showConfirmationModal(idPret) {
      currentPretId = idPret;
      document.getElementById('confirmation-modal').classList.add('modal-open');
    }

    // Gestion des événements modaux
    document.getElementById('btn-cancel').onclick = function() {
      document.getElementById('confirmation-modal').classList.remove('modal-open');
      currentPretId = null;
    };

    document.getElementById('btn-confirm').onclick = function() {
      if (!currentPretId) return;
      
      const btn = this;
      const originalHtml = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
      btn.disabled = true;
      
      approuverPret(currentPretId, function() {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
        document.getElementById('confirmation-modal').classList.remove('modal-open');
      });
    };

    function approuverPret(idPret, callback) {
      ajax("PUT", `/pret/approuver/${idPret}`, null, (res) => {
        showNotification(res.message, 'success');
        chargerPrets();
        if (callback) callback();
      });
    }

    // Fonctions utilitaires
    function getPretIcon(typePret) {
      const icons = {
        'Immobilier': 'fa-home',
        'Voiture': 'fa-car',
        'Étudiant': 'fa-graduation-cap',
        'Personnel': 'fa-user-tie'
      };
      return icons[typePret] || 'fa-hand-holding-usd';
    }

    function formatCurrency(amount) {
      return Number(amount || 0).toLocaleString('fr-FR');
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

    // Charger les prêts au démarrage
    document.addEventListener('DOMContentLoaded', chargerPrets);
  </script>
</body>
</html>