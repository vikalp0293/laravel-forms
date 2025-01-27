
$(document).ready(function(){
    var root_url = "<?php echo url('/'); ?>";

    var subject = $('#subject').val();
    if(subject != ''){
        getTopics(subject);
    }

    // // Initialize the fields' state based on the checkbox
    // toggleBackgroundFields($('#background').is(':checked'));
    // // toggleBackgroundFields(true);

    

});

$(document).on('change', '.question-choice', function(){ 
    var questionNo = $(this).data('question');

    $('.mc_section_'+questionNo).hide();
    $('.sa_section_'+questionNo).hide();
    $('.admin_section_'+questionNo).hide();
    $('.mc_options_'+questionNo).attr('required', false);

    if ($(this).val() === 'mc') {
        $('.mc_section_'+questionNo).show();
        $('.mc_options_'+questionNo).attr('required', true);
    } else if ($(this).val() === 'sa') {
        $('.sa_section_'+questionNo).show();
    } else if ($(this).val() === 'admin') {
        $('.admin_section_'+questionNo).show();
    }
});

$(document).on('click', '.btn-nobackground', function(){ 
    $('.button_background').attr('style', 'display: none !important;');
});

$(document).on('change', '.background', function(){ 
    var backgroundSectionNo = $(this).data('backgroundsection');
    var backgroundValue = $(this).val();
    toggleBackgroundFields(this.checked,backgroundSectionNo,backgroundValue);
});

function toggleBackgroundFields(isEnabled,backgroundSectionNo,backgroundValue) {

    if(isEnabled === true){
        $('.background_section_'+backgroundSectionNo).show();
        $('.question_background_'+backgroundSectionNo).val(backgroundValue);
        $('.button_background').show();
        $('.question_background').val(1);

        var lastCheckbox = $('.background').last();

        // Check if the last checkbox is checked
        if (lastCheckbox.is(':checked')) {
            var lastCheckboxValue = lastCheckbox.val();
        }else{
            var lastCheckboxValue = 0;
        }

        $('.button_background').data('backgroundvalue', lastCheckboxValue);

    }else{
        $('.background_section_'+backgroundSectionNo).hide();
        $('.question_background_'+backgroundSectionNo).val(0);
        $('.button_background').attr('style', 'display: none !important;');
        $('.question_background').val(0);
        $('.button_background').data('backgroundvalue', 0);
    }

    $('.background-image-input-'+backgroundSectionNo).each(function () {
        $(this).prop('disabled', !isEnabled); // Disable if checkbox is unchecked
    });
}


// Add more questions code goes here
$(document).on('click', '.add_button', function(){ 
    // Add more questions code goes here
    var totalQuestions = $('.question-box').length;
    var newQuestionCount = totalQuestions+1;

    var backgroundType = $(this).data('background');
    var backgroundValue = 0;
    
    if(backgroundType == 'same-background'){
        var backgroundValue = $(this).data('backgroundvalue');
        var background = '<p class="background_section_'+newQuestionCount+'">Using same background as above</p>';
    }else{
        var background = '\
            <div class="row g-3 align-center background_section_'+newQuestionCount+'">\
                <div class="col-lg-3">\
                    <label class="form-label" for="Background">Background</label>\
                </div>\
                <div class="col-lg-9">\
                    <div class="custom-control custom-control-md custom-switch">\
                        <input type="checkbox" data-backgroundsection="'+newQuestionCount+'" name="background" id="background_'+newQuestionCount+'" class="custom-control-input background" value="'+newQuestionCount+'" data-parsley-multiple="background">\
                        <label class="custom-control-label" for="background_'+newQuestionCount+'"> </label>\
                    </div>\
                </div>\
            </div>\
            <div class="background_section_'+newQuestionCount+'" style="display:none;">\
                <div class="row g-3 align-center">\
                    <div class="col-lg-3">\
                        <div class="form-group">\
                            <label class="form-label" for="instructions">Instructions</label>\
                        </div>\
                    </div>\
                    <div class="col-lg-9">\
                        <textarea data-parsley-errors-container=".parsley-container-instructions" id="instructions"  name="instructions" class="form-control background-image-input-'+newQuestionCount+'" autocomplete="off"></textarea>\
                    </div>\
                </div>\
                <div class="row g-3 align-center">\
                    <div class="col-lg-3">\
                        <div class="form-group">\
                            <label class="form-label" for="instructions_2">Instructions 2</label>\
                        </div>\
                    </div>\
                    <div class="col-lg-9">\
                        <textarea  data-parsley-errors-container=".parsley-container-instructions_2" id="instructions_2" name="instructions_2" class="form-control background-image-input-'+newQuestionCount+'" autocomplete="off"></textarea>\
                    </div>\
                </div>\
                <div class="row g-3 align-center ">\
                    <div class="col-lg-3">\
                        <x-inputs.verticalFormLabel label="Upload Image 1" for="default-06"/>\
                    </div>\
                    <div class="col-lg-9">\
                        <div class="form-group">\
                            <div class="form-control-wrap">\
                                <div class="custom-file">\
                                    <input type="file" class="custom-file-input background-image-input-'+newQuestionCount+'" id="image_upload_1" name="image_upload_1">\
                                    <label class="custom-file-label" for="image_upload_1">Choose file</label>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
                <div class="row g-3 align-center">\
                    <div class="col-lg-3">\
                        <div class="form-group">\
                            <label class="form-label" for="instructions_3">Instructions 3</label>\
                        </div>\
                    </div>\
                    <div class="col-lg-9">\
                        <textarea  data-parsley-errors-container=".parsley-container-instructions_3" id="instructions_3" name="instructions_3" class="form-control background-image-input-'+newQuestionCount+'" autocomplete="off"></textarea>\
                    </div>\
                </div>\
                <div class="row g-3 align-center">\
                    <div class="col-lg-3">\
                        <x-inputs.verticalFormLabel label="Upload Image 2" for="default-06"/>\
                    </div>\
                    <div class="col-lg-9">\
                        <div class="form-group">\
                            <div class="form-control-wrap">\
                                <div class="custom-file">\
                                    <input type="file" class="custom-file-input background-image-input-'+newQuestionCount+'" id="image_upload_2" name="image_upload_2">\
                                    <label class="custom-file-label" for="image_upload_2">Choose file</label>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>  \
            </div>\
        ';
    }



    var fieldHTML = '\
        '+background+'\
        <div class="question-box question-wrapper-'+newQuestionCount+'">\
            <input type="text" class="question_background_'+newQuestionCount+'" value="'+backgroundValue+'" name="questions['+newQuestionCount+'][question_background]">\
            <div class="row g-3 align-center">\
                <div class="col-lg-3">\
                    <div class="form-group">\
                        <label class="form-label" for="question">Question<span class="text-danger">*</span></label>\
                    </div>\
                </div>\
                <div class="col-lg-9">\
                    <textarea required data-parsley-errors-container=".parsley-container-question" id="question"  name="questions['+newQuestionCount+'][question]" class="form-control" autocomplete="off"></textarea>\
                </div>\
            </div>\
            <div class="row g-3 align-center">\
                <div class="col-lg-3">\
                    <label class="form-label" for="default-06">Question Image 1</label>\
                </div>\
                <div class="col-lg-9">\
                    <div class="form-group">\
                        <div class="form-control-wrap">\
                            <div class="custom-file">\
                                <input type="file" class="custom-file-input" id="question_image_1'+newQuestionCount+'" name="questions['+newQuestionCount+'][question_image_1]">\
                                <label class="custom-file-label" for="question_image_1'+newQuestionCount+'">Choose file</label>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div>\
            \
            <div class="row g-3 align-center">\
                <div class="col-lg-3">\
                    <div class="form-group">\
                        <label class="form-label" for="question_text_2">Question Text 2</label>\
                    </div>\
                </div>\
                <div class="col-lg-9">\
                    <textarea  data-parsley-errors-container=".parsley-container-question_text_2" id="question_text_2" name="questions['+newQuestionCount+'][question_text_2]" class="form-control" autocomplete="off"></textarea>\
                </div>\
            </div>\
            <div class="row g-3 align-center">\
                <div class="col-lg-3">\
                    <label class="form-label" for="default-06">Question Image 2</label>\
                </div>\
                <div class="col-lg-9">\
                    <div class="form-group">\
                        <div class="form-control-wrap">\
                            <div class="custom-file">\
                                <input type="file" class="custom-file-input" id="question_image_2'+newQuestionCount+'" name="questions['+newQuestionCount+'][question_image_2]">\
                                <label class="custom-file-label" for="question_image_2'+newQuestionCount+'">Choose file</label>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div>\
            <div class="row g-3 align-center">\
                <div class="col-lg-3">\
                    <label class="form-label" for="default-06">Question Type</label>\
                </div>\
                <div class="col-lg-9">\
                    <div class="form-group">\
                        <div class="form-control-wrap">\
                            <div class="custom-control custom-control-xs custom-radio">\
                                <input type="radio" name="questions['+newQuestionCount+'][question_type]" data-question="'+newQuestionCount+'" id="question_type_mc_'+newQuestionCount+'" class="custom-control-input radio-btn question-choice" value="mc">\
                                <label class="custom-control-label"  for="question_type_mc_'+newQuestionCount+'">Make it MC</label>\
                            </div>\
                            <div class="custom-control custom-control-xs custom-radio">\
                                <input type="radio" name="questions['+newQuestionCount+'][question_type]" data-question="'+newQuestionCount+'" id="question_type_sa_'+newQuestionCount+'" class="custom-control-input radio-btn question-choice" value="sa">\
                                <label class="custom-control-label"  for="question_type_sa_'+newQuestionCount+'">Make it Short answer</label>\
                            </div>\
                            <div class="custom-control custom-control-xs custom-radio">\
                                <input type="radio" name="questions['+newQuestionCount+'][question_type]" data-question="'+newQuestionCount+'" id="question_type_admin_'+newQuestionCount+'" class="custom-control-input radio-btn question-choice" value="admin">\
                                <label class="custom-control-label"  for="question_type_admin_'+newQuestionCount+'">Admin Only</label>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div>\
            <hr>\
            <div class="mc_section_'+newQuestionCount+'" style="display: none;">\
                <div class="row g-3 align-center">\
                    <div class="col-lg-3">\
                        <x-inputs.verticalFormLabel label="MC Options" for="default-112"/>\
                    </div>\
                    <div class="col-lg-9">\
                        <table>\
                            <tr>\
                                <td>\
                                    Correct Answer\
                                </td>\
                                <td></td>\
                            </tr>\
                            <tr>\
                                <td></td>\
                                <td style="width:80%">\
                                    <div class="custom-radio">\
                                        <input type="radio" name="questions['+newQuestionCount+'][correct_option]" id="option_radio_1_'+newQuestionCount+'" class="custom-control-input radio-btn mc_options_'+newQuestionCount+'" value="1">\
                                        <label class="custom-control-label col-lg-9"  for="option_radio_1_'+newQuestionCount+'">\
                                            <input type="text" name="questions['+newQuestionCount+'][option1]" placeholder="Option 1" class="form-control mc_options_'+newQuestionCount+'"  required>\
                                        </label>\
                                    </div>\
                                    <br><br>\
                                    <div class="custom-radio">\
                                        <input type="radio" name="questions['+newQuestionCount+'][correct_option]" id="option_radio_2_'+newQuestionCount+'" class="custom-control-input radio-btn mc_options_'+newQuestionCount+'" value="2">\
                                        <label class="custom-control-label col-lg-9"  for="option_radio_2_'+newQuestionCount+'">\
                                            <input type="text" name="questions['+newQuestionCount+'][option2]" placeholder="Option 2" class="form-control mc_options_'+newQuestionCount+'"  required>\
                                        </label>\
                                    </div>\
                                    <br><br>\
                                    <div class="custom-radio">\
                                        <input type="radio" name="questions['+newQuestionCount+'][correct_option]" id="option_radio_3_'+newQuestionCount+'" class="custom-control-input radio-btn mc_options_'+newQuestionCount+'" value="3">\
                                        <label class="custom-control-label col-lg-9"  for="option_radio_3_'+newQuestionCount+'">\
                                            <input type="text" name="questions['+newQuestionCount+'][option3]" placeholder="Option 3" class="form-control mc_options_'+newQuestionCount+'"  required>\
                                        </label>\
                                    </div>\
                                    <br><br>\
                                    <div class="custom-radio">\
                                        <input type="radio" name="questions['+newQuestionCount+'][correct_option]" id="option_radio_4_'+newQuestionCount+'" class="custom-control-input radio-btn mc_options_'+newQuestionCount+'"  value="4">\
                                        <label class="custom-control-label col-lg-9"  for="option_radio_4_'+newQuestionCount+'">\
                                            <input type="text" name="questions['+newQuestionCount+'][option4]" placeholder="Option 4" class="form-control mc_options_'+newQuestionCount+'"  required>\
                                        </label>\
                                    </div>\
                                </td>\
                            </tr>\
                        </table>\
                    </div>\
                </div>\
                <div class="row g-3 align-center">\
                    <div class="col-lg-3">\
                        <div class="form-group">\
                            <label class="form-label" for="mc_explanation_text">Explanation Text</label>\
                        </div>\
                    </div>\
                    <div class="col-lg-9">\
                        <textarea  data-parsley-errors-container=".parsley-container-mc_explanation_text" id="mc_explanation_text"  name="questions['+newQuestionCount+'][mc_explanation_text]" class="form-control" autocomplete="off"></textarea>\
                    </div>\
                </div>\
            </div>\
            <div class="sa_section_'+newQuestionCount+'" style="display: none;">\
                <div class="row g-3 align-center">\
                    <div class="col-lg-3">\
                        <div class="form-group">\
                            <label class="form-label" for="sa_answer_1">Short Answer 1<span class="text-danger">*</span></label>\
                        </div>\
                    </div>\
                    <div class="col-lg-9">\
                        <textarea  data-parsley-errors-container=".parsley-container-sa_answer_1" id="sa_answer_1" name="questions['+newQuestionCount+'][sa_answer_1]" class="form-control" autocomplete="off"></textarea>\
                    </div>\
                </div>\
                <div class="row g-3 align-center">\
                    <div class="col-lg-3">\
                        <div class="form-group">\
                            <label class="form-label" for="sa_answer_1">Short Answer 2<span class="text-danger">*</span></label>\
                        </div>\
                    </div>\
                    <div class="col-lg-9">\
                        <textarea  data-parsley-errors-container=".parsley-container-sa_answer_2" id="sa_answer_2" name="questions['+newQuestionCount+'][sa_answer_2]" class="form-control" autocomplete="off"></textarea>\
                    </div>\
                </div>\
                <div class="row g-3 align-center">\
                    <div class="col-lg-3">\
                        <div class="form-group">\
                            <label class="form-label" for="sa_explanation_text">Explanation Text</label>\
                        </div>\
                    </div>\
                    <div class="col-lg-9">\
                        <textarea  data-parsley-errors-container=".parsley-container-sa_explanation_text" id="sa_explanation_text" name="questions['+newQuestionCount+'][sa_explanation_text]" class="form-control" autocomplete="off"></textarea>\
                    </div>\
                </div>\
            </div>\
            <a href="javascript:void(0);" class="btn btn-danger remove_button" data-box="'+newQuestionCount+'"><em class="icon ni ni-trash"></em> Remove Question</a>\
            <hr>\
        </div>\
    ';

    $('.question-block').append(fieldHTML); //Add field html
});

$(document).on('click', '.remove_button', function(e){
    e.preventDefault();
    var boxCount = $(this).data('box');
    $('.question-wrapper-'+boxCount).remove();
    $('.background_section_'+boxCount).remove();
});


