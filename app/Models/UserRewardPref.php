<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRewardPref extends Model
{
	protected $table="users_rewards_pref";

    protected $fillable = ['users_customers_id','survey_rewards_id','status'];
    protected $primaryKey = 'users_rewards_pref_id';
    public $timestamps = false;

}