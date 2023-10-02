<?php

namespace App\Models;

use App\Services\Util\RoleService;
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

    /**
     * Books
     *
     * @return BelongsToMany
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, "author_book", "author_id", "book_id")
                    ->withPivot(["role"]);
    }

    /**
     * Ordered Book
     *
     * @return BelongsToMany
     */
    public function orderedBooks(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, "author_book", "author_id", "book_id")
                    ->withPivot(["role"])
                    ->orderByRaw(RoleService::getOrderStatement());
    }
}
