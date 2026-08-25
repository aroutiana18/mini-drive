<div class="w-full max-w-md">

    <div class="mb-4">
        <a
            href="<?= BASE_URL ?>/login"
            class="inline-flex items-center gap-2
                   text-gray-500 hover:text-blue-600
                   text-sm font-semibold
                   transition-colors duration-200"
        >
            <i class="fas fa-arrow-left text-xs"></i>

            <span>
                Retour
            </span>
        </a>
    </div>

    <div class="text-center mb-8">

        <div class="inline-flex items-center justify-center w-16 h-16
                    bg-blue-600 text-white rounded-2xl shadow-lg mb-4">
            <i class="fas fa-user-shield text-2xl"></i>
        </div>

        <h1 class="text-3xl font-bold text-gray-800">
            Compte utilisateur
        </h1>

        <p class="text-gray-500 mt-2">
            Accès à Mini-Drive
        </p>

    </div>

    <div class="glass-card rounded-3xl p-8 md:p-10">

        <div class="mb-6 p-5 bg-blue-50 border border-blue-100 rounded-2xl">

            <div class="flex items-start">

                <div class="flex-shrink-0 text-blue-600 mt-1">
                    <i class="fas fa-info-circle text-xl"></i>
                </div>

                <div class="ml-4">

                    <h2 class="text-sm font-bold text-blue-900 mb-2">
                        Création de compte
                    </h2>

                    <p class="text-sm leading-6 text-blue-800 mb-3">
                        La création des comptes est gérée exclusivement
                        par l'administrateur du système.
                    </p>

                    <p class="text-sm leading-6 text-blue-800 mb-0">
                        Si vous souhaitez accéder à Mini-Drive, veuillez
                        demander à l'administrateur de créer votre compte
                        professionnel dans l'annuaire de l'organisation.
                    </p>

                </div>

            </div>

        </div>

        <div class="text-center mb-6">

            <p class="text-gray-600 text-sm leading-6">
                Une fois votre adresse email créée, vous pourrez utiliser
                vos identifiants pour vous connecter à Mini-Drive.
            </p>

        </div>

        <div class="flex items-center justify-center gap-2
                    text-gray-600 text-sm font-semibold mb-6">

            <i class="fas fa-shield-alt text-blue-600"></i>

            <span>
                Contactez votre administrateur pour obtenir l'accès.
            </span>

        </div>

        <div class="border-t border-gray-100 pt-6 text-center">

            <p class="text-gray-600 text-sm">

                Vous avez déjà un compte ?

                <a
                    href="<?= BASE_URL ?>/login"
                    class="text-blue-600 font-bold hover:text-blue-700
                           hover:underline transition-colors ml-1"
                >
                    Se connecter
                </a>

            </p>

        </div>

    </div>

    <p class="text-center text-gray-400 text-xs mt-8 uppercase tracking-widest">
        &copy; HIU_2026 Mini Drive | Tous droits réservés
    </p>

</div>