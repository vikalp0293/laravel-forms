
    let autoSaveTimer;
    let examUUID = $('#uuid').val();

    $(document).ready(function () {
        // Auto-save every 30 seconds
        // autoSaveTimer = setInterval(autoSaveForm, 30000);

        // Auto-save on input field change
        $(document).on('change', 'input, textarea, select', function () {
            autoSaveForm();
        });

        // Auto-save images when selected
        $(document).on('change', 'input[type="file"]', function () {
            autoSaveImage(this);
        });
    });

    function autoSaveForm() {
        let formData = new FormData();
        formData.append('_token', "{{ csrf_token() }}");
        formData.append('exam_uuid', examUUID);
        formData.append('title', $('#title').val() || '');
        formData.append('description', $('#description').val() || '');
        formData.append('subject', $('#subject').val() || '');
        formData.append('topic', $('#topic').val() || '');
        formData.append('subtopic', $('#subtopic').val() || '');
        formData.append('state', $('#state').val() || '');
        formData.append('grade', $('#grade').val() || '');

        // Collect Backgrounds Data
        let backgrounds = [];
        $('.sections .section-box').each(function (index) {
            let backgroundID = $(this).find('.background').attr('data-backgroundsection') || index + 1;
            backgrounds.push({
                background: backgroundID,
                instructions: $(this).find('[name^="backgrounds["][name$="[instructions]"]').val() || '',
                instructions_2: $(this).find('[name^="backgrounds["][name$="[instructions_2]"]').val() || '',
                instructions_3: $(this).find('[name^="backgrounds["][name$="[instructions_3]"]').val() || '',
            });

            // Handle Background Images
            let bgImage1 = $(this).find('input[name^="backgrounds["][name$="[image_upload_1]"]')[0];
            let bgImage2 = $(this).find('input[name^="backgrounds["][name$="[image_upload_2]"]')[0];

            if (bgImage1 && bgImage1.files.length > 0) {
                formData.append(`backgrounds[${index}][image_upload_1]`, bgImage1.files[0]);
            }
            if (bgImage2 && bgImage2.files.length > 0) {
                formData.append(`backgrounds[${index}][image_upload_2]`, bgImage2.files[0]);
            }
        });
        formData.append('backgrounds', JSON.stringify(backgrounds));

        // Collect Questions Data
        let questions = [];
        $('.question-box').each(function (index) {
            questions.push({
                question: $(this).find('[name^="questions["][name$="[question]"]').val() || '',
                question_text_2: $(this).find('[name^="questions["][name$="[question_text_2]"]').val() || '',
                question_type: $(this).find('[name^="questions["][name$="[question_type]"]:checked').val() || '',
                correct_option: $(this).find('[name^="questions["][name$="[correct_option]"]:checked').val() || '',
                mc_explanation_text: $(this).find('[name^="questions["][name$="[mc_explanation_text]"]').val() || '',
                sa_explanation_text: $(this).find('[name^="questions["][name$="[sa_explanation_text]"]').val() || '',
            });

            // Handle Question Images
            let qImage1 = $(this).find('input[name^="questions["][name$="[question_image_1]"]')[0];
            let qImage2 = $(this).find('input[name^="questions["][name$="[question_image_2]"]')[0];

            if (qImage1 && qImage1.files.length > 0) {
                formData.append(`questions[${index}][question_image_1]`, qImage1.files[0]);
            }
            if (qImage2 && qImage2.files.length > 0) {
                formData.append(`questions[${index}][question_image_2]`, qImage2.files[0]);
            }
        });
        formData.append('questions', JSON.stringify(questions));

        // Debugging - Check data before sending
        console.log("Auto-save payload:", formData);

        return;

        $.ajax({
            url: "{{ route('exams.autoSave') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status === "success") {
                    examUUID = response.exam_uuid;
                    localStorage.setItem('exam_uuid', examUUID);
                    console.log("Auto-saved at " + new Date().toLocaleTimeString());
                }
            },
            error: function (xhr) {
                console.error("Auto-save failed: " + xhr.responseText);
            }
        });
    }

    function autoSaveImage(input) {
        let formData = new FormData();
        formData.append('_token', "{{ csrf_token() }}");
        formData.append('exam_uuid', examUUID);
        formData.append(input.name, input.files[0]);

        $.ajax({
            url: "{{ route('exams.autoSave') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log("Image auto-saved successfully.");
            },
            error: function (xhr) {
                console.error("Image auto-save failed: " + xhr.responseText);
            }
        });
    }

