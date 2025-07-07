<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des Fonds Financiers</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    input, button {
      padding: 6px;
      margin: 5px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: center;
    }
    th {
      background-color: #f4f4f4;
    }
  </style>
</head>
<body>
  <h2>Ajouter un Fond Financier</h2>
  <form onsubmit="ajouterFondFinancier(); return false;">

  <label>Date de création :</label>
  <input type="date" id="date_creation" required><br>
  <label>Solde initial :</label>
  <input type="number" id="solde_initiale" step="0.01" required><br>

  <button type="submit">Enregistrer</button>
</form>
  </form>

  <h2>Liste des Fonds Financiers</h2>
  <table>
    <thead>
      <tr>
        <th>Année</th>
        <th>Solde Initiale</th>
        <th>Solde Finale</th>
        <th>Solde en Cours</th>
        <th>Date de Création</th>
      </tr>
    </thead>
    <tbody id="table-fond">
      <!-- lignes générées -->
    </tbody>
  </table>

  <script>
    const apiBase = "http://localhost/ETU3209-ETU3111-ETU3118/ws";

    function ajax(method, url, data, callback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            callback(JSON.parse(xhr.responseText));
          } else {
            alert("Erreur AJAX " + xhr.status + ": " + xhr.responseText);
          }
        }
      };
      xhr.send(data);
    }

    function ajouterFondFinancier() {
      const date = document.getElementById("date_creation").value;
        const initiale = document.getElementById("solde_initiale").value;
        

  const data = `date_creation=${encodeURIComponent(date)}&solde_initiale=${initiale}`;

        ajax("POST", "/fond-financier", data, () => {
    chargerFonds();
  });
  }
    function chargerFonds() {
      ajax("GET", "/fond-financier", null, function (fonds) {
        const tbody = document.getElementById("table-fond");
        tbody.innerHTML = "";
        fonds.forEach(f => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${f.annee}</td>
            <td>${Number(f.solde_initiale).toFixed(2)} Ar</td>
            <td>${Number(f.solde_final || 0).toFixed(2)} Ar</td>
            <td>${Number(f.solde_en_cours || 0).toFixed(2)} Ar</td>
            <td>${f.date_creation}</td>
          `;
          tbody.appendChild(row);
        });
      });
    }

    // charger les fonds à l'ouverture
    chargerFonds();
  </script>
</body>
</html>
