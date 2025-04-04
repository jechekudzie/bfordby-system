<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ModuleContent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'module_id',
        'title',
        'description',
        'content_type',
        'file_path',
        'external_url',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the module that owns the content
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the full URL for the file if it exists
     */
    public function getFileUrl()
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }
        return null;
    }

    /**
     * Check if the content is an external resource (YouTube or link)
     */
    public function isExternal()
    {
        return in_array($this->content_type, ['youtube', 'external_link']);
    }

    /**
     * Check if the content is a file
     */
    public function isFile()
    {
        return in_array($this->content_type, ['pdf', 'video', 'audio', 'image']);
    }

    /**
     * Get the icon class for the content type
     */
    public function getIconClass()
    {
        return match($this->content_type) {
            'pdf' => 'fas fa-file-pdf',
            'video' => 'fas fa-file-video',
            'audio' => 'fas fa-file-audio',
            'image' => 'fas fa-file-image',
            'youtube' => 'fab fa-youtube',
            'external_link' => 'fas fa-external-link-alt',
            default => 'fas fa-file',
        };
    }
}
