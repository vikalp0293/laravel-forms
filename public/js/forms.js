const QUSETIONS_BLOCK = (sectionid, id) => `<div class="question-block" >
<button class="remove_question btn btn-danger" type="button">Delete Question</button>
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="question">Question<span class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <textarea  data-parsley-errors-container=".parsley-container-question" id="question" value="{{ isset($user) ? $user->question : old('question') }}" name="section[${sectionid}].section_questions[${id}].question_text_1" class="form-control" autocomplete="off"></textarea>                                                
                                            </div>
                                        </div>

                                        <div class="row g-3 align-center">
                                            <div class="col-lg-3">
                                                <x-inputs.verticalFormLabel label="Question Image 1" for="default-06"/>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="question_image_1" name="section[${sectionid}].section_questions[${id}].question_image_1">
                                                            <label class="custom-file-label" for="question_image_1">Choose file</label>
                                                           
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-3 align-center">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="question_text_2">Question Text 2<span class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <textarea  data-parsley-errors-container=".parsley-container-question_text_2" id="question_text_2" value="{{ isset($user) ? $user->question_text_2 : old('question_text_2') }}" name="section[${sectionid}].section_questions[${id}].question_text_2" class="form-control" autocomplete="off"></textarea>

                                                
                                            </div>
                                        </div>

                                        <div class="row g-3 align-center">
                                            <div class="col-lg-3">
                                                <x-inputs.verticalFormLabel label="Question Image 2" for="default-06"/>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="question_image_2" name="section[${sectionid}].section_questions[${id}].question_image_2">
                                                            <label class="custom-file-label" for="question_image_2">Choose file</label>
                                                            
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-3 align-center">
                                            <div class="col-lg-3">
                                                <x-inputs.verticalFormLabel label="Question Type" for="default-06"/>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">

                                                        <div class="custom-control custom-control-xs custom-radio">
                                                            <input type="radio" name="section[${sectionid}].section_questions[${id}].question_type" id="question_type_${sectionid}_mc_${id}" class="custom-control-input question_type radio-btn" value="mc">
                                                            <label class="custom-control-label"  for="question_type_${sectionid}_mc_${id}">Make it MC</label>
                                                        </div>

                                                        <div class="custom-control custom-control-xs custom-radio">
                                                            <input type="radio" name="section[${sectionid}].section_questions[${id}].question_type" id="question_type_${sectionid}_sa_${id}" class="custom-control-input question_type radio-btn" value="sa">
                                                            <label class="custom-control-label"  for="question_type_${sectionid}_sa_${id}">Make it Short answer</label>
                                                        </div>

                                                        <div class="custom-control custom-control-xs custom-radio">
                                                            <input type="radio" name="section[${sectionid}].section_questions[${id}].question_type" id="question_type_${sectionid}_admin_${id}" class="custom-control-input question_type radio-btn" value="admin">
                                                            <label class="custom-control-label"  for="question_type_${sectionid}_admin_${id}">Admin Only</label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                     

                                        <div class="mc_section" style="display: none;">
                                            <div class="row g-3 align-center">
                                                <div class="col-lg-3">
                                                    <x-inputs.verticalFormLabel label="MC Options" for="default-112"/>
                                                </div>
                                                <div class="col-lg-9">

                                                    <div class="custom-radio">
                                                        <input type="radio" name="section[${sectionid}].section_questions[${id}].answer[0].is_correct" id="option_radio_${sectionid}_${id}_1" class="custom-control-input radio-btn" value="1">
                                                        <label class="custom-control-label col-lg-9"  for="option_radio_${sectionid}_${id}_1">
                                                            <input type="text" name="section[${sectionid}].section_questions[${id}].answer[0].option" placeholder="Option 1" class="form-control"  required>
                                                        </label>
                                                    </div>
                                                    <br><br>
                                                    <div class="custom-radio">
                                                        <input type="radio" name="section[${sectionid}].section_questions[${id}].answer[1].is_correct" id="option_radio_${sectionid}_${id}_2" class="custom-control-input radio-btn" value="2">
                                                        <label class="custom-control-label col-lg-9"  for="option_radio_${sectionid}_${id}_2">
                                                            <input type="text" name="section[${sectionid}].section_questions[${id}].answer[1].option" placeholder="Option 2" class="form-control"  required>
                                                        </label>
                                                    </div>
                                                    <br><br>
                                                    <div class="custom-radio">
                                                        <input type="radio" name="section[${sectionid}].section_questions[${id}].answer[2].is_correct" id="option_radio_${sectionid}_${id}_3" class="custom-control-input radio-btn" value="3">
                                                        <label class="custom-control-label col-lg-9"  for="option_radio_${sectionid}_${id}_3">
                                                            <input type="text" name="section[${sectionid}].section_questions[${id}].answer[2].option" placeholder="Option 3" class="form-control"  required>
                                                        </label>
                                                    </div>
                                                    <br><br>
                                                    <div class="custom-radio">
                                                        <input type="radio" name="section[${sectionid}].section_questions[${id}].answer[3].is_correct" id="option_radio_${sectionid}_${id}_4" class="custom-control-input radio-btn" value="4">
                                                        <label class="custom-control-label col-lg-9"  for="option_radio_${sectionid}_${id}_4">
                                                            <input type="text" name="section[${sectionid}].section_questions[${id}].answer[3].option" placeholder="Option 4" class="form-control"  required>
                                                        </label>
                                                    </div>
                                                    
                                                </div>
                                            </div>

                                            <div class="row g-3 align-center">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="mc_explanation_test">Explanation Test<span class="text-danger">*</span></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <textarea  data-parsley-errors-container=".parsley-container-mc_explanation_test" id="mc_explanation_test" value="{{ isset($user) ? $user->mc_explanation_test : old('mc_explanation_test') }}" name="section[${sectionid}].section_questions[${id}].mc_explanation_test" class="form-control" autocomplete="off"></textarea>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="sa_section" style="display: none;">
                                            <div class="row g-3 align-center">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="sa_answer_1">Short Answer 1<span class="text-danger">*</span></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <textarea  data-parsley-errors-container=".parsley-container-sa_answer_1" id="sa_answer_1" value="{{ isset($user) ? $user->sa_answer_1 : old('sa_answer_1') }}" name="section[${sectionid}].section_questions[${id}].answer[0].sa_answer" class="form-control" autocomplete="off"></textarea>
                                                </div>
                                            </div>

                                            <div class="row g-3 align-center">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="sa_answer_1">Short Answer 2<span class="text-danger">*</span></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <textarea  data-parsley-errors-container=".parsley-container-sa_answer_2" id="sa_answer_2" value="{{ isset($user) ? $user->sa_answer_2 : old('sa_answer_2') }}" name="section[${sectionid}].section_questions[${id}].answer[1].sa_answer" class="form-control" autocomplete="off"></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="row g-3 align-center">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="sa_explanation_test">Explanation Test<span class="text-danger">*</span></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <textarea  data-parsley-errors-container=".parsley-container-sa_explanation_test" id="sa_explanation_test" value="{{ isset($user) ? $user->sa_explanation_test : old('sa_explanation_test') }}" name="section[${sectionid}].section_questions[${id}].sa_explanation_test" class="form-control" autocomplete="off"></textarea>
                                                </div>
                                            </div>                                            
                                        </div>

                                    </div>`
const FORM_SECTION = (id) => `
        <div class="sp-plan-info card-inner form-section">                            
        <hr/>
        <hr/>
                <button class="remove_section btn btn-danger" type="button">Delete Section</button>
                            <div class="row g-3 align-center">
                                <div class="col-lg-3">
                                    <x-inputs.verticalFormLabel label="Background" for="Background" />
                                </div>
                                <div class="col-lg-9">

                                   
                                    <div class="custom-control custom-control-md custom-switch">
                                        <input type="checkbox" id="background_upload_${id}" name="background" class="background_upload custom-control-input test" value="1" data-parsley-multiple="background" {{$checked}}
                                        >
                                        <label class="custom-control-label" for="background_upload_${id}"> </label>
                                    </div> 
                                   
                                </div>
                            </div>
                            <div class="background_section">
                                <div class="row g-3 align-center">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label" for="instructions">Instructions<span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <textarea  required="true" data-parsley-errors-container=".parsley-container-instructions" id="instructions" value="{{ isset($user) ? $user->instructions : old('instructions') }}" name="section[${id}].instructions_1" class="form-control" autocomplete="off"></textarea>

                                        
                                    </div>
                                </div>


                                <div class="row g-3 align-center">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label" for="instructions_2">Instructions 2<span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <textarea  data-parsley-errors-container=".parsley-container-instructions_2" id="instructions_2" value="{{ isset($user) ? $user->instructions_2 : old('instructions_2') }}" name="section[${id}].instructions_2" class="form-control" autocomplete="off"></textarea>

                                        
                                    </div>
                                </div>
                                <div class="upload-image"  style="display:none">
                                    <div class="row g-3 align-center">
                                        <div class="col-lg-3">
                                            <x-inputs.verticalFormLabel label="Upload Image 1" for="default-06"/>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="image_upload_1" name="section[${id}].image_upload_1">
                                                        <label class="custom-file-label" for="image_upload_1">Choose file</label>
                                                        
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 align-center">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label" for="instructions_3">Instructions 3<span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <textarea  data-parsley-errors-container=".parsley-container-instructions_3" id="instructions_3" value="{{ isset($user) ? $user->instructions_3 : old('instructions_3') }}" name="section[${id}].instructions_3" class="form-control" autocomplete="off"></textarea>

                                        
                                    </div>
                                </div>

                                <div class="upload-image"  style="display:none">
                                    <div class="row g-3 align-center upload-image">
                                        <div class="col-lg-3">
                                            <x-inputs.verticalFormLabel label="Upload Image 2" for="default-06"/>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="image_upload_2" name="section[${id}].image_upload_2">
                                                        <label class="custom-file-label" for="image_upload_2">Choose file</label>
                                                        
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="nk-block">
                                    
                                    <div class="question-list" data-index="${id}">
                                        ${QUSETIONS_BLOCK(id,0)}
                                    </div>
                                    <div class="row g-3 align-center">
                                            <div class="col-lg-3">
                                                <!-- <x-inputs.verticalFormLabel label="Background" for="Background" /> -->
                                            </div>
                                            <div class="col-lg-9">
                                                <a href="javascript::void(0);" class="btn btn-secondary d-none d-md-inline-flex add-question"><em class="icon ni ni-plus"></em><span>Add Question</span></a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
        `

jQuery('#addmore_section').on('click', function () {
    
    var count = jQuery('#section_wrapper').find('.form-section').length
    jQuery('#section_wrapper').find('.section-list').append(FORM_SECTION(count+1))
})
jQuery('#section_wrapper').on('click', '.add-question', function () {
    var closest = jQuery(this).closest('.nk-block').find('.question-list');
    var index = closest.attr('data-index');
    var count = closest.find('.question-block').length
    closest.append(QUSETIONS_BLOCK(index,count+1))
});

//Delete Section
jQuery('#section_wrapper').on('click', '.remove_section', function () {
    var removeElem = jQuery(this).closest('.form-section');
    var sectionList = removeElem.closest('#section_wrapper').find('.form-section');
    if (sectionList.length === 1) {
        alert('Atleast one question is required')
        return
    };
    if (confirm('Are you sure you want to delete this section?')) {
        removeElem.remove();
    }

});

//Delete question
jQuery('#section_wrapper').on('click', '.remove_question', function () {
    var removeElem = jQuery(this).closest('.question-block');
    var questionList = removeElem.closest('.question-list').find('.question-block');

    if (questionList.length === 1) {
        alert('Atleast one question is required')
        return
    };
    if (confirm('Are you sure you want to delete this question?')) {
        removeElem.remove();
    }
})

function toggleBackgroundFields(elem, isEnabled) {
    var section = jQuery(elem).closest('.form-section');
    if (isEnabled === true) {
        section.find('.upload-image').show();
    } else {
        section.find('.upload-image').hide();
    }

    section.find('.upload-image :input').each(function () {
        $(this).prop('disabled', !isEnabled); // Disable if checkbox is unchecked
    });
}

jQuery('#section_wrapper').on('change', '.background_upload', function () {
    toggleBackgroundFields(this, this.checked);
});

jQuery('#section_wrapper').on('change', '.question_type', function () {
    var section = jQuery(this).closest('.question-block');
    // Get the selected value within this specific block
    var selectedValue = section.find('.question_type:checked').val();

    // Hide all sections within this block
    section.find('.mc_section, .sa_section, .admin_section').hide();

    // Show the relevant section within this block
    if (selectedValue === 'mc') {
        section.find('.mc_section').show();
    } else if (selectedValue === 'sa') {
        section.find('.sa_section').show();
    } else if (selectedValue === 'admin') {
        section.find('.admin_section').show();
    }
})


$(document).ready(function () {
    // Initialize the fields' state based on the checkbox
    // toggleBackgroundFields(false);
});

jQuery('#exmaForm').on('submit', function (e) {
    e.preventDefault();
   // Serialize the form data into an array of objects
   var formArray = $(this).serializeArray();

   // Convert the array into a nested object
   var formObject = {};

   // Helper function to build the object for nested keys
   function setObjectValue(obj, keys, value) {
       keys.reduce(function (acc, key, idx) {
           if (idx === keys.length - 1) {
               acc[key] = value; // Set the final value
           } else {
               if (!acc[key]) {
                   acc[key] = (Number(keys[idx + 1]) >= 0) ? [] : {}; // Initialize array or object
               }
           }
           return acc[key];
       }, obj);
   }

   // Loop through each form field and build the object
   $.each(formArray, function () {
       var keys = this.name.split('.'); // Split name by dot to handle array/object structure
       setObjectValue(formObject, keys, this.value);
   });

   console.log(formObject); // Log the form data as an object
});