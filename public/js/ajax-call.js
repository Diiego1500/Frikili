
function MeGusta(id){
    var Ruta = Routing.generate('Likes');
    $.ajax({
        type: 'POST',
        url: Ruta,
        data: ({id: id}),
        async: true,
        dataType: "json",
        success: function (data) {
            window.location.reload();
        }
    });
}

