<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cosupervisor extends Model
{
    protected $guarded = [];
    protected $with = ['person'];

    public function person()
    {
        return $this->morphTo();
    }

    public function getNameAttribute()
    {
        return $this->person->name;
    }

    public function getEmailAttribute()
    {
        return $this->person->email;
    }

    public function getDesignationAttribute()
    {
        return $this->person->designation;
    }

    public function getAffiliationAttribute()
    {
        if ($this->person_type === User::class) {
            return optional($this->person->college)->name ?? 'Unknown';
        }

        return $this->person->affiliation;
    }
}
