<h2>Nouvelle Demande de Prêt</h2>
<form onsubmit="ajouterPret(); return false;">
  <label>Client:</label>
  <select id="idClient"></select><br>

  <label>Type de prêt:</label>
  <select id="idTypePret"></select><br>

  <button type="submit">Soumettre</button>
</form>

<div id="message"></div>

<script>
   
  function ajax(method, url, data, callback) {
  const xhr = new XMLHttpRequest();
  xhr.open(method, "http://localhost/ETU3209-ETU3111-ETU3118/ws" + url, true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        callback(JSON.parse(xhr.responseText));
      } else {
        document.getElementById("message").innerText = "Vous ne pouvez pas souscrire au prêt choisi " ;
      }
    }
  };
  xhr.send(data);
}
function chargerClients() {
  ajax("GET", "/clients", null, data => {
    const select = document.getElementById("idClient");
    data.forEach(c => {
      select.innerHTML += `<option value="${c.idClient}">${c.nom}</option>`;
    });
  });
}

function chargerTypesPret() {
  ajax("GET", "/type-pret", null, data => {
    const select = document.getElementById("idTypePret");
    data.forEach(p => {
      select.innerHTML += `<option value="${p.idType}">${p.libelle} (${p.montant} Ar / ${p.duree_mois_max} mois)</option>`;
    });
  });
}

function ajouterPret() {
  const idClient = document.getElementById("idClient").value;
  const idTypePret = document.getElementById("idTypePret").value;

  const data = `idClient=${idClient}&idTypePret=${idTypePret}`;

  ajax("POST", "/pret", data, (res) => {
    document.getElementById("message").innerText = res.message;
  });
}

chargerClients();
chargerTypesPret();
</script>
