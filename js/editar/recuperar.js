$(document).ready(function() {
    let instancia = sessionStorage.getItem("id-editar");

    if (instancia) {
        $.ajax({
            url: '../php/recuperar/usuarios.php', // A URL do PHP para onde os dados serão enviados
            type: 'GET', // Método de envio alterado para GET
            data: { instancia: instancia }, // Passa a variável 'instancia' como parâmetro na URL
            success: function(response) {
                // Aqui você trata o que fazer quando a requisição for bem-sucedida
                iziToast.warning({
                    title: 'Editar',
                    message: 'Usuário selecionado para edição!',
                    position: 'topRight',
                    timeout: 3000
                });
                let data = response

                preencherFormulario(data)
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
    }
});

function preencherFormulario(data) {
    // Supondo que a requisição sempre traga ao menos um objeto, usamos data[0] para acessar o primeiro
    let usuario = data[0];
    
    // Mapeando os campos do formulário
    sessionStorage.setItem('id-item-editar', usuario.id)
    document.querySelector('input[name="nome"]').value = usuario.nome;
    document.querySelector('input[name="cpf"]').value = usuario.cpf;
    document.querySelector('input[name="nascimento"]').value = usuario.dataNascimento;
    document.querySelector('input[name="telefone"]').value = usuario.telefone;
    document.querySelector('input[name="email"]').value = usuario.email;
    document.querySelector('select[name="perfil"]').value = usuario.status; // Supondo que status é usado para o perfil
}
