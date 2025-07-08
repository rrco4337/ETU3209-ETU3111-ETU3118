<!-- dashboard_client.html -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Client</title>
  <style>
    body { font-family: Arial; padding: 20px; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
    .card { padding: 10px; border: 1px solid #aaa; margin-bottom: 20px; }
    .form-container { display: none; margin-top: 10px; border: 1px solid #aaa; padding: 10px; }
    .btn-ajout-solde { margin-left: 10px; }
  </style>
</head>
<body>
  <h1 id="welcome">Bienvenue !</h1>

  <div class="card" id="resume">
    <p><strong>Solde disponible :</strong> 
      <span id="solde">...Ar</span>
      <button id="btn-ajout-solde" class="btn-ajout-solde">Ajouter Solde</button>
    </p>
    <p><strong>Montant restant :</strong> <span id="reste">...</span> Ar</p>
    <p><strong>Montant total prêt :</strong> <span id="total">...</span> Ar</p>
  </div>

  <h2>Liste des prêts</h2>
  <div id="prets-container"></div>

  <!-- Formulaire d'ajout solde, caché par défaut -->
  <div class="form-container" id="form-ajout-solde">
    <h4>Ajouter un solde</h4>
    <form id="form-solde">
      <label>Montant : <input type="number" id="montant-solde" required></label><br><br>
      <button type="submit">Valider</button>
      <button type="button" id="btn-annuler">Annuler</button>
    </form>
  </div>

  <script>
    const apiBase = "http://localhost/ETU3209-ETU3111-ETU3118/ws";

    const user = JSON.parse(localStorage.getItem("user"));
    if (!user || !user.idClient) {
      alert("Non connecté !");
      window.location.href = "Login.php";
    }

    document.getElementById("welcome").textContent = `Bienvenue, ${user.nom} !`;

    const btnAjoutSolde = document.getElementById("btn-ajout-solde");
    const formAjoutSolde = document.getElementById("form-ajout-solde");
    const btnAnnuler = document.getElementById("btn-annuler");

    btnAjoutSolde.onclick = function() {
      formAjoutSolde.style.display = "block";
      formAjoutSolde.scrollIntoView({ behavior: "smooth" });
    };

    btnAnnuler.onclick = function() {
      formAjoutSolde.style.display = "none";
    };

    document.getElementById("form-solde").onsubmit = function(event) {
      event.preventDefault();

      const montant = parseFloat(document.getElementById("montant-solde").value);
      if (isNaN(montant) || montant <= 0) {
        alert("Veuillez entrer un montant valide.");
        return;
      }

      fetch(`${apiBase}/dashboard/update-solde`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ idClient: user.idClient, montant })
      })
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          alert("Erreur : " + data.message);
        } else {
          alert("Solde ajouté avec succès !");
          formAjoutSolde.style.display = "none";
          chargerDashboard();
        }
      })
      .catch(err => {
        console.error(err);
        alert("Une erreur est survenue lors de l'ajout du solde.");
      });
    };

    function chargerDashboard() {
      fetch(`${apiBase}/dashboard/client/${user.idClient}`)
        .then(res => res.json())
        .then(data => {
          const soldeDisponible = parseFloat(data.resume.solde_disponible || 0);
          document.getElementById("solde").innerText = soldeDisponible.toLocaleString('fr-FR');

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

          document.getElementById("reste").innerText = montantRestant.toLocaleString();
          document.getElementById("total").innerText = montantTotalPret.toLocaleString();

          afficherPrets(data.pretsParType);
        });
    }

    function afficherPrets(prets) {
  const container = document.getElementById("prets-container");
  container.innerHTML = "";
  const types = {};

  prets.forEach(pret => {
    if (!types[pret.type_pret]) types[pret.type_pret] = [];
    types[pret.type_pret].push(pret);
  });

  for (const type in types) {
    const bloc = document.createElement("div");
    bloc.innerHTML = `
      <h3>${type}</h3>
      <table>
        <thead>
          <tr><th>Date début</th><th>Payé</th><th>Restant</th><th>Action</th></tr>
        </thead>
        <tbody>
          ${types[type].map(p => `
            <tr>
              <td>${p.date_debut_pret}</td>
              <td>${parseFloat(p.montant_paye).toLocaleString()} Ar</td>
              <td>${parseFloat(p.montant_restant).toLocaleString()} Ar</td>
              <td><button onclick="ouvrirForm(${p.idPret}, '${type}')">Payé</button></td>
            </tr>`).join("")}
        </tbody>
      </table>
    `;
    container.appendChild(bloc);
  }
}

    function ouvrirForm(idPret, typeLibelle) {
  window.location.href = `Payer.php?idPret=${idPret}&type=${encodeURIComponent(typeLibelle)}`;
}

    function soumettrePaiement(e, idTypePret) {
      e.preventDefault();
      const date = document.getElementById("date").value;
      const montant = document.getElementById("montant").value;

      fetch(`${apiBase}/dashboard/pay`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ idClient: user.idClient, idTypePret, date, montant })
      })
      .then(res => res.json())
      .then(data => {
        alert("Paiement enregistré !");
        document.getElementById("form-paiement").remove();
        chargerDashboard();
      });
    }

    chargerDashboard();
  </script>
</body>
</html>
