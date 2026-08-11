<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class FileModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getRootContents($userId, $parentId = null) {
        if ($parentId === null) {
            $stmt = $this->db->prepare("SELECT * FROM files WHERE user_id = ? AND parent_id IS NULL AND is_deleted = 0 ORDER BY is_folder DESC, original_name ASC");
            $stmt->execute([$userId]);
        } else {
            $stmt = $this->db->prepare("SELECT * FROM files WHERE user_id = ? AND parent_id = ? AND is_deleted = 0 ORDER BY is_folder DESC, original_name ASC");
            $stmt->execute([$userId, $parentId]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createFolder($userId, $name, $parentId = null) {
        $uniqueName = uniqid('folder_');
        $path = $parentId ? $this->getPath($parentId) . '/' . $uniqueName : $uniqueName;
        $stmt = $this->db->prepare("INSERT INTO files (user_id, filename, original_name, file_path, is_folder, parent_id) VALUES (?, ?, ?, ?, 1, ?)");
        return $stmt->execute([$userId, $uniqueName, $name, $path, $parentId]);
    }

    public function createFile($userId, $originalName, $uniqueName, $size, $mime, $path, $parentId = null) {
        $stmt = $this->db->prepare("INSERT INTO files (user_id, filename, original_name, file_size, mime_type, file_path, parent_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $uniqueName, $originalName, $size, $mime, $path, $parentId]);
    }

    public function getById($id, $userId) {
        $stmt = $this->db->prepare("SELECT * FROM files WHERE id = ? AND user_id = ? AND is_deleted = 0");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer un fichier même supprimé
    public function getByIdEvenDeleted($id, $userId) {
        $stmt = $this->db->prepare("SELECT * FROM files WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markDeleted($id, $userId) {
        $stmt = $this->db->prepare("UPDATE files SET is_deleted = 1, deleted_at = NOW() WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }

    // Suppression récursive d'un dossier 
    public function markDeletedRecursive($folderId, $userId) {
        $this->markDeleted($folderId, $userId);
        $stmt = $this->db->prepare("SELECT id FROM files WHERE user_id = ? AND parent_id = ? AND is_deleted = 0");
        $stmt->execute([$userId, $folderId]);
        $children = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($children as $child) {
            $this->markDeletedRecursive($child['id'], $userId);
        }
    }

    public function restore($id, $userId) {
        $stmt = $this->db->prepare("UPDATE files SET is_deleted = 0, deleted_at = NULL WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }

    public function permanentDelete($id, $userId) {
        $file = $this->getByIdEvenDeleted($id, $userId);
        if ($file && !$file['is_folder']) {
            $fullPath = UPLOAD_DIR . $file['filename'];
            if (file_exists($fullPath)) unlink($fullPath);
        }
        $stmt = $this->db->prepare("DELETE FROM files WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }

    public function getTrash($userId) {
        $stmt = $this->db->prepare("SELECT * FROM files WHERE user_id = ? AND is_deleted = 1 ORDER BY deleted_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($userId, $query) {
        $stmt = $this->db->prepare("SELECT * FROM files WHERE user_id = ? AND is_deleted = 0 AND original_name LIKE ? ORDER BY is_folder DESC");
        $stmt->execute([$userId, "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateShareToken($id, $userId) {
        $token = bin2hex(random_bytes(32));
        $stmt = $this->db->prepare("UPDATE files SET share_token = ? WHERE id = ? AND user_id = ? AND is_deleted = 0");
        $stmt->execute([$token, $id, $userId]);
        return $token;
    }

    public function getByShareToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM files WHERE share_token = ? AND is_deleted = 0");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPath($fileId) {
        $stmt = $this->db->prepare("SELECT file_path FROM files WHERE id = ?");
        $stmt->execute([$fileId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res['file_path'] : '';
    }

    public function getParentId($fileId) {
        $stmt = $this->db->prepare("SELECT parent_id FROM files WHERE id = ?");
        $stmt->execute([$fileId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res['parent_id'] : null;
    }

    public function findFolderByName($userId, $name, $parentId) {
        if ($parentId === null) {
            $stmt = $this->db->prepare("SELECT id FROM files WHERE user_id = ? AND original_name = ? AND parent_id IS NULL AND is_folder = 1 AND is_deleted = 0");
            $stmt->execute([$userId, $name]);
        } else {
            $stmt = $this->db->prepare("SELECT id FROM files WHERE user_id = ? AND original_name = ? AND parent_id = ? AND is_folder = 1 AND is_deleted = 0");
            $stmt->execute([$userId, $name, $parentId]);
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}