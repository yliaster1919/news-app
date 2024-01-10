<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $fillable = [
        'newsId',
        'webTitle',
        'type',
        'sectionId',
        'sectionName',
        'webPublicationDate',
        'webUrl',
        'apiUrl',
        'pillarId',
        'pillarName'
    ];
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('d-m-Y');
    }
}
