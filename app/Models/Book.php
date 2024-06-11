<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, string $string): Builder|QueryBuilder
    {
        return $query->where('title', 'LIKE', "%$string%");
    }

    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withCount([
            'reviews' => fn(Builder $q) => $this->dateFilterRange($q, $from, $to)
        ]);
    }
    public function scopeWithAvgRating(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $this->dateFilterRange($q, $from, $to)
        ], 'stars');
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->WithReviewsCount()->orderBy('reviews_count', 'desc');
    }

    public function scopeMinReviews(Builder $query, int $minReview) : Builder|QueryBuilder
    {
        return $query->having('reviews_count', '>=', $minReview);
    }

    public function scopeHighRatings(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->WithAvgRating()->orderBy('reviews_avg_stars', 'desc');
    }

    private function dateFilterRange(Builder $query, $from = null, $to = null){
        if($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to){
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    public function scopePopularLastMonth(Builder $query) : Builder | QueryBuilder
    {
        return $query->popular(now()->subMonth(), now())
        ->highRatings(now()->subMonth(), now())
        ->minReviews(2);
    }
    public function scopePopularLast6Months(Builder $query) : Builder | QueryBuilder
    {
        return $query->popular(now()->subMonths(6), now())
        ->highRatings(now()->subMonths(6), now())
        ->minReviews(5);
    }

    public function scopeHighestRatedLastMonth(Builder $query) : Builder | QueryBuilder
    {
        return $query->highRatings(now()->subMonth(), now())
        ->popular(now()->subMonth(), now())
        ->minReviews(2);
    }
    public function scopeHighestRatedLast6Months(Builder $query) : Builder | QueryBuilder
    {
        return $query->highRatings(now()->subMonths(6), now())
        ->popular(now()->subMonths(6), now())
        ->minReviews(5);
    }

    protected static function booted()
    {
        static::updated(fn(Book $book) => cache()->forget("book:$book->id"));
        static::deleted(fn(Book $book) => cache()->forget("book:$book->id"));
    }


}
