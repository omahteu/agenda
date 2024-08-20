// $(document).ready(function() {
//     agendaDiaria()
// });

// async function agendaDiaria() {
//     const rq = await fetch("../php/leitura/ler_agenda.php")
//     const rs = await rq.json()
//     let data = rs
//     console.log(data)
//     var quadro = document.getElementById("agenda-diaria")
//     quadro.innerHTML = ''

//     data.forEach(e => {
//         quadro.innerHTML += `
//             <tr>
//                 <td>${e.data}</td>
//                 <td>${e.colaborador}</td>
//                 <td>${e.cep}</td>
//                 <td>${e.numero}</td>
//                 <td>${e.horario}</td>
//                 <td>${e.observacoes}</td>
//             </tr>
//         `
//     });
// }
$(document).ready(function() {
    agendaDiaria();

    // Adiciona o evento de mudança nos selects
    $('#filtro-data, #colaborador').on('change', function() {
        filtrarAgenda();
    });
});

async function agendaDiaria() {
    const rq = await fetch("../php/leitura/ler_agenda.php");
    const rs = await rq.json();
    let data = rs;
    console.log(data);
    var quadro = document.getElementById("agenda-diaria");
    quadro.innerHTML = '';

    data.forEach(e => {
        quadro.innerHTML += `
            <tr>
                <td>${e.data}</td>
                <td>${e.colaborador}</td>
                <td>${e.hospital}</td>
                <td>${e.material}</td>
                <td>${e.medico}</td>
                <td>${e.convenio}</td>
                <td>${e.horario}</td>
                <td>${e.observacoes}</td>
            </tr>
        `;
    });
}

async function filtrarAgenda() {
    // Obtém os valores selecionados nos filtros
    const dataSelecionada = $('#filtro-data').val();
    const colaboradorSelecionado = $('#filtro-colaborador').val();

    // Cria a URL com os parâmetros de filtro
    let url = `../php/recuperar/agenda.php?`;
    if (dataSelecionada) url += `data=${encodeURIComponent(dataSelecionada)}&`;
    if (colaboradorSelecionado) url += `colaborador=${encodeURIComponent(colaboradorSelecionado)}`;

    // Faz a requisição filtrada
    const rq = await fetch(url);
    const rs = await rq.json();
    let data = rs;
    console.log(data);

    // Atualiza a tabela com os dados filtrados
    var quadro = document.getElementById("agenda-diaria");
    quadro.innerHTML = '';

    data.forEach(e => {
        quadro.innerHTML += `
            <tr>
                <td>${e.data}</td>
                <td>${e.colaborador}</td>
                <td>${e.hospital}</td>
                <td>${e.material}</td>
                <td>${e.medico}</td>
                <td>${e.convenio}</td>
                <td>${e.horario}</td>
                <td>${e.observacoes}</td>
            </tr>
        `;
    });
}
