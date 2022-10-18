<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskModel;

class DeleteOverdueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deleted Overdue Tasks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $items = TaskModel::withTrashed()->where('due_date', '<=', date('Y-m-d', strtotime('-1 month')))->get();
        $items->map(function ($item) {
            $item->forceDelete();
        });
    }
}
