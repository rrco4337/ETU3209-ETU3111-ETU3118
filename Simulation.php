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
    .simulation-card {
      transition: all 0.3s;
      border-left: 4px solid transparent;
    }
    .simulation-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .simulation-card.selected {
      border-left: 4px solid #317AC1;
      background-color: #f0f7ff;
    }
    .comparison-table th {
      background-color: #f8fafc;
      position: sticky;
      top: 0;
    }
  </style>
</head>
<body class="min-h-screen bg-gray-50">
  <main class="container mx-auto px-4 py-8">
    <!-- Section de simulation actuelle -->
    <div class="card bg-white shadow-xl mb-8 animate-fade-in">
      <div class="card-body">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
          <i class="fas fa-calculator mr-2 text-blue-600"></i>
          Simulateur de Prêt - Annuités Constant
        </h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
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

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">Taux Assurance (%) (facult.)</span>
            </label>
            <input type="number" id="tauxAssurance" min="0" max="5" step="0.01" placeholder="Ex: 0.36" class="input input-bordered input-bank">
          </div>
        </div>
        
        <div class="flex flex-wrap gap-4">
          <button onclick="calculerPret()" class="btn btn-bank text-white">
            <i class="fas fa-calculator mr-2"></i>
            Calculer
          </button>
          
          <button onclick="sauvegarderSimulation()" class="btn btn-success text-white">
            <i class="fas fa-save mr-2"></i>
            Sauvegarder
          </button>
          
          <button onclick="afficherComparaison()" class="btn btn-warning text-white">
            <i class="fas fa-exchange-alt mr-2"></i>
            Comparer (2 max)
          </button>
        </div>
      </div>
    </div>

    <!-- Section des simulations sauvegardées -->
    <div class="card bg-white shadow-xl mb-8 animate-fade-in" id="savedSimulationsSection">
      <div class="card-body">
        <h2 class="card-title text-xl mb-4">
          <i class="fas fa-folder-open mr-2 text-blue-600"></i>
          Simulations Sauvegardées
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="savedSimulationsContainer">
          <!-- Les simulations sauvegardées apparaîtront ici -->
        </div>
      </div>
    </div>

    <!-- Section de comparaison (cachée par défaut) -->
    <div class="card bg-white shadow-xl mb-8 hidden" id="comparisonSection">
      <div class="card-body">
        <h2 class="card-title text-xl mb-4">
          <i class="fas fa-exchange-alt mr-2 text-blue-600"></i>
          Comparaison des Simulations
        </h2>
        <div class="overflow-x-auto">
          <table class="table w-full comparison-table">
            <thead>
              <tr>
                <th class="bg-gray-50">Critère</th>
                <th class="bg-gray-50" id="simulation1Title">Simulation 1</th>
                <th class="bg-gray-50" id="simulation2Title">Simulation 2</th>
                <th class="bg-gray-50">Différence</th>
              </tr>
            </thead>
            <tbody id="comparisonBody">
              <!-- Les données de comparaison apparaîtront ici -->
            </tbody>
          </table>
        </div>
        
        <div class="mt-8">
          <h3 class="text-lg font-semibold mb-4">
            <i class="fas fa-chart-line mr-2 text-blue-600"></i>
            Comparaison Graphique
          </h3>
          <div class="chart-container">
            <canvas id="comparisonChart"></canvas>
          </div>
        </div>
        
        <div class="flex justify-end mt-4">
          <button onclick="fermerComparaison()" class="btn btn-error text-white">
            <i class="fas fa-times mr-2"></i>
            Fermer la comparaison
          </button>
        </div>
      </div>
    </div>

    <!-- Section des résultats de la simulation actuelle -->
    <div class="card bg-white shadow-xl mb-8 animate-fade-in" id="currentSimulationResults">
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

    <div class="card bg-white shadow-xl animate-fade-in" id="currentSimulationTable">
      <div class="card-body">
        <h2 class="card-title text-xl mb-4">
          <i class="fas fa-table mr-2 text-blue-600"></i>
          Tableau d'Amortissement
        </h2>
        <div class="overflow-x-auto">
          <table class="table w-full table-bank">
            <thead id="amortissementHeader">
              <!-- L'en-tête sera généré dynamiquement -->
            </thead>
            <tbody id="amortissementBody">
              
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <script>
    let myChart = null;
    let comparisonChart = null;
    let savedSimulations = JSON.parse(localStorage.getItem('savedSimulations')) || [];
    let selectedSimulations = [];
    let currentSimulation = null;

    function calculerPret() {
      const montant = parseFloat(document.getElementById('montant').value);
      const tauxAnnuel = parseFloat(document.getElementById('taux').value);
      const duree = parseInt(document.getElementById('duree').value);
      const tauxAssuranceAnnuel = parseFloat(document.getElementById('tauxAssurance').value) || 0;
      const hasInsurance = tauxAssuranceAnnuel > 0;

      if (isNaN(montant) || isNaN(tauxAnnuel) || isNaN(duree)) {
        showNotification("Veuillez remplir les champs principaux correctement", "error");
        return;
      }

      const tauxMensuel = tauxAnnuel / 100 / 12;
      const nbMois = duree;
      
      const mensualiteHorsAssurance = montant * (tauxMensuel * Math.pow(1 + tauxMensuel, nbMois)) / (Math.pow(1 + tauxMensuel, nbMois) - 1);
      
      const coutAssuranceMensuel = hasInsurance ? (montant * (tauxAssuranceAnnuel / 100)) / 12 : 0;
      const mensualiteAvecAssurance = mensualiteHorsAssurance + coutAssuranceMensuel;
      
      let capitalRestant = montant;
      let totalInterets = 0;
      let tableauAmortissement = [];
      let labels = [], capitalData = [], interetsData = [], assuranceData = [];
      
      for (let i = 1; i <= nbMois; i++) {
        const interets = capitalRestant * tauxMensuel;
        const amortissement = mensualiteHorsAssurance - interets;
        capitalRestant -= amortissement;
        
        if (i === nbMois) capitalRestant = 0;
        
        totalInterets += interets;
        
        // Echantillonage pour le graphique (une fois par an pour la lisibilité)
        if (i % 12 === 0 || i === 1 || i === nbMois) {
          labels.push(`Mois ${i}`);
          capitalData.push(amortissement);
          interetsData.push(interets);
          assuranceData.push(coutAssuranceMensuel);
        }
        
        tableauAmortissement.push({
          mois: i,
          mensualiteHorsAssurance: mensualiteHorsAssurance,
          mensualiteAvecAssurance: mensualiteAvecAssurance,
          assurance: coutAssuranceMensuel,
          interets: interets,
          capital: amortissement,
          reste: capitalRestant > 0 ? capitalRestant : 0
        });
      }
      
      mettreAJourGraphique(labels, capitalData, interetsData, assuranceData, hasInsurance);
      remplirTableauAmortissement(tableauAmortissement, hasInsurance);
      
      const totalAssurance = coutAssuranceMensuel * nbMois;
      
      currentSimulation = {
        montant: montant,
        taux: tauxAnnuel,
        duree: duree,
        tauxAssurance: tauxAssuranceAnnuel,
        mensualite: mensualiteHorsAssurance,
        mensualiteAvecAssurance: mensualiteAvecAssurance,
        totalInterets: totalInterets,
        totalAssurance: totalAssurance,
        coutTotal: montant + totalInterets,
        coutTotalAvecAssurance: montant + totalInterets + totalAssurance,
        date: new Date().toLocaleString(),
        tableauAmortissement: tableauAmortissement,
        // Sauvegarde des données du graphique pour la comparaison
        labels: labels,
        capitalData: capitalData,
        interetsData: interetsData,
        assuranceData: assuranceData
      };
      
      showNotification("Calcul effectué avec succès", "success");
    }

    function sauvegarderSimulation() {
      if (!currentSimulation) {
        showNotification("Veuillez d'abord effectuer une simulation", "error");
        return;
      }
      
      const simulationId = Date.now().toString();
      currentSimulation.id = simulationId;
      const nomBase = `Sim ${savedSimulations.length + 1} - ${currentSimulation.montant.toLocaleString()}€/${currentSimulation.duree}m`;
      currentSimulation.nom = currentSimulation.tauxAssurance > 0 ? `${nomBase} (avec ass.)` : nomBase;

      savedSimulations.push(currentSimulation);
      localStorage.setItem('savedSimulations', JSON.stringify(savedSimulations));
      afficherSimulationsSauvegardees();
      showNotification("Simulation sauvegardée avec succès", "success");
    }

    function afficherSimulationsSauvegardees() {
      const container = document.getElementById('savedSimulationsContainer');
      container.innerHTML = '';
      
      if (savedSimulations.length === 0) {
        container.innerHTML = '<p class="text-gray-500">Aucune simulation sauvegardée</p>';
        return;
      }
      
      savedSimulations.forEach(sim => {
        const hasInsurance = sim.tauxAssurance > 0;
        const card = document.createElement('div');
        card.className = `simulation-card card bg-base-100 shadow-md cursor-pointer ${selectedSimulations.includes(sim.id) ? 'selected' : ''}`;
        card.innerHTML = `
          <div class="card-body p-4">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="card-title text-base">${sim.nom}</h3>
                <p class="text-xs text-gray-500">${sim.date}</p>
              </div>
              <input type="checkbox" class="checkbox checkbox-primary checkbox-sm" 
                ${selectedSimulations.includes(sim.id) ? 'checked' : ''}
                onclick="toggleSimulationSelection('${sim.id}', event)">
            </div>
            <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
              <div>
                <p class="text-gray-500">Mensualité ${hasInsurance ? 'totale' : ''}</p>
                <p class="font-bold">${(hasInsurance ? sim.mensualiteAvecAssurance : sim.mensualite).toFixed(2)} €</p>
              </div>
              <div>
                <p class="text-gray-500">Coût total ${hasInsurance ? 'total' : ''}</p>
                <p class="font-bold">${(hasInsurance ? sim.coutTotalAvecAssurance : sim.coutTotal).toFixed(2)} €</p>
              </div>
              <div>
                <p class="text-gray-500">Total intérêts</p>
                <p class="font-bold">${sim.totalInterets.toFixed(2)} €</p>
              </div>
              ${hasInsurance ? `
              <div>
                <p class="text-gray-500">Total assurance</p>
                <p class="font-bold">${sim.totalAssurance.toFixed(2)} €</p>
              </div>
              ` : `
              <div>
                <p class="text-gray-500">Durée</p>
                <p class="font-bold">${sim.duree} mois</p>
              </div>
              `}
            </div>
            <div class="card-actions justify-end mt-2">
              <button class="btn btn-xs btn-error text-white" onclick="supprimerSimulation('${sim.id}', event)">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
        `;
        container.appendChild(card);
      });
    }

    function toggleSimulationSelection(id, event) {
      event.stopPropagation();
      const index = selectedSimulations.indexOf(id);
      if (index === -1) {
        if (selectedSimulations.length >= 2) {
          showNotification("Vous ne pouvez comparer que 2 simulations maximum", "warning");
          event.target.checked = false;
          return;
        }
        selectedSimulations.push(id);
      } else {
        selectedSimulations.splice(index, 1);
      }
      afficherSimulationsSauvegardees();
    }

    function supprimerSimulation(id, event) {
      event.stopPropagation();
      if (confirm("Voulez-vous vraiment supprimer cette simulation ?")) {
        savedSimulations = savedSimulations.filter(sim => sim.id !== id);
        selectedSimulations = selectedSimulations.filter(simId => simId !== id);
        localStorage.setItem('savedSimulations', JSON.stringify(savedSimulations));
        afficherSimulationsSauvegardees();
        showNotification("Simulation supprimée avec succès", "success");
      }
    }
    
    function afficherComparaison() {
      if (selectedSimulations.length !== 2) {
        showNotification("Veuillez sélectionner exactement 2 simulations à comparer", "error");
        return;
      }
      
      const sim1 = savedSimulations.find(sim => sim.id === selectedSimulations[0]);
      const sim2 = savedSimulations.find(sim => sim.id === selectedSimulations[1]);
      
      document.getElementById('simulation1Title').textContent = sim1.nom;
      document.getElementById('simulation2Title').textContent = sim2.nom;
      
      const comparisonBody = document.getElementById('comparisonBody');
      comparisonBody.innerHTML = '';
      
      const rows = [
        { label: 'Montant emprunté', v1: sim1.montant, v2: sim2.montant, unit: '€' },
        { label: 'Taux d\'intérêt', v1: sim1.taux, v2: sim2.taux, unit: '%' },
        { label: 'Durée', v1: sim1.duree, v2: sim2.duree, unit: ' mois' },
        { label: 'Mensualité (hors ass.)', v1: sim1.mensualite, v2: sim2.mensualite, unit: '€' },
        { label: 'Total intérêts', v1: sim1.totalInterets, v2: sim2.totalInterets, unit: '€' }
      ];

      if (sim1.tauxAssurance > 0 || sim2.tauxAssurance > 0) {
        rows.push({ label: 'Taux assurance', v1: sim1.tauxAssurance, v2: sim2.tauxAssurance, unit: '%' });
        rows.push({ label: 'Mensualité (avec ass.)', v1: sim1.mensualiteAvecAssurance, v2: sim2.mensualiteAvecAssurance, unit: '€' });
        rows.push({ label: 'Coût total assurance', v1: sim1.totalAssurance, v2: sim2.totalAssurance, unit: '€' });
        rows.push({ label: 'Coût total (avec ass.)', v1: sim1.coutTotalAvecAssurance, v2: sim2.coutTotalAvecAssurance, unit: '€' });
      } else {
        rows.push({ label: 'Coût total (hors ass.)', v1: sim1.coutTotal, v2: sim2.coutTotal, unit: '€' });
      }

      rows.forEach(row => {
        const diff = row.v1 - row.v2;
        const diffText = row.label.includes('Taux') ? diff.toFixed(3) : diff.toFixed(2);
        tr = document.createElement('tr');
        tr.innerHTML = `
          <td class="font-semibold">${row.label}</td>
          <td>${row.v1.toFixed(2)} ${row.unit}</td>
          <td>${row.v2.toFixed(2)} ${row.unit}</td>
          <td class="${diff < 0 ? 'text-green-500' : diff > 0 ? 'text-red-500' : ''}">${diff > 0 ? '+' : ''}${diffText} ${row.unit}</td>
        `;
        comparisonBody.appendChild(tr);
      });
      
      mettreAJourGraphiqueComparaison(sim1, sim2);
      
      document.getElementById('comparisonSection').classList.remove('hidden');
      document.getElementById('currentSimulationResults').classList.add('hidden');
      document.getElementById('currentSimulationTable').classList.add('hidden');
      document.getElementById('savedSimulationsSection').classList.add('hidden');
    }

    function fermerComparaison() {
      document.getElementById('comparisonSection').classList.add('hidden');
      document.getElementById('currentSimulationResults').classList.remove('hidden');
      document.getElementById('currentSimulationTable').classList.remove('hidden');
      document.getElementById('savedSimulationsSection').classList.remove('hidden');
    }

    function mettreAJourGraphiqueComparaison(sim1, sim2) {
      const ctx = document.getElementById('comparisonChart').getContext('2d');
      if (comparisonChart) {
        comparisonChart.destroy();
      }
      
      const maxLength = Math.max(sim1.labels.length, sim2.labels.length);
      const labels = Array.from({ length: maxLength }, (_, i) => sim1.labels[i] || sim2.labels[i]);
      
      comparisonChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [
            {
              label: `${sim1.nom} - Mensualité totale`,
              data: sim1.labels.map((_, i) => (sim1.capitalData[i] || 0) + (sim1.interetsData[i] || 0) + (sim1.assuranceData[i] || 0)),
              borderColor: 'rgba(54, 162, 235, 1)',
              backgroundColor: 'rgba(54, 162, 235, 0.1)',
              borderWidth: 2,
              tension: 0.1,
              fill: true
            },
            {
              label: `${sim2.nom} - Mensualité totale`,
              data: sim2.labels.map((_, i) => (sim2.capitalData[i] || 0) + (sim2.interetsData[i] || 0) + (sim2.assuranceData[i] || 0)),
              borderColor: 'rgba(255, 99, 132, 1)',
              backgroundColor: 'rgba(255, 99, 132, 0.1)',
              borderWidth: 2,
              tension: 0.1,
              fill: true
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: { beginAtZero: false, title: { display: true, text: 'Mensualité Totale (€)' } },
            x: { title: { display: true, text: 'Échéances' } }
          },
          plugins: {
            title: { display: true, text: 'Comparaison des Mensualités Totales', font: { size: 16 } },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return `${context.dataset.label}: ${context.raw.toFixed(2)} €`;
                }
              }
            }
          }
        }
      });
    }
    
    // --- FONCTION MODIFIÉE ---
    function mettreAJourGraphique(labels, capitalData, interetsData, assuranceData, hasInsurance) {
        const ctx = document.getElementById('evolutionChart').getContext('2d');
        if (myChart) myChart.destroy();
        
        const datasets = [
            { 
                label: 'Capital remboursé', 
                data: capitalData, 
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true,
                tension: 0.1
            },
            { 
                label: 'Intérêts payés', 
                data: interetsData, 
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: true,
                tension: 0.1
            }
        ];

        if (hasInsurance) {
            datasets.push({ 
                label: 'Assurance', 
                data: assuranceData, 
                borderColor: 'rgba(255, 206, 86, 1)',
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                fill: true,
                tension: 0.1
            });
        }

        myChart = new Chart(ctx, {
            type: 'line', // Changé en 'line'
            data: { labels: labels, datasets: datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { 
                    x: { 
                        stacked: true, 
                        title: { display: true, text: 'Échéances (par année)' }
                    }, 
                    y: { 
                        stacked: true, 
                        beginAtZero: true,
                        title: { display: true, text: 'Montant (€)' }
                    } 
                },
                plugins: {
                    title: { 
                        display: true, 
                        text: 'Composition des Mensualités (Courbes)', 
                        font: { size: 16 } 
                    },
                    tooltip: {
                        callbacks: {
                            footer: (tooltipItems) => {
                                let total = tooltipItems.reduce((sum, item) => sum + item.parsed.y, 0);
                                return 'Total mensualité: ' + total.toFixed(2) + ' €';
                            }
                        }
                    }
                }
            }
        });
    }

    function remplirTableauAmortissement(tableau, hasInsurance) {
        const header = document.getElementById('amortissementHeader');
        const body = document.getElementById('amortissementBody');
        body.innerHTML = '';

        header.innerHTML = hasInsurance ? `
            <tr>
                <th class="bg-gray-50">Mois</th>
                <th class="bg-gray-50">Mensualité (sans ass.)</th>
                <th class="bg-gray-50">Mensualité (avec ass.)</th>
                <th class="bg-gray-50">Intérêts</th>
                <th class="bg-gray-50">Capital</th>
                <th class="bg-gray-50">Capital Restant</th>
            </tr>
        ` : `
            <tr>
                <th class="bg-gray-50">Mois</th>
                <th class="bg-gray-50">Mensualité</th>
                <th class="bg-gray-50">Intérêts</th>
                <th class="bg-gray-50">Capital</th>
                <th class="bg-gray-50">Capital Restant</th>
            </tr>
        `;
        
        const uniqueKeys = new Set();
        const moisAAfficher = [];
        const addRow = (echeance) => {
            if (!uniqueKeys.has(echeance.mois)) {
                moisAAfficher.push(echeance);
                uniqueKeys.add(echeance.mois);
            }
        };

        if (tableau.length <= 30) {
            tableau.forEach(addRow);
        } else {
            tableau.slice(0, 12).forEach(addRow);
            for(let i = 24; i < tableau.length - 12; i += 12) {
              addRow(tableau[i - 1]);
            }
            tableau.slice(-12).forEach(addRow);
        }

        moisAAfficher.forEach(echeance => {
            const tr = document.createElement('tr');
            tr.className = "hover:bg-gray-50";
            tr.innerHTML = hasInsurance ? `
                <td class="font-semibold">${echeance.mois}</td>
                <td>${echeance.mensualiteHorsAssurance.toFixed(2)} €</td>
                <td>${echeance.mensualiteAvecAssurance.toFixed(2)} €</td>
                <td>${echeance.interets.toFixed(2)} €</td>
                <td>${echeance.capital.toFixed(2)} €</td>
                <td>${echeance.reste.toFixed(2)} €</td>
            ` : `
                <td class="font-semibold">${echeance.mois}</td>
                <td>${echeance.mensualiteHorsAssurance.toFixed(2)} €</td>
                <td>${echeance.interets.toFixed(2)} €</td>
                <td>${echeance.capital.toFixed(2)} €</td>
                <td>${echeance.reste.toFixed(2)} €</td>
            `;
            body.appendChild(tr);
        });

        const totalInterets = tableau.reduce((sum, e) => sum + e.interets, 0);
        const totalCapital = tableau.reduce((sum, e) => sum + e.capital, 0);
        
        const trTotal = document.createElement('tr');
        trTotal.className = "font-bold bg-gray-100";
        trTotal.innerHTML = hasInsurance ? `
            <td>Total</td>
            <td>${(totalCapital + totalInterets).toFixed(2)} €</td>
            <td>${(totalCapital + totalInterets + (currentSimulation.totalAssurance || 0)).toFixed(2)} €</td>
            <td>${totalInterets.toFixed(2)} €</td>
            <td>${totalCapital.toFixed(2)} €</td>
            <td>-</td>
        ` : `
            <td>Total</td>
            <td>${(totalCapital + totalInterets).toFixed(2)} €</td>
            <td>${totalInterets.toFixed(2)} €</td>
            <td>${totalCapital.toFixed(2)} €</td>
            <td>-</td>
        `;
        body.appendChild(trTotal);
    }
    
    function showNotification(message, type) {
      const types = {
        success: { icon: 'fa-check-circle', color: 'bg-green-500' },
        error: { icon: 'fa-times-circle', color: 'bg-red-500' },
        warning: { icon: 'fa-exclamation-circle', color: 'bg-yellow-500' }
      };
      const notif = document.createElement('div');
      notif.className = `fixed top-4 right-4 z-50 ${types[type].color} text-white px-4 py-2 rounded shadow-lg flex items-center animate-fade-in`;
      notif.innerHTML = `<i class="fas ${types[type].icon} mr-2"></i><span>${message}</span>`;
      document.body.appendChild(notif);
      setTimeout(() => {
        notif.classList.add('opacity-0', 'transition-opacity', 'duration-500');
        setTimeout(() => notif.remove(), 500);
      }, 3000);
    }
    
    window.onload = function() {
      afficherSimulationsSauvegardees();
      calculerPret();
    };
  </script>
</body>
</html>