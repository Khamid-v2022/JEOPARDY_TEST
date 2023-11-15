@extends('layouts/adminLayout')

@section('title', 'Question Management')

@section('content')
<div class="container-xxl">
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Questions</h5>
            <button class="btn btn-primary me-4" type="button" id="add_question_btn"><i class='bx bx-plus me-1'></i>Add Question</button>
        </div>
        <div class="card-datatable table-responsive">
            <table class="dt-responsive table border-top" id="questions_table">
                <thead>
                    <tr class="text-nowrap">
                        <!-- <th>#</th> -->
                        <th>Category</th>
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Edit Modal -->
<div class="modal fade" id="question_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="question_form">
                <input type="hidden" value="" id="m_selected_q_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" id="m_category" class="form-control" placeholder="Enter Category" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="question" class="form-label">Question</label>
                            <textarea type="text" id="m_question" class="form-control" rows="8" placeholder="Enter Question" required></textarea>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-0">
                            <label for="answer" class="form-label">Answer</label>
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
<script src="{{asset('assets/js/pages/admin/question-management.js')}}"></script>
@endsection