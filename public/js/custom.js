jQuery(document).ready(function($){
	
	$("#avatar").on("change", function(e) {

        e.preventDefault();
        var file_data = $("#avatar").prop("files")[0];
        var form_data = new FormData();
        form_data.append("image", file_data);

        $.ajax({
            url: './imageUpload',
            type: "POST",
            // allowedFileExtensions: ["jpg", "png", "gif", "jpeg"],
            // allowedFileTypes: ['image'],
            data: form_data,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
                if(data.success){
                    console.log('Image Upload Successful.');
                }
                if(data.error){
                    console.log('Only image files supported.');
                }
            },
            error:function(error){
                console.log('Something went wrong');
            }

        });
    });

});