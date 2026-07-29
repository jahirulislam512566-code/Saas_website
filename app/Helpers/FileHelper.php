<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    /**
     * Format file size
     */
    public static function formatSize($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get file extension
     */
    public static function extension($filename): string
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    /**
     * Get file name without extension
     */
    public static function name($filename): string
    {
        return pathinfo($filename, PATHINFO_FILENAME);
    }

    /**
     * Check if file is image
     */
    public static function isImage($filename): bool
    {
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico'];
        return in_array(strtolower(self::extension($filename)), $extensions);
    }

    /**
     * Check if file is video
     */
    public static function isVideo($filename): bool
    {
        $extensions = ['mp4', 'webm', 'ogg', 'avi', 'mov', 'wmv', 'flv', 'mkv'];
        return in_array(strtolower(self::extension($filename)), $extensions);
    }

    /**
     * Check if file is audio
     */
    public static function isAudio($filename): bool
    {
        $extensions = ['mp3', 'wav', 'ogg', 'flac', 'aac', 'wma'];
        return in_array(strtolower(self::extension($filename)), $extensions);
    }

    /**
     * Check if file is document
     */
    public static function isDocument($filename): bool
    {
        $extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'];
        return in_array(strtolower(self::extension($filename)), $extensions);
    }

    /**
     * Get MIME type
     */
    public static function mimeType($path): string
    {
        return mime_content_type($path) ?: '';
    }

    /**
     * Delete file
     */
    public static function delete($path, $disk = 'public'): bool
    {
        return Storage::disk($disk)->delete($path);
    }

    /**
     * Move file
     */
    public static function move($from, $to, $disk = 'public'): bool
    {
        return Storage::disk($disk)->move($from, $to);
    }

    /**
     * Copy file
     */
    public static function copy($from, $to, $disk = 'public'): bool
    {
        return Storage::disk($disk)->copy($from, $to);
    }

    /**
     * Download file
     */
    public static function download($path, $name = null, $disk = 'public')
    {
        $name = $name ?? basename($path);
        return Storage::disk($disk)->download($path, $name);
    }

    /**
     * Get file contents
     */
    public static function get($path, $disk = 'public'): ?string
    {
        return Storage::disk($disk)->get($path);
    }

    /**
     * Put file contents
     */
    public static function put($path, $contents, $disk = 'public'): bool
    {
        return Storage::disk($disk)->put($path, $contents);
    }

    /**
     * Check if file exists
     */
    public static function exists($path, $disk = 'public'): bool
    {
        return Storage::disk($disk)->exists($path);
    }

    /**
     * Get file size
     */
    public static function size($path, $disk = 'public'): int
    {
        return Storage::disk($disk)->size($path);
    }

    /**
     * Get file timestamp
     */
    public static function timestamp($path, $disk = 'public'): int
    {
        return Storage::disk($disk)->lastModified($path);
    }
}