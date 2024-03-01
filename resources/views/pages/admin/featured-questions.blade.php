@extends('layouts/adminLayout')

@section('title', 'Featured Tasks Edit')

@section('content')
<div class="container-xxl">
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Featured Task: <strong>{{$task->title}}</strong> Questions</h5>
            <input type="hidden" value="{{$task->id}}" id="task_id">
            <div>
                <button class="btn btn-secondary me-2" type="button" id="add_question_btn"><i class='bx bx-plus me-1'></i>Add a Question</button>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="dt-responsive table border-top" id="feature_questions_table">
                <thead>
                    <tr class="text-nowrap">
                        <th>Category</th>
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($questions as $question)
                    <tr data-id="{{$question->id}}" data-category="{{$question->category}}" data-question="{{$question->question}}" data-answer="{{$question->answer}}">
                        <td class="question-category">{{$question->category}}</td>
                        <td class="question-question">{{$question->question}}</td>
                        <td class="question-answer">{{ $question->answer }}</td>
                        <td>
                            <a href="javascript:;" class="edit-question" data-id="{{$question->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question"><i class="bx bx-edit"></i></a>
                            <a href="javascript:;" class="delete-question" data-id="{{$question->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Question"><i class="bx bx-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Edit Title -->
<div class="modal fade" id="add_update_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Update Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="m_question_form">
                <input type="hidden" value="" id="m_selected_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-2">
                            <label for="m_category" class="form-label">Category</label>
                            <input type="text" id="m_category" class="form-control" placeholder="Category" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-2">
                            <label for="m_question" class="form-label">Question</label>
                            <textarea type="text" id="m_question" class="form-control" placeholder="Question" required rows="8"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-2">
                            <label for="m_answer" class="form-label">Answer</label>
                            <input type="text" id="m_answer" class="form-control" placeholder="Answer" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('page-script')
<script src="{{asset('assets/js/pages/admin/featured-questions-management.js')}}"></script>
@endsection