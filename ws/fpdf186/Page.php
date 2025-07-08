<?php
require('fpdf.php'); // Inclure la bibliothèque FPDF

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'universite');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Créer une classe PDF qui étend FPDF
class PDF extends FPDF {
    // En-tête du document
    function Header() {
        $this->
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'RELEVE DE NOTES ET RESULTATS', 0, 1, 'C');
        $this->Ln(10);
    }

    // Pied de page du document
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // Tableau des notes
    function TableHeader() {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 6, 'UE', 1, 0, 'C');
        $this->Cell(70, 6, 'Intitule', 1, 0, 'C');
        $this->Cell(20, 6, 'Credits', 1, 0, 'C');
        $this->Cell(30, 6, 'Note/20', 1, 0, 'C');
        $this->Cell(30, 6, 'Resultat', 1, 1, 'C');
    }

    function TableRow($ue, $intitule, $credits, $note, $resultat) {
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, 6, $ue, 1, 0, 'C');
        $this->Cell(70, 6, $intitule, 1, 0, 'C');
        $this->Cell(20, 6, $credits, 1, 0, 'C');
        $this->Cell(30, 6, $note, 1, 0, 'C');
        $this->Cell(30, 6, $resultat, 1, 1, 'C');
    }
}

// Instancier un objet PDF
$pdf = new PDF();
$pdf->AddPage();

// Récupérer les données de l'étudiant
$etudiant_id = 1; // ID de l'étudiant
$sql_etudiant = "SELECT * FROM etudiants WHERE id = $etudiant_id";
$result_etudiant = $conn->query($sql_etudiant);
$etudiant = $result_etudiant->fetch_assoc();

// Afficher les informations de l'étudiant
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 7, 'Nom: ' . $etudiant['nom'], 0, 1);
$pdf->Cell(0, 7, 'Prenom(s): ' . $etudiant['prenom'], 0, 1);
$pdf->Cell(0, 7, 'Ne le: ' . $etudiant['date_naissance'], 0, 1);
$pdf->Cell(0, 7, 'N° d\'inscription: ' . $etudiant['matricule'], 0, 1);
$pdf->Cell(0, 7, 'Inscrit en: ' . $etudiant['filiere'], 0, 1);
$pdf->Ln(10);

// Récupérer les notes de l'étudiant
$sql_notes = "SELECT n.matiere_id, m.nom_matiere, m.credit, n.note, n.resultat, s.nom as semestre 
              FROM notes n 
              JOIN matieres m ON n.matiere_id = m.id 
              JOIN semestre s ON n.semestre_id = s.id 
              WHERE n.etudiant_id = $etudiant_id";
$result_notes = $conn->query($sql_notes);

// Afficher les notes par semestre
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Semestre 1', 0, 1);
$pdf->TableHeader();
while ($row = $result_notes->fetch_assoc()) {
    if ($row['semestre'] == 'Semestre 1') {
        $pdf->TableRow($row['matiere_id'], $row['nom_matiere'], $row['credit'], $row['note'], $row['resultat']);
    }
}
$pdf->Ln(2);

$pdf->Cell(0, 10, 'Semestre 2', 0, 1);
$pdf->TableHeader();
$result_notes->data_seek(0); // Réinitialiser le pointeur du résultat
while ($row = $result_notes->fetch_assoc()) {
    if ($row['semestre'] == 'Semestre 2') {
        $pdf->TableRow($row['matiere_id'], $row['nom_matiere'], $row['credit'], $row['note'], $row['resultat']);
    }
}
$pdf->Ln(10);

// Calculer la moyenne générale et les crédits
$sql_moyenne = "SELECT AVG(note) as moyenne, SUM(credit) as credits 
                FROM notes n 
                JOIN matieres m ON n.matiere_id = m.id 
                WHERE n.etudiant_id = $etudiant_id";
$result_moyenne = $conn->query($sql_moyenne);
$moyenne = $result_moyenne->fetch_assoc();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Resultat general: Credits: ' . $moyenne['credits'], 0, 1);
$pdf->Cell(0, 10, 'Moyenne generale: ' . number_format($moyenne['moyenne'], 2), 0, 1);
$pdf->Cell(0, 10, 'Mention: Passable', 0, 1);
$pdf->Cell(0, 10, 'ADMIS', 0, 1);
$pdf->Cell(0, 10, 'Session: 08/2016', 0, 1);
$pdf->setX(130);
$pdf->Cell(50, 10, 'Fait a Antananarivo, le 12/09/2016', 0, 1);
$pdf->setX(130);
$pdf->Cell(50, 10, 'Le Recteur de l\'IT University', 0, 1);

// Fermer la connexion à la base de données
$conn->close();

// Générer le PDF
$pdf->Output();
?>