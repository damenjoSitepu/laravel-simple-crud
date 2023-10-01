<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "authors";

    protected $primaryKey = "id";

    public $timestamps = true;

    protected $fillable = [
        "name",
    ];

    protected $appends = [

    ];

    // Relationship
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, "author_book", "author_id", "book_id");
    }
}
