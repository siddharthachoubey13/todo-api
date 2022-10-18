<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'task';
    protected $fillable = ['title', 'due_date', 'status'];

    public function subtask()
    {
        return $this->hasMany(SubTaskModel::class, 'task_id', 'id');
    }
}
