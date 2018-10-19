<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FingervaultToken
 *
 * @property \DateTime $valid_to
 * @property \DateTime $used_at
 * @property string $login_token
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property User $user
 *
 * @package App
 */
class FingervaultToken extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
