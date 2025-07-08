<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Intérêts gagnés par mois | Banque Horizon</title>
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
    .filter-section {
      background: #f8fafc;
      border-radius: 0.5rem;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
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
        <i class="fas fa-chart-line mr-2 text-blue-600"></i>
        Intérêts gagnés par mois
      </h1>
      <div class="flex space-x-2">
        <button class="btn btn-sm btn-outline">
          <i class="fas fa-file-export mr-2"></i> Exporter
        </button>
      </div>
    </div>

    <!-- Filtres -->
    <div class="filter-section animate-fade-in">
      <h2 class="text-xl font-semibold mb-4">
        <i class="fas fa-filter mr-2 text-blue-600"></i>
        Filtres
      </h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">Mois début</span>
          </label>
          <select id="moisDebut" class="select select-bordered input-bank w-full"></select>
        </div>
        
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">Année début</span>
          </label>
          <select id="anneeDebut" class="select select-bordered input-bank w-full"></select>
        </div>
        
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">Mois fin</span>
          </label>
          <select id="moisFin" class="select select-bordered input-bank w-full"></select>
        </div>
        
        <div class="form-control">
          <label class="label">
            <span class="label-text font-medium">Année fin</span>
          </label>
          <select id="anneeFin" class="select select-bordered input-bank w-full"></select>
        </div>
      </div>
      
      <div class="mt-4 flex justify-end">
        <button onclick="chargerStats()" class="btn btn-bank text-white">
          <i class="fas fa-chart-bar mr-2"></i>
          Afficher les statistiques
        </button>
      </div>
    </div>

    <!-- Tableau des données -->
    <div class="card bg-white shadow-xl mb-8 animate-fade-in">
      <div class="card-body">
        <h2 class="card-title text-xl mb-4">
          <i class="fas fa-table mr-2 text-blue-600"></i>
          Détails par mois
        </h2>
        
        <div class="overflow-x-auto">
          <table class="table w-full table-bank">
            <thead>
              <tr>
                <th class="bg-gray-50">Mois</th>
                <th class="bg-gray-50">Année</th>
                <th class="bg-gray-50">Intérêt (Ar)</th>
              </tr>
            </thead>
            <tbody id="tableauStats" class="divide-y">
              <!-- Les données seront chargées ici -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Graphique -->
    <div class="card bg-white shadow-xl animate-fade-in">
      <div class="card-body">
        <h2 class="card-title text-xl mb-4">
          <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
          Visualisation graphique
        </h2>
        <div class="w-full overflow-auto">
          <canvas id="graphInterets" width="600" height="300"></canvas>
        </div>
      </div>
    </div>
  </main>

  <?php include('footer.php'); ?>

  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    // Base de l'API
    const apiBase = "/ETU3209-ETU3111-ETU3118/ws";

    // Fonction AJAX générique
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
              alert("Erreur de parsing JSON : " + e.message + "\nRéponse brute :\n" + xhr.responseText);
            }
          } else {
            showNotification('Erreur lors de la récupération des données', 'error');
          }
        }
      };
      xhr.send(data);
    }

    // Initialisation des listes déroulantes
    function initSelects() {
      const moisSelects = [document.getElementById("moisDebut"), document.getElementById("moisFin")];
      const moisNoms = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
      
      for (let i = 0; i < 12; i++) {
        moisSelects.forEach(select => {
          const option = document.createElement("option");
          option.value = i + 1;
          option.textContent = moisNoms[i];
          select.appendChild(option);
        });
      }

      const year = new Date().getFullYear();
      const anneeSelects = [document.getElementById("anneeDebut"), document.getElementById("anneeFin")];
      for (let y = 2020; y <= year + 1; y++) {
        anneeSelects.forEach(select => {
          const option = document.createElement("option");
          option.value = y;
          option.textContent = y;
          select.appendChild(option);
        });
      }

      // Valeurs par défaut
      document.getElementById("moisDebut").value = 1;
      document.getElementById("anneeDebut").value = 2024;
      document.getElementById("moisFin").value = 12;
      document.getElementById("anneeFin").value = year;
    }

    // Récupérer et afficher les données
    function chargerStats() {
      const md = document.getElementById("moisDebut").value;
      const ad = document.getElementById("anneeDebut").value;
      const mf = document.getElementById("moisFin").value;
      const af = document.getElementById("anneeFin").value;

      // Afficher un indicateur de chargement
      const tbody = document.getElementById("tableauStats");
      tbody.innerHTML = `
        <tr>
          <td colspan="3" class="text-center py-8">
            <div class="flex justify-center">
              <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
            </div>
            <p class="mt-2 text-gray-500">Chargement des données...</p>
          </td>
        </tr>
      `;

      ajax("GET", `/interets-par-mois?moisDebut=${md}&anneeDebut=${ad}&moisFin=${mf}&anneeFin=${af}`, null, (data) => {
        tbody.innerHTML = "";

        const labels = [], valeurs = [];

        if (data.length === 0) {
          tbody.innerHTML = `
            <tr>
              <td colspan="3" class="text-center py-8 text-gray-500">
                Aucune donnée disponible pour la période sélectionnée
              </td>
            </tr>
          `;
          return;
        }

        data.forEach(row => {
          const moisNom = new Date(row.annee, row.mois - 1).toLocaleString('fr-FR', { month: 'long' });
          labels.push(`${moisNom} ${row.annee}`);
          valeurs.push(row.total_interet);
          
          const tr = document.createElement("tr");
          tr.className = "hover:bg-gray-50 transition animate-fade-in";
          tr.innerHTML = `
            <td class="font-medium">${moisNom}</td>
            <td>${row.annee}</td>
            <td class="text-right">${Number(row.total_interet).toLocaleString('fr-FR')} Ar</td>
          `;
          tbody.appendChild(tr);
        });

        afficherGraphique(labels, valeurs);
        showNotification('Données chargées avec succès', 'success');
      });
    }

    // Afficher le graphique avec Chart.js
    function afficherGraphique(labels, data) {
      const ctx = document.getElementById('graphInterets').getContext('2d');
      if (window.chart) window.chart.destroy(); // Supprimer l'ancien si existant
      
      window.chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Intérêts gagnés (Ar)',
            data: data,
            backgroundColor: '#317AC1',
            borderColor: '#1E3A8A',
            borderWidth: 1,
            hoverBackgroundColor: '#2563A8'
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return Number(context.raw).toLocaleString('fr-FR') + ' Ar';
                }
              }
            }
          },
          scales: {
            y: { 
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return Number(value).toLocaleString('fr-FR') + ' Ar';
                }
              }
            }
          }
        }
      });
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

    // Lancer au chargement
    document.addEventListener('DOMContentLoaded', () => {
      initSelects();
      chargerStats();
    });
  </script>
</body>
</html>