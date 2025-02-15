$(function() {
    // Function to open modal with form content
    window.openGlobalModal = function(url, title, submitUrl) {
        $('#globalModalLabel').text(title);
        
        // Reset form and set the correct submit URL
        if (submitUrl) {
            $('#globalForm').attr('action', submitUrl);
        } else {
            // Jika submitUrl tidak diberikan, gunakan URL default untuk save
            $('#globalForm').attr('action', 'example/save');
        }
        $('#globalModalContent').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');
        
        // Load form content
        $.get(url, function(data) {
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
                            // Refresh data table if exists
                            if (typeof dataTable !== 'undefined') {
                                dataTable.ajax.reload();
                            }
                            
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

    // Function to update CSRF token
    function updateCsrfToken() {
        // Check if response header contains new CSRF token
        var csrfName = $('.csrf_token').attr('name');
        var csrfHash = $('.csrf_token').val();
        
        // If we have CSRF token in the response
        if (typeof csrfHash !== 'undefined') {
            // Update all CSRF tokens in the form
            $('.csrf_token').val(csrfHash);
        }
    }
});