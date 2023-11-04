'use strict';
var question = [];
var current_index = 0;
var myInterval = null;

(function () {
    $("#start_btn").on("click", function(){
        loadQuestions();
    })

    $("#submit_form").on("submit", function(e){
        e.preventDefault();
        const answer = $("#answer_input").val();
        if(answer.trim()==""){
            return;
        }

        clearInterval(myInterval);
        question[current_index]['user_answer'] = answer;

        $("#answer_input").val("");

        current_index++;
        if(current_index >= question.length){
            console.log("ended");
            alert("You are completed the test");
            return;
        }
        ask_question();
    })
})();

function loadQuestionsTest() {
    $("#start_btn").attr("disabled", true);
    $("#start_btn fa-spinner").removeClass("d-none");

    const _url = '/jeopardy-test/get-questions';
    $.ajax({
        url: _url,
        type: "GET",
        success: function(response) {
            if(response.code == 200) {
                question = response.questions;
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

function loadQuestions() {
    $("#start_btn").attr("disabled", true);
    $("#start_btn fa-spinner").removeClass("d-none");

    const _url = '/jeopardy-test/get-questions';
    $.ajax({
        url: _url,
        type: "GET",
        success: function(response) {
            if(response.code == 200) {
                question = response.questions;
                $("#start_step").addClass("d-none");
                $("#begin_step").removeClass("d-none");
                startCountDownForStartTest();
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
    $(".count-down").html("0:15");
    $("#question_index").html((current_index + 1) + "/50");

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
        if(remain_second == 0){
            clearInterval(myInterval);
            const answer = $("#answer_input").val();
            question[current_index]['user_answer'] = answer;

            $("#answer_input").val("");
            current_index++;
            if(current_index >= question.length){
                console.log("ended");
                alert("You are completed the test");
                return;
            }
            ask_question();
            return;
        }
        const html = "0:" + (remain_second >= 10 ? remain_second : ("0" + remain_second)); 
        $(".count-down").html(html);
    }, 1000);   
}


