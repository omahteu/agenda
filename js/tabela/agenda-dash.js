// $(document).ready(function(){
//     setTimeout(() => {
//         build_tabela()
//     }, 1000);
// });

// function build_tabela() {
//     $.ajax({
//         url: '../php/leitura/ler_agenda_tab.php', // O caminho para o script PHP
//         type: 'GET',
//         dataType: 'json',
//         success: function(data) {
//             console.log(data);
//             // Define os horários para as colunas
//             let horarios = [];
//             for (let i = 0; i < 24; i++) {
//                 horarios.push((i < 10 ? '0' : '') + i + ':00');
//                 horarios.push((i < 10 ? '0' : '') + i + ':30');
//             }

//             let tabela = '<div class="table-responsive"><table class="table"><tr><th>NOME</th>';
//             horarios.forEach(function(hora) {
//                 tabela += '<th>' + hora + '</th>';
//             });
//             tabela += '</tr>';

//             let colaboradores = {};

//             // Organiza os horários por colaborador
//             data.forEach(function(registro) {
//                 if (!colaboradores[registro.colaborador]) {
//                     colaboradores[registro.colaborador] = [];
//                 }
//                 colaboradores[registro.colaborador].push(registro.horario);
//             });

//             // Monta as linhas da tabela
//             Object.keys(colaboradores).forEach(function(colaborador) {
//                 tabela += '<tr>';
//                 tabela += '<td>' + colaborador + '</td>';

//                 horarios.forEach(function(hora) {
//                     let fundo = '';

//                     if (colaboradores[colaborador].includes(hora)) {
//                         fundo = 'style="background-color: lightblue;"';

//                         // Verifica conflitos de horário (outros colaboradores no mesmo horário)
//                         let conflitos = 0;
//                         Object.keys(colaboradores).forEach(function(outroColaborador) {
//                             if (outroColaborador !== colaborador && colaboradores[outroColaborador].includes(hora)) {
//                                 conflitos++;
//                             }
//                         });

//                         if (conflitos > 0) {
//                             fundo = 'style="background-color: red;"';
//                         }
//                     }
//                     tabela += '<td ' + fundo + '></td>';
//                 });
//                 tabela += '</tr>';
//             });

//             tabela += '</table></div>';

//             // Insere a tabela no HTML
//             $('#tabela-diario').html(tabela);
//         }
//     });
// }

// $(document).ready(function(){
//     setTimeout(() => {
//         build_tabela()
//     }, 1000);
// });

// function build_tabela() {
//     $.ajax({
//         url: '../php/leitura/ler_agenda_tab.php', // O caminho para o script PHP
//         type: 'GET',
//         dataType: 'json',
//         success: function(data) {
            
//             // Define os horários para as colunas
//             let horarios = [];
//             for (let i = 0; i < 24; i++) {
//                 horarios.push((i < 10 ? '0' : '') + i + ':00');
//                 horarios.push((i < 10 ? '0' : '') + i + ':30');
//             }

//             let tabela = '<div class="table-responsive"><table class="table"><tr><th>NOME</th>';
//             horarios.forEach(function(hora) {
//                 tabela += '<th>' + hora + '</th>';
//             });
//             tabela += '</tr>';

//             let colaboradores = {};

//             // Organiza os horários por colaborador e intervalo
//             data.forEach(function(registro) {
//                 console.log(registro);
//                 if (!colaboradores[registro.colaborador]) {
//                     colaboradores[registro.colaborador] = [];
//                 }
//                 colaboradores[registro.colaborador].push({
//                     inicio: registro.horario_inicio,
//                     fim: registro.horario_fim,
//                     hospital: registro.hospital
//                 });
//             });

//             // Monta as linhas da tabela
//             Object.keys(colaboradores).forEach(function(colaborador) {
//                 tabela += '<tr>';
//                 tabela += '<td>' + colaborador + '</td>';

//                 horarios.forEach(function(hora, index) {
//                     let fundo = '';
//                     let colspan = 1;
//                     let hospitalName = '';

//                     colaboradores[colaborador].forEach(function(intervalo) {
//                         if (hora === intervalo.inicio) {
//                             let inicioIndex = horarios.indexOf(intervalo.inicio);
//                             let fimIndex = horarios.indexOf(intervalo.fim);
//                             colspan = fimIndex - inicioIndex;
//                             fundo = 'style="background-color: lightblue;"';
//                             console.log(intervalo.hospital)
//                             hospitalName = intervalo.hospital;
//                         }
//                     });

//                     if (colspan > 1) {
//                         tabela += '<td ' + fundo + ' colspan="' + colspan + '">';
//                         tabela += '<button class="btn btn-primary">' + hospitalName + '</button>';
//                         tabela += '</td>';
//                     } else if (colspan === 1) {
//                         tabela += '<td ' + fundo + '></td>';
//                     }
//                 });

//                 tabela += '</tr>';
//             });

//             tabela += '</table></div>';

//             // Insere a tabela no HTML
//             $('#tabela-diario').html(tabela);
//         }
//     });
// }

$(document).ready(function(){
    setTimeout(() => {
        build_tabela()
    }, 1000);
});

function build_tabela() {
    $.ajax({
        url: '../php/leitura/ler_agenda_tab.php', // O caminho para o script PHP
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log(data);
            // Define os horários para as colunas
            let horarios = [];
            for (let i = 0; i < 24; i++) {
                horarios.push((i < 10 ? '0' : '') + i + ':00');
                horarios.push((i < 10 ? '0' : '') + i + ':30');
            }

            let tabela = '<div class="table-responsive"><table class="table"><tr><th>NOME</th>';
            horarios.forEach(function(hora) {
                tabela += '<th>' + hora + '</th>';
            });
            tabela += '</tr>';

            let colaboradores = {};

            // Organiza os horários por colaborador e intervalo
            data.forEach(function(registro) {
                if (!colaboradores[registro.colaborador]) {
                    colaboradores[registro.colaborador] = [];
                }
                colaboradores[registro.colaborador].push({
                    inicio: registro.horario_inicio,
                    fim: registro.horario_fim,
                    hospital: registro.hospital
                });
            });

            // Monta as linhas da tabela
            Object.keys(colaboradores).forEach(function(colaborador) {
                tabela += '<tr>';
                tabela += '<td>' + colaborador + '</td>';

                horarios.forEach(function(hora, index) {
                    let fundo = '';
                    let colspan = 1;
                    let hospitalName = '';

                    colaboradores[colaborador].forEach(function(intervalo) {
                        if (hora === intervalo.inicio) {
                            let inicioIndex = horarios.indexOf(intervalo.inicio);
                            let fimIndex = horarios.indexOf(intervalo.fim);
                            colspan = fimIndex - inicioIndex;
                            fundo = 'style="background-color: lightblue;"';
                            hospitalName = intervalo.hospital;
                        }
                    });

                    if (colspan > 1) {
                        tabela += '<td ' + fundo + ' colspan="' + colspan + '" style="text-align: center; vertical-align: middle;">';
                        tabela += '<h4>' + hospitalName + '</h4>';
                        tabela += '</td>';
                    } else if (colspan === 1) {
                        tabela += '<td ' + fundo + '></td>';
                    }
                });

                tabela += '</tr>';
            });

            tabela += '</table></div>';

            // Insere a tabela no HTML
            $('#tabela-diario').html(tabela);
        }
    });
}
