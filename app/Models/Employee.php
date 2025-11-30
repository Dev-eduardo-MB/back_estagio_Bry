<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model {
    use HasFactory;

    protected $fillable = ['login','name','cpf','email','password'];
     
    //proteção desativada para passar a senha no objeto 
    //protected $hidden = ['password'];

    public function companies() {
        return $this->belongsToMany(Company::class);
    }
}
