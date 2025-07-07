<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Banque Horizon - Navigation</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@1.14.2/dist/full.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .bg-gradient-bank {
      background: linear-gradient(135deg, #317AC1 0%, #1E3A8A 100%);
    }
    .nav-link {
      position: relative;
      padding: 0.75rem 1.25rem;
      transition: all 0.3s ease;
      font-size: 1rem;
    }
    .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 2px;
      background: white;
      transition: width 0.3s ease;
    }
    .nav-link:hover::after {
      width: 70%;
    }
    .nav-link.active::after {
      width: 70%;
    }
    .dropdown-menu {
      opacity: 0;
      visibility: hidden;
      transform: translateY(10px);
      transition: all 0.3s ease;
    }
    .dropdown:hover .dropdown-menu {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }
    .mobile-menu {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.5s ease-out;
    }
    .mobile-menu.open {
      max-height: 500px;
    }
    .navbar-thick {
      min-height: 80px;
    }
    .logo-large {
      width: 3rem;
      height: 3rem;
    }
    .user-avatar-large {
      width: 2.5rem;
      height: 2.5rem;
    }
  </style>
</head>
<body>
  <!-- Navbar Premium -->
  <nav class="bg-gradient-bank text-white shadow-xl sticky top-0 z-50 navbar-thick">
    <div class="container mx-auto px-4">
      <!-- Desktop Navbar -->
      <div class="hidden md:flex items-center justify-between py-5">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
          <div class="logo-large rounded-full bg-white flex items-center justify-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <span class="text-2xl font-bold">Horizon Banque</span>
        </div>

        <!-- Menu Principal -->
        <div class="flex space-x-2">
          <a href="#" class="nav-link active flex items-center">
            <i class="fas fa-home mr-2"></i> Accueil
          </a>
          
          <div class="dropdown relative">
            <a href="#" class="nav-link flex items-center">
              <i class="fas fa-user mr-2"></i> Comptes <i class="fas fa-chevron-down ml-1 text-xs"></i>
            </a>
            <div class="dropdown-menu absolute left-0 mt-2 w-48 bg-white text-gray-800 rounded-md shadow-xl py-1 z-50">
              <a href="TypePretConfig.php" class="block px-4 py-2 hover:bg-blue-50 hover:text-blue-600">
                <i class="fas fa-piggy-bank mr-2"></i> Type prêt financier
              </a>
              <a href="FondFinancierConfig.php" class="block px-4 py-2 hover:bg-blue-50 hover:text-blue-600">
                <i class="fas fa-coins mr-2"></i> fond financier
              </a>
              <a href="#" class="block px-4 py-2 hover:bg-blue-50 hover:text-blue-600">
                <i class="fas fa-business-time mr-2"></i> Comptes Professionnels
              </a>
            </div>
          </div>
          
          <div class="dropdown relative">
            <a href="#" class="nav-link flex items-center">
              <i class="fas fa-hand-holding-usd mr-2"></i> Prêts <i class="fas fa-chevron-down ml-1 text-xs"></i>
            </a>
            <div class="dropdown-menu absolute left-0 mt-2 w-48 bg-white text-gray-800 rounded-md shadow-xl py-1 z-50">
              <a href="#" class="block px-4 py-2 hover:bg-blue-50 hover:text-blue-600">
                <i class="fas fa-home mr-2"></i> Immobiliers
              </a>
              <a href="#" class="block px-4 py-2 hover:bg-blue-50 hover:text-blue-600">
                <i class="fas fa-car mr-2"></i> Automobiles
              </a>
              <a href="#" class="block px-4 py-2 hover:bg-blue-50 hover:text-blue-600">
                <i class="fas fa-user mr-2"></i> Personnels
              </a>
            </div>
          </div>
          
          <a href="#" class="nav-link flex items-center">
            <i class="fas fa-credit-card mr-2"></i> Cartes
          </a>
          
          <a href="#" class="nav-link flex items-center">
            <i class="fas fa-chart-line mr-2"></i> Investissements
          </a>
        </div>

        <!-- Côté Droit -->
        <div class="flex items-center space-x-5">
          <button class="p-3 rounded-full hover:bg-blue-700 transition relative">
            <i class="fas fa-bell text-lg"></i>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
          </button>
          
          <div class="dropdown relative">
            <div class="flex items-center space-x-3 cursor-pointer">
              <div class="user-avatar-large rounded-full bg-blue-200 flex items-center justify-center shadow-inner">
                <i class="fas fa-user text-blue-800 text-lg"></i>
              </div>
              <span class="text-lg">Jean Dupont</span>
              <i class="fas fa-chevron-down text-xs"></i>
            </div>
            <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-md shadow-xl py-1 z-50">
              <a href="#" class="block px-4 py-2 hover:bg-blue-50 hover:text-blue-600">
                <i class="fas fa-user-cog mr-2"></i> Mon Profil
              </a>
              <a href="#" class="block px-4 py-2 hover:bg-blue-50 hover:text-blue-600">
                <i class="fas fa-cog mr-2"></i> Paramètres
              </a>
              <div class="border-t my-1"></div>
              <a href="#" class="block px-4 py-2 hover:bg-blue-50 hover:text-blue-600">
                <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Mobile Navbar -->
      <div class="md:hidden flex items-center justify-between py-4">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
          <div class="logo-large rounded-full bg-white flex items-center justify-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <span class="text-2xl font-bold">Horizon Banque</span>
        </div>

        <!-- Bouton Hamburger -->
        <button id="mobile-menu-button" class="p-3 rounded-md hover:bg-blue-700 focus:outline-none">
          <i class="fas fa-bars text-xl"></i>
        </button>
      </div>

      <!-- Menu Mobile -->
      <div id="mobile-menu" class="mobile-menu md:hidden bg-blue-800">
        <div class="px-2 pt-2 pb-3 space-y-1">
          <a href="#" class="block px-3 py-3 rounded-md text-white font-medium hover:bg-blue-700">
            <i class="fas fa-home mr-2"></i> Accueil
          </a>
          
          <div class="relative">
            <button class="w-full text-left px-3 py-3 rounded-md text-white font-medium hover:bg-blue-700 flex justify-between items-center">
              <span><i class="fas fa-user mr-2"></i> Comptes</span>
              <i class="fas fa-chevron-down"></i>
            </button>
            <div class="pl-4 mt-1 space-y-1">
              <a href="#" class="block px-3 py-2 rounded-md text-blue-200 hover:bg-blue-700">
                <i class="fas fa-piggy-bank mr-2"></i> Comptes Courants
              </a>
              <a href="#" class="block px-3 py-2 rounded-md text-blue-200 hover:bg-blue-700">
                <i class="fas fa-coins mr-2"></i> Comptes Épargne
              </a>
              <a href="#" class="block px-3 py-2 rounded-md text-blue-200 hover:bg-blue-700">
                <i class="fas fa-business-time mr-2"></i> Comptes Pro
              </a>
            </div>
          </div>
          
          <div class="relative">
            <button class="w-full text-left px-3 py-3 rounded-md text-white font-medium hover:bg-blue-700 flex justify-between items-center">
              <span><i class="fas fa-hand-holding-usd mr-2"></i> Prêts</span>
              <i class="fas fa-chevron-down"></i>
            </button>
            <div class="pl-4 mt-1 space-y-1">
              <a href="#" class="block px-3 py-2 rounded-md text-blue-200 hover:bg-blue-700">
                <i class="fas fa-home mr-2"></i> Immobiliers
              </a>
              <a href="#" class="block px-3 py-2 rounded-md text-blue-200 hover:bg-blue-700">
                <i class="fas fa-car mr-2"></i> Automobiles
              </a>
              <a href="#" class="block px-3 py-2 rounded-md text-blue-200 hover:bg-blue-700">
                <i class="fas fa-user mr-2"></i> Personnels
              </a>
            </div>
          </div>
          
          <a href="#" class="block px-3 py-3 rounded-md text-white font-medium hover:bg-blue-700">
            <i class="fas fa-credit-card mr-2"></i> Cartes
          </a>
          
          <a href="#" class="block px-3 py-3 rounded-md text-white font-medium hover:bg-blue-700">
            <i class="fas fa-chart-line mr-2"></i> Investissements
          </a>
          
          <div class="pt-4 border-t border-blue-700">
            <a href="#" class="block px-3 py-3 rounded-md text-white font-medium hover:bg-blue-700">
              <i class="fas fa-user-cog mr-2"></i> Mon Profil
            </a>
            <a href="#" class="block px-3 py-3 rounded-md text-white font-medium hover:bg-blue-700">
              <i class="fas fa-cog mr-2"></i> Paramètres
            </a>
            <a href="#" class="block px-3 py-3 rounded-md text-white font-medium hover:bg-blue-700">
              <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>

  
  </div>

  <script>
    
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('open');
      
      // Animation de l'icône
      if (mobileMenu.classList.contains('open')) {
        mobileMenuButton.innerHTML = '<i class="fas fa-times text-xl"></i>';
      } else {
        mobileMenuButton.innerHTML = '<i class="fas fa-bars text-xl"></i>';
      }
    });

    // Fermer le menu mobile quand on clique à l'extérieur
    document.addEventListener('click', (e) => {
      if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
        mobileMenu.classList.remove('open');
        mobileMenuButton.innerHTML = '<i class="fas fa-bars text-xl"></i>';
      }
    });

    // Effet de scroll sur le navbar
    window.addEventListener('scroll', () => {
      const nav = document.querySelector('nav');
      if (window.scrollY > 10) {
        nav.classList.add('shadow-lg');
        nav.classList.add('bg-blue-900');
        nav.classList.remove('bg-gradient-bank');
      } else {
        nav.classList.remove('shadow-lg');
        nav.classList.remove('bg-blue-900');
        nav.classList.add('bg-gradient-bank');
      }
    });
  </script>
</body>
</html>