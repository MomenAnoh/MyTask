<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المهام</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📋 إدارة المهام</h1>
        <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#addTaskModal">
            ➕ إضافة مهمة جديدة
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">❌ {{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="tasks-grid">
        @forelse($tasks as $todo)
        <div class="task-card">
            <div class="task-header">
                <div>
                    <h3 class="task-title">{{ $todo->title }}</h3>
                    <p class="task-description">{{ $todo->description ?? 'لا يوجد وصف' }}</p>
                </div>
            </div>

            <div class="task-meta">
                <div class="task-date">
                    📅 {{ $todo->date ?? 'غير محدد' }}
                </div>
                <div>
                    @if($todo->status === 'completed')
                        <span class="status-badge status-completed">مكتملة ✓</span>
                    @elseif($todo->status === 'in_progress')
                        <span class="status-badge status-progress">قيد التنفيذ</span>
                    @else
                        <span class="status-badge status-pending">معلقة</span>
                    @endif
                </div>
            </div>

            <div class="task-actions">
                <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{$todo->id}}">
                    ✏️ تعديل
                </button>
                <button class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{$todo->id}}">
                    🗑️ حذف
                </button>
            </div>
        </div>

        <div class="modal fade" id="editModal{{$todo->id}}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('tasks.update', $todo->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">✏️ تعديل المهمة</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">العنوان</label>
                                <input type="text" name="title" class="form-control" value="{{ $todo->title }}" required placeholder="عنوان المهمة">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الوصف</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="تفاصيل المهمة...">{{ $todo->description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">التاريخ</label>
                                <input type="date" name="date" class="form-control" value="{{ $todo->date }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الحالة</label>
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $todo->status == 'pending' ? 'selected' : '' }}>معلقة</option>
                                    <option value="in_progress" {{ $todo->status == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                                    <option value="completed" {{ $todo->status == 'completed' ? 'selected' : '' }}>مكتملة</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn-primary-modal">💾 حفظ التغييرات</button>
                            <button type="button" class="btn-secondary-modal" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="deleteModal{{$todo->id}}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">🗑️ تأكيد الحذف</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p style="color: #374151; font-size: 1.1rem;">هل أنت متأكد من حذف المهمة: <strong>"{{ $todo->title }}"</strong>؟</p>
                        <p style="color: #6b7280;">لا يمكن التراجع عن هذا الإجراء.</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('tasks.destroy', $todo->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger-modal">نعم، احذف</button>
                        </form>
                        <button type="button" class="btn-secondary-modal" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state" style="grid-column: 1/-1;">
            <div class="empty-state-icon">📝</div>
            <p class="empty-state-text">Not Found Tasks This Time 🤷🏼‍♀️  </p>
        </div>
        @endforelse
    </div>

    <div style="margin-top: 30px;">
        {{ $tasks->links() }}
    </div>
</div>
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">➕ إضافة مهمة جديدة</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">العنوان</label>
                        <input type="text" name="title" class="form-control" placeholder="ماذا تريد أن تفعل؟" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="تفاصيل إضافية..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">التاريخ</label>
                        <input type="date" name="date" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-primary-modal">➕ إضافة المهمة</button>
                    <button type="button" class="btn-secondary-modal" data-bs-dismiss="modal">إلغاء</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
