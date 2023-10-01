<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "books";

    protected $primaryKey = "id";

    public $timestamps = true;

    protected $fillable = [
        "name",
        "isbn",
    ];

    protected $appends = [

    ];

    /**
     * Many Authors!
     *
     * @return BelongsToMany
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class,"author_book","book_id","author_id")
                    ->withPivot(["role"]);
    }
}
