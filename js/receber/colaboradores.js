$(document).ready(function() {
    
    // $.ajax({
    //     url: '../../php/leitura/usuarios.php',
    //     method: 'GET',
    //     dataType: 'json',
    //     success: function(data) {

    //         console.log(data)
    //     },
    //     error: function(jqXHR, textStatus, errorThrown) {
    //         console.log('Erro ao buscar os dados: ' + textStatus);
    //     }
    // });
    quadroColaboradores()
});

async function quadroColaboradores() {
    const rq = await fetch("../php/leitura/ler_usuarios.php")
    const rs = await rq.json()
    let data = JSON.parse(rs)

    var quadro = document.getElementById("quadro-colaboradores")
    quadro.innerHTML = ''

    data.forEach(e => {
        quadro.innerHTML += `
            <tr>
                <td>${e.nome}</td>
                <td>${e.cpf}</td>
                <td>${e.dataNascimento}</td>
                <td>${e.telefone}</td>
                <td>${e.email}</td>
                <td>${e.status}</td>
                <td><button type="button" class="btn btn-primary" value="${e.id}"><i class="bi bi-pencil"></i></button></td>
                <td><button type="button" class="btn btn-danger" value="${e.id}"><i class="bi bi-trash"></i></button></td>
            </tr>
        `
    });
}