<?php

namespace Modules\User\Entities;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tier extends Model
{
    protected $table = 'tiers';
}
