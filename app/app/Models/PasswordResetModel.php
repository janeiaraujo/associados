<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table = 'password_resets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'token_hash',
        'expires_at',
        'used_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;
    protected $deletedField = null;

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;

    /**
     * Create password reset token
     */
    public function createResetToken(int $userId, string $token, int $expirationMinutes = 30): bool
    {
        $tokenHash = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$expirationMinutes} minutes"));
        
        $data = [
            'user_id' => $userId,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
        ];
        
        return $this->insert($data) !== false;
    }

    /**
     * Verify reset token
     */
    public function verifyToken(string $token): ?array
    {
        $tokenHash = hash('sha256', $token);
        $now = date('Y-m-d H:i:s');
        
        return $this->where('token_hash', $tokenHash)
            ->where('expires_at >', $now)
            ->where('used_at', null)
            ->first();
    }

    /**
     * Mark token as used
     */
    public function markTokenAsUsed(int $resetId): bool
    {
        return $this->update($resetId, ['used_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Delete expired tokens
     */
    public function deleteExpiredTokens(): int
    {
        $now = date('Y-m-d H:i:s');
        return $this->where('expires_at <', $now)->delete();
    }

    /**
     * Delete user tokens
     */
    public function deleteUserTokens(int $userId): bool
    {
        return $this->where('user_id', $userId)->delete();
    }
}
