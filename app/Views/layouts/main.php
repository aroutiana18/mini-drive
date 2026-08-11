<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Drive | Cloud Sécurisé</title>
 
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(241, 245, 249, 1);
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-full">
        <nav class="glass-nav sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <a href="<?= BASE_URL ?>/dashboard" class="flex items-center group">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform">
                                <i class="fas fa-folder-open text-lg"></i>
                            </div>
                            <span class="ml-3 text-xl font-extrabold text-slate-800 tracking-tight">Mini Drive</span>
                        </a>
                    </div>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="flex items-center gap-6">
                        <div class="hidden md:flex flex-col text-right">
                            <span class="text-sm font-bold text-slate-700"><?= htmlspecialchars($_SESSION['username']) ?></span>
                        </div>
                        <div class="h-10 w-[1px] bg-slate-100 mx-2"></div>
                        <a href="<?= BASE_URL ?>/logout" class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                            <i class="fas fa-power-off"></i>
                            <span class="hidden sm:inline">Déconnexion</span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <main class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <?php require_once $viewFile; ?>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>const BASE_URL = '<?= BASE_URL ?>';</script>
    <script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>