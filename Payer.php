<?php
  $idPret = $_GET['idPret'] ?? 0;
  $typePret = $_GET['type'] ?? '';
  session_start();
  $client = $_SESSION['idClient'] ?? null;
  if (!$client) {
    header("Location: Login.php");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paiement de prêt | Banque Horizon</title>
  <script src="https://unpkg.com/htmlincludejs"></script>
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
    .animate-fade-in {
      animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .loan-header {
      border-bottom: 2px solid #e2e8f0;
      padding-bottom: 1rem;
      margin-bottom: 1.5rem;
    }
  </style>
</head>
<body class="min-h-screen bg-gray-50">
  <!-- Header -->
  <?php include('sidebar.php'); ?>

  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">
    <div class="card bg-white shadow-xl max-w-2xl mx-auto animate-fade-in">
      <div class="card-body">
        <div class="loan-header">
          <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-money-bill-wave mr-2 text-blue-600"></i>
            Paiement de prêt
          </h1>
          <p class="text-lg text-gray-600 mt-2">
            Type: <span class="font-semibold"><?php echo htmlspecialchars($typePret); ?></span>
          </p>
        </div>

        <form id="formPaiement" onsubmit="envoyerPaiement(event)" class="space-y-6">
          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">Date de paiement</span>
            </label>
            <input type="date" id="datePaiement" name="datePaiement" 
                   class="input input-bordered input-bank w-full" required>
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text font-medium">Montant à payer</span>
            </label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Ar</span>
              <input type="number" id="montantPaiement" placeholder="Entrez le montant" step="1000"
                     class="input input-bordered input-bank w-full pl-10" required>
            </div>
          </div>

          <div class="form-control mt-8">
            <button type="submit" class="btn btn-bank text-white">
              <i class="fas fa-check-circle mr-2"></i> Valider le paiement
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmation-modal">
      <div class="modal-box">
        <h3 class="font-bold text-lg">
          <i class="fas fa-check-circle mr-2 text-blue-600"></i>
          Confirmer le paiement
        </h3>
        <p class="py-4" id="confirmation-text">Êtes-vous sûr de vouloir effectuer ce paiement ?</p>
        <div class="modal-action">
          <button id="btn-cancel" class="btn btn-outline">Annuler</button>
          <button id="btn-confirm" class="btn btn-bank text-white">
            <i class="fas fa-check mr-2"></i> Confirmer
          </button>
        </div>
      </div>
    </div>
  </main>

  <?php include('footer.php'); ?>

  <script>
    const apiBase = "http://localhost/ETU3209-ETU3111-ETU3118/ws";
    const idPret = <?php echo json_encode($idPret); ?>;
    const idClient = <?php echo json_encode($client ?? 0); ?>;
    const typePret = <?php echo json_encode($typePret); ?>;

    // Pré-remplir la date du jour par défaut
    document.addEventListener('DOMContentLoaded', () => {
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('datePaiement').value = today;
    });

    function showConfirmationModal() {
      const date = document.getElementById('datePaiement').value;
      const montant = document.getElementById('montantPaiement').value;
      
      if (!date || !montant) {
        showNotification('Veuillez remplir tous les champs', 'warning');
        return;
      }

      document.getElementById('confirmation-text').innerHTML = `
        <p>Vous êtes sur le point d'effectuer un paiement de :</p>
        <p class="text-xl font-bold my-2">${Number(montant).toLocaleString('fr-FR')} Ar</p>
        <p>pour votre prêt <strong>${typePret}</strong> le ${formatDate(date)}.</p>
      `;
      
      document.getElementById('confirmation-modal').classList.add('modal-open');
    }

    function envoyerPaiement(event) {
      event.preventDefault();
      showConfirmationModal();
    }

    // Gestion des événements modaux
    document.getElementById('btn-cancel').onclick = function() {
      document.getElementById('confirmation-modal').classList.remove('modal-open');
    };

    document.getElementById('btn-confirm').onclick = function() {
      const btn = this;
      const originalHtml = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
      btn.disabled = true;
      
      const datePaiement = document.getElementById('datePaiement').value;
      const montantPaiement = document.getElementById('montantPaiement').value;

      fetch(`${apiBase}/pret/payer`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
          idClient, 
          idPret, 
          datePaiement,
          montant: montantPaiement 
        })
      })
      .then(async res => {
        const text = await res.text();
        try {
          const data = JSON.parse(text);
          if (data.error) {
            showNotification('Erreur: ' + data.message, 'error');
          } else {
            showNotification('Paiement enregistré avec succès !', 'success');
            setTimeout(() => {
              window.location.href = 'dashboard_client.php';
            }, 1500);
          }
        } catch(e) {
          console.error("Réponse non JSON:", text);
          showNotification('Erreur serveur inattendue', 'error');
        }
      })
      .catch(err => {
        console.error(err);
        showNotification('Erreur réseau', 'error');
      })
      .finally(() => {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
        document.getElementById('confirmation-modal').classList.remove('modal-open');
      });
    };

    // Fonctions utilitaires
    function formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('fr-FR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
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
  </script>
</body>
</html>