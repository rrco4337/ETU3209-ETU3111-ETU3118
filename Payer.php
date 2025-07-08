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
<h2>Paiement du prêt : <?php echo htmlspecialchars($typePret); ?></h2>

<form id="formPaiement" onsubmit="envoyerPaiement(event)">
  <label for="datePaiement">Date de paiement :</label>
  <input type="date" id="datePaiement" name="datePaiement" required>
  <button type="submit">Payer</button>
</form>

<script>
  const apiBase = "http://localhost/ETU3209-ETU3111-ETU3118/ws";
  const idPret = <?php echo json_encode($idPret); ?>;
  const idClient = <?php echo json_encode($client ?? 0); ?>;

  function envoyerPaiement(event) {
    event.preventDefault();

    const datePaiement = document.getElementById('datePaiement').value;

    if (!datePaiement) {
      alert("Veuillez sélectionner une date.");
      return;
    }
  console.log({ idClient, idPret, datePaiement });
  
fetch(`${apiBase}/pret/payer`, {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({ idClient, idPret, datePaiement })
})
.then(async res => {
  const text = await res.text();
  try {
    const data = JSON.parse(text);
    if (data.error) alert("Erreur: " + data.message);
    else {
      alert("Paiement enregistré !");
      location.reload();
    }
  } catch(e) {
    console.error("Réponse non JSON:", text);
    alert("Erreur serveur inattendue. Voir console.");
  }
})

.catch(err => {
  alert("Erreur réseau.");
  console.error(err);
});
 }
</script>
