$(document).ready(function() {
    quadroColaboradoresSelect()
});

async function quadroColaboradoresSelect() {
    const rq = await fetch("../php/leitura/ler_usuarios.php")
    const rs = await rq.json()
    let data = JSON.parse(rs)
    console.log(data)

    var empresaSelect = $('#colaborador');
    empresaSelect.empty();
    empresaSelect.append('<option value="" hidden>Selecione um colaborador</option>');
    $.each(data, function(index, empresa) {
        empresaSelect.append($('<option>', {
            value: empresa.id,
            text: `${empresa.nome} | ${empresa.cpf}`
        }));
    });
}