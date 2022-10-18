<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubTaskModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sub_task';
    protected $fillable = ['task_id', 'title', 'due_date', 'status'];
}
