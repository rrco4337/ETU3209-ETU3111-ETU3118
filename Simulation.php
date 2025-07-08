<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simulateur de Prêt - Annuités Constant</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    .chart-container {
      height: 400px;
      margin-top: 2rem;
    }
  </style>
</head>
<body class="min-h-screen bg-gray-50">
  <?php include('sidebar.php'); ?>

  <main class="container mx-auto px-4 py-8">
    <div class="card bg-white shadow-xl mb-8 animate-fade-in">
      <div class="card-body">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
          <i class="fas fa-calculator mr-2 text-blue-600"></i>
          Simulateur de Prêt - Annuités Constant
        </h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">Montant du Prêt (€)</span>
            </label>
            <input type="number" id="montant" min="1000" step="100" value="100000" class="input input-bordered input-bank">
          </div>
          
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">Taux d'Intérêt Annuel (%)</span>
            </label>
            <input type="number" id="taux" min="0" max="20" step="0.01" value="3.5" class="input input-bordered input-bank">
          </div>
          
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">Durée (en mois)</span>
            </label>
            <input type="number" id="duree" min="6" max="360" step="1" value="240" class="input input-bordered input-bank">
          </div>
        </div>
        
        <button onclick="calculerPret()" class="btn btn-bank text-white">
          <i class="fas fa-calculator mr-2"></i>
          Calculer
        </button>
      </div>
    </div>

    <div class="card bg-white shadow-xl mb-8 animate-fade-in">
      <div class="card-body">
        <h2 class="card-title text-xl mb-4">
          <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
          Évolution du Remboursement
        </h2>
        <div class="chart-container">
          <canvas id="evolutionChart"></canvas>
        </div>
      </div>
    </div>

    <div class="card bg-white shadow-xl animate-fade-in">
      <div class="card-body">
        <h2 class="card-title text-xl mb-4">
          <i class="fas fa-table mr-2 text-blue-600"></i>
          Tableau d'Amortissement
        </h2>
        <div class="overflow-x-auto">
          <table class="table w-full table-bank">
            <thead>
              <tr>
                <th class="bg-gray-50">Mois</th>
                <th class="bg-gray-50">Mensualité</th>
                <th class="bg-gray-50">Intérêts</th>
                <th class="bg-gray-50">Capital</th>
                <th class="bg-gray-50">Capital Restant</th>
              </tr>
            </thead>
            <tbody id="amortissementBody">
              
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
  <?php include('footer.php'); ?>

  <script>
    let myChart = null;
    
    function calculerPret() {
    
      const montant = parseFloat(document.getElementById('montant').value);
      const tauxAnnuel = parseFloat(document.getElementById('taux').value);
      const duree = parseInt(document.getElementById('duree').value);
      
      // Validation des entrées
      if (isNaN(montant) || isNaN(tauxAnnuel) || isNaN(duree)) {
        showNotification("Veuillez remplir tous les champs correctement", "error");
        return;
      }
      
      // Calculs préliminaires
      const tauxMensuel = tauxAnnuel / 100 / 12;
      const nbMois = duree;
      
      // Formule de l'annuité constante
      const annuite = montant * (tauxMensuel * Math.pow(1 + tauxMensuel, nbMois)) / (Math.pow(1 + tauxMensuel, nbMois) - 1);
      
      // Initialisation des variables
      let capitalRestant = montant;
      let totalInterets = 0;
      let tableauAmortissement = [];
      let labels = [];
      let capitalData = [];
      let interetsData = [];
      
      // Génération du tableau d'amortissement
      for (let i = 1; i <= nbMois; i++) {
        const interets = capitalRestant * tauxMensuel;
        const amortissement = annuite - interets;
        capitalRestant -= amortissement;
        
        // Correction des erreurs d'arrondi pour le dernier mois
        if (i === nbMois) {
          capitalRestant = 0;
        }
        
        totalInterets += interets;
        
        // Stockage des données pour le graphique (tous les 6 mois)
        if (i % 6 === 0 || i === 1 || i === nbMois) {
          labels.push(`Mois ${i}`);
          capitalData.push(amortissement);
          interetsData.push(interets);
        }
        
        tableauAmortissement.push({
          mois: i,
          annuite: annuite,
          interets: interets,
          capital: amortissement,
          reste: capitalRestant > 0 ? capitalRestant : 0
        });
      }
      
      // Mise à jour du graphique
      mettreAJourGraphique(labels, capitalData, interetsData);
      
      // Remplissage du tableau d'amortissement
      remplirTableauAmortissement(tableauAmortissement);
      
      showNotification("Calcul effectué avec succès", "success");
    }
    
    function mettreAJourGraphique(labels, capitalData, interetsData) {
      const ctx = document.getElementById('evolutionChart').getContext('2d');
      
      // Détruire le graphique précédent s'il existe
      if (myChart) {
        myChart.destroy();
      }
      
      myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [
            {
              label: 'Capital remboursé',
              data: capitalData,
              backgroundColor: 'rgba(54, 162, 235, 0.7)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
            },
            {
              label: 'Intérêts payés',
              data: interetsData,
              backgroundColor: 'rgba(255, 99, 132, 0.7)',
              borderColor: 'rgba(255, 99, 132, 1)',
              borderWidth: 1
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: {
              stacked: true,
              title: {
                display: true,
                text: 'Périodes'
              }
            },
            y: {
              stacked: true,
              title: {
                display: true,
                text: 'Montant (€)'
              },
              beginAtZero: true
            }
          },
          plugins: {
            title: {
              display: true,
              text: 'Évolution de la Composition des Mensualités',
              font: {
                size: 16
              }
            },
            tooltip: {
              callbacks: {
                afterBody: function(context) {
                  const dataIndex = context[0].dataIndex;
                  const total = capitalData[dataIndex] + interetsData[dataIndex];
                  return `Total mensualité: ${total.toFixed(2)} €`;
                }
              }
            }
          }
        }
      });
    }
    
    function remplirTableauAmortissement(tableauAmortissement) {
      const tbody = document.getElementById('amortissementBody');
      tbody.innerHTML = '';
      
      // Afficher les 12 premiers mois
      const premiersMois = tableauAmortissement.slice(0, 12);
      
      // Afficher un échantillon des mois intermédiaires (tous les 12 mois)
      const moisIntermediaires = [];
      for (let i = 12; i < tableauAmortissement.length - 12; i += 12) {
        moisIntermediaires.push(tableauAmortissement[i]);
      }
      
      // Afficher les 12 derniers mois
      const derniersMois = tableauAmortissement.slice(-12);
      
      // Combiner tous les mois à afficher
      const moisAAfficher = [...premiersMois, ...moisIntermediaires, ...derniersMois];
      
      moisAAfficher.forEach(echeance => {
        const tr = document.createElement('tr');
        tr.className = "hover:bg-gray-50";
        
        // Mettre en évidence le premier et dernier mois
        if (echeance.mois === 1) {
          tr.className += " bg-blue-50";
        } else if (echeance.mois === tableauAmortissement.length) {
          tr.className += " bg-green-50";
        }
        
        tr.innerHTML = `
          <td class="font-semibold">${echeance.mois}</td>
          <td>${echeance.annuite.toFixed(2)} €</td>
          <td>${echeance.interets.toFixed(2)} €</td>
          <td>${echeance.capital.toFixed(2)} €</td>
          <td>${echeance.reste.toFixed(2)} €</td>
        `;
        
        tbody.appendChild(tr);
      });
      
      // Ajouter une ligne de totaux
      const totalAnnuite = tableauAmortissement.reduce((sum, e) => sum + e.annuite, 0);
      const totalInterets = tableauAmortissement.reduce((sum, e) => sum + e.interets, 0);
      const totalCapital = tableauAmortissement.reduce((sum, e) => sum + e.capital, 0);
      
      const trTotal = document.createElement('tr');
      trTotal.className = "font-bold bg-gray-100";
      trTotal.innerHTML = `
        <td>Total</td>
        <td>${totalAnnuite.toFixed(2)} €</td>
        <td>${totalInterets.toFixed(2)} €</td>
        <td>${totalCapital.toFixed(2)} €</td>
        <td>-</td>
      `;
      tbody.appendChild(trTotal);
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
    
    // Initialisation au chargement de la page
    window.onload = function() {
      // Créer un graphique vide au départ
      const ctx = document.getElementById('evolutionChart').getContext('2d');
      myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: [],
          datasets: []
        },
        options: {
          responsive: true,
          maintainAspectRatio: false
        }
      });
      
      // Calculer avec les valeurs par défaut
      calculerPret();
    };
  </script>
</body>
</html>