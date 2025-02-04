
$(document).ready(function(){
    var root_url = "<?php echo url('/'); ?>";

    var subject = $('#subject').val();
    var uuid = $('#uuid').val();
    
    if(subject != '' && uuid == undefined){
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
        $('.addQuestionBtn_'+backgroundSectionNo).show();
    }else{
        $('.background_section_'+backgroundSectionNo).hide();
        $('.addQuestionBtn_'+backgroundSectionNo).attr('style', 'display: none !important;');
    }

    $('.background-image-input-'+backgroundSectionNo).each(function () {
        $(this).prop('disabled', !isEnabled); // Disable if checkbox is unchecked
    });

    updateQuestionsBasedOnCheckbox();
}

function updateQuestionsBasedOnCheckbox() {
    // Loop through each checkbox with class 'background'
    $('.background').each(function () {
        const sectionId = $(this).data('backgroundsection'); // Get the section number from data attribute
        const isChecked = $(this).is(':checked'); // Check if the checkbox is checked
        const value = isChecked ? $(this).val() : 0; // If checked, use its value; otherwise, use 0

        // Find all inputs with class 'question' inside the same section and update their value
        $(`.sections-${sectionId} .question-box .question`).val(value);
    });

    $('.question-box').each(function (index) {
        const questionNumber = index + 1; // Get question number (1-based index)
        $(this).find('h6').text(`Question ${questionNumber}`);
    });
}

function backgroundFields(newSectionCount, newQuestionCount){
    const randomColor = getRandomLightColor();
    var background = '\
        <br>\
        <div class="section-box sections-'+newSectionCount+'" style="border: 1px solid; padding: 15px; border-radius: 10px; box-shadow: 10px 10px #969b9d; background-color:'+randomColor+';">\
            <div class="row g-3 align-center">\
                <div class="col-lg-3">\
                    <label class="form-label" for="Background">Background</label>\
                </div>\
                <div class="col-lg-9">\
                    <div class="custom-control custom-control-md custom-switch">\
                        <input type="checkbox" data-backgroundsection="'+newSectionCount+'" name="backgrounds['+newSectionCount+'][background]" id="background_'+newSectionCount+'" class="custom-control-input background" value="'+newSectionCount+'" data-parsley-multiple="background">\
                        <label class="custom-control-label" for="background_'+newSectionCount+'"> </label>\
                    </div>\
                </div>\
            </div>\
            <div class="background_section_'+newSectionCount+'" style="display:none;">\
                <div class="row g-3 align-center">\
                    <div class="col-lg-3">\
                        <div class="form-group">\
                            <label class="form-label" for="instructions">Instructions</label>\
                        </div>\
                    </div>\
                    <div class="col-lg-9">\
                        <textarea data-parsley-errors-container=".parsley-container-instructions" id="instructions"  name="backgrounds['+newSectionCount+'][instructions]" class="form-control background-image-input-'+newSectionCount+'" autocomplete="off"></textarea>\
                    </div>\
                </div>\
                <div class="row g-3 align-center">\
                    <div class="col-lg-3">\
                        <div class="form-group">\
                            <label class="form-label" for="instructions_2">Instructions 2</label>\
                        </div>\
                    </div>\
                    <div class="col-lg-9">\
                        <textarea  data-parsley-errors-container=".parsley-container-instructions_2" id="instructions_2" name="backgrounds['+newSectionCount+'][instructions_2]" class="form-control background-image-input-'+newSectionCount+'" autocomplete="off"></textarea>\
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
                                    <input type="file" class="custom-file-input background-image-input-'+newSectionCount+'" id="image_upload_1" name="backgrounds['+newSectionCount+'][image_upload_1]">\
                                    <label class="custom-file-label" for="image_upload_1">Choose file</label>\
                                    <span class="error-message" style="color: red; display: none;">Invalid file type or size exceeds 2MB</span>\
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
                        <textarea  data-parsley-errors-container=".parsley-container-instructions_3" id="instructions_3" name="backgrounds['+newSectionCount+'][instructions_3]" class="form-control background-image-input-'+newSectionCount+'" autocomplete="off"></textarea>\
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
                                    <input type="file" class="custom-file-input background-image-input-'+newSectionCount+'" id="image_upload_2" name="backgrounds['+newSectionCount+'][image_upload_2]">\
                                    <label class="custom-file-label" for="image_upload_2">Choose file</label>\
                                    <span class="error-message" style="color: red; display: none;">Invalid file type or size exceeds 2MB</span>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
                <hr>\
            </div>\
            <div class="question-block-'+newSectionCount+'">\
            '+questionFileds(newQuestionCount)+'\
            </div>\
            <div class="text-right">\
                <a style="display:none !important;" href="javascript:void(0);" data-section="'+newSectionCount+'" class="btn btn-secondary d-none d-md-inline-flex addQuestionSameBackground addQuestionBtn_'+newSectionCount+'"><em class="icon ni ni-plus"></em><span>Add Another Question Using Same Background</span></a>\
            </div>\
        </div>\
    ';

    return background;
}

function questionFileds(newQuestionCount){
    var fieldHTML = '\
        <div class="question-box question-wrapper-'+newQuestionCount+'">\
            <hr>\
            <h6 class="text-center mt-3"> Question 1</h6>\
            <input type="hidden" name="questions['+newQuestionCount+'][background]" class="question">\
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
                                <span class="error-message" style="color: red; display: none;">Invalid file type or size exceeds 2MB</span>\
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
                                <span class="error-message" style="color: red; display: none;">Invalid file type or size exceeds 2MB</span>\
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
                                <input type="radio" name="questions['+newQuestionCount+'][question_type]" data-question="'+newQuestionCount+'" id="question_type_mc_'+newQuestionCount+'" class="custom-control-input radio-btn question-choice" value="mc" checked>\
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
            <div class="mc_section_'+newQuestionCount+'" >\
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
                                        <input type="radio" name="questions['+newQuestionCount+'][correct_option]" id="option_radio_1_'+newQuestionCount+'" class="custom-control-input radio-btn mc_options_'+newQuestionCount+'" value="1" checked>\
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
                            <label class="form-label" for="sa_answer_1">Short Answer 2</label>\
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
            <div class="text-right" style="margin:5px 0px 5px 0px !important;"><a href="javascript:void(0);" class="btn btn-danger remove_button" data-box="'+newQuestionCount+'"><em class="icon ni ni-trash"></em> Remove Question</a></div>\
        </div>\
    ';

    return fieldHTML;
}



// Add more questions code goes here
$(document).on('click', '.addQuestionSameBackground', function(){ 
    const maxQuestions = $('#maxQuestions').val();
    const totalQuestionBoxes = $('.question-box').length;
    if(totalQuestionBoxes >= maxQuestions){
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: "Can't add more than "+maxQuestions+" questions",
          //footer: '<a href>Why do I have this issue?</a>'
        })
        return;
    }

    var section = $(this).data('section');

    let lastQuestionBox = $('.question-box').last();
    // Extract the number from the class name
    let lastQuestionWrapper = lastQuestionBox.attr('class').match(/question-wrapper-(\d+)/)[1];
    let newQuestionCount = parseFloat(lastQuestionWrapper)+1;
    
    var questionHtml = questionFileds(newQuestionCount)

    $('.question-block-'+section).append(questionHtml); //Add field html
    updateQuestionsBasedOnCheckbox();
});

// Add more questions code goes here
$(document).on('click', '.addNewSection', function(){ 

    const maxQuestions = $('#maxQuestions').val();
    const totalQuestionBoxes = $('.question-box').length;
    if(totalQuestionBoxes >= maxQuestions){
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: "Can't add more than "+maxQuestions+" questions",
          //footer: '<a href>Why do I have this issue?</a>'
        })
        return;
    }

    var section = $(this).data('section');

    let lastSectionBox = $('.section-box').last();
    // Extract the number from the class name
    let lastSectionCount = lastSectionBox.attr('class').match(/sections-(\d+)/)[1];
    let newSectionCount = parseFloat(lastSectionCount)+1;

    let lastQuestionBox = $('.question-box').last();
    // Extract the number from the class name
    let lastQuestionWrapper = lastQuestionBox.attr('class').match(/question-wrapper-(\d+)/)[1];
    let newQuestionCount = parseFloat(lastQuestionWrapper)+1;

    var backgroundHtml = backgroundFields(newSectionCount, newQuestionCount)
    $('.sections').append(backgroundHtml); //Add field html
    updateQuestionsBasedOnCheckbox();

    
});

$(document).on('click', '.remove_button', function(e){
    e.preventDefault();
    var boxCount = $(this).data('box');
    $('.question-wrapper-'+boxCount).remove();
    removeEmptySectionBoxes();
    updateQuestionsBasedOnCheckbox();
});

function removeEmptySectionBoxes() {
    // Loop through each section-box
    $('.section-box').each(function() {
        // Check if it has any child with the class question-box
        if ($(this).find('.question-box').length === 0) {
            // If no question-box is found, remove the section-box
            $(this).remove();
        }
    });
}

function getRandomLightColor() {
    const r = Math.floor(Math.random() * 20) + 230; // Red (230-250)
    const g = Math.floor(Math.random() * 20) + 230; // Green (230-250)
    const b = Math.floor(Math.random() * 50) + 180; // Blue (180-230)
    return `rgb(${r}, ${g}, ${b})`; // Return the color in RGB format
}


// Function to validate file input
function validateFile(input) {
    const file = input.files[0]; // Get the selected file
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']; // Allowed file types
    const maxSize = 2 * 1024 * 1024; // Max file size (2MB)

    // Reset any previous error message
    $(input).siblings('.error-message').hide();
    $(input).siblings('label').text('Choose file'); // Reset label text to default

    // Check if a file is selected
    if (file) {
        const fileType = file.type; // Get the file type
        const fileSize = file.size; // Get the file size in bytes

        // Check file type
        if (!allowedTypes.includes(fileType)) {
            $(input).siblings('.error-message').text('Only image files (jpg, png, gif) are allowed.').show();
            $(input).siblings('label').text('Invalid file type'); // Update label text to reflect the error
            input.value = ''; // Clear the input value
            return false;
        }

        // Check file size
        if (fileSize > maxSize) {
            $(input).siblings('.error-message').text('File size must not exceed 2MB.').show();
            $(input).siblings('label').text('File size exceeds 2MB'); // Update label text to reflect the error
            input.value = ''; // Clear the input value
            return false;
        }
    }

    return true; // If all validations pass
}

// Attach validation to the file input
$(document).on('change', 'input[type="file"]', function () {
    validateFile(this);
});

