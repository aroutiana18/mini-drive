<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\FileModel;

class FileController extends Controller {
    private $fileModel;

    public function __construct() {
        $this->requireLogin();
        $this->fileModel = new FileModel();
    }

    public function index() {
        $parentId = isset($_GET['folder']) ? (int)$_GET['folder'] : null;
        $files = $this->fileModel->getRootContents($_SESSION['user_email'], $parentId);
        $parentFolder = null;
        if ($parentId !== null) {
            $parentFolder = $this->fileModel->getParentId($parentId);
        }
        $this->view('dashboard', [
            'files' => $files,
            'currentFolder' => $parentId,
            'parentFolder' => $parentFolder
        ]);
    }

    public function createFolder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $parentId = isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null;
            if (!empty($name)) {
                $this->fileModel->createFolder($_SESSION['user_email'], $name, $parentId);
            }
        }
        $redirectUrl = 'dashboard' . (isset($parentId) && $parentId ? "?folder=$parentId" : '');
        $this->redirect($redirectUrl);
    }

    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $parentId = isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null;
            if ($file['error'] !== UPLOAD_ERR_OK) {
                echo "Erreur upload";
                return;
            }
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            $allowedMimes = ['image/jpeg', 'image/png', 'application/pdf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!in_array($mime, $allowedMimes)) {
                echo "Type non autorisé";
                return;
            }
            if ($file['size'] > MAX_FILE_SIZE) {
                echo "Fichier trop volumineux";
                return;
            }
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $uniqueName = uniqid() . '.' . $ext;
            $destination = UPLOAD_DIR . $uniqueName;
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $path = $parentId ? $this->fileModel->getPath($parentId) . '/' . $uniqueName : $uniqueName;
                $this->fileModel->createFile($_SESSION['user_email'], $file['name'], $uniqueName, $file['size'], $mime, $path, $parentId);
                echo "success";
            } else {
                echo "Erreur déplacement";
            }
        } else {
            echo "Requête invalide";
        }
    }

    public function uploadFolder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files'])) {
            $parentId = isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null;
            $files = $_FILES['files'];
            $paths = isset($_POST['paths']) ? $_POST['paths'] : [];
            $userEmail = $_SESSION['user_email'];
            $uploadedCount = 0;
            $createdFolders = [];

            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
                $relativePath = $paths[$i];
                $pathParts = explode('/', $relativePath);
                $filename = array_pop($pathParts);
                $folderPath = implode('/', $pathParts);

                $currentParentId = $parentId;
                if (!empty($folderPath)) {
                    $folders = explode('/', $folderPath);
                    foreach ($folders as $folderName) {
                        $key = $currentParentId . '_' . $folderName;
                        if (!isset($createdFolders[$key])) {
                            $existing = $this->fileModel->findFolderByName($userEmail, $folderName, $currentParentId);
                            if ($existing) {
                                $currentParentId = $existing['id'];
                            } else {
                                $this->fileModel->createFolder($userEmail, $folderName, $currentParentId);
                                $newFolder = $this->fileModel->findFolderByName($userEmail, $folderName, $currentParentId);
                                $currentParentId = $newFolder['id'];
                            }
                            $createdFolders[$key] = $currentParentId;
                        } else {
                            $currentParentId = $createdFolders[$key];
                        }
                    }
                }

                $tmpName = $files['tmp_name'][$i];
                $originalName = $filename;
                $size = $files['size'][$i];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $tmpName);
                finfo_close($finfo);
                $allowedMimes = ['image/jpeg', 'image/png', 'application/pdf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!in_array($mime, $allowedMimes)) continue;
                if ($size > MAX_FILE_SIZE) continue;

                $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                $uniqueName = uniqid() . '.' . $ext;
                $destination = UPLOAD_DIR . $uniqueName;
                if (move_uploaded_file($tmpName, $destination)) {
                    $filePath = $currentParentId
                        ? $this->fileModel->getPath($currentParentId) . '/' . $uniqueName
                        : $uniqueName;                    
                    $this->fileModel->createFile($userEmail, $originalName, $uniqueName, $size, $mime, $filePath, $currentParentId);
                    $uploadedCount++;
                }
            }
            echo $uploadedCount > 0 ? "success" : "error";
        } else {
            echo "Requête invalide";
        }
    }

    public function download() {
        $id = (int)($_GET['id'] ?? 0);
        $file = $this->fileModel->getById($id, $_SESSION['user_email']);
        if ($file && !$file['is_folder']) {
            $fullPath = UPLOAD_DIR . $file['filename'];
            if (file_exists($fullPath)) {
                header('Content-Type: ' . $file['mime_type']);
                header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
                header('Content-Length: ' . $file['file_size']);
                readfile($fullPath);
                exit;
            }
        }
        $this->redirect('dashboard');
    }

    public function downloadMultiple() {
        $ids = isset($_GET['ids']) ? explode(',', $_GET['ids']) : [];
        if (empty($ids)) {
            $this->redirect('dashboard');
            return;
        }
        $filesToZip = [];
        foreach ($ids as $id) {
            $id = (int)$id;
            $file = $this->fileModel->getById($id, $_SESSION['user_email']);
            if ($file && !$file['is_folder']) {
                $fullPath = UPLOAD_DIR . $file['filename'];
                if (file_exists($fullPath)) {
                    $filesToZip[] = ['path' => $fullPath, 'name' => $file['original_name']];
                }
            }
        }
        if (empty($filesToZip)) {
            $this->redirect('dashboard');
            return;
        }
        $zip = new \ZipArchive();
        $zipName = 'download_' . time() . '.zip';
        $zipPath = sys_get_temp_dir() . '/' . $zipName;
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($filesToZip as $f) {
                $zip->addFile($f['path'], $f['name']);
            }
            $zip->close();
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipName . '"');
            header('Content-Length: ' . filesize($zipPath));
            readfile($zipPath);
            unlink($zipPath);
            exit;
        }
        $this->redirect('dashboard');
    }

    public function delete() {
        $ids = isset($_GET['ids']) ? explode(',', $_GET['ids']) : [];
        if (empty($ids) && isset($_GET['id'])) {
            $ids = [$_GET['id']];
        }
        foreach ($ids as $id) {
            $id = (int)$id;
            $file = $this->fileModel->getByIdEvenDeleted($id, $_SESSION['user_email']);
            if ($file) {
                if ($file['is_folder']) {
                    $this->fileModel->markDeletedRecursive($id, $_SESSION['user_email']);
                } else {
                    $this->fileModel->markDeleted($id, $_SESSION['user_email']);
                }
            }
        }
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : BASE_URL . '/dashboard';
        header("Location: $redirect");
        exit;
    }

    public function restore() {
        $id = (int)($_GET['id'] ?? 0);
        $this->fileModel->restore($id, $_SESSION['user_email']);
        $this->redirect('trash');
    }

    public function permanentDelete() {
        $id = (int)($_GET['id'] ?? 0);
        $this->fileModel->permanentDelete($id, $_SESSION['user_email']);
        $this->redirect('trash');
    }

    public function trash() {
        $files = $this->fileModel->getTrash($_SESSION['user_email']);
        $this->view('trash', ['files' => $files]);
    }

    public function search() {
        $query = $_GET['q'] ?? '';
        $files = [];
        if (!empty($query)) {
            $files = $this->fileModel->search($_SESSION['user_email'], $query);
        }
        $this->view('dashboard', ['files' => $files, 'searchQuery' => $query, 'currentFolder' => null]);
    }

    public function share() {
        $id = (int)($_GET['id'] ?? 0);
        $token = $this->fileModel->generateShareToken($id, $_SESSION['user_email']);
        echo BASE_URL . "/public-share?token=$token";
    }

    public function shareMultiple() {
        $ids = isset($_GET['ids']) ? explode(',', $_GET['ids']) : [];
        $links = [];
        foreach ($ids as $id) {
            $id = (int)$id;
            $token = $this->fileModel->generateShareToken($id, $_SESSION['user_email']);
            $links[] = BASE_URL . "/public-share?token=$token";
        }
        echo implode("\n", $links);
    }

    public function publicShare() {
        $token = $_GET['token'] ?? '';
        $file = $this->fileModel->getByShareToken($token);
        if ($file && !$file['is_folder']) {
            $fullPath = UPLOAD_DIR . $file['filename'];
            if (file_exists($fullPath)) {
                header('Content-Type: ' . $file['mime_type']);
                header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
                header('Content-Length: ' . $file['file_size']);
                readfile($fullPath);
                exit;
            }
        }
        echo "Fichier non trouvé ou lien invalide";
    }
}