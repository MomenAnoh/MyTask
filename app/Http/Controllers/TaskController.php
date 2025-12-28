<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use App\Http\Requests\AddTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())->paginate(10);
        return view('dashboard', compact('tasks'));
    }

    public function store(AddTaskRequest $request)
    {
        $data=$request->validated();
       $response= $this->taskService->store($data);
       if(!$response){
        return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة المهمة');
       }
        return redirect()->back()->with('success', 'تم إضافة المهمة بنجاح');
    }


    public function update(UpdateTaskRequest $request,$id)
    {
         $data=$request->validated();
         $response= $this->taskService->update($data,$id);
        if(!$response){
           return redirect()->back()->with('error', 'حدث خطأ أثناء تعديل المهمة');
        }
        return redirect()->back()->with('success', 'تم التعديل بنجاح');

    }

    public function destroy($id)
    {
        $response = $this->taskService->destroy($id);
        if(!$response){
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المهمة');
        }
        return redirect()->back()->with('success', 'تم حذف المهمة');
    }
}
