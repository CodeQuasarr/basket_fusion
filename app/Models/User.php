<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Clubs\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $weight
 * @property string $height
 * @property string $birth_date
 * @property int $number
 * @property int $role
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, softDeletes;


    public const ADMIN = 99;
    public const COACH = 2;
    public const PLAYER = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role', 'name', 'email', 'password', 'weight', 'height', 'birth_date', 'number', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @description The attributes that should be mutated to dates.
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('name', 'asc');
        });
    }

    /**************************************************************************
     * Relationships
     **************************************************************************/

    /**
     * @description Get the players that the coach has
     * @return BelongsToMany
     */
    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'coaches_players', 'coach_id', 'player_id');
    }

    /**
     * @description Get the coach of the player
     * @return BelongsToMany
     */
    public function Coach(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'coaches_players', 'player_id', 'coach_id');
    }

    /**
     * @description Get the teams that the user belongs to
     * @return BelongsToMany
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'user_teams', 'user_id', 'team_id');
    }


    /**************************************************************************
     * Methods
     **************************************************************************/

    /**
     * @description Get the role name
     * @return string
     */
    public function getRoleName(): string
    {
        return match ($this->role) {
            self::ADMIN => 'Admin',
            self::COACH => 'Coach',
            self::PLAYER => 'Player',
            default => 'Unknown',
        };
    }

    /**
     * @description Check if the user has a specific role
     * @param int $role
     * @return bool
     */
    public function hasRole(int $role): bool
    {
        return $this->role === $role;
    }

    /**
     * @description method to get the user name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
