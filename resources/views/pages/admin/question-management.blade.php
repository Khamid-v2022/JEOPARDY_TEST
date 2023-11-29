@extends('layouts/adminLayout')

@section('title', 'Question Management')

@section('content')
<div class="container-xxl">
    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Questions</h5>
            <div>
                <button class="btn btn-secondary me-2" type="button" data-bs-toggle="modal" data-bs-target="#import_modal"><i class='bx bx-upload me-1'></i>Add From CSV</button>
                <button class="btn btn-primary me-4" type="button" id="add_question_btn"><i class='bx bx-plus me-1'></i>Add</button>
                <!-- <a href="/admin/question-management/structure-question" class="btn btn-primary">Make as structure questions</a>  -->
                <!-- <a href="/admin/question-management/update_questions_remove_html" class="btn btn-primary d-none">Remove HTML tag</a>  -->
                <!-- <a href="/admin/question-management/remove_questions_have_html" class="btn btn-primary">Remove Questions have HTML tags</a> -->
                <!-- <a href="/admin/question-management/insert_question_info_to_answer_table" class="btn btn-primary">Insert Questions Info to Answer Table</a> -->
            </div>
            
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

<!-- Import Modal -->
<div class="modal fade" id="import_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Questions From CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="pt-0 row g-2" id="form_import_file" enctype="multipart/form-data">
                <div class="modal-body">
                    <small class="px-3">Import files should be .csv extension</small><br>
                    <small class="px-3 mt-3">Required Fields: (The order of the fields is important)<br>
                    <span class="px-3">category, value, question, answer</span></small>
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
<script src="{{asset('assets/js/pages/admin/question-management.js')}}"></script>
@endsection