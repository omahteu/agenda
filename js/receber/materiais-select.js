$(document).ready(function() {
    quadroColaboradoresSelect()
});

async function quadroColaboradoresSelect() {
    const rq = await fetch("../php/leitura/ler_materiais.php")
    const rs = await rq.json()
    let data = JSON.parse(rs)
    console.log(data)

    var empresaSelect = $('#filtro-material');
    empresaSelect.empty();
    empresaSelect.append('<option value="" hidden>Selecione um material</option>');
    $.each(data, function(index, e) {
        empresaSelect.append($('<option>', {
            value: e.material,
            text: e.material
        }));
    });
}