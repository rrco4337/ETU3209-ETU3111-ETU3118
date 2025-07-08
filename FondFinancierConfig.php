<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Fonds Financiers | Banque Horizon</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@1.14.2/dist/full.css" rel="stylesheet" type="text/css" />
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
        .table-container {
            overflow-x: auto;
        }
        .table-zebra tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600"></div>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 rounded-full bg-gradient-bank flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-2xl font-bold text-gray-800">Horizon Banque</span>
            </div>
            <div class="text-sm text-gray-600">
                <span id="current-date"></span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card shadow-xl bg-white mb-8">
            <div class="card-body p-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Gestion des Fonds Financiers</h1>
                
                <!-- Ajout de fond -->
                <div class="mb-10 p-6 bg-gray-50 rounded-lg border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Ajouter un nouveau fond</h2>
                    <form onsubmit="ajouterFondFinancier(); return false;" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-control">
                                <label class="label" for="date_creation">
                                    <span class="label-text text-gray-700 font-medium">Date de création</span>
                                </label>
                                <input type="date" id="date_creation" required 
                                       class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div class="form-control">
                                <label class="label" for="solde_initiale">
                                    <span class="label-text text-gray-700 font-medium">Solde initial (Ar)</span>
                                </label>
                                <input type="number" id="solde_initiale" step="0.01" required 
                                       class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary btn-bank text-white px-6 py-3 rounded-lg font-semibold shadow-md">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Liste des fonds -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Liste des fonds financiers</h2>
                    <div class="table-container">
                        <table class="table-auto w-full table-zebra">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-gray-700 font-medium">Année</th>
                                    <th class="px-4 py-3 text-right text-gray-700 font-medium">Solde Initiale</th>
                                    <th class="px-4 py-3 text-right text-gray-700 font-medium">Solde Finale</th>
                                    <th class="px-4 py-3 text-right text-gray-700 font-medium">Solde en Cours</th>
                                    <th class="px-4 py-3 text-left text-gray-700 font-medium">Date de Création</th>
                                </tr>
                            </thead>
                            <tbody id="table-fond" class="divide-y divide-gray-200">
                                <!-- lignes générées -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-xs text-gray-500">
                &copy; 2023 Horizon Banque. Tous droits réservés.
            </p>
        </div>
    </div>

    <script>
        const apiBase = "http://localhost/ETU3209-ETU3111-ETU3118/ws";

        // Afficher la date actuelle
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('fr-FR', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

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
                // Réinitialiser le formulaire
                document.getElementById("date_creation").value = '';
                document.getElementById("solde_initiale").value = '';
                
                // Recharger la liste
                chargerFonds();
            });
        }

        function chargerFonds() {
            ajax("GET", "/fond-financier", null, function (fonds) {
                const tbody = document.getElementById("table-fond");
                tbody.innerHTML = "";
                
                fonds.forEach(f => {
                    const row = document.createElement("tr");
                    row.className = "hover:bg-gray-50";
                    row.innerHTML = `
                        <td class="px-4 py-3 text-gray-700">${f.annee}</td>
                        <td class="px-4 py-3 text-right font-medium text-gray-700">${Number(f.solde_initiale).toFixed(2)} Ar</td>
                        <td class="px-4 py-3 text-right font-medium ${f.solde_final >= 0 ? 'text-green-600' : 'text-red-600'}">${Number(f.solde_final || 0).toFixed(2)} Ar</td>
                        <td class="px-4 py-3 text-right font-medium ${f.solde_en_cours >= 0 ? 'text-green-600' : 'text-red-600'}">${Number(f.solde_en_cours || 0).toFixed(2)} Ar</td>
                        <td class="px-4 py-3 text-gray-700">${f.date_creation}</td>
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