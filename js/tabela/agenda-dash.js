$('#filtro-data').on('change', function() {
    console.log('iniciando')
    const dataSelecionada = $(this).val();
    build_tabela({data: dataSelecionada})
});

$('#colaborador').on('change', function() {
    const data = $("#filtro-data").val();
    const colaborador = $(this).val();
    build_tabela({data: data, colaborador: colaborador})
});


function build_tabela(data) {

    $.ajax({
        url: '../php/leitura/ler_agenda_tab.php',
        type: 'GET',
        dataType: 'json',
        data: data,

        success: function(data) {
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

            $('#tabela-diario').html(tabela);
        }
    });
}
