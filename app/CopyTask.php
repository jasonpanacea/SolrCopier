<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CopyTask extends Model
{
    //
    protected $table = 'tasks';

    protected $fillable = ['indexList','srcHost','srcPort','destHost','destPort',
        'query', 'sortField', 'sortOrder', 'batchSize', 'progress', 'status'];
}
