@extends('layouts/adminLayout')

@section('title', 'Featured Tasks Management')

@section('content')
<div class="container-xxl">
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Questions</h5>
            <div>
                <button class="btn btn-secondary me-2" type="button" data-bs-toggle="modal" data-bs-target="#import_modal"><i class='bx bx-upload me-1'></i>Add From CSV</button>
            </div>
            
        </div>
        <div class="card-datatable table-responsive">
            <table class="dt-responsive table border-top" id="feature_tasks_table">
                <thead>
                    <tr class="text-nowrap">
                        <!-- <th>#</th> -->
                        <th>Thumbnail</th>
                        <th>Test Title</th>
                        <th>Number of Questions</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feature_tasks as $task)
                    <tr>
                        <td>
                            <img src="{{ asset($task->thumbnail) }}" alt class="thumbnail-img" />
                        </td>
                        <td>{{ $task->title }}</td>
                        <td>{{ count($task->get_question())}}</td>
                        <td><a href="javascript:;" class="delete-task" data-id="{{$task->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Task"><i class="bx bx-trash"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="import_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Featured Task From CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="pt-0 row g-2" id="form_import_file" enctype="multipart/form-data">
                <div class="modal-body">
                    <small class="px-3">Import files should be .csv extension</small><br>
                    <small class="px-3 mt-3">Required Fields: (The order of the fields is important)<br>
                    <span class="px-3">category, value, question, answer</span></small>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" id="m_title" class="form-control" placeholder="Enter Feature Task Title" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="title" class="form-label">Thumbnail</label>
                            <input type="file" id="m_thumbnail" name="thumbnail" class="form-control" accept="image/png, image/jpeg">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="formFile" class="form-label">Select CSV File</label>
                            <input class="form-control" type="file" id="formFile" name="formFile[]" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="m_add_file_btn">Import<i class="fas fa-spinner ms-2 fa-spin d-none"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages/admin/featured-tasks-management.js')}}"></script>
@endsection