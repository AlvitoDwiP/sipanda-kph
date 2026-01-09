<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogService
{
    /**
     * Create a new log entry
     *
     * @param string $aksi The action performed
     * @param int|null $userId Optional user ID (if not provided, uses current authenticated user)
     * @return \App\Models\Log|null
     */
    public function createLog(string $aksi, ?int $userId = null): ?Log
    {
        try {
            $userId = $userId ?? Auth::id();
            
            if (!$userId) {
                return null; // Don't create log if no user is authenticated and no user ID provided
            }

            return Log::create([
                'user_id' => $userId,
                'aksi' => $aksi,
            ]);
        } catch (\Exception $e) {
            // Log the error but don't throw it to avoid breaking the main functionality
            \Log::error('Failed to create log entry: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a log entry for a specific action
     *
     * @param string $action The action description
     * @param array $additionalData Additional data to be stored with the log
     * @return \App\Models\Log|null
     */
    public function logAction(string $action, array $additionalData = []): ?Log
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return null;
        }

        $aksi = $action;
        if (!empty($additionalData)) {
            $aksi .= ' - ' . json_encode($additionalData);
        }

        return $this->createLog($aksi, $userId);
    }
}