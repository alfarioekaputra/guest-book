$(function() {
    // Function to open modal with form content
    window.openGlobalModal = function (url, title, submitUrl) {
        $('#globalModalLabel').text(title);
        
        // Reset form and set the correct submit URL
        if (submitUrl) {
            $('#globalForm').attr('action', submitUrl);
        } else {
            // Jika submitUrl tidak diberikan, gunakan URL default untuk save
            $('#globalForm').attr('action', 'example/save');
        }
        $('#globalModalContent').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');
        
        $('#error-message').addClass("d-none");
        $('.simpan').removeClass("d-none");
        // Load form content
        $.get(url, function (data) {
            if (data.success === false) {
                $('#error-message').removeClass("d-none");
                $('#error-message').text(data.message);
                $('.simpan').addClass("d-none");
            }

            $('#globalModalContent').html(data);

        });
        
        $('#globalModal').modal('show');
    };
    
    // Handle form submission
    $('#globalForm').on('submit', function(e) {
        e.preventDefault();
        
         const csrfToken = $('meta[name="csrf-token"]').attr('content');
        var form = $(this);
        var url = form.attr('action');
        
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            async: true,
            dataType: 'json',
            success: function (response) {
                
                if (response.success) {
                    // Show success message with Bootbox
                    bootbox.alert({
                        title: "Berhasil",
                        message: response.message,
                        callback: function() {
                            //refreh datatable
                            $('#dataTable').DataTable().ajax.reload();

                            // Close modal
                            $('#globalModal').modal('hide');
                        }
                    });
                } else {
                    // Show error message with Bootbox
                    bootbox.alert({
                        title: "Error",
                        message: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                bootbox.alert({
                    title: "Error",
                    message: "Terjadi kesalahan pada server"
                });
            }
        });
    });
});