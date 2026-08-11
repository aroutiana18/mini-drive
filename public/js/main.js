document.addEventListener('DOMContentLoaded', function() {
    // Upload simple avec barre de progression
    const uploadForm = document.getElementById('uploadForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let xhr = new XMLHttpRequest();
            xhr.open('POST', uploadForm.action, true);
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    let percent = (e.loaded / e.total) * 100;
                    let progressBar = document.getElementById('progressBar');
                    let statusDiv = document.getElementById('uploadStatus');
                    if (progressBar) progressBar.value = percent;
                    if (statusDiv) statusDiv.innerHTML = Math.round(percent) + '%';
                }
            });
            xhr.onload = function() {
                if (xhr.responseText === 'success') {
                    alert('Upload terminé');
                    location.reload();
                } else {
                    alert('Erreur: ' + xhr.responseText);
                }
            };
            xhr.send(formData);
        });
    }

    // Drag & Drop zone
    const dropzone = document.getElementById('dropzone');
    const fileInputDrag = document.getElementById('fileInputDrag');
    if (dropzone) {
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('bg-light');
        });
        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('bg-light');
        });
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('bg-light');
            let items = e.dataTransfer.items;
            let files = [];
            for (let i = 0; i < items.length; i++) {
                let entry = items[i].webkitGetAsEntry();
                if (entry) {
                    traverseFileTree(entry, files);
                }
            }
            setTimeout(() => {
                uploadFilesAndFolders(files);
            }, 200);
        });
        dropzone.addEventListener('click', () => {
            fileInputDrag.click();
        });
        fileInputDrag.addEventListener('change', (e) => {
            let files = Array.from(e.target.files);
            uploadFilesAndFolders(files);
        });
    }

    function traverseFileTree(entry, files, path = '') {
        if (entry.isFile) {
            entry.file(file => {
                file.relativePath = (path ? path + '/' : '') + file.name;
                files.push(file);
            });
        } else if (entry.isDirectory) {
            let reader = entry.createReader();
            reader.readEntries(entries => {
                for (let subEntry of entries) {
                    traverseFileTree(subEntry, files, (path ? path + '/' : '') + entry.name);
                }
            });
        }
    }

    function uploadFilesAndFolders(files) {
        if (files.length === 0) return;
        let parentId = document.querySelector('input[name="parent_id"]')?.value || '';
        let formData = new FormData();
        for (let file of files) {
            formData.append('files[]', file);
            formData.append('paths[]', file.relativePath || file.name);
        }
        formData.append('parent_id', parentId);
        let xhr = new XMLHttpRequest();
        xhr.open('POST', BASE_URL + '/upload-folder', true);
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                let percent = (e.loaded / e.total) * 100;
                let progressBar = document.getElementById('progressBar');
                if (progressBar) {
                    progressBar.value = percent;
                    document.getElementById('uploadStatus').innerHTML = Math.round(percent) + '%';
                }
            }
        });
        xhr.onload = () => {
            if (xhr.responseText === 'success') {
                alert('Upload terminé');
                location.reload();
            } else {
                alert('Erreur: ' + xhr.responseText);
            }
        };
        xhr.send(formData);
    }
});