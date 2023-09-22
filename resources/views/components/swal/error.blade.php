Swal.fire({
    icon: 'error',
    title: 'Error',
    text: error.response.data.message,
    timer: 1500,
    showConfirmButton: false,
});