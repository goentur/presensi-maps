$(function() {
     $.ajaxSetup({
          headers: {
               "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
          }
     })
})
function alertApp(t,m) {
    Swal.fire({
        icon: t,
        title: m,
        showConfirmButton: false,
        timer: 1500
    });
}