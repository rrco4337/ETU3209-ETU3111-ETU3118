<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nouvelle Demande de Prêt | Banque Horizon</title>
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
    }
    .card-hover {
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .card-hover:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="min-h-screen bg-gray-100">

  <?php include('sidebar.php'); ?>

  <div class="ml-64 p-8"> <!-- Ajustez la marge selon votre sidebar -->
    <div class="max-w-4xl mx-auto">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">
          <i class="fas fa-hand-holding-usd text-blue-600 mr-2"></i>
          Nouvelle Demande de Prêt
        </h1>
      </div>

      <!-- Client Selection Card -->
      <div class="card bg-white shadow-sm card-hover mb-6">
        <div class="card-body">
          <h2 class="card-title text-lg font-semibold mb-4">
            <i class="fas fa-user text-blue-500 mr-2"></i>
            Sélection du Client
          </h2>
          <select id="idClient" class="select select-bordered w-full">
            <option value="" disabled selected>Choisir un client...</option>
          </select>
        </div>
      </div>

      <!-- Loan Type Selection Card -->
      <div class="card bg-white shadow-sm card-hover mb-6">
        <div class="card-body">
          <h2 class="card-title text-lg font-semibold mb-4">
            <i class="fas fa-home text-blue-500 mr-2"></i>
            Type de Prêt
          </h2>
          <select id="idTypePret" class="select select-bordered w-full">
            <option value="" disabled selected>Sélectionner un type de prêt...</option>
          </select>
          <div class="mt-4 text-sm text-gray-600" id="loanDetails">
            <!-- Les détails du prêt apparaîtront ici -->
          </div>
        </div>
      </div>

      <!-- Action Card -->
      <div class="card bg-white shadow-sm">
        <div class="card-body">
          <button onclick="ajouterPret()" class="btn btn-bank text-white w-full py-3">
            <i class="fas fa-paper-plane mr-2"></i>
            Envoyer la Demande
          </button>
          <div id="message" class="mt-4 text-center"></div>
        </div>
      </div>
    </div>
  </div>

  <?php include('footer.php'); ?>

  <script>
    const apiBase = "http://localhost/tp-flightphp-crud/ws";

    function ajax(method, url, data, callback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            callback(JSON.parse(xhr.responseText));
          } else {
            showMessage("Vous ne pouvez pas souscrire au prêt choisi", "error");
          }
        }
      };
      xhr.send(data);
    }

    function chargerClients() {
      ajax("GET", "/client", null, data => {
        const select = document.getElementById("idClient");
        data.forEach(c => {
          select.innerHTML += `<option value="${c.idClient}">${c.nom} (ID: ${c.idClient})</option>`;
        });
      });
    }

    function chargerTypesPret() {
      ajax("GET", "/type-pret", null, data => {
        const select = document.getElementById("idTypePret");
        data.forEach(p => {
          select.innerHTML += `<option value="${p.idType}" 
                              data-montant="${p.montant}" 
                              data-duree="${p.duree_mois_max}">
                              ${p.libelle}
                            </option>`;
        });

        // Afficher les détails quand un prêt est sélectionné
        document.getElementById("idTypePret").addEventListener("change", function() {
          const selected = this.options[this.selectedIndex];
          if (selected.value) {
            document.getElementById("loanDetails").innerHTML = `
              <div class="grid grid-cols-2 gap-4 mt-2">
                <div>
                  <span class="font-medium">Montant:</span>
                  <span>${selected.getAttribute("data-montant")} Ar</span>
                </div>
                <div>
                  <span class="font-medium">Durée max:</span>
                  <span>${selected.getAttribute("data-duree")} mois</span>
                </div>
              </div>
            `;
          }
        });
      });
    }

    function ajouterPret() {
      const idClient = document.getElementById("idClient").value;
      const idTypePret = document.getElementById("idTypePret").value;

      if (!idClient || !idTypePret) {
        showMessage("Veuillez sélectionner un client et un type de prêt", "error");
        return;
      }

      showMessage("Traitement de votre demande...", "info");

      const data = `idClient=${idClient}&idTypePret=${idTypePret}`;

      ajax("POST", "/pret", data, (res) => {
        showMessage(res.message, "success");
        // Réinitialiser les sélections
        document.getElementById("idClient").value = "";
        document.getElementById("idTypePret").value = "";
        document.getElementById("loanDetails").innerHTML = "";
      });
    }

    function showMessage(text, type) {
      const messageDiv = document.getElementById("message");
      messageDiv.textContent = text;
      
      // Reset classes
      messageDiv.className = "mt-4 text-center p-3 rounded-lg";
      
      // Add type-specific classes
      if (type === "error") {
        messageDiv.classList.add("bg-red-100", "text-red-700");
      } else if (type === "success") {
        messageDiv.classList.add("bg-green-100", "text-green-700");
      } else {
        messageDiv.classList.add("bg-blue-100", "text-blue-700");
      }
    }

    // Chargement initial
    document.addEventListener('DOMContentLoaded', () => {
      chargerClients();
      chargerTypesPret();
    });
  </script>
</body>
</html>