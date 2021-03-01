$(document).ready(function ()
{
    const flashdatas = $('.error').data('flashdata');
    if (flashdatas)
    {
        Swal.fire(
            'Good job!',
            'You clicked the button!',
            'success'
          )
    }
    const flashdata = $('.success').data('flashdata');
    if (flashdata) {
        swal({
            title: "Proses Berhasil",
            text: flashdata,
            type: "success",
        });
    }

    const flashdatasi = $('.info').data('flashdata');
    if(flashdatasi){
        swal({
            title: "Informasi",
            text: flashdatasi,
            type: "info",
        });
    }
});
