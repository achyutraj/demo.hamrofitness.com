<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;

trait LogUserDetailIdTrait
{
    protected static function bootLogUserDetailIdTrait()
    {
        // Handle create and update operations
        static::saved(function ($model) {
            self::updateActivityLog($model);
        });

        // Handle delete operations
        static::deleted(function ($model) {
            self::updateActivityLog($model);
        });
    }

    private static function updateActivityLog($model)
    {
        $user = self::getAuthenticatedUser();

        // Update the activity log with the user details
        if ($user && $model->activities()->latest()->first()) {
            $model->activities()->latest()->first()->update([
                'detail_id' => $user->detail_id ?? null,
                'causer_id' => $user->id,
                'causer_type' => get_class($user),
            ]);
        }
    }

    /**
     * Get the authenticated user object
     *
     * @return mixed
     */
    protected static function getAuthenticatedUser()
    {
        $guards = ['merchant', 'customer', 'customer-api'];
        
        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                return auth()->guard($guard)->user();
            }
        }

        return null;
    }

    /**
     * Get the current authenticated user's username
     *
     * @return string
     */
    protected function getCurrentUser(): string
    {
        $user = self::getAuthenticatedUser();
        return $user ? $user->username : 'Unknown User';
    }

    /**
     * Get default activity log options
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        $userId = $this->getCurrentUser();
        
        return LogOptions::defaults()
            ->logOnly($this->getLoggedAttributes())
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => $this->getLogDescription($eventName, $userId))
            ->useLogName($this->getLogName());
    }

    /**
     * Get the attributes that should be logged
     * Override this method in your model to customize logged attributes
     *
     * @return array
     */
    protected function getLoggedAttributes(): array
    {
        return $this->fillable ?? ['*'];
    }

    /**
     * Get the log description
     * Override this method in your model to customize log description
     *
     * @param string $eventName
     * @param string $userId
     * @return string
     */
    protected function getLogDescription(string $eventName, string $userId): string
    {
        return "{$userId} has {$eventName} a " . class_basename($this);
    }

    /**
     * Get the log name
     * Override this method in your model to customize log name
     *
     * @return string
     */
    protected function getLogName(): string
    {
        return class_basename($this);
    }
}

