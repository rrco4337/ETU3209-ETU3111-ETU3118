<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de Bord Client</title>
  <style>
    body { font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; background-color: #f4f7f9; color: #333; margin: 0; padding: 20px; }
    h1, h2 { color: #2c3e50; }
    .dashboard-container { max-width: 1200px; margin: auto; }
    .summary-cards { display: flex; gap: 20px; justify-content: space-around; flex-wrap: wrap; margin-bottom: 40px; }
    .card { background-color: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 25px; text-align: center; flex-grow: 1; }
    .card h3 { margin-top: 0; color: #3498db; }
    .card .value { font-size: 2.5em; font-weight: bold; color: #2c3e50; }
    .details-section { display: flex; gap: 30px; flex-wrap: wrap; }
    .details-block { background-color: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); padding: 25px; flex-basis: 48%; flex-grow: 1; }
    #offres-table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    #offres-table th, #offres-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    #offres-table th { background-color: #3498db; color: white; }
    #offres-table tr:nth-child(even) { background-color: #f2f2f2; }
    .loader { text-align: center; font-style: italic; color: #7f8c8d; }
  </style>
</head>
<body>

  <div class="dashboard-container">
    <h1>Tableau de Bord de Jean Dupont (Client #1)</h1>

    <!-- Section Résumé -->
    <div class="summary-cards">
      <div class="card">
        <h3>Solde en cours</h3>
        <p class="value" id="solde-client">Chargement...</p>
      </div>
      <div class="card">
        <h3>Reste à Payer (Prêt)</h3>
        <p class="value" id="reste-a-payer">Chargement...</p>
      </div>
      <div class="card">
        <h3>Montant Total du Prêt</h3>
        <p class="value" id="total-pret">Chargement...</p>
      </div>
    </div>

    <div class="details-section">
      <!-- Section Prêt en cours -->
      <div class="details-block">
        <h2>Votre Prêt en Cours</h2>
        <div id="pret-en-cours-details">
          <p class="loader">Chargement des informations du prêt...</p>
        </div>
      </div>

      <!-- Section Offres disponibles -->
      <div class="details-block">
        <h2>Nos Offres de Prêt</h2>
        <div id="offres-pret-container">
          <p class="loader">Chargement des offres...</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    // --- Configuration ---
    const apiBase = "http://localhost/tp-flightphp-crud/ws"; // Adaptez avec l'URL de votre API
    const clientId = 2; // ID du client à afficher. Dans une vraie app, il viendrait d'une session de login.

    // --- Fonctions Utilitaires ---
    function ajax(method, url, callback, errorCallback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
          if (xhr.status >= 200 && xhr.status < 300) {
            callback(JSON.parse(xhr.responseText));
          } else {
            if (errorCallback) errorCallback(xhr);
          }
        }
      };
      xhr.send();
    }

    function formatCurrency(value) {
        if (value === null || isNaN(value)) {
            return "N/A";
        }
        return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(value);
    }

    // --- Fonctions de Chargement des Données ---

    // 1. Charger le résumé (les 3 cartes)
    function chargerResume() {
      ajax("GET", `/client/${clientId}/summary`, (data) => {
        document.getElementById("solde-client").textContent = formatCurrency(data.solde_client);
        document.getElementById("reste-a-payer").textContent = formatCurrency(data.montant_restant);
        document.getElementById("total-pret").textContent = formatCurrency(data.montant_total_pret);
      }, () => {
         document.getElementById("solde-client").textContent = "Erreur";
         document.getElementById("reste-a-payer").textContent = "Erreur";
         document.getElementById("total-pret").textContent = "Erreur";
      });
    }

    // 2. Charger les détails du prêt en cours
    function chargerPretEnCours() {
      ajax("GET", `/client/${clientId}/current-loan`, (data) => {
        const container = document.getElementById("pret-en-cours-details");
        if (Object.keys(data).length === 0) {
          container.innerHTML = "<p>Vous n'avez aucun prêt en cours.</p>";
          return;
        }
        container.innerHTML = `
          <p><strong>Type de prêt :</strong> ${data.type_pret_libelle}</p>
          <p><strong>Date de début :</strong> ${new Date(data.date_debut_pret).toLocaleDateString('fr-FR')}</p>
          <p><strong>Montant déjà payé :</strong> ${formatCurrency(data.montant_paye)}</p>
          <p><strong>Montant restant à payer :</strong> <strong style="color: #e74c3c;">${formatCurrency(data.montant_restant)}</strong></p>
        `;
      }, () => {
         const container = document.getElementById("pret-en-cours-details");
         container.innerHTML = "<p>Erreur lors du chargement du prêt.</p>";
      });
    }

    // 3. Charger les offres de prêt disponibles
    function chargerOffres() {
      ajax("GET", "/loan-offers", (data) => {
        const container = document.getElementById("offres-pret-container");
        if (data.length === 0) {
          container.innerHTML = "<p>Aucune offre de prêt disponible pour le moment.</p>";
          return;
        }
        
        let tableHTML = `
          <table id="offres-table">
            <thead>
              <tr>
                <th>Libellé</th>
                <th>Montant Max</th>
                <th>Taux</th>
                <th>Durée Max</th>
              </tr>
            </thead>
            <tbody>`;

        data.forEach(offre => {
          tableHTML += `
            <tr>
              <td>${offre.libelle}</td>
              <td>${formatCurrency(offre.montant)}</td>
              <td>${offre.taux} %</td>
              <td>${offre.duree_mois_max} mois</td>
            </tr>
          `;
        });

        tableHTML += `</tbody></table>`;
        container.innerHTML = tableHTML;
      }, () => {
        const container = document.getElementById("offres-pret-container");
        container.innerHTML = "<p>Erreur lors du chargement des offres.</p>";
      });
    }

    // --- Initialisation du Tableau de Bord ---
    document.addEventListener("DOMContentLoaded", () => {
      chargerResume();
      chargerPretEnCours();
      chargerOffres();
    });
  </script>

</body>
</html>