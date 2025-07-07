<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Types de Prêt | Banque Horizon</title>
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
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-hand-holding-usd mr-2 text-blue-600"></i>
        Gestion des Types de Prêt
      </h1>
      <div class="flex space-x-2">
        <button class="btn btn-sm btn-outline">
          <i class="fas fa-file-export mr-2"></i> Exporter
        </button>
        <button class="btn btn-sm btn-bank text-white">
          <i class="fas fa-plus mr-2"></i> Nouveau type
        </button>
      </div>
    </div>

    <!-- Form Card -->
    <div class="card glass-card shadow-xl mb-8 animate-fade-in" id="form-card">
      <div class="card-body">
        <h2 class="card-title text-xl mb-4">
          <i class="fas fa-edit mr-2 text-blue-600"></i>
          Formulaire de gestion
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
          <input type="hidden" id="idType">
          
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">Libellé</span>
            </label>
            <input type="text" id="libelle" placeholder="Ex: Prêt immobilier" 
                   class="input input-bordered input-bank w-full">
          </div>
          
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">Montant (€)</span>
            </label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">€</span>
              <input type="number" id="montant" placeholder="0.00" step="0.01" 
                     class="input input-bordered input-bank w-full pl-8">
            </div>
          </div>
          
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">Taux (%)</span>
            </label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">%</span>
              <input type="number" id="taux" placeholder="0.00" step="0.01" 
                     class="input input-bordered input-bank w-full pl-8">
            </div>
          </div>
          
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">Durée (mois)</span>
            </label>
            <input type="number" id="duree" placeholder="Durée en mois" 
                   class="input input-bordered input-bank w-full">
          </div>
          
          <div class="form-control flex flex-col justify-end">
            <button onclick="ajouterOuModifierTypePret()" class="btn btn-bank text-white">
              <i class="fas fa-save mr-2"></i>
              <span id="submit-btn-text">Ajouter</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Table Card -->
    <div class="card bg-white shadow-xl">
      <div class="card-body">
        <div class="flex justify-between items-center mb-4">
          <h2 class="card-title text-xl">
            <i class="fas fa-list-alt mr-2 text-blue-600"></i>
            Liste des types de prêt
          </h2>
          <div class="form-control">
            <div class="input-group">
              <input type="text" placeholder="Rechercher..." class="input input-bordered">
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
                <th class="bg-gray-50">ID</th>
                <th class="bg-gray-50">Libellé</th>
                <th class="bg-gray-50">Montant</th>
                <th class="bg-gray-50">Taux</th>
                <th class="bg-gray-50">Durée max</th>
                <th class="bg-gray-50">Actions</th>
              </tr>
            </thead>
            <tbody id="table-types-pret">
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

    function chargerTypesPret() {
      ajax("GET", "/type-pret", null, (data) => {
        const tbody = document.querySelector("#table-types-pret");
        tbody.innerHTML = "";
        
        data.forEach(t => {
          const tr = document.createElement("tr");
          tr.className = "hover:bg-gray-50 transition animate-fade-in";
          tr.innerHTML = `
            <td class="font-semibold">${t.idType}</td>
            <td>${t.libelle}</td>
            <td>${Number(t.montant).toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'})}</td>
            <td>${Number(t.taux).toFixed(2)}%</td>
            <td>${t.duree_mois_max} mois</td>
            <td>
              <div class="flex space-x-2">
                <button onclick='remplirFormulaireTypePret(${JSON.stringify(t)})' 
                        class="btn btn-xs btn-outline btn-info">
                  <i class="fas fa-edit"></i>
                </button>
                <button onclick='supprimerTypePret(${t.idType})' 
                        class="btn btn-xs btn-outline btn-error">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          `;
          tbody.appendChild(tr);
        });
        
        // Mettre à jour les informations de pagination
        document.getElementById('total-items').textContent = data.length;
      });
    }

    function ajouterOuModifierTypePret() {
      const idType = document.getElementById("idType").value;
      const libelle = document.getElementById("libelle").value;
      const montant = document.getElementById("montant").value;
      const taux = document.getElementById("taux").value;
      const duree = document.getElementById("duree").value;

      if (!libelle || !montant || !taux || !duree) {
        showNotification('Veuillez remplir tous les champs', 'warning');
        return;
      }

      const data = 
        `libelle=${encodeURIComponent(libelle)}&` +
        `montant=${encodeURIComponent(montant)}&` +
        `taux=${encodeURIComponent(taux)}&` +
        `duree_mois_max=${encodeURIComponent(duree)}`;

      if (idType) {
        ajax("PUT", `/type-pret/${idType}`, data, () => {
          showNotification('Type de prêt modifié avec succès', 'success');
          resetFormTypePret();
          chargerTypesPret();
        });
      } else {
        ajax("POST", "/type-pret", data, () => {
          showNotification('Type de prêt ajouté avec succès', 'success');
          resetFormTypePret();
          chargerTypesPret();
        });
      }
    }

    function remplirFormulaireTypePret(t) {
      document.getElementById("idType").value = t.idType;
      document.getElementById("libelle").value = t.libelle;
      document.getElementById("montant").value = t.montant;
      document.getElementById("taux").value = t.taux;
      document.getElementById("duree").value = t.duree_mois_max;
      document.getElementById("submit-btn-text").textContent = "Modifier";
      
      // Scroll vers le formulaire
      document.getElementById('form-card').scrollIntoView({ behavior: 'smooth' });
      
      // Animation pour attirer l'attention
      const formCard = document.getElementById('form-card');
      formCard.classList.add('ring-2', 'ring-blue-500');
      setTimeout(() => {
        formCard.classList.remove('ring-2', 'ring-blue-500');
      }, 2000);
    }

    function supprimerTypePret(idType) {
      // Utilisation d'une boîte de dialogue plus moderne
      const modal = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-bold mb-4">Confirmer la suppression</h3>
            <p class="mb-6">Êtes-vous sûr de vouloir supprimer ce type de prêt ? Cette action est irréversible.</p>
            <div class="flex justify-end space-x-3">
              <button onclick="closeModal()" class="btn btn-outline btn-sm">Annuler</button>
              <button onclick="confirmDelete(${idType})" class="btn btn-error btn-sm">Supprimer</button>
            </div>
          </div>
        </div>
      `;
      
      const div = document.createElement('div');
      div.id = 'confirmation-modal';
      div.innerHTML = modal;
      document.body.appendChild(div);
    }

    function confirmDelete(idType) {
      ajax("DELETE", `/type-pret/${idType}`, null, () => {
        showNotification('Type de prêt supprimé avec succès', 'success');
        chargerTypesPret();
        closeModal();
      });
    }

    function closeModal() {
      const modal = document.getElementById('confirmation-modal');
      if (modal) {
        modal.remove();
      }
    }

    function resetFormTypePret() {
      document.getElementById("idType").value = "";
      document.getElementById("libelle").value = "";
      document.getElementById("montant").value = "";
      document.getElementById("taux").value = "";
      document.getElementById("duree").value = "";
      document.getElementById("submit-btn-text").textContent = "Ajouter";
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
      chargerTypesPret();
      
      // Ajouter un effet de chargement
      const tbody = document.querySelector("#table-types-pret");
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="text-center py-8">
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