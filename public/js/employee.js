$(document).ready(function () {
    var position_id = $('#position_id').val();
    var status = $('#status_employee').val();

    if (position_id) {
        $('#employeePosition').select2().val(position_id).trigger('change');
    } else {
        $('#employeePosition').select2();
    }

    if (status) {
        $('#statusEmployee').select2().val(status).trigger('change');
    } else {
        $('#statusEmployee').select2();
    }
    
 });