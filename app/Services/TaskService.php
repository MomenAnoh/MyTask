<?php

namespace App\Services;
use App\Models\Task;

class TaskService
{
    public function store($data)
    {
        try {
           $data['user_id'] = auth()->id();
           Task::create($data);
           return true;
         } catch (\Exception $e) {
              return false;
         }
    }


    public function update($data,$id)
    {
        try {
            $task = Task::find($id);
            if (!$task) {
                return false;
            }
            $data['user_id'] = auth()->id();
            $task->update($data);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return false;
        }
        $task->delete();
        return true;
    }
}
