<?php

namespace App\Models\Banks;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Media;
use App\Models\Banks\TCHistoryDetails;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TCHistory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tc_history';
    protected $primaryKey = 'id';
    protected $appends =[
        'file_name'
    ];

    protected $casts = [
        'customer_info' => 'array'
    ];

    protected $fillable = [
        "printed_on",
        "branch",
        "statement_period_start",
        "statement_period_end",
        "currency",
        "open_date",
        "account_number",
        "current_available_balance",
        "current_ledger_balance",
        "customer_info",
        "author_id"
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    /**
     * Get all of the comments for the TransactionHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function history_details()
    {
        return $this->hasMany(TCHistoryDetails::class, 'tc_history_id', 'id');
    }

    public function mediable():morphOne
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function getFileNameAttribute(){
        return '40586';
    }

    public function getFileTypeAttribute()
    {
        foreach (self::$types as $type => $mimes) {
            if (in_array($this->mime_type, $mimes)) {
                return $type;
            }
        }

        return 'other';
    }

    public function getPreviewUrlAttribute()
    {
        $urls = collect([
            'image' => url("storage/media/{$this->created_at->format('Y/m/d')}/{$this->id}/{$this->file_name}"),
            'audio' => asset('images/file-type-audio.svg'),
            'video' => asset('images/file-type-video.svg'),
            'document' => asset('images/file-type-document.svg'),
            'archive' => asset('images/file-type-archive.svg'),
            'other' => asset("images/file-type-other.svg")
        ]);

        return $urls[$this->file_type];
    }

    public function getUrlAttribute()
    {
        return url($this->path);
    }

    public function getPathAttribute()
    {
        return "media/{$this->created_at->format('Y/m/d')}/{$this->id}/{$this->file_name}";
    }

    public static function getMimes($fileType)
    {
        return self::$types[$fileType] ?? [];
    }

    public function scopeType(Builder $builder, $type)
    {
        if (!is_null($type)) {
            $builder->whereIn('mime_type', self::getMimes($type));
        }

        return $builder;
    }

    public function scopeMonth(Builder $builder, $date)
    {
        if (!is_null($date)) {
            $builder->whereBetween('created_at', [
                Carbon::createFromFormat('d-m-Y', $date)->startOfMonth(),
                Carbon::createFromFormat('d-m-Y', $date)->endOfMonth(),
            ]);
        }

        return $builder;
    }

    public function scopeSearch(Builder $builder, $term)
    {
        if (!is_null($term)) {
            $builder->where('name', 'LIKE', "%$term%");
        }

        return $builder;
    }
}
