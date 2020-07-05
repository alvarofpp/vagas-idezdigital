<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_branch', 'number', 'digit',
        'user_id', 'account_type_id',
    ];

    /**
     * Checks whether the account is of the Company type.
     *
     * @return bool
     */
    public function getIsCompanyAttribute()
    {
        return $this->account_type_id == AccountType::TYPE_COMPANY;
    }

    /**
     * Returns all transactions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getTransactionsAttribute()
    {
        return $this->transactions_made
            ->merge($this->transactions_received);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account_type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne(Company::class, 'account_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions_made()
    {
        return $this->hasMany(Transaction::class, 'account_from_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions_received()
    {
        return $this->hasMany(Transaction::class, 'account_to_id', 'id');
    }
}
