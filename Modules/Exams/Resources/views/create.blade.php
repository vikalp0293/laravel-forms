@extends('layouts.app')
@php
$userPermission = \Session::get('userPermission');
$organization_type = \Session::get('organization_type');
$currentOrganization = \Session::get('currentOrganization');
@endphp
@section('content')

    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title"><a href="javascript:history.back()" class="pt-3"><em class="icon ni ni-chevron-left back-icon"></em> </a> Create Exam</h3>
            </div><!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    <form role="form" method="post" enctype="multipart/form-data" id="exmaForm">
        @csrf
        
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
                    
                    <div class="col-md-12" id="section_wrapper">
                        <div class="section-list">
                            <div class="sp-plan-info card-inner form-section">                            
                                <button class="remove_section btn btn-danger" type="button">Delete Section</button>
                                <div class="row g-3 align-center">
                                    <div class="col-lg-3">
                                        <x-inputs.verticalFormLabel label="Background" for="Background" />
                                    </div>
                                    <div class="col-lg-9">

                                        @php
                                            if(isset($user) && $user->background){
                                                $checked = 'checked';
                                            }else{
                                                $checked = '';
                                            }
                                        @endphp
                                        <div class="custom-control custom-control-md custom-switch">
                                            <input type="checkbox" id="background_upload" name="background" class=" background_upload custom-control-input test" value="1" data-parsley-multiple="background" {{$checked}}
                                            >
                                            <label class="custom-control-label" for="background_upload"> </label>
                                        </div> 
                                        @if ($errors->has('background'))
                                            <span class="text-danger">{{ $errors->first('background') }}</span>
                                        @endif
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
                                            <textarea  required="true" data-parsley-errors-container=".parsley-container-instructions" id="instructions" value="{{ isset($user) ? $user->instructions : old('instructions') }}" name="section[0].instructions_1" class="form-control" autocomplete="off"></textarea>
                                        </div>
                                    </div>


                                    <div class="row g-3 align-center">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="form-label" for="instructions_2">Instructions 2<span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <textarea  data-parsley-errors-container=".parsley-container-instructions_2" id="instructions_2" value="{{ isset($user) ? $user->instructions_2 : old('instructions_2') }}" name="section[0].instructions_2" class="form-control" autocomplete="off"></textarea>
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
                                                            <input type="file" class="custom-file-input" id="image_upload_1" name="section[0].image_upload_1">
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
                                            <textarea  data-parsley-errors-container=".parsley-container-instructions_3" id="instructions_3" value="{{ isset($user) ? $user->instructions_3 : old('instructions_3') }}" name="section[0].instructions_3" class="form-control" autocomplete="off"></textarea>

                                          
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
                                                            <input type="file" class="custom-file-input" id="image_upload_2" name="section[0].image_upload_2">
                                                            <label class="custom-file-label" for="image_upload_2">Choose file</label>
                                                            
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="nk-block">
                                        <div class="question-list" data-index="0">
                                        <div class="question-block">
                                        <button class="remove_question btn btn-danger" type="button">Delete Question</button>
                                            <div class="row g-3 align-center">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="question">Question<span class="text-danger">*</span></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <textarea  data-parsley-errors-container=".parsley-container-question" id="question" value="{{ isset($user) ? $user->question : old('question') }}" name="section[0].section_questions[0].question_text_1" class="form-control" autocomplete="off"></textarea>

                                                   
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
                                                                <input type="file" class="custom-file-input" id="question_image_1" name="section[0].section_questions[0].question_image_1">
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
                                                    <textarea  data-parsley-errors-container=".parsley-container-question_text_2" id="question_text_2" value="{{ isset($user) ? $user->question_text_2 : old('question_text_2') }}" name="section[0].section_questions[0].question_text_2" class="form-control" autocomplete="off"></textarea>

                                                    
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
                                                                <input type="file" class="custom-file-input" id="question_image_2" name="section[0].section_questions[0].question_image_2">
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
                                                                <input type="radio" name="section[0].section_questions[0].question_type" id="question_type_mc" class="question_type custom-control-input radio-btn" value="mc">
                                                                <label class="custom-control-label"  for="question_type_mc">Make it MC</label>
                                                            </div>

                                                            <div class="custom-control custom-control-xs custom-radio">
                                                                <input type="radio" name="section[0].section_questions[0].question_type" id="question_type_sa" class="question_type custom-control-input radio-btn" value="sa">
                                                                <label class="custom-control-label"  for="question_type_sa">Make it Short answer</label>
                                                            </div>

                                                            <div class="custom-control custom-control-xs custom-radio">
                                                                <input type="radio" name="section[0].section_questions[0].question_type" id="question_type_admin" class="question_type custom-control-input radio-btn" value="admin">
                                                                <label class="custom-control-label"  for="question_type_admin">Admin Only</label>
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
                                                            <input type="radio" name="section[0].section_questions[0].answer[0].is_correct" id="option_radio_0_0_1" class="custom-control-input radio-btn" value="1">
                                                            <label class="custom-control-label col-lg-9"  for="option_radio_0_0_1">
                                                                <input type="text" name="section[0].section_questions[0].answer[0].option" placeholder="Option 1" class="form-control"  required>
                                                            </label>
                                                        </div>
                                                        <br><br>
                                                        <div class="custom-radio">
                                                            <input type="radio" name="section[0].section_questions[0].answer[1].is_correct" id="option_radio_0_0_2" class="custom-control-input radio-btn" value="2">
                                                            <label class="custom-control-label col-lg-9"  for="option_radio_0_0_2">
                                                                <input type="text" name="section[0].section_questions[0].answer[1].option" placeholder="Option 2" class="form-control"  required>
                                                            </label>
                                                        </div>
                                                        <br><br>
                                                        <div class="custom-radio">
                                                            <input type="radio" name="section[0].section_questions[0].answer[2].is_correct" id="option_radio_0_0_3" class="custom-control-input radio-btn" value="3">
                                                            <label class="custom-control-label col-lg-9"  for="option_radio_0_0_3">
                                                                <input type="text" name="section[0].section_questions[0].answer[2].option" placeholder="Option 3" class="form-control"  required>
                                                            </label>
                                                        </div>
                                                        <br><br>
                                                        <div class="custom-radio">
                                                            <input type="radio" name="section[0].section_questions[0].answer[3].is_correct" id="option_radio_0_0_4" class="custom-control-input radio-btn" value="4">
                                                            <label class="custom-control-label col-lg-9"  for="option_radio_0_0_4">
                                                                <input type="text" name="section[0].section_questions[0].answer[3].option" placeholder="Option 4" class="form-control"  required>
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
                                                        <textarea  data-parsley-errors-container=".parsley-container-mc_explanation_test" id="mc_explanation_test" value="{{ isset($user) ? $user->mc_explanation_test : old('mc_explanation_test') }}" name="section[0].section_questions[0].mc_explanation_test" class="form-control" autocomplete="off"></textarea>
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
                                                        <textarea  data-parsley-errors-container=".parsley-container-sa_answer_1" id="sa_answer_1" value="{{ isset($user) ? $user->sa_answer_1 : old('sa_answer_1') }}" name="section[0].section_questions[0].answer[0].sa_answer" class="form-control" autocomplete="off"></textarea>
                                                    </div>
                                                </div>

                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="sa_answer_1">Short Answer 2<span class="text-danger">*</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <textarea  data-parsley-errors-container=".parsley-container-sa_answer_2" id="sa_answer_2" value="{{ isset($user) ? $user->sa_answer_2 : old('sa_answer_2') }}" name="section[0].section_questions[0].answer[1].sa_answer" class="form-control" autocomplete="off"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="sa_explanation_test">Explanation Test<span class="text-danger">*</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <textarea  data-parsley-errors-container=".parsley-container-sa_explanation_test" id="sa_explanation_test" value="{{ isset($user) ? $user->sa_explanation_test : old('sa_explanation_test') }}" name="section[0].section_questions[0].sa_explanation_test" class="form-control" autocomplete="off"></textarea>
                                                    </div>
                                                </div>                                            
                                            </div>

                                        </div>
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
                        </div>
                        <button class="btn btn-secondary" id="addmore_section" type="button">Add more sections</button>
                    </div>
                </div>
            </div>
        </div>

        
      
        <div class="nk-block">
            @isset($user)
                <input type="hidden" name="userId" id="userId" value="{{ $user->id }}">
            @endisset
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
        <input type="hidden" name="userFound" id="userFound" value="0">
    </form>
   
   

    <script type="text/javascript">

        $(document).ready(function(){
            var root_url = "<?php echo url('/'); ?>";

            var subject = $('#subject').val();
            if(subject != ''){
                getTopics(subject);
            }

            
        });

       

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
    <script src="{{url('js/forms.js')}}"></script>
@endsection
