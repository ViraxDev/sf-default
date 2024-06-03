import $ from "jquery";

$(document).ready(function () {
    init();
})

function init() {
    onPhotoChange([$('#profile-image'), $('#upload-cover-image')]);
}

function onPhotoChange(elements) {
    elements.forEach(function (element) {
        element.on("change", function(){
            if($(this).prop('files').length > 0)
            {
                let formData = new FormData();
                let file = $(this).prop('files')[0];

                formData.append('photo', file);
                formData.append('property', $(this).data('property'));

                $.ajax({
                    url: $(this).data('path-upload'),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'html',
                    success: function (data) {
                        let picturesBloc = $(data).find('#pictures-bloc');
                        $('#pictures-bloc').replaceWith(picturesBloc);

                        init();
                    }
                });
            }
        });
    });
}
