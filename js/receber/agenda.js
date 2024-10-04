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
    $('#filtro-data, #colaborador, #filtro-hospital, #filtro-material').on('change', function() {
        filtrarAgenda();
    });
});

function agendaDiaria() {

    $.ajax({
        url: "../php/leitura/ler_agenda.php",
        type: 'GET',
        success: function(response) {
            
            let data = response;
    
            var quadro = document.getElementById("agenda-diaria");
            quadro.innerHTML = '';
        
            const combinacoes = {};
        
        
        
            data.forEach(e => {
        
                const chave = `${e.data}-${e.horario}-${e.hospital}`;
        
                // Verifica se a chave já existe
                if (combinacoes[chave]) {
                    iziToast.warning({
                        title: 'Conflito',
                        message: `Atenção: Os colaboradores ${combinacoes[chave]} e ${e.colaborador} estão no mesmo dia, na mesma hora e no mesmo local.`,
                        position: 'topRight',
                        timeout: 3000
                    });
                    //alert(`Atenção: Os colaboradores ${combinacoes[chave]} e ${e.colaborador} estão no mesmo dia, na mesma hora e no mesmo local.`);
                } else {
                    // Armazena o colaborador na combinação
                    combinacoes[chave] = e.colaborador;
                }
        
                quadro.innerHTML += `
                    <tr>
                        <td>${e.data}</td>
                        <td>${e.colaborador}</td>
                        <td>${e.hospital}</td>
                        <td>${e.material}</td>
                        <td>${e.medico}</td>
                        <td>${e.convenio}</td>
                        <td>${e.horario_inicio}</td>
                        <td>${e.horario_fim}</td>
                        <td>${e.observacoes}</td>
                        <td><button type="button" class="btn btn-primary edit-btn" value="${e.id}"><i class="bi bi-pencil"></i></button></td>
                        <td><button type="button" class="btn btn-danger del-btn" value="${e.id}"><i class="bi bi-trash"></i></button></td>
                    </tr>
                `;
            });
        },
        error: function(xhr, status, error) {
            let msg_raw_erro = JSON.parse(xhr.responseText);
            iziToast.error({
                title: 'Erro',
                message: msg_raw_erro.error,
                position: 'topRight',
                timeout: 3000
            });
        }
    });
    
    // const rq = await fetch("../php/leitura/ler_agenda.php");
    // const rs = await rq.json();
    // let data = rs;
    
    // var quadro = document.getElementById("agenda-diaria");
    // quadro.innerHTML = '';

    // const combinacoes = {};



    // data.forEach(e => {

    //     const chave = `${e.data}-${e.horario}-${e.hospital}`;

    //     // Verifica se a chave já existe
    //     if (combinacoes[chave]) {
    //         alert(`Atenção: Os colaboradores ${combinacoes[chave]} e ${e.colaborador} estão no mesmo dia, na mesma hora e no mesmo local.`);
    //     } else {
    //         // Armazena o colaborador na combinação
    //         combinacoes[chave] = e.colaborador;
    //     }

    //     quadro.innerHTML += `
    //         <tr>
    //             <td>${e.data}</td>
    //             <td>${e.colaborador}</td>
    //             <td>${e.hospital}</td>
    //             <td>${e.material}</td>
    //             <td>${e.medico}</td>
    //             <td>${e.convenio}</td>
    //             <td>${e.horario_inicio}</td>
    //             <td>${e.horario_fim}</td>
    //             <td>${e.observacoes}</td>
    //             <td><button type="button" class="btn btn-primary" value="${e.id}"><i class="bi bi-pencil"></i></button></td>
    //             <td><button type="button" class="btn btn-danger" value="${e.id}"><i class="bi bi-trash"></i></button></td>
    //         </tr>
    //     `;
    // });
}


async function filtrarAgenda() {
    // Obtém os valores selecionados nos filtros
    const dataSelecionada = $('#filtro-data').val();
    const colaboradorSelecionado = $('#colaborador').val();
    const hospitalSelecionado = $('#filtro-hospital').val();
    const materialSelecionado = $('#filtro-material').val();

    // Cria a URL com os parâmetros de filtro
    let url = `../php/recuperar/agenda.php?`;
    if (dataSelecionada) url += `data=${encodeURIComponent(dataSelecionada)}&`;
    if (colaboradorSelecionado) url += `colaborador=${encodeURIComponent(colaboradorSelecionado)}&`;
    if (hospitalSelecionado) url += `hospital=${encodeURIComponent(hospitalSelecionado)}&`;
    if (materialSelecionado) url += `material=${encodeURIComponent(materialSelecionado)}&`;

    // Faz a requisição filtrada
    const rq = await fetch(url);
    const rs = await rq.json();
    let data = rs;

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
                <td>${e.horario_inicio}</td>
                <td>${e.horario_fim}</td>
                <td>${e.observacoes}</td>
                <td><button type="button" class="btn btn-primary edit-btn" value="${e.id}"><i class="bi bi-pencil"></i></button></td>
                <td><button type="button" class="btn btn-danger del-btn" value="${e.id}"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;
    });
}
