<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../fpdf186/fpdf.php';

$idPret = isset($_GET['idPret']) ? intval($_GET['idPret']) : 0;
$idClient = isset($_GET['idClient']) ? intval($_GET['idClient']) : 0;

if (!$idPret || !$idClient) {
  die("Paramètres manquants");
}

try {
  $db = getDB();

  $stmt = $db->prepare("SELECT * FROM ef_suivipret WHERE idPret = ? AND idClient = ?");
  $stmt->execute([$idPret, $idClient]);
  $suivis = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (!$suivis) {
    die("Aucune donnée trouvée");
  }

  $pdf = new FPDF('L', 'mm', 'A4');
  $pdf->AddPage();

  // Titre
  $pdf->SetFont('Arial', 'B', 16);
  $pdf->SetTextColor(0, 51, 102); // bleu foncé
  $pdf->Cell(0, 12, "Suivi de pret - Client #$idClient", 0, 1, 'C');
  $pdf->Ln(4);

  // Couleurs et style en-tête
  $pdf->SetFillColor(173, 216, 230); // bleu clair
  $pdf->SetDrawColor(0, 51, 102);    // bleu foncé bordure
  $pdf->SetLineWidth(0.3);
  $pdf->SetFont('Arial', 'B', 11);
  $pdf->SetTextColor(0, 51, 102);

  $widths = [
    'date_debut'      => 30,
    'date_paiement'   => 30,
    'montant_attendu' => 35,
    'montant_paye'    => 35,
    'interet_paye'    => 35,
    'annuite'         => 30,
    'amortissement'   => 35,
    'interet_a_payer' => 35,
  ];

  // En-tête avec fond coloré
  $pdf->Cell($widths['date_debut'], 10, 'Date Debut', 1, 0, 'C', true);
  $pdf->Cell($widths['date_paiement'], 10, 'Date Paiement', 1, 0, 'C', true);
  $pdf->Cell($widths['montant_attendu'], 10, 'Montant Attendu', 1, 0, 'C', true);
  $pdf->Cell($widths['montant_paye'], 10, 'Paye', 1, 0, 'C', true);
  $pdf->Cell($widths['interet_paye'], 10, 'Interet Paye', 1, 0, 'C', true);
  $pdf->Cell($widths['annuite'], 10, 'Annuite', 1, 0, 'C', true);
  $pdf->Cell($widths['amortissement'], 10, 'Amortissement', 1, 0, 'C', true);
  $pdf->Cell($widths['interet_a_payer'], 10, "Interet a Payer", 1, 1, 'C', true);

  // Police des données et couleur du texte noir
  $pdf->SetFont('Arial', '', 10);
  $pdf->SetTextColor(0, 0, 0);

  // Zebra striping - alterner fond blanc et gris clair
  $fill = false;
  foreach ($suivis as $row) {
    $pdf->SetFillColor($fill ? 240 : 255, $fill ? 240 : 255, $fill ? 240 : 255);
    $pdf->Cell($widths['date_debut'], 8, $row['date_debut_pret'], 1, 0, 'C', $fill);
    $pdf->Cell($widths['date_paiement'], 8, $row['date_prevu_payement'], 1, 0, 'C', $fill);
    $pdf->Cell($widths['montant_attendu'], 8, number_format($row['montant_attendu'], 0, ',', ' ') . ' Ar', 1, 0, 'R', $fill);
    $pdf->Cell($widths['montant_paye'], 8, number_format($row['montant_paye'], 0, ',', ' ') . ' Ar', 1, 0, 'R', $fill);
    $pdf->Cell($widths['interet_paye'], 8, number_format($row['interet_paye'], 0, ',', ' ') . ' Ar', 1, 0, 'R', $fill);
    $pdf->Cell($widths['annuite'], 8, number_format($row['annuite'], 0, ',', ' ') . ' Ar', 1, 0, 'R', $fill);
    $pdf->Cell($widths['amortissement'], 8, number_format($row['amortissement'], 0, ',', ' ') . ' Ar', 1, 0, 'R', $fill);
    $pdf->Cell($widths['interet_a_payer'], 8, number_format($row['interet_a_payer'], 0, ',', ' ') . ' Ar', 1, 1, 'R', $fill);

    $fill = !$fill; // inverse la couleur pour la ligne suivante
  }

  $pdf->Output("I", "SuiviPret_Client{$idClient}_Pret{$idPret}.pdf");

} catch (Exception $e) {
  echo "Erreur : " . $e->getMessage();
}
