<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Photo;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;

/**
 * Store photo files under webroot/img/uploads/YEAR/MONTH/{id}.{ext}
 * and extract EXIF metadata for admin forms.
 */
class PhotoFileService
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    /**
     * Absolute filesystem path for a relative filename stored in photos.filename.
     *
     * @param string $relative Relative path under webroot/img/.
     * @return string
     */
    public function absolutePath(string $relative): string
    {
        return WWW_ROOT . 'img' . DS . str_replace(['/', '\\'], DS, ltrim($relative, '/\\'));
    }

    /**
     * Build relative storage path: uploads/YYYY/MM/{id}.{ext}
     *
     * @param int $id Photo id.
     * @param string $extension File extension without dot.
     * @param string|null $yearMonth Optional "YYYY/MM"; defaults to now.
     * @return string
     */
    public function relativePath(int $id, string $extension, ?string $yearMonth = null): string
    {
        $extension = strtolower($extension);
        if ($extension === 'jpeg') {
            $extension = 'jpg';
        }
        $yearMonth = $yearMonth ?: date('Y/m');

        return 'uploads/' . $yearMonth . '/' . $id . '.' . $extension;
    }

    /**
     * Extract EXIF + dimension fields from an image on disk.
     *
     * @param string $path Absolute file path.
     * @return array<string, mixed>
     */
    public function extractExif(string $path): array
    {
        $result = [
            'camera' => null,
            'lens' => null,
            'exposure' => null,
            'aperture' => null,
            'iso' => null,
            'focal' => null,
            'shot_date' => null,
            'shot_time' => null,
            'dimensions' => null,
        ];

        $size = @getimagesize($path);
        if (is_array($size) && !empty($size[0]) && !empty($size[1])) {
            $result['dimensions'] = $size[0] . '×' . $size[1];
        }

        if (!function_exists('exif_read_data')) {
            return $result;
        }

        $exif = @exif_read_data($path, null, true);
        if (!is_array($exif)) {
            return $result;
        }

        $ifd0 = $exif['IFD0'] ?? [];
        $exifIfd = $exif['EXIF'] ?? [];

        $make = trim((string)($ifd0['Make'] ?? ''));
        $model = trim((string)($ifd0['Model'] ?? ''));
        if ($make !== '' || $model !== '') {
            $camera = trim($make . ' ' . $model);
            if ($make !== '' && $model !== '' && str_starts_with(strtolower($model), strtolower($make))) {
                $camera = $model;
            }
            $result['camera'] = $camera !== '' ? $camera : null;
        }

        $lens = $exifIfd['UndefinedTag:0xA434']
            ?? $exifIfd['LensModel']
            ?? $exifIfd['Lens']
            ?? $ifd0['UndefinedTag:0xA434']
            ?? null;
        if (is_string($lens) && trim($lens) !== '') {
            $result['lens'] = trim($lens);
        }

        if (!empty($exifIfd['ExposureTime'])) {
            $result['exposure'] = $this->formatExposure($exifIfd['ExposureTime']);
        }
        if (!empty($exifIfd['FNumber'])) {
            $result['aperture'] = 'f/' . rtrim(rtrim(sprintf('%.1f', $this->exifNumber($exifIfd['FNumber'])), '0'), '.');
        }
        if (!empty($exifIfd['ISOSpeedRatings'])) {
            $iso = $exifIfd['ISOSpeedRatings'];
            $result['iso'] = is_array($iso) ? (string)($iso[0] ?? '') : (string)$iso;
        }
        if (!empty($exifIfd['FocalLength'])) {
            $focal = $this->exifNumber($exifIfd['FocalLength']);
            $result['focal'] = rtrim(rtrim(sprintf('%.1f', $focal), '0'), '.') . 'mm';
        }

        $dateRaw = $exifIfd['DateTimeOriginal'] ?? $exifIfd['DateTimeDigitized'] ?? $ifd0['DateTime'] ?? null;
        if (is_string($dateRaw) && preg_match('/^(\d{4}):(\d{2}):(\d{2}) (\d{2}):(\d{2}):(\d{2})$/', $dateRaw, $m)) {
            $result['shot_date'] = $m[1] . '-' . $m[2] . '-' . $m[3];
            $result['shot_time'] = $m[4] . ':' . $m[5] . ':' . $m[6];
        }

        return $result;
    }

    /**
     * Store an uploaded file for a saved photo (must have an id).
     *
     * @param \App\Model\Entity\Photo $photo Photo entity with id.
     * @param \Psr\Http\Message\UploadedFileInterface $file Uploaded file.
     * @param string|null $oldRelative Previous relative path to delete.
     * @return string New relative path under img/.
     */
    public function storeUploaded(Photo $photo, UploadedFileInterface $file, ?string $oldRelative = null): string
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new RuntimeException(__('The image could not be uploaded.'));
        }

        $extension = $this->extensionFromUpload($file);
        $yearMonth = $this->yearMonthFromRelative($oldRelative) ?: date('Y/m');
        $relative = $this->relativePath((int)$photo->id, $extension, $yearMonth);
        $absolute = $this->absolutePath($relative);

        $dir = dirname($absolute);
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new RuntimeException(__('Could not create upload directory.'));
        }

        if ($oldRelative && $oldRelative !== $relative) {
            $this->deleteRelative($oldRelative);
        } elseif (is_file($absolute)) {
            @unlink($absolute);
        }

        $file->moveTo($absolute);
        if (!is_file($absolute)) {
            throw new RuntimeException(__('The image could not be saved.'));
        }

        return $relative;
    }

    /**
     * Delete a stored photo file under webroot/img/ (path-traversal safe).
     *
     * @param string|null $relative Relative path from photos.filename.
     * @return void
     */
    public function deleteRelative(?string $relative): void
    {
        if ($relative === null || $relative === '' || $relative === 'pending') {
            return;
        }

        $normalized = str_replace('\\', '/', ltrim($relative, '/\\'));
        if ($normalized === '' || str_contains($normalized, '..')) {
            return;
        }

        $absolute = $this->absolutePath($normalized);
        $imgRoot = realpath(WWW_ROOT . 'img');
        if ($imgRoot === false) {
            return;
        }

        $parent = realpath(dirname($absolute));
        if ($parent === false || !str_starts_with($parent, $imgRoot)) {
            return;
        }

        if (is_file($absolute)) {
            @unlink($absolute);
        }

        if (str_starts_with($normalized, 'uploads/')) {
            $this->removeEmptyUploadDirs(dirname($absolute));
        }
    }

    /**
     * Delete every on-disk asset belonging to a photo record.
     *
     * @param \App\Model\Entity\Photo $photo Photo entity (must still have filename/slug).
     * @return void
     */
    public function deleteForPhoto(Photo $photo): void
    {
        $this->deleteRelative($photo->filename !== null ? (string)$photo->filename : null);

        // Same id may have been stored with a different extension earlier; clean leftovers.
        if (!empty($photo->id)) {
            $this->deleteUploadVariants((int)$photo->id, $photo->filename !== null ? (string)$photo->filename : null);
        }

        $slug = trim((string)($photo->slug ?? ''));
        if ($slug !== '') {
            $shield = WWW_ROOT . 'protect' . DS . $slug . '.png';
            if (is_file($shield)) {
                @unlink($shield);
            }
        }
    }

    /**
     * Remove leftover uploads/YEAR/MONTH/{id}.* files for this photo id.
     *
     * @param int $id Photo id.
     * @param string|null $knownRelative Already known primary path.
     * @return void
     */
    protected function deleteUploadVariants(int $id, ?string $knownRelative): void
    {
        if (!$knownRelative || !preg_match('#^uploads/(\d{4}/\d{2})/#', str_replace('\\', '/', $knownRelative), $m)) {
            return;
        }

        $dir = WWW_ROOT . 'img' . DS . 'uploads' . DS . str_replace('/', DS, $m[1]);
        if (!is_dir($dir)) {
            return;
        }

        foreach (glob($dir . DS . $id . '.*') ?: [] as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        $this->removeEmptyUploadDirs($dir);
    }

    /**
     * Remove empty month/year folders under img/uploads.
     *
     * @param string $dir Directory path.
     * @return void
     */
    protected function removeEmptyUploadDirs(string $dir): void
    {
        $uploadsRoot = realpath(WWW_ROOT . 'img' . DS . 'uploads');
        $current = realpath($dir) ?: $dir;

        for ($i = 0; $i < 3; $i++) {
            if (!is_dir($current)) {
                return;
            }
            $real = realpath($current);
            if ($uploadsRoot && $real && ($real === $uploadsRoot || !str_starts_with($real, $uploadsRoot . DIRECTORY_SEPARATOR))) {
                return;
            }
            if (count(array_diff(scandir($current) ?: [], ['.', '..'])) > 0) {
                return;
            }
            if (!@rmdir($current)) {
                return;
            }
            $current = dirname($current);
        }
    }

    /**
     * Merge EXIF data into request data (only fills empty fields unless $overwrite).
     *
     * @param array<string, mixed> $data Form data.
     * @param array<string, mixed> $exif EXIF payload.
     * @param bool $overwrite Replace existing EXIF field values.
     * @return array<string, mixed>
     */
    public function mergeExifIntoData(array $data, array $exif, bool $overwrite = false): array
    {
        foreach (['camera', 'lens', 'exposure', 'aperture', 'iso', 'focal', 'dimensions'] as $field) {
            $value = $exif[$field] ?? null;
            if ($value === null || $value === '') {
                continue;
            }
            if ($overwrite || empty($data[$field])) {
                $data[$field] = $value;
            }
        }

        if (!empty($exif['shot_date']) && ($overwrite || empty($data['shot_date']))) {
            $data['shot_date'] = $exif['shot_date'];
        }
        if (!empty($exif['shot_time']) && ($overwrite || empty($data['shot_time']))) {
            $data['shot_time'] = $exif['shot_time'];
        }

        return $data;
    }

    /**
     * Original client filename including extension (e.g. IMG_4821.jpg).
     *
     * @param \Psr\Http\Message\UploadedFileInterface $file Upload.
     * @return string
     */
    public function originalNameFromUpload(UploadedFileInterface $file): string
    {
        $clientName = trim(str_replace(['\\', '/'], '', (string)$file->getClientFilename()));
        if ($clientName === '') {
            return '';
        }

        return mb_substr($clientName, 0, 255);
    }

    /**
     * @param \Psr\Http\Message\UploadedFileInterface $file Upload.
     * @return string
     */
    public function extensionFromUpload(UploadedFileInterface $file): string
    {
        $clientName = (string)$file->getClientFilename();
        $extension = strtolower(pathinfo($clientName, PATHINFO_EXTENSION));
        if ($extension === '' || !in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            $tmp = $file->getStream()->getMetadata('uri');
            if (is_string($tmp) && is_file($tmp)) {
                $info = @getimagesize($tmp);
                $map = [
                    IMAGETYPE_JPEG => 'jpg',
                    IMAGETYPE_PNG => 'png',
                    IMAGETYPE_GIF => 'gif',
                    IMAGETYPE_WEBP => 'webp',
                ];
                if (is_array($info) && isset($map[$info[2]])) {
                    return $map[$info[2]];
                }
            }
            throw new RuntimeException(__('Only JPG, PNG, WEBP or GIF images are allowed.'));
        }

        return $extension === 'jpeg' ? 'jpg' : $extension;
    }

    /**
     * Copy an existing file into the uploads tree for a photo id.
     *
     * @param int $id Photo id.
     * @param string $sourceAbsolute Source file.
     * @return string Relative path under img/.
     */
    public function importExistingFile(int $id, string $sourceAbsolute): string
    {
        if (!is_file($sourceAbsolute)) {
            throw new RuntimeException('Source file missing: ' . $sourceAbsolute);
        }

        $extension = strtolower(pathinfo($sourceAbsolute, PATHINFO_EXTENSION)) ?: 'jpg';
        $relative = $this->relativePath($id, $extension);
        $absolute = $this->absolutePath($relative);
        $dir = dirname($absolute);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if (!copy($sourceAbsolute, $absolute)) {
            throw new RuntimeException('Could not copy to ' . $absolute);
        }

        return $relative;
    }

    /**
     * @param string|null $relative Relative path.
     * @return string|null YYYY/MM
     */
    protected function yearMonthFromRelative(?string $relative): ?string
    {
        if (!$relative) {
            return null;
        }
        if (preg_match('#^uploads/(\d{4}/\d{2})/#', str_replace('\\', '/', $relative), $m)) {
            return $m[1];
        }

        return null;
    }

    /**
     * @param mixed $value EXIF numeric or "a/b" rational.
     * @return float
     */
    protected function exifNumber(mixed $value): float
    {
        if (is_numeric($value)) {
            return (float)$value;
        }
        if (is_string($value) && str_contains($value, '/')) {
            [$a, $b] = array_pad(explode('/', $value, 2), 2, '1');
            $b = (float)$b;

            return $b != 0.0 ? (float)$a / $b : 0.0;
        }

        return 0.0;
    }

    /**
     * @param mixed $value ExposureTime EXIF value.
     * @return string
     */
    protected function formatExposure(mixed $value): string
    {
        $number = $this->exifNumber($value);
        if ($number <= 0) {
            return (string)$value;
        }
        if ($number >= 1) {
            return rtrim(rtrim(sprintf('%.1f', $number), '0'), '.') . 's';
        }
        $denom = (int)round(1 / $number);

        return '1/' . max(1, $denom) . 's';
    }
}
