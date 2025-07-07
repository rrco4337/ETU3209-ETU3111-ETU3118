
<h2>Intérêts gagnés par mois</h2>

<!-- Filtres -->
<label>Mois début:</label>
<select id="moisDebut"></select>
<label>Année début:</label>
<select id="anneeDebut"></select><br>

<label>Mois fin:</label>
<select id="moisFin"></select>
<label>Année fin:</label>
<select id="anneeFin"></select><br>

<button onclick="chargerStats()">Afficher</button>

<!-- Tableau des données -->
<table border="1">
  <thead>
    <tr><th>Mois</th><th>Année</th><th>Intérêt (Ar)</th></tr>
  </thead>
  <tbody id="tableauStats"></tbody>
</table>

<!-- Graphique -->
<canvas id="graphInterets" width="600" height="300"></canvas>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  // Base de l’API
  const apiBase = "/ETU3209-ETU3111-ETU3118/ws";

  // Fonction AJAX générique
  function ajax(method, url, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, apiBase + url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          callback(JSON.parse(xhr.responseText));
        } else {
        alert("Erreur de parsing JSON : " + e.message + "\nRéponse brute :\n" + xhr.responseText);
          
        }
      }
    };
    xhr.send(data);
  }

  // Initialisation des listes déroulantes
  function initSelects() {
    const moisSelects = [document.getElementById("moisDebut"), document.getElementById("moisFin")];
    for (let i = 1; i <= 12; i++) {
      moisSelects.forEach(select => {
        const option = document.createElement("option");
        option.value = i;
        option.textContent = i;
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

    ajax("GET", `/interets-par-mois?moisDebut=${md}&anneeDebut=${ad}&moisFin=${mf}&anneeFin=${af}`, null, (data) => {
      const tbody = document.getElementById("tableauStats");
      tbody.innerHTML = "";

      const labels = [], valeurs = [];

      data.forEach(row => {
        const moisNom = new Date(row.annee, row.mois - 1).toLocaleString('fr-FR', { month: 'long' });
        labels.push(`${moisNom} ${row.annee}`);
        valeurs.push(row.total_interet);
        tbody.innerHTML += `
          <tr>
            <td>${moisNom}</td>
            <td>${row.annee}</td>
            <td>${Number(row.total_interet).toLocaleString()} Ar</td>
          </tr>`;
      });

      afficherGraphique(labels, valeurs);
    });
  }

  // Afficher le graphique avec Chart.js
  function afficherGraphique(labels, data) {
    const ctx = document.getElementById('graphInterets').getContext('2d');
    if (window.chart) window.chart.destroy(); // Supprimer l’ancien si existant
    window.chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Intérêts gagnés',
          data: data,
          backgroundColor: '#3498db'
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  }

  // Lancer au chargement
  initSelects();
  chargerStats();
</script>

