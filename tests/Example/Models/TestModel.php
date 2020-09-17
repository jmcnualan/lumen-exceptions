<?php

namespace Dmn\Exceptions\Example\Models;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $table = 'test';

    protected $fillable = ['name'];

    public $timestamps = false;
}
