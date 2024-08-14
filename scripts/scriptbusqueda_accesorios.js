function consulta_buscador(busqueda) {
    var dato = 'busca';
    var parametros = { "busqueda": busqueda, "dato": dato };
    $.ajax({
        data: parametros,
        url: '../../scripts/busquedaAccesorio.php',
        type: 'POST',
        beforeSend: function () {
            console.log('ando viendo grasias');
        },
        success: function (data) {
            console.log('Todo bien joven');
            if (busqueda == '') {
                document.getElementById("card_busqueda").style.opacity = 0;
            } else {
                document.getElementById("card_busqueda").style.opacity = 1;
                document.getElementById("card_busqueda").style.transition = "all 1s";
            }
            document.getElementById("resultados_busqueda_nav").innerHTML = data;
        },
        error: function (data, error) {
            console.log('ta mal esto');
        }
    });
}
