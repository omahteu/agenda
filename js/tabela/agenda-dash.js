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

            let tabela = '<div class="table-responsive"><table class="table"><tr><th>NOME</th>';

            // Encontra o horário mínimo e máximo por colaborador
            let horarioMin = '23:59';
            let horarioMax = '00:00';

            Object.values(colaboradores).forEach(function(intervalos) {
                intervalos.forEach(function(intervalo) {
                    if (intervalo.inicio < horarioMin) horarioMin = intervalo.inicio;
                    if (intervalo.fim > horarioMax) horarioMax = intervalo.fim;
                });
            });

            // Adiciona mais 1 hora ao horário máximo
            let maxHour = parseInt(horarioMax.split(':')[0]) + 1;
            if (maxHour < 24) {
                horarioMax = (maxHour < 10 ? '0' : '') + maxHour + ':00';
            } else {
                horarioMax = '23:59'; // Limita até 23:59 se passar de meia-noite
            }

            // Gera os horários apenas entre o primeiro e o último (incluindo a hora extra)
            let horarios = [];
            let startHour = parseInt(horarioMin.split(':')[0]);
            let endHour = parseInt(horarioMax.split(':')[0]);
            
            for (let i = startHour; i <= endHour; i++) {
                horarios.push((i < 10 ? '0' : '') + i + ':00');
                horarios.push((i < 10 ? '0' : '') + i + ':30');
            }

            horarios = horarios.filter(function(hora) {
                return hora >= horarioMin && hora <= horarioMax;
            });

            // Monta cabeçalho da tabela com horários encontrados
            horarios.forEach(function(hora) {
                tabela += '<th>' + hora + '</th>';
            });
            tabela += '</tr>';

            // Preenche a tabela com as informações de cada colaborador
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


