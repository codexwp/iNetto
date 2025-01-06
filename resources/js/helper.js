window.confirmFormModal = function (route, title='Confirmation',  message = 'Are you sure?',  confirm_label = 'Confirm', cancel_label = 'Cancel')
{
    const csrf_token = $("meta[name='csrf-token']").attr("content");
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success btn-sm',
            cancelButton: 'btn btn-danger mr-3 btn-sm',
            container : 'custom-swal-container'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: title,
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: confirm_label,
        cancelButtonText: cancel_label,
        reverseButtons: true,
    }).then((result) => {
        if (result.value) {
            $('<form method="POST" action="' + route + '"><input type="hidden" name="_token" value="' + csrf_token + '"></form>')
                .appendTo('body')
                .submit();
        }
    })
}

window.confirmModal = function (callback, id='', message = 'Are you sure?',title='Confirmation',  confirm_label = 'Confirm', cancel_label = 'Cancel')
{
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success btn-sm',
            cancelButton: 'btn btn-danger mr-3 btn-sm',
            container : 'custom-swal-container'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: title,
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check"></i> ' + confirm_label,
        cancelButtonText: '<i class="fas fa-times"></i> ' + cancel_label,
        reverseButtons: true,
    }).then((result) => {
        if (result.value) {
            if(id)
                callback(id);
            else
                callback();
        }
    })
}

window.notify = function (message, type = 'success', callback = null) {
    Swal.fire({
        toast: true,
        position: 'bottom-right',
        icon: type,
        text: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', () => {
                Swal.stopTimer();
            });
            toast.addEventListener('mouseleave', () => {
                Swal.resumeTimer();
            });
        },
        willClose: () => {
            if(typeof callback === 'function')
            {
                callback();
            }
        }
    });
}

window.loading = function (show_hide = true) {
    const loader = $("#loading-overlay");
    if(show_hide){
        loader.show();
    } else {
        loader.hide();
    }
}
