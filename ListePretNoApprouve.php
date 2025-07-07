<?php
session_start();

$value = false; // Par défaut

if (isset($_SESSION['idDepartement'])) {
    if ($_SESSION['idDepartement'] == 1) {
        $value = true; // Département Finance
    }
}
?>

<h2>Prêts en attente d'approbation</h2>

<table border="1" id="tablePrets">
  <thead>
    <tr>
      <th>Client</th>
      <th>Type de prêt</th>
      <th>Montant restant</th>
      <th>Date début</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<script>

 
   const apiBase = "http://localhost/ETU3209-ETU3111-ETU3118/ws";
   const roleAdminFinance = <?= json_encode($value) ?>;
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


function chargerPrets() {
  ajax("GET", "/pret/non-approuves", null, (data) => {
    const tbody = document.querySelector("#tablePrets tbody");
    tbody.innerHTML = "";
    data.forEach(pre => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${pre.client_nom}</td>
        <td>${pre.type_pret_libelle}</td>
        <td>${pre.montant_restant}</td>
        <td>${pre.date_debut_pret}</td>
        <td>
          ${roleAdminFinance ? `<button onclick="approuverPret(${pre.idPret})">Approuver</button>` : "En attente"}
        </td>
      `;
      tbody.appendChild(tr);
    });
  });
}

function approuverPret(idPret) {
  if (!confirm("Confirmer l'approbation du prêt ?")) return;
  
  ajax("PUT", `/pret/approuver/${idPret}`, null, (res) => {
    alert(res.message);
    chargerPrets();
  });
}

chargerPrets();
</script>
