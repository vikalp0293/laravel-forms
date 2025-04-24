@extends('layouts.app')

@php
$userPermission = \Session::get('userPermission');
@endphp

@section('content')
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title"><a href="javascript:history.back()" class="pt-3"><em class="icon ni ni-chevron-left back-icon"></em> </a> Edit Exam</h3>
            </div><!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->

    <form role="form" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="uuid" name="uuid" value="{{ $exam->uuid }}">
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
                                    <input type="text" required="true" data-parsley-errors-container=".parsley-container-title" id="title" value="{{ $exam->title }}" name="title" maxlength="50" class="form-control" autocomplete="off">
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
                                    <textarea rows="2" required="true" data-parsley-errors-container=".parsley-container-description" id="description" name="description" class="form-control" autocomplete="off">{{ $exam->description }}</textarea>
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
                                    <select size="sm" class="form-select form-control form-control-lg" data-placeholder="Select Subject" data-parsley-errors-container=".subjectParsley" name="subject" id="subject" data-search='on' required onchange="getTopics(this.options[this.selectedIndex].value)">
                                        <option value="">Select subject</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ $exam->subject == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
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
                                    <select size="sm" class="form-select form-control form-control-lg" data-placeholder="Select Topic" data-parsley-errors-container=".topicParsley" name="topic" id="topic" data-search='on' required onchange="getSubTopics(this.options[this.selectedIndex].value)">
                                        
                                        @foreach ($topics as $topic)
                                            <option value="{{ $topic->id }}" {{ $exam->topic == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                                        @endforeach
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
                                    <select size="sm" class="form-select form-control form-control-lg" data-placeholder="Select Subtopic" data-parsley-errors-container=".subtopicParsley" name="subtopic" id="subtopic" data-search='on' required>
                                        <option value="">Select Subtopic</option>
                                        @foreach ($subtopics as $subtopic)
                                            <option value="{{ $subtopic->id }}" {{ $exam->subtopic == $subtopic->id ? 'selected' : '' }}>{{ $subtopic->name }}</option>
                                        @endforeach
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
                                            <select size="sm" class="form-select form-control form-control-lg" data-placeholder="Select State" data-parsley-errors-container=".stateParsley" name="state" id="state" data-search='on' required>
                                                <option value="">Select state</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}" {{ $exam->state == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
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
                                            <select size="sm" class="form-select form-control form-control-lg" data-placeholder="Select Grade" data-parsley-errors-container=".gradeParsley" name="grade" id="grade" data-search='on' required>
                                                <option value="">Select grade</option>
                                                @foreach ($grades as $grade)
                                                    <option value="{{ $grade->id }}" {{ $exam->grade == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
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
                            <div class="row g-3 align-center">
                                <div class="col-lg-3">
                                    <x-inputs.verticalFormLabel label="Search Questions" for="search" suggestion="Search questions from existing exams." />
                                </div>
                                <div class="col-lg-8">
                                    <input type="text" data-parsley-errors-container=".parsley-container-search" id="search" value="" name="search" placeholder="Search questions from existing exams" class="form-control" autocomplete="off">

                                    
                                </div>
                                <div class="col-lg-1">
                                    <a 
                                    href="javascript:void(0);"
                                    class="btn btn-info searchBtn" 
                                    >
                                        <em class="icon ni ni-search"></em>
                                    </a>
                                </div>
                                
                            </div>
                            <div  id="questionRows"></div>
                            <a style="display: none;" href="javascript:void(0);" class="btn btn-dark addSearchQuestionBtn"><em class="icon ni ni-plus"></em> Add Question</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="nk-block">
            <div class="card card-bordered sp-plan">
                <div class="row no-gutters">
                    <div class="col-md-12">
                        <div class="sp-plan-info card-inner">
                            <div class="sections">
                                @foreach ($mergedData as $index => $section)
                                    <div class="section-box sections-{{ $index + 1 }}" style="border: 1px solid; padding: 15px; border-radius: 10px; box-shadow: 10px 10px #969b9d; background-color: rgb(241, 243, 202);">
                                        <div class="row g-3 align-center">
                                            <div class="col-lg-3">
                                                <label class="form-label" for="Background">Background</label>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="custom-control custom-control-md custom-switch">
                                                    <input type="checkbox" data-backgroundsection="{{ $index + 1 }}" name="backgrounds[{{ $index + 1 }}][background]" id="background_{{ $index + 1 }}" class="custom-control-input background" value="{{ $index + 1 }}" data-parsley-multiple="background" {{ !empty($section['background']) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="background_{{ $index + 1 }}"> </label>
                                                </div>
                                            </div>
                                        </div>

                                        @if (!empty($section['background']))

                                            <input type="hidden" name="backgrounds[{{ $index + 1 }}][id]" value="{{ $section['background']['id'] }}">

                                            <div class="background_section_{{ $index + 1 }}" style="display: block;">
                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="instructions">Instructions</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <textarea data-parsley-errors-container=".parsley-container-instructions" id="instructions" name="backgrounds[{{ $index + 1 }}][instructions]" class="form-control background-image-input-{{ $index + 1 }}" autocomplete="off">{{ $section['background']['instruction'] }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="instructions_2">Instructions 2</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <textarea data-parsley-errors-container=".parsley-container-instructions_2" id="instructions_2" name="backgrounds[{{ $index + 1 }}][instructions_2]" class="form-control background-image-input-{{ $index + 1 }}" autocomplete="off">{{ $section['background']['instruction_two'] }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <x-inputs.verticalFormLabel label="Upload Image 1" for="default-06" />
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input background-image-input-{{ $index + 1 }}" id="image_upload_1" name="backgrounds[{{ $index + 1 }}][image_upload_1]" accept=".png, .jpg, .jpeg" >
                                                                    <label class="custom-file-label" for="image_upload_1">Choose file</label>
                                                                    <span class="error-message" style="color: red; display: none;">Invalid file type or size exceeds 2MB</span>
                                                                </div>
                                                                @if(isset($section['background']['image_one']) && !is_null($section['background']['image_one']))
                                                                <div class="media_box imageone_box_background_{{ $section['background']['id'] }}">
                                                                    <img height="100" width="100" src="{{url('uploads/questions/backgrounds/'.$section['background']['image_one'])}}">
                                                                    <a 
                                                                    href="javascript:void(0);" 
                                                                    data-id="{{ $section['background']['id'] }}" 
                                                                    data-type="background"
                                                                    data-name="image_one"
                                                                    data-box="imageone_box_background_{{ $section['background']['id'] }}"
                                                                    
                                                                    class="removeImage">
                                                                        <i class="fa fa-trash"></i> Remove
                                                                    </a>
                                                                </div>
                                                                @endif
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
                                                        <textarea data-parsley-errors-container=".parsley-container-instructions_3" id="instructions_3" name="backgrounds[{{ $index + 1 }}][instructions_3]" class="form-control background-image-input-{{ $index + 1 }}" autocomplete="off">{{ $section['background']['instruction_three'] }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="row g-3 align-center">
                                                    <div class="col-lg-3">
                                                        <x-inputs.verticalFormLabel label="Upload Image 2" for="default-06" />
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input background-image-input-{{ $index + 1 }}" id="image_upload_2" name="backgrounds[{{ $index + 1 }}][image_upload_2]" accept=".png, .jpg, .jpeg" >
                                                                    <label class="custom-file-label" for="image_upload_2">Choose file</label>
                                                                    <span class="error-message" style="color: red; display: none;">Invalid file type or size exceeds 2MB</span>
                                                                </div>
                                                            </div>
                                                            @if(isset($section['background']['image_two']) && !is_null($section['background']['image_two']))
                                                            <div class="media_box imagetwo_box_background_{{ $section['background']['id'] }}">
                                                                <img height="100" width="100" src="{{url('uploads/questions/backgrounds/'.$section['background']['image_two'])}}">
                                                                <a 
                                                                href="javascript:void(0);" 
                                                                data-id="{{ $section['background']['id'] }}" 
                                                                data-type="background"
                                                                data-name="image_two"
                                                                data-box="imagetwo_box_background_{{ $section['background']['id'] }}"
                                                                
                                                                class="removeImage"
                                                                >
                                                                    <i class="fa fa-trash"></i> Remove
                                                                </a>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                        @endif

                                        <div class="question-block-{{ $index + 1 }}">
                                            @foreach ($section['questions'] as $questionIndex => $question)

                                                <input type="hidden" name="questions[{{ $question['id'] }}][id]" value="{{ $question['id'] }}">

                                                <div class="question-box question-wrapper-{{ $question['id'] }}">
                                                    <h6 class="text-center mt-3"> Question {{ $question['id'] }}</h6>
                                                    <input type="hidden" name="questions[{{ $question['id'] }}][background]" class="question" value="{{ $question['background_number'] }}">
                                                    <div class="row g-3 align-center">
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label class="form-label" for="question">Question<span class="text-danger">*</span></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-9">
                                                            <textarea required data-parsley-errors-container=".parsley-container-question" id="question" name="questions[{{ $question['id'] }}][question]" class="form-control" autocomplete="off">{{ $question['question'] }}</textarea>
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
                                                                        <input type="file" class="custom-file-input" id="question_image_1" name="questions[{{ $question['id'] }}][question_image_1]" accept=".png, .jpg, .jpeg" >
                                                                        <label class="custom-file-label" for="question_image_1">Choose file</label>
                                                                        <span class="error-message" style="color: red; display: none;">Invalid file type or size exceeds 2MB</span>
                                                                    </div>
                                                                </div>
                                                                @if(isset($question['question_image_one']) && !is_null($question['question_image_one']))
                                                                <div class="media_box questionimageone_box_{{ $question['id'] }}">
                                                                    <img height="100" width="100" src="{{url('uploads/questions/'.$question['question_image_one'])}}">
                                                                    <a href="javascript:void(0);" 
                                                                    data-id="{{ $question['id'] }}" 
                                                                    data-type="question"
                                                                    data-name="question_image_1"
                                                                    data-box="questionimageone_box_{{ $question['id'] }}"
                                                                    
                                                                    class="removeImage">
                                                                        <i class="fa fa-trash"></i> Remove
                                                                    </a>
                                                                </div>
                                                                @endif
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
                                                            <textarea data-parsley-errors-container=".parsley-container-question_text_2" id="question_text_2" name="questions[{{ $question['id'] }}][question_text_2]" class="form-control" autocomplete="off">{{ $question['question_text_two'] }}</textarea>
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
                                                                        <input type="file" class="custom-file-input" id="question_image_2" name="questions[{{ $question['id'] }}][question_image_2]" accept=".png, .jpg, .jpeg" >
                                                                        <label class="custom-file-label" for="question_image_2">Choose file</label>
                                                                        <span class="error-message" style="color: red; display: none;">Invalid file type or size exceeds 2MB</span>
                                                                    </div>
                                                                </div>
                                                                @if(isset($question['question_image_two']) && !is_null($question['question_image_two']))
                                                                <div class="media_box questionimagetwo_box_{{ $question['id'] }}">
                                                                    <img height="100" width="100" src="{{url('uploads/questions/'.$question['question_image_two'])}}">
                                                                    <a href="javascript:void(0);" 
                                                                    data-id="{{ $question['id'] }}" 
                                                                    data-type="question"
                                                                    data-name="question_image_2"
                                                                    data-box="questionimagetwo_box_{{ $question['id'] }}"
                                                                    
                                                                    class="removeImage">
                                                                        <i class="fa fa-trash"></i> Remove
                                                                    </a>
                                                                </div>

                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 align-center">
                                                        <div class="col-lg-3">
                                                            <label class="form-label" for="default-06">Standard</label>
                                                        </div>
                                                        <div class="col-lg-9">
                                                            <div class="form-group">
                                                                <div class="form-control-wrap">
                                                                    <select size="sm" class="form-select form-control form-control-lg standards standardOptions" data-placeholder="Select Standard" data-parsley-errors-container=".gradeParsley" name="questions[{{ $question['id'] }}][standard]" id="standard_{{$question['id']}}" data-search='on' > 
                                                                        <option value="0">Select standard</option>
                                                                        @foreach ($standards as $standard)
                                                                        <option value="{{ $standard->id }}" {{ $question['standard'] == $standard->id ? 'selected' : '' }}>{{ $standard->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 align-center">
                                                        <div class="col-lg-3">
                                                            <x-inputs.verticalFormLabel label="Question Type" for="default-06" />
                                                        </div>
                                                        <div class="col-lg-9">
                                                            <div class="form-group">
                                                                <div class="form-control-wrap">
                                                                    <div class="custom-control custom-control-xs custom-radio">
                                                                        <input type="radio" name="questions[{{ $question['id'] }}][question_type]" data-question="{{ $question['id'] }}" id="question_type_mc_{{ $question['id'] }}" class="custom-control-input radio-btn question-choice" value="mc" {{ $question['question_type'] == 'mc' ? 'checked' : '' }}>
                                                                        <label class="custom-control-label" for="question_type_mc_{{ $question['id'] }}">Make it MC</label>
                                                                    </div>

                                                                    <div class="custom-control custom-control-xs custom-radio">
                                                                        <input type="radio" name="questions[{{ $question['id'] }}][question_type]" data-question="{{ $question['id'] }}" id="question_type_sa_{{ $question['id'] }}" class="custom-control-input radio-btn question-choice" value="sa" {{ $question['question_type'] == 'sa' ? 'checked' : '' }}>
                                                                        <label class="custom-control-label" for="question_type_sa_{{ $question['id'] }}">Make it Short answer</label>
                                                                    </div>

                                                                    <!-- <div class="custom-control custom-control-xs custom-radio">
                                                                        <input type="radio" name="questions[{{ $question['id'] }}][question_type]" data-question="{{ $question['id'] }}" id="question_type_admin_{{ $question['id'] }}" class="custom-control-input radio-btn question-choice" value="admin" {{ $question['question_type'] == 'admin' ? 'checked' : '' }}>
                                                                        <label class="custom-control-label" for="question_type_admin_{{ $question['id'] }}">Admin Only</label>
                                                                    </div> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @php
                                                        $showMCSection = 'display: none;';
                                                        if ($question['question_type'] == 'mc'){
                                                            $showMCSection = 'display: block;';
                                                        }
                                                    @endphp

                                                        <div class="mc_section_{{ $question['id'] }}" style="{{ $showMCSection  }}">
                                                            <div class="row g-3 align-center">
                                                                <div class="col-lg-3">
                                                                    <x-inputs.verticalFormLabel label="MC Options" for="default-112" />
                                                                </div>
                                                                <div class="col-lg-9">
                                                                    <table>
                                                                        <tr>
                                                                            <td>Correct Answer</td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td style="width:80%">
                                                                                <div class="custom-radio">
                                                                                    <input type="radio" name="questions[{{ $question['id'] }}][correct_option]" id="option_radio_1_{{ $question['id'] }}" class="custom-control-input radio-btn mc_options_{{ $question['id'] }}" value="1" {{ $question['correct_option'] == 1 ? 'checked' : '' }}>
                                                                                    <label class="custom-control-label col-lg-9" for="option_radio_1_{{ $question['id'] }}">
                                                                                        <input type="text" name="questions[{{ $question['id'] }}][option1]" placeholder="Option 1" class="form-control mc_options_{{ $question['id'] }}" value="{{ $question['option_one'] }}" required>
                                                                                    </label>
                                                                                </div>
                                                                                <br><br>
                                                                                <div class="custom-radio">
                                                                                    <input type="radio" name="questions[{{ $question['id'] }}][correct_option]" id="option_radio_2_{{ $question['id'] }}" class="custom-control-input radio-btn mc_options_{{ $question['id'] }}" value="2" {{ $question['correct_option'] == 2 ? 'checked' : '' }}>
                                                                                    <label class="custom-control-label col-lg-9" for="option_radio_2_{{ $question['id'] }}">
                                                                                        <input type="text" name="questions[{{ $question['id'] }}][option2]" placeholder="Option 2" class="form-control mc_options_{{ $question['id'] }}" value="{{ $question['option_two'] }}" required>
                                                                                    </label>
                                                                                </div>
                                                                                <br><br>
                                                                                <div class="custom-radio">
                                                                                    <input type="radio" name="questions[{{ $question['id'] }}][correct_option]" id="option_radio_3_{{ $question['id'] }}" class="custom-control-input radio-btn mc_options_{{ $question['id'] }}" value="3" {{ $question['correct_option'] == 3 ? 'checked' : '' }}>
                                                                                    <label class="custom-control-label col-lg-9" for="option_radio_3_{{ $question['id'] }}">
                                                                                        <input type="text" name="questions[{{ $question['id'] }}][option3]" placeholder="Option 3" class="form-control mc_options_{{ $question['id'] }}" value="{{ $question['option_three'] }}" required>
                                                                                    </label>
                                                                                </div>
                                                                                <br><br>
                                                                                <div class="custom-radio">
                                                                                    <input type="radio" name="questions[{{ $question['id'] }}][correct_option]" id="option_radio_4_{{ $question['id'] }}" class="custom-control-input radio-btn mc_options_{{ $question['id'] }}" value="4" {{ $question['correct_option'] == 4 ? 'checked' : '' }}>
                                                                                    <label class="custom-control-label col-lg-9" for="option_radio_4_{{ $question['id'] }}">
                                                                                        <input type="text" name="questions[{{ $question['id'] }}][option4]" placeholder="Option 4" class="form-control mc_options_{{ $question['id'] }}" value="{{ $question['options_four'] }}" required>
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
                                                                    <textarea data-parsley-errors-container=".parsley-container-mc_explanation_text" id="mc_explanation_text" name="questions[{{ $question['id'] }}][mc_explanation_text]" class="form-control" autocomplete="off">{{ $question['explanation'] }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                    @php
                                                        $showSASection = 'display: none;';
                                                        if ($question['question_type'] == 'sa'){
                                                            $showSASection = 'display: block;';
                                                        }
                                                    @endphp

                                                    
                                                        <div class="sa_section_{{ $question['id'] }}" style="{{ $showSASection  }}">
                                                            <div class="row g-3 align-center">
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="sa_answer_1">Short Answer 1<span class="text-danger">*</span></label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9">
                                                                    <textarea data-parsley-errors-container=".parsley-container-sa_answer_1" id="sa_answer_1" name="questions[{{ $question['id'] }}][sa_answer_1]" class="form-control sa_answer_{{ $question['id'] }}" autocomplete="off">{{ $question['short_answer_one'] }}</textarea>
                                                                </div>
                                                            </div>

                                                            <div class="row g-3 align-center">
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="sa_answer_1">Short Answer 2</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9">
                                                                    <textarea data-parsley-errors-container=".parsley-container-sa_answer_2" id="sa_answer_2" name="questions[{{ $question['id'] }}][sa_answer_2]" class="form-control" autocomplete="off">{{ $question['short_answer_two'] }}</textarea>
                                                                </div>
                                                            </div>

                                                            <div class="row g-3 align-center">
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="sa_explanation_text">Explanation Text</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9">
                                                                    <textarea data-parsley-errors-container=".parsley-container-sa_explanation_text" id="sa_explanation_text" name="questions[{{ $question['id'] }}][sa_explanation_text]" class="form-control sa_answer_{{ $question['id'] }}" autocomplete="off">{{ $question['explanation'] }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    

                                                    <input type="hidden" value="{{ $question['copied_from_question'] }}" name="questions[{{ $question['id'] }}][copied_from_question]">

                                                    <div class="text-right" style="margin:5px 0px 5px 0px !important;">
                                                        <a href="javascript:void(0);" class="btn btn-danger remove_button delete_question" data-box="{{ $question['id'] }}"><em class="icon ni ni-trash"></em> Remove Question</a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="text-right">
                                            <a style="display:none !important;" href="javascript:void(0);" data-section="{{ $index + 1 }}" class="btn btn-secondary d-none d-md-inline-flex addQuestionSameBackground addQuestionBtn_{{ $index + 1 }}"><em class="icon ni ni-plus"></em><span>Add Another Question Using Same Background</span></a>
                                        </div>
                                    </div>
                                @endforeach
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

        <input type="hidden" name="delete_question" value="" id="delete_question_input">

        <input type="hidden" id="status" value="published" name="status">

        <div class="nk-block">
            <div class="row">
                <div class="col-md-12">
                    <div class="sp-plan-info pt-0 pb-0 card-inner">
                        <div class="row">
                            <div class="col-lg-7 text-right offset-lg-5">
                                <div class="form-group">
                                    <a href="javascript:history.back()" class="btn btn-outline-light">Cancel</a>
                                    <button type="submit" class="btn btn-secondary" onclick="setStatus(event, 'draft')">Save as Draft</button>
                                    <button type="submit" class="btn btn-primary" onclick="setStatus(event, 'published')">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div><!-- .sp-plan-info -->
                </div><!-- .col -->
            </div><!-- .row -->
        </div>
    </form>

    <script type="text/javascript">


        function setStatus(event, status) {
            document.getElementById("status").value = status; // Set status dynamically
        }

        var root_url = "<?php echo Request::root(); ?>";
        
        $(document).on('click', '.removeImage', function(){ 

            if (confirm("Are you sure you want to remove this image?")) {
                var id = $(this).data('id');
                var type = $(this).data('type');
                var name = $(this).data('name');
                var box = $(this).data('box');

                var root_url = "<?php echo Request::root(); ?>";
                $.ajax({
                    url: root_url + '/exams/remove-image/' + id + '/' + type + '/' + name,
                    data: {},
                    method: "GET",
                    cache: false,
                    success: function(data) {
                        if(data.success){

                            $('.'+box).remove();

                            Swal.fire(
                              'Good job!',
                              'Image removed Successfully.',
                              'success'
                            )
                        }else{
                            Swal.fire({
                              icon: 'error',
                              title: 'Oops...',
                              text: 'Something went wrong',
                            })
                        }
                    }
                });
            }

        });

        function getTopics(subject_id) {
            var root_url = "<?php echo Request::root(); ?>";
            $.ajax({
                url: root_url + '/exams/get-topics-by-subject/' + subject_id,
                data: {},
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
            $.ajax({
                url: root_url + '/exams/get-subtopics-by-topic/' + topic_id,
                data: {},
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

            $.ajax({
                url: root_url + '/exams/get-standards-by-subtopic/' + topic_id,
                data: {},
                //dataType: "html",
                method: "GET",
                cache: false,
                success: function(response) {
                    $(".standards").html('');
                    $(".standards").append($('<option value="0" selected>Select Standard</option>').val(0).html('Select Standard'));

                    $.each(response.standards, function(key, value) {
                        if (value.id != 0) {
                            $(".standards").append($('<option></option>').val(value.id).html(value.name));
                        }
                    });
                }
            });
        }
    </script>

    <script src="{{ url('js/exam-forms.js') }}?time={{ time() }}"></script>
    <script type="text/javascript">
        updateQuestionsBasedOnCheckbox();
    </script>
@endsection