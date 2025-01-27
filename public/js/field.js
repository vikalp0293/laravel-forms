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