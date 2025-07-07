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
  </style>
</head>
<body>
  <h1 id="welcome">Bienvenue !</h1>

  <div class="card" id="resume">
    <p><strong>Solde disponible :</strong> <span id="solde">...</span> Ar</p>
    <p><strong>Montant restant :</strong> <span id="reste">...</span> Ar</p>
    <p><strong>Montant total prêt :</strong> <span id="total">...</span> Ar</p>
  </div>

  <h2>Liste des prêts</h2>
  <div id="prets-container"></div>

  <script>
    const apiBase = "http://localhost/ETU3209-ETU3111-ETU3118/ws";

    const user = JSON.parse(localStorage.getItem("user"));
    if (!user || !user.idClient) {
      alert("Non connecté !");
      window.location.href = "Login.php";
    }

    document.getElementById("welcome").textContent = `Bienvenue, ${user.nom} !`;

    function chargerDashboard() {
      fetch(`${apiBase}/dashboard/client/${user.idClient}`)
        .then(res => res.json())
        .then(data => {
          document.getElementById("solde").innerText = parseFloat(data.resume.solde_disponible).toLocaleString();
          document.getElementById("reste").innerText = parseFloat(data.resume.montant_restant).toLocaleString();
          document.getElementById("total").innerText = parseFloat(data.resume.montant_total_pret).toLocaleString();

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
              <tr>
                <th>Date début</th>
                <th>Payé</th>
                <th>Restant</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              ${types[type].map(p => `
                <tr>
                  <td>${p.date_debut_pret}</td>
                  <td>${parseFloat(p.montant_paye).toLocaleString()} Ar</td>
                  <td>${parseFloat(p.montant_restant).toLocaleString()} Ar</td>
                  <td><button onclick="ouvrirForm(${p.idTypePret}, '${type}')">Payé</button></td>
                </tr>
              `).join("")}
            </tbody>
          </table>
        `;
        container.appendChild(bloc);
      }
    }

    function ouvrirForm(idTypePret, typeLibelle) {
      const existing = document.getElementById("form-paiement");
      if (existing) existing.remove();

      const form = document.createElement("div");
      form.className = "form-container";
      form.id = "form-paiement";
      form.innerHTML = `
        <h4>Paiement pour : ${typeLibelle}</h4>
        <form onsubmit="soumettrePaiement(event, ${idTypePret})">
          <label>Date : <input type="date" id="date" required></label><br><br>
          <label>Montant : <input type="number" id="montant" required></label><br><br>
          <button type="submit">Valider paiement</button>
        </form>
      `;
      document.body.appendChild(form);
      form.scrollIntoView({ behavior: "smooth" });
    }

    function soumettrePaiement(e, idTypePret) {
      e.preventDefault();
      const date = document.getElementById("date").value;
      const montant = document.getElementById("montant").value;

      fetch(`${apiBase}/dashboard/pay`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          idClient: user.idClient,
          idTypePret: idTypePret,
          date: date,
          montant: montant
        })
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
