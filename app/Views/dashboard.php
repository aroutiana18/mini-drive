<div class="space-y-8">
    <!-- En-tête + recherche -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Mes fichiers</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez vos documents en toute sécurité</p>
        </div>
        <form class="relative" action="<?= BASE_URL ?>/search" method="GET">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="q" value="<?= htmlspecialchars($searchQuery ?? '') ?>" placeholder="Rechercher ..." class="w-full md:w-80 pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow">
        </form>
    </div>

    <!-- Barre d'actions -->
    <div class="flex flex-wrap gap-3 items-center">
        <?php if ($currentFolder !== null): ?>
            <a href="<?= BASE_URL ?>/dashboard<?= $parentFolder ? "?folder=$parentFolder" : '' ?>" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-700 hover:bg-slate-50 transition shadow-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        <?php endif; ?>
        <!-- <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl shadow-md hover:bg-blue-700 transition" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload"></i> Importer
        </button> -->
        <!-- <button class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-xl shadow-md hover:bg-green-700 transition" data-bs-toggle="modal" data-bs-target="#folderModal">
            <i class="fas fa-folder-plus"></i> Dossier
        </button> -->
        <button id="batchDownload" class="flex items-center gap-2 px-4 py-2 bg-slate-600 text-white rounded-xl shadow-md hover:bg-slate-700 transition">
            <i class="fas fa-download"></i> Télécharger
        </button>
        <button id="batchDelete" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-xl shadow-md hover:bg-red-700 transition">
            <i class="fas fa-trash"></i> Supprimer
        </button>
        <button id="batchShare" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl shadow-md hover:bg-indigo-700 transition">
            <i class="fas fa-share-alt"></i> Partager
        </button>
        <a href="<?= BASE_URL ?>/trash" class="flex items-center gap-2 px-4 py-2 bg-slate-200 text-slate-700 rounded-xl shadow-sm hover:bg-slate-300 transition">
            <i class="fas fa-trash-alt"></i> Corbeille
        </a>
    </div>

    <!-- Zone Drag & Drop -->
    <div id="dropzone" class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center bg-slate-50/50 hover:bg-slate-100 transition cursor-pointer">
        <i class="fas fa-cloud-upload-alt fa-3x text-slate-400 mb-3"></i>
        <p class="text-slate-600 font-medium">Glissez-déposez des fichiers ou dossiers ici</p>
        <p class="text-slate-400 text-sm">ou <label for="fileInputDrag" class="text-blue-600 cursor-pointer hover:underline"><b>parcourez</b></label></p>
        <input type="file" id="fileInputDrag" multiple style="display: none;">
        <progress id="progressBar" value="0" max="100" class="w-full mt-4 rounded-full h-2"></progress>
        <div id="uploadStatus" class="text-sm text-slate-500 mt-1"></div>
    </div>

    <!-- Grille des fichiers/dossiers -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        <?php if (empty($files)): ?>
            <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-slate-200">
                <i class="fas fa-folder-open fa-4x text-slate-300 mb-3"></i>
                <p class="text-slate-500">Aucun fichier ou dossier pour l'instant</p>
            </div>
        <?php else: ?>
            <?php foreach ($files as $item): ?>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition group relative">
                    <div class="absolute top-3 left-3 z-10">
                        <input type="checkbox" class="file-checkbox w-4 h-4 text-blue-600 rounded border-slate-300" data-id="<?= $item['id'] ?>">
                    </div>
                    <div class="p-5 text-center">
                        <?php if ($item['is_folder']): ?>
                            <i class="fas fa-folder fa-4x text-amber-500"></i>
                            <h3 class="font-bold text-slate-800 mt-3 truncate"><?= htmlspecialchars($item['original_name']) ?></h3>
                            <a href="<?= BASE_URL ?>/dashboard?folder=<?= $item['id'] ?>" class="inline-block mt-3 text-sm text-blue-600 hover:underline">Ouvrir</a>
                        <?php else: ?>
                            <i class="fas fa-file-alt fa-4x text-slate-500"></i>
                            <h3 class="font-bold text-slate-800 mt-3 truncate"><?= htmlspecialchars($item['original_name']) ?></h3>
                            <div class="flex justify-center gap-2 mt-3">
                                <a href="<?= BASE_URL ?>/download?id=<?= $item['id'] ?>" class="text-slate-600 hover:text-blue-600"><i class="fas fa-download"></i></a>
                                <button onclick="deleteItem(<?= $item['id'] ?>)" class="text-slate-600 hover:text-red-600"><i class="fas fa-trash"></i></button>
                                <button onclick="shareItem(<?= $item['id'] ?>)" class="text-slate-600 hover:text-indigo-600"><i class="fas fa-share-alt"></i></button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modals (Upload simple et Dossier) en Bootstrap (car déjà utilisé) – mais on peut simplifier -->
<div class="flex gap-8 p-8"> 
<div class="modal fade  w-[50%] " id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-2xl">
            <div class="modal-header border-0">
                <h5 class="modal-title font-bold">Importer un fichier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" action="<?= BASE_URL ?>/upload" method="POST" enctype="multipart/form-data">
                    <input type="file" name="file" class="form-control" required>
                    <input type="hidden" name="parent_id" value="<?= $currentFolder ?? '' ?>">
                    <button type="submit" class="mt-3 w-full bg-blue-600 text-white py-2 rounded-xl">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade  w-[50%] " id="folderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-2xl">
            <div class="modal-header border-0">
                <h5 class="modal-title font-bold">Nouveau dossier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?= BASE_URL ?>/create-folder">
                    <input type="text" name="name" class="form-control" placeholder="Nom du dossier" required>
                    <input type="hidden" name="parent_id" value="<?= $currentFolder ?? '' ?>">
                    <button type="submit" class="mt-3 w-full bg-green-600 text-white py-2 rounded-xl">Créer</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script>
function deleteItem(id) {
    if (confirm("Déplacer vers la corbeille ?")) {
        window.location.href = BASE_URL + "/delete?id=" + id;
    }
}
function shareItem(id) {
    fetch(BASE_URL + "/share?id=" + id)
        .then(res => res.text())
        .then(link => prompt("Lien de partage :", link));
}
function getSelectedIds() {
    return Array.from(document.querySelectorAll('.file-checkbox:checked')).map(cb => cb.dataset.id);
}
document.getElementById('batchDownload')?.addEventListener('click', () => {
    let ids = getSelectedIds();
    if (ids.length) window.location.href = BASE_URL + '/download-multiple?ids=' + ids.join(',');
    else alert("Sélectionnez au moins un fichier");
});
document.getElementById('batchDelete')?.addEventListener('click', () => {
    let ids = getSelectedIds();
    if (ids.length && confirm("Déplacer vers la corbeille ?")) 
        window.location.href = BASE_URL + '/delete?ids=' + ids.join(',');
});
document.getElementById('batchShare')?.addEventListener('click', () => {
    let ids = getSelectedIds();
    if (ids.length) {
        fetch(BASE_URL + '/share-multiple?ids=' + ids.join(','))
            .then(res => res.text())
            .then(links => prompt("Liens de partage :", links));
    }
});
</script>