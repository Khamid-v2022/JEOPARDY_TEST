'use strict';
var question = [];
var current_index = 0;
var myInterval = null;
var question_count = 50;

var current_trial_test_header = null;

(function () {
    $(".start-btn").on("click", function(){
        question_count = $("#question_count_sel").val();
        $(".question-count").html(question_count);
        loadQuestions();
    })

    $(".start-again-btn").on("click", function(){
        question_count = $("#question_count_again_sel").val();
        $(".question-count").html(question_count);

        loadQuestions();
    })

    $(".start-feature-test-btn").on("click", function(){
        // question_count = $("#question_count_again_sel").val();
        // $(".question-count").html(question_count);

        // loadQuestions();
        $(this).find("fa-spinner").removeClass("d-none"); 
        
        let test_id = $(this).attr("data-id");
        loadFeaturedTestQuestions(test_id);
    })

    $("#submit_form").on("submit", function(e){
        e.preventDefault();
        const answer = $("#answer_input").val();
        if(answer.trim()==""){
            return;
        }
        
        var audio = new Audio('/assets/sound/correct_effect.mp3');
        audio.play();
        
        ask_next();
    })
})();

function loadQuestions() {
    $(".start-btn").attr("disabled", true);
    $(".start-btn fa-spinner").removeClass("d-none");  
    $(".start-again-btn").attr("disabled", true);
    $(".start-again-btn fa-spinner").removeClass("d-none");    

    $(".start-feature-test-btn").attr("disabled", true);

    const _url = '/jeopardy-test/get-questions/' + question_count;
    $.ajax({
        url: _url,
        type: "GET",
        success: function(response) {
            if(response.code == 200) {
                current_trial_test_header = response.header_id;
                question = response.questions;

                
                $("#start_step").addClass("d-none");
                $("#complete_step").addClass("d-none");
                $("#featured_tests_wrapper").addClass("d-none");

                $("#begin_step").removeClass("d-none");
                startCountDownForStartTest();

                $(".start-btn").removeAttr("disabled");
                $(".start-btn fa-spinner").addClass("d-none");
                $(".start-again-btn").removeAttr("disabled");
                $(".start-again-btn fa-spinner").addClass("d-none");
                $(".start-feature-test-btn").removeAttr("disabled");
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: '',
                    text: response.message,
                }).then(function(){
                    location.reload();
                })
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            Swal.fire({
                icon: 'error',
                title: '',
                text: XMLHttpRequest.responseJSON.message,
            })
        }       
    })
}

function loadFeaturedTestQuestions(test_id) {
    $(".start-btn").attr("disabled", true);
    $(".start-again-btn").attr("disabled", true);  

    $(".start-feature-test-btn").attr("disabled", true);

    const _url = '/jeopardy-test/get-feature-test/' + test_id;
    $.ajax({
        url: _url,
        type: "GET",
        success: function(response) {
            if(response.code == 200) {
                current_trial_test_header = response.header_id;
                question = response.questions;
                question_count = question.length;
                $(".question-count").html(question_count);

                $("#start_step").addClass("d-none");
                $("#complete_step").addClass("d-none");
                $("#featured_tests_wrapper").addClass("d-none");

                $("#begin_step").removeClass("d-none");
                startCountDownForStartTest();

                $(".start-btn").removeAttr("disabled");
                $(".start-again-btn").removeAttr("disabled");
                $(".start-feature-test-btn").removeAttr("disabled");
                $(".start-feature-test-btn fa-spinner").addClass("d-none");
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: '',
                    text: response.message,
                }).then(function(){
                    location.reload();
                })
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            Swal.fire({
                icon: 'error',
                title: '',
                text: XMLHttpRequest.responseJSON.message,
            })
        }       
    })
}

function startCountDownForStartTest() {
    let remain_second = 10;
    let myInterval = setInterval(function () {
        remain_second--;
        if(remain_second == 0){
            clearInterval(myInterval);
            $("#begin_step").addClass("d-none");
            $("#question_form").removeClass("d-none");
            current_index = 0;
            ask_question();
            return;
        }
        $(".begin-count-down").html("0:0" + remain_second);
    }, 1000);   
}

function ask_question(){
    $(".count-down").html("15");
    $("#question_index").html((current_index + 1) + "/" + question_count);

    $(".question-wrapper .category").html(question[current_index].category);
    $(".question-wrapper .question").html(question[current_index].question);
    $(".question-wrapper .category-title").removeClass("d-none");
    $(".question-wrapper .question-scene").addClass("d-none");
    $(".submit-form").addClass("d-none");
    setTimeout(function(){
        $(".question-wrapper .category-title").addClass("d-none");
        $(".question-wrapper .question-scene").removeClass("d-none");
        $(".submit-form").removeClass("d-none");
        $("#answer_input").focus()
        startCountDownForQustion();
    }, 3000);
}

function startCountDownForQustion() {
    let remain_second = 15;
    myInterval = setInterval(function () {
        remain_second--;
        const html = remain_second >= 10 ? remain_second : ("0" + remain_second); 
        $(".count-down").html(html);

        if(remain_second == 0){
            ask_next();
            return;
        }
    }, 1000);   
}

function ask_next(){
    clearInterval(myInterval);
    const answer = $("#answer_input").val();
    question[current_index]['user_answer'] = answer;
    question[current_index]['answer_time'] = 15 - parseInt($(".count-down").html());

    $("#answer_input").val("");
    current_index++;
    if(current_index >= question.length){
        end_question();
        return false;
    }

    ask_question();
    return true;
}

function end_question() {
    $("#question_form").addClass("d-none");
    $("#complete_step").removeClass("d-none");
    

    const _url = "/jeopardy-test/submit-response";
    $.ajax({
        url: _url,
        type: "POST",
        data: {
            data: JSON.stringify(question),
            header_id: current_trial_test_header
        },
        success: function(response) {
            if(response.code == 200) {
                $(".submitting-wrapper").addClass("d-none");
                $(".your-score").html(response.score);
                $(".result-wrapper").removeClass("d-none");

                drawingShareMyScoreSection(response);
                
                $("#featured_tests_wrapper").removeClass("d-none");
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            Swal.fire({
                icon: 'error',
                title: '',
                text: XMLHttpRequest.responseJSON.message,
            })
        }       
    })
}

function goto_detailPage() {
    if(current_trial_test_header)
        location.href = '/jeopardy-test/view-detail/' + current_trial_test_header;
}

function drawingShareMyScoreSection(response) {
    $(".answer-check").html("");

    let score_checks_html = ''; 
    if(response.my_answers.length > 0) {
        for(let index = 0; index < response.my_answers.length; index++) {
            if(response.my_answers[index].is_correct == 1) {
                score_checks_html += '✅';
            } else {
                score_checks_html += '❌';
            }
        }
    }

    $(".answer-check").html(score_checks_html);
    $(".test-time").html(response.test_time);
    $(".current-streak").html(response.streak_days);

    let social_btns_html = "";
    social_btns_html += "<li><a href='" + response.shareComponent.facebook + "' class='btn btn-sm btn-secondary' target='_blank'> <i class='bx bxl-facebook me-2'></i> Share </a></li>";
    social_btns_html += "<li><a href='" + response.shareComponent.twitter + "' class='btn btn-sm btn-secondary' target='_blank'> <i class='bx bxl-twitter me-2'></i> Share </a></li>";

    $(".share-btns-wrapper").html(social_btns_html);
    $(".share-wrapper").removeClass("d-none");
}

