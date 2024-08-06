<?php

namespace App\Models\Clubs;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $name
 */
class Team extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'club_id', 'name', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**************************************************************************
     * Relationships
     **************************************************************************/

    /**
     * @description  The players that belong to the team.
     * @return BelongsToMany
     */
    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_teams', 'team_id', 'user_id');
    }

    /**************************************************************************
     * Methods
     **************************************************************************/
    public function getName(): string
    {
        return $this->name;
    }
}
