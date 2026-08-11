<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800">Corbeille</h1>
            <p class="text-slate-500">Fichiers supprimés (restauration possible)</p>
        </div>
        <a href="<?= BASE_URL ?>/dashboard" class="px-4 py-2 bg-slate-200 rounded-xl hover:bg-slate-300 transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        <?php if (empty($files)): ?>
            <div class="col-span-full text-center py-12 bg-white rounded-2xl border">
                <i class="fas fa-trash-alt fa-4x text-slate-300 mb-3"></i>
                <p class="text-slate-500">Corbeille vide</p>
            </div>
        <?php else: ?>
            <?php foreach ($files as $item): ?>
                <div class="bg-white rounded-2xl border border-slate-200 p-4 text-center">
                    <i class="fas <?= $item['is_folder'] ? 'fa-folder' : 'fa-file-alt' ?> fa-3x text-slate-500"></i>
                    <p class="font-bold mt-2 truncate"><?= htmlspecialchars($item['original_name']) ?></p>
                    <p class="text-xs text-slate-400 mt-1">Supprimé le <?= $item['deleted_at'] ?></p>
                    <div class="flex justify-center gap-3 mt-3">
                        <a href="<?= BASE_URL ?>/restore?id=<?= $item['id'] ?>" class="text-green-600 hover:underline text-sm">Restaurer</a>
                        <a href="<?= BASE_URL ?>/permanent-delete?id=<?= $item['id'] ?>" onclick="return confirm('Suppression définitive ?')" class="text-red-600 hover:underline text-sm">Effacer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>