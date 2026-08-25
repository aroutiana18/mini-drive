<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class FileModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupère les fichiers/dossiers d'un utilisateur dans un répertoire donné.
     */
    public function getRootContents(string $userEmail, ?int $parentId = null): array
    {
        if ($parentId === null) {
            $stmt = $this->db->prepare(
                "SELECT *
                 FROM files
                 WHERE user_email = ?
                   AND parent_id IS NULL
                   AND is_deleted = 0
                 ORDER BY is_folder DESC, original_name ASC"
            );

            $stmt->execute([$userEmail]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT *
                 FROM files
                 WHERE user_email = ?
                   AND parent_id = ?
                   AND is_deleted = 0
                 ORDER BY is_folder DESC, original_name ASC"
            );

            $stmt->execute([$userEmail, $parentId]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée un dossier appartenant à l'utilisateur.
     */
    public function createFolder(
        string $userEmail,
        string $name,
        ?int $parentId = null
    ): bool {
        $uniqueName = uniqid('folder_', true);

        $path = $parentId
            ? $this->getPath($parentId) . '/' . $uniqueName
            : $uniqueName;

        $stmt = $this->db->prepare(
            "INSERT INTO files
                (user_email, filename, original_name, file_path, is_folder, parent_id)
             VALUES (?, ?, ?, ?, 1, ?)"
        );

        return $stmt->execute([
            $userEmail,
            $uniqueName,
            $name,
            $path,
            $parentId
        ]);
    }

    /**
     * Crée un fichier appartenant à l'utilisateur.
     */
    public function createFile(
        string $userEmail,
        string $originalName,
        string $uniqueName,
        int $size,
        string $mime,
        string $path,
        ?int $parentId = null
    ): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO files
                (user_email, filename, original_name, file_size, mime_type, file_path, parent_id)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $userEmail,
            $uniqueName,
            $originalName,
            $size,
            $mime,
            $path,
            $parentId
        ]);
    }

    /**
     * Récupère un fichier appartenant à l'utilisateur.
     */
    public function getById(int $id, string $userEmail): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM files
             WHERE id = ?
               AND user_email = ?
               AND is_deleted = 0"
        );

        $stmt->execute([$id, $userEmail]);

        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        return $file ?: null;
    }

    /**
     * Récupère un fichier même lorsqu'il est placé dans la corbeille.
     */
    public function getByIdEvenDeleted(int $id, string $userEmail): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM files
             WHERE id = ?
               AND user_email = ?"
        );

        $stmt->execute([$id, $userEmail]);

        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        return $file ?: null;
    }

    /**
     * Place un fichier ou dossier dans la corbeille.
     */
    public function markDeleted(int $id, string $userEmail): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE files
             SET is_deleted = 1,
                 deleted_at = NOW()
             WHERE id = ?
               AND user_email = ?"
        );

        return $stmt->execute([$id, $userEmail]);
    }

    /**
     * Place récursivement un dossier et son contenu dans la corbeille.
     */
    public function markDeletedRecursive(int $folderId, string $userEmail): void
    {
        $this->markDeleted($folderId, $userEmail);

        $stmt = $this->db->prepare(
            "SELECT id
             FROM files
             WHERE user_email = ?
               AND parent_id = ?
               AND is_deleted = 0"
        );

        $stmt->execute([$userEmail, $folderId]);

        $children = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($children as $child) {
            $this->markDeletedRecursive(
                (int) $child['id'],
                $userEmail
            );
        }
    }

    /**
     * Restaure un fichier ou dossier depuis la corbeille.
     */
    public function restore(int $id, string $userEmail): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE files
             SET is_deleted = 0,
                 deleted_at = NULL
             WHERE id = ?
               AND user_email = ?"
        );

        return $stmt->execute([$id, $userEmail]);
    }

    /**
     * Supprime définitivement un fichier.
     */
    public function permanentDelete(int $id, string $userEmail): bool
    {
        $file = $this->getByIdEvenDeleted($id, $userEmail);

        if ($file && !$file['is_folder']) {
            $fullPath = UPLOAD_DIR . $file['filename'];

            if (is_file($fullPath)) {
                unlink($fullPath);
            }
        }

        $stmt = $this->db->prepare(
            "DELETE FROM files
             WHERE id = ?
               AND user_email = ?"
        );

        return $stmt->execute([$id, $userEmail]);
    }

    /**
     * Récupère les éléments placés dans la corbeille.
     */
    public function getTrash(string $userEmail): array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM files
             WHERE user_email = ?
               AND is_deleted = 1
             ORDER BY deleted_at DESC"
        );

        $stmt->execute([$userEmail]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche les fichiers appartenant à l'utilisateur.
     */
    public function search(string $userEmail, string $query): array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM files
             WHERE user_email = ?
               AND is_deleted = 0
               AND original_name LIKE ?
             ORDER BY is_folder DESC"
        );

        $stmt->execute([
            $userEmail,
            '%' . $query . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Génère un token de partage sécurisé.
     */
    public function generateShareToken(int $id, string $userEmail): string
    {
        $token = bin2hex(random_bytes(32));

        $stmt = $this->db->prepare(
            "UPDATE files
             SET share_token = ?
             WHERE id = ?
               AND user_email = ?
               AND is_deleted = 0"
        );

        $stmt->execute([
            $token,
            $id,
            $userEmail
        ]);

        return $token;
    }

    /**
     * Récupère un fichier à partir de son token de partage.
     */
    public function getByShareToken(string $token): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM files
             WHERE share_token = ?
               AND is_deleted = 0"
        );

        $stmt->execute([$token]);

        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        return $file ?: null;
    }

    /**
     * Récupère le chemin relatif d'un fichier/dossier.
     */
    public function getPath(int $fileId): string
    {
        $stmt = $this->db->prepare(
            "SELECT file_path
             FROM files
             WHERE id = ?"
        );

        $stmt->execute([$fileId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['file_path'] : '';
    }

    /**
     * Récupère le parent d'un fichier/dossier.
     */
    public function getParentId(int $fileId): ?int
    {
        $stmt = $this->db->prepare(
            "SELECT parent_id
             FROM files
             WHERE id = ?"
        );

        $stmt->execute([$fileId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result && $result['parent_id'] !== null
            ? (int) $result['parent_id']
            : null;
    }

    /**
     * Recherche un dossier par son nom dans le répertoire courant.
     */
    public function findFolderByName(
        string $userEmail,
        string $name,
        ?int $parentId
    ): ?array {
        if ($parentId === null) {
            $stmt = $this->db->prepare(
                "SELECT id
                 FROM files
                 WHERE user_email = ?
                   AND original_name = ?
                   AND parent_id IS NULL
                   AND is_folder = 1
                   AND is_deleted = 0"
            );

            $stmt->execute([
                $userEmail,
                $name
            ]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT id
                 FROM files
                 WHERE user_email = ?
                   AND original_name = ?
                   AND parent_id = ?
                   AND is_folder = 1
                   AND is_deleted = 0"
            );

            $stmt->execute([
                $userEmail,
                $name,
                $parentId
            ]);
        }

        $folder = $stmt->fetch(PDO::FETCH_ASSOC);

        return $folder ?: null;
    }
}