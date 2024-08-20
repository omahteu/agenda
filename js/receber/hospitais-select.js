$(document).ready(function() {
    quadroColaboradoresSelect()
});

async function quadroColaboradoresSelect() {
    const rq = await fetch("../php/leitura/ler_hospitais.php")
    const rs = await rq.json()
    let data = JSON.parse(rs)
    console.log(data)

    var empresaSelect = $('#filtro-hospital');
    empresaSelect.empty();
    empresaSelect.append('<option value="" hidden>Selecione um hospital</option>');
    $.each(data, function(index, e) {
        empresaSelect.append($('<option>', {
            value: e.hospital,
            text: e.hospital
        }));
    });
}