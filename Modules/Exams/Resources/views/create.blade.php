@extends('layouts.app')
@php
$userPermission = \Session::get('userPermission');
@endphp
@section('content')
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title"><a href="javascript:history.back()" class="pt-3"><em class="icon ni ni-chevron-left back-icon"></em> </a> Create Exam</h3>
            </div><!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    <form role="form" method="post" enctype="multipart/form-data" >
        @csrf
        <input type="hidden" name="maxQuestions" id="maxQuestions" value="{{ $maxQuestions }}">
        <div class="nk-block">
            <div class="card card-bordered sp-plan">
                <div class="row no-gutters">
                    
                    <div class="col-md-12">
                        <div class="sp-plan-info card-inner">

                            <div class="row g-3 align-center">
                                <div class="col-lg-3">
                                    <x-inputs.verticalFormLabel label="Title" for="title" suggestion="Specify the title of the exam." required="true" />
                                </div>
                                <div class="col-lg-9">


                                    <input type="text" required="true" data-parsley-errors-container=".parsley-container-title" id="title" value="{{ isset($user) ? $user->title : old('title') }}" name="title" maxlength="50" class="form-control" autocomplete="off">

                                    @if ($errors->has('title'))
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-3 align-center">
                                <div class="col-lg-3">
                                    <x-inputs.verticalFormLabel label="Description" for="description" suggestion="Specify the description of the exam." required="true" />
                                </div>
                                <div class="col-lg-9">


                                    <textarea rows="2" required="true" data-parsley-errors-container=".parsley-container-description" id="description" value="{{ isset($user) ? $user->description : old('description') }}" name="description" class="form-control" autocomplete="off"></textarea>

                                    @if ($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-3 align-center">
                                <div class="col-lg-3">
                                    <x-inputs.verticalFormLabel label="Subject" for="subject" required="true" suggestion="Select the subject of the user." />
                                </div>
                                <div class="col-lg-9">
                                    <select size="sm" class="form-select form-control form-control-lg" data-placeholder="Select Subject" data-parsley-errors-container=".subjectParsley" name="subject" id="subject" data-search='on'  required onchange="getTopics(this.options[this.selectedIndex].value)"> 
                                        <option value="">Select subject</option>
                                        
                                        @foreach ($subjects as $subject)

                                        <option
                                        @if(isset($user) && $user->subject_id == $subject->id)
                                        selected
                                        @elseif(auth()->user()->subject_id == $subject->id) 
                                        selected
                                        @endif
                                        value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('subject'))
                                        <span class="text-danger">{{ $errors->first('subject') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row g-3 align-center">
                                <div class="col-lg-3">
                                    <x-inputs.verticalFormLabel label="Topic" for="topic" required="true" suggestion="Select the topic of the exam." />
                                </div>
                                <div class="col-lg-9">

                                    <select size="sm" class="form-select form-control form-control-lg" data-placeholder="Select Topic" data-parsley-errors-container=".topicParsley" name="topic" id="topic" data-search='on'  required onchange="getSubTopics(this.options[this.selectedIndex].value)"> 
                                        <option value="">Select Topic</option>
                                    </select>
                                    @if ($errors->has('topic'))
                                        <span class="text-danger">{{ $errors->first('topic') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row g-3 align-center">
                                <div class="col-lg-3">
                                    <x-inputs.verticalFormLabel label="Subtopic" for="subtopic" required="true" suggestion="Select the subtopic of the exam." />
                                </div>
                                <div class="col-lg-9">

                                    <select size="sm" class="form-select form-control form-control-lg" data-placeholder="Select Subtopic" data-parsley-errors-container=".subtopicParsley" name="subtopic" id="subtopic" data-search='on'  required> 
                                        <option value="">Select Subtopic</option>
                                    </select>
                                    @if ($errors->has('subtopic'))
                                        <span class="text-danger">{{ $errors->first('subtopic') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .nk-block -->

        <div class="nk-block">
            <div class="card card-bordered sp-plan">
                <div class="row no-gutters">
                    
                    <div class="col-md-12">
                        <div class="sp-plan-info card-inner">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row g-3 align-center">
                                        <div class="col-lg-5">
                                            <x-inputs.verticalFormLabel label="State" for="state" required="true" suggestion="Select the state of the user." />
                                        </div>
                                        <div class="col-lg-7">
                                            <select size="sm" class="form-select form-control form-control-lg" data-placeholder="Select State" data-parsley-errors-container=".stateParsley" name="state" id="state" data-search='on'  required> 
                                                <option value="">Select state</option>
                                                
                                                @foreach ($states as $state)

                                                <option
                                                @if(isset($user) && $user->state == $state->id)
                                                selected
                                                @elseif(auth()->user()->state == $state->id) 
                                                selected
                                                @endif
                                                value="{{ $state->id }}">{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('state'))
                                                <span class="text-danger">{{ $errors->first('state') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="row g-3 align-center">
                                        <div class="col-lg-5">
                                            <x-inputs.verticalFormLabel label="Grade" for="grade" required="true" suggestion="Select the grade of the user." />
                                        </div>
                                        <div class="col-lg-7">
                                            <select size="sm" class="form-select form-control form-control-lg" data-placeholder="Select Grade" data-parsley-errors-container=".gradeParsley" name="grade" id="grade" data-search='on'  required> 
                                                <option value="">Select grade</option>
                                                
                                                @foreach ($grades as $grade)

                                                <option
                                                @if(isset($user) && $user->grade_id == $grade->id)
                                                selected
                                                @elseif(auth()->user()->grade_id == $grade->id) 
                                                selected
                                                @endif
                                                value="{{ $grade->id }}">{{ $grade->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('grade'))
                                                <span class="text-danger">{{ $errors->first('grade') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .nk-block -->
        
        <div class="nk-block">
            <div class="card card-bordered sp-plan">
                <div class="row no-gutters">
                    
                    <div class="col-md-12">
                        <div class="sp-plan-info card-inner">


                            <div class="sections">
                                <div class="section-box sections-1" style="border: 1px solid; padding: 15px; border-radius: 10px; box-shadow: 10px 10px #969b9d; background-color: rgb(241, 243, 202);">
                                    <div class="row g-3 align-center">
                                        <div class="col-lg-3">
                                            <label class="form-label" for="Background">Background</label>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="custom-control custom-control-md custom-switch">
                                                <input type="checkbox" data-backgroundsection="1" name="backgrounds[1][background]" id="background_1" class="custom-control-input background" value="1" data-parsley-multiple="background">
                                                <label class="custom-control-label" for="background_1"> </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="background_section_1" style="display:none;">
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="instructions">Instructions</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <textarea data-parsley-errors-container=".parsley-container-instructions" id="instructions"  name="backgrounds[1][instructions]" class="form-control background-image-input-1" autocomplete="off"></textarea>
                                            </div>
                                        </div>


                                        <div class="row g-3 align-center">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="instructions_2">Instructions 2</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <textarea  data-parsley-errors-container=".parsley-container-instructions_2" id="instructions_2" name="backgrounds[1][instructions_2]" class="form-control background-image-input-1" autocomplete="off"></textarea>
                                            </div>
                                        </div>

                                        <div class="row g-3 align-center ">
                                            <div class="col-lg-3">
                                                <x-inputs.verticalFormLabel label="Upload Image 1" for="default-06"/>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input background-image-input-1" id="image_upload_1" name="backgrounds[1][image_upload_1]">
                                                            <label class="custom-file-label" for="image_upload_1">Choose file</label>
                                                            <span class="error-message" style="color: red; display: none;">Invalid file type or size exceeds 2MB</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-3 align-center">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="instructions_3">Instructions 3</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <textarea  data-parsley-errors-container=".parsley-container-instructions_3" id="instructions_3" name="backgrounds[1][instructions_3]" class="form-control background-image-input-1" autocomplete="off"></textarea>
                                            </div>
                                        </div>

                                        <div class="row g-3 align-center">
                                            <div class="col-lg-3">
                                                <x-inputs.verticalFormLabel label="Upload Image 2" for="default-06"/>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input background-image-input-1" id="image_upload_2" name="backgrounds[1][image_upload_2]">
                                                            <label class="custom-file-label" for="image_upload_2">Choose file</label>
                                                            <span class="error-message" style="color: red; display: none;">Invalid file type or size exceeds 2MB</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>

                                    <div class="question-block-1">
                                        <div class="question-box question-wrapper-1">
                                            <h6 class="text-center mt-3"> Question 1</h6>
                                            <input type="hidden" name="questions[1][background]" class="question" value="0">
                                            <div class="row g-3 align-center">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="question">Question<span class="text-danger">*</span></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <textarea required data-parsley-errors-container=".parsley-container-question" id="question" name="questions[1][question]" class="form-control" autocomplete="off"></textarea>
                                                </div>
                                            </div>

                                            <div class="row g-3 align-center">
                                                <div class="col-lg-3">
                                                    <label class="form-label" for="default-06">Question Image 1</label>
                                                </div>
                                                <div class="col-lg-9">
                                                    <div class="form-group">
                                                        <div class="form-control-wrap">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="question_image_1" name="questions[1][question_image_1]">
                                                                <label class="custom-file-label" for="question_image_1">Choose file</label>
                                                                <span class="error-message" style="color: red; display: none;">Invalid file type or size exceeds 2MB</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-3 align-center">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="question_text_2">Question Text 2</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <textarea  data-parsley-errors-container=".parsley-container-question_text_2" id="question_text_2" name="questions[1][question_text_2]" class="form-control" autocomplete="off"></textarea>
                                                </div>
                                            </div>

                                            <div class="row g-3 align-center">
                                                <div class="col-lg-3">
                                                    <label class="form-label" for="default-06">Question Image 2</label>
                                                </div>
                                                <div class="col-lg-9">
                                                    <div class="form-group">
                                                        <div class="form-control-wrap">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="question_image_2" name="questions[1][question_image_2]">
                                                                <label class="custom-file-label" for="question_image_2">Choose file</label>
                                                                <span class="error-message" style="color: red; display: none;">Invalid file type or size exceeds 2MB</span>
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
                                                                <input type="radio" name="questions[1][question_type]" data-question="1" id="question_type_mc_1" class="custom-control-input radio-btn question-choice" value="mc" checked>
                                                                <label class="custom-control-label"  for="question_type_mc_1">Make it MC</label>
                                                            </div>

                                                            <div class="custom-control custom-control-xs custom-radio">
                                                                <input type="radio" name="questions[1][question_type]" data-question="1" id="question_type_sa_1" class="custom-control-input radio-btn question-choice" value="sa">
                                                                <label class="custom-control-label"  for="question_type_sa_1">Make it Short answer</label>
                                                            </div>

                                                            <div class="custom-control custom-control-xs custom-radio">
                                                                <input type="radio" name="questions[1][question_type]" data-question="1" id="question_type_admin_1" class="custom-control-input radio-btn question-choice" value="admin">
                                                                <label class="custom-control-label"  for="question_type_admin_1">Admin Only</label>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="mc_section_1">
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <x-inputs.verticalFormLabel label="MC Options" for="default-112"/>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    Correct Answer
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td style="width:80%">
                                                                    <div class="custom-radio">
                                                                        <input type="radio" name="questions[1][correct_option]" id="option_radio_1" class="custom-control-input radio-btn mc_options_1" value="1" checked>
                                                                        <label class="custom-control-label col-lg-9"  for="option_radio_1">
                                                                            <input type="text" name="questions[1][option1]" placeholder="Option 1" class="form-control mc_options_1">
                                                                        </label>
                                                                    </div>
                                                                    <br><br>
                                                                    <div class="custom-radio">
                                                                        <input type="radio" name="questions[1][correct_option]" id="option_radio_2" class="custom-control-input radio-btn mc_options_1" value="2">
                                                                        <label class="custom-control-label col-lg-9"  for="option_radio_2">
                                                                            <input type="text" name="questions[1][option2]" placeholder="Option 2" class="form-control mc_options_1">
                                                                        </label>
                                                                    </div>
                                                                    <br><br>
                                                                    <div class="custom-radio">
                                                                        <input type="radio" name="questions[1][correct_option]" id="option_radio_3" class="custom-control-input radio-btn mc_options_1" value="3">
                                                                        <label class="custom-control-label col-lg-9"  for="option_radio_3">
                                                                            <input type="text" name="questions[1][option3]" placeholder="Option 3" class="form-control mc_options_1">
                                                                        </label>
                                                                    </div>
                                                                    <br><br>
                                                                    <div class="custom-radio">
                                                                        <input type="radio" name="questions[1][correct_option]" id="option_radio_4" class="custom-control-input radio-btn mc_options_1" value="4">
                                                                        <label class="custom-control-label col-lg-9"  for="option_radio_4">
                                                                            <input type="text" name="questions[1][option4]" placeholder="Option 4" class="form-control mc_options_1">
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="mc_explanation_text">Explanation Text</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <textarea  data-parsley-errors-container=".parsley-container-mc_explanation_text" id="mc_explanation_text"  name="questions[1][mc_explanation_text]" class="form-control" autocomplete="off"></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="sa_section_1" style="display: none;">
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="sa_answer_1">Short Answer 1<span class="text-danger">*</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <textarea  data-parsley-errors-container=".parsley-container-sa_answer_1" id="sa_answer_1" name="questions[1][sa_answer_1]" class="form-control" autocomplete="off"></textarea>
                                                    </div>
                                                </div>

                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="sa_answer_1">Short Answer 2</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <textarea  data-parsley-errors-container=".parsley-container-sa_answer_2" id="sa_answer_2" name="questions[1][sa_answer_2]" class="form-control" autocomplete="off"></textarea>

                                                        
                                                    </div>
                                                </div>
                                                
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="sa_explanation_text">Explanation Text<span class="text-danger">*</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <textarea  data-parsley-errors-container=".parsley-container-sa_explanation_text" id="sa_explanation_text" name="questions[1][sa_explanation_text]" class="form-control" autocomplete="off"></textarea>
                                                    </div>
                                                </div>                                            
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <a style="display:none !important;" href="javascript:void(0);" data-section="1" class="btn btn-secondary d-none d-md-inline-flex addQuestionSameBackground addQuestionBtn_1"><em class="icon ni ni-plus"></em><span>Add Another Question Using Same Background</span></a>
                                    </div>

                                </div>
                                
                            </div>
                            <br>
                            <div class="text-right">
                                <a href="javascript:void(0);" data-background="new-background" class="btn btn-info d-none d-md-inline-flex btn-nobackground addNewSection"><em class="icon ni ni-plus"></em><span>Add Another Question Not Using Same Background</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
      
        <div class="nk-block">
            <div class="row">
                <div class="col-md-12">
                    <div class="sp-plan-info pt-0 pb-0 card-inner">  
                            <div class="row">
                                <div class="col-lg-7 text-right offset-lg-5">
                                    <div class="form-group">
                                        <a href="javascript:history.back()" class="btn btn-outline-light">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                    </div><!-- .sp-plan-info -->
                </div><!-- .col -->
            </div><!-- .row -->
        </div>
    </form>


    <script type="text/javascript">
        function getTopics(subject_id) {
            var root_url = "<?php echo Request::root(); ?>";
            //var subject_id = $(".subject_id").val();
            $.ajax({
                url: root_url + '/exams/get-topics-by-subject/' + subject_id,
                data: {},
                //dataType: "html",
                method: "GET",
                cache: false,
                success: function(response) {
                    $("#topic").html('');
                    $("#topic").append($('<option value="" selected disabled></option>').val('').html('Select Topic'));

                    $.each(response.topics, function(key, value) {
                        if (value.id != 0) {
                            $("#topic").append($('<option></option>').val(value.id).html(value.name));
                        }
                    });
                }
            });
        }

        function getSubTopics(topic_id) {
            var root_url = "<?php echo Request::root(); ?>";
            //var topic_id = $(".topic_id").val();
            $.ajax({
                url: root_url + '/exams/get-subtopics-by-topic/' + topic_id,
                data: {},
                //dataType: "html",
                method: "GET",
                cache: false,
                success: function(response) {
                    $("#subtopic").html('');
                    $("#subtopic").append($('<option value="" selected disabled></option>').val('').html('Select Subtopic'));

                    $.each(response.subtopics, function(key, value) {
                        if (value.id != 0) {
                            $("#subtopic").append($('<option></option>').val(value.id).html(value.name));
                        }
                    });
                }
            });
        }
    </script>

    <script src="{{ url('js/exam-forms.js') }}?time={{ time() }}"></script>
@endsection
