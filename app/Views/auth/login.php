<div class="w-full max-w-md">
    
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 text-white rounded-2xl shadow-lg mb-4">
            <i class="fas fa-lock text-2xl"></i>   
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Bienvenue</h1>
        <p class="text-gray-500 mt-2">Veuillez vous connecter à votre compte</p>
    </div>

    <div class="glass-card rounded-3xl p-8 md:p-10">
        <!-- Message d'erreur PHP -->
        <?php if (isset($error)): ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/login" class="space-y-6">
            <!-- Champ Utilisateur -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="email">
                    Adresse email
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="far fa-user"></i>
                    </span>
                    <input type="email" name="email" placeholder="Adresse email" required
                           placeholder="votrenom@12eni.mg"
                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                </div>
            </div>

            <!-- Champ Mot de passe -->
            <div>
                <div class="flex justify-between mb-2">
                    <label class="text-sm font-semibold text-gray-700" for="password">
                        Mot de passe
                    </label>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" required
                           placeholder="••••••••"
                           class="w-full pl-10 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i id="toggleIcon" class="far fa-eye"></i>
                    </button>
                </div>
            </div>

            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transform transition active:scale-95 flex justify-center items-center">
                <span id="btnText">Se connecter</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <p class="text-gray-600 text-sm">
                Pas encore de compte ? 
                <a href="<?= BASE_URL ?>/register" class="text-blue-600 font-bold hover:underline">Créer un compte</a>
            </p>
        </div>
    </div>

    <p class="text-center text-gray-400 text-xs mt-8 uppercase tracking-widest">
        &copy; HIU_2026 Mini Drive | Tous droits réservés
    </p>
</div>

<script>
    function togglePassword() {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            pwd.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }


    document.querySelector('form').addEventListener('submit', function() {
        const btnText = document.getElementById('btnText');
        btnText.innerHTML = '<i class="fas fa-circle-notch animate-spin mr-2"></i> Connexion...';
    });
</script>