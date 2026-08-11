<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 text-white rounded-2xl shadow-lg mb-4">
            <i class="fas fa-user-plus text-2xl"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Inscription</h1>
        <p class="text-gray-500 mt-2">Créez votre compte gratuitement</p>
    </div>

    <div class="glass-card rounded-3xl p-8 md:p-10">
        <?php if (isset($error)): ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/register" class="space-y-6">
            <!-- Nom d'utilisateur -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="username">Nom d'utilisateur</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="far fa-user"></i>
                    </span>
                    <input type="text" id="username" name="username" required placeholder="pseudo" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="email">Adresse email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="far fa-envelope"></i>
                    </span>
                    <input type="email" id="email" name="email" required placeholder="vous@exemple.com" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                </div>
            </div>

            <!-- Mot de passe -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="password">Mot de passe</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full pl-10 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                    <button type="button" onclick="togglePassword('password', 'toggleIcon1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i id="toggleIcon1" class="far fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Confirmation mot de passe -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="confirm_password">Confirmer le mot de passe</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="••••••••" class="w-full pl-10 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                    <button type="button" onclick="togglePassword('confirm_password', 'toggleIcon2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i id="toggleIcon2" class="far fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transform transition active:scale-95 flex justify-center items-center">
                <span id="btnText">S'inscrire</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <p class="text-gray-600 text-sm">Déjà un compte ? <a href="<?= BASE_URL ?>/login" class="text-blue-600 font-bold hover:underline">Se connecter</a></p>
        </div>
    </div>

    <p class="text-center text-gray-400 text-xs mt-8 uppercase tracking-widest">&copy; HIU_2026 Mini Drive • Tous droits réservés</p>
</div>

<script>
    function togglePassword(fieldId, iconId) {
        const pwd = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            pwd.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('btnText').innerHTML = '<i class="fas fa-circle-notch animate-spin mr-2"></i> Inscription...';
    });
</script>