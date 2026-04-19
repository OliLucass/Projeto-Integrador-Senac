document.addEventListener("DOMContentLoaded", function () {

    const inputCpf = document.getElementById('cpf');
    if (inputCpf) {
        inputCpf.addEventListener('input', function (event) {
            let valor = event.target.value.replace(/\D/g, '').slice(0, 11);
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            event.target.value = valor;
        });
    }

    function validarNome(nome) {
        return nome.trim().length >= 3 && /^[a-zA-ZÀ-ÿ\s]+$/.test(nome.trim());
    }

    function validarEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim());
    }

    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]/g, '');
        if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

        let soma = 0;
        for (let i = 0; i < 9; i++) soma += parseInt(cpf[i]) * (10 - i);
        let resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(cpf[9])) return false;

        soma = 0;
        for (let i = 0; i < 10; i++) soma += parseInt(cpf[i]) * (11 - i);
        resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        return resto === parseInt(cpf[10]);
    }

    function setStatus(id, valido) {
        document.getElementById(id).style.outline = valido
            ? '2px solid #4caf50'
            : '1px solid #e63b2e';
    }

    function mostrarErros(erros) {
        const bloco = document.getElementById('blocoErro');
        const lista = document.getElementById('listaErros');
        lista.innerHTML = '';
        erros.forEach(function (e) {
            const li = document.createElement('li');
            li.textContent = e;
            lista.appendChild(li);
        });
        bloco.style.display = 'block';
        bloco.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function limparErros() {
        document.getElementById('blocoErro').style.display = 'none';
        document.getElementById('listaErros').innerHTML = '';
    }

    function setBtnLoading(loading) {
        const btn = document.getElementById('bntContinuar');
        btn.disabled = loading;
        btn.textContent = loading ? '⏳' : '➤';
    }

    const formSuporte = document.getElementById('formSuporte');

    if (formSuporte) {
        formSuporte.addEventListener('submit', function (event) {
            event.preventDefault();

            const nome       = document.getElementById('name').value;
            const email      = document.getElementById('email').value;
            const cpf        = document.getElementById('cpf').value;
            const reclamacao = document.getElementById('reclamacao').value;

            const erros = [];

            if (!validarNome(nome))            { erros.push('Nome inválido (mín. 3 letras, sem números).'); setStatus('name', false); }
            else                               { setStatus('name', true); }

            if (!validarEmail(email))          { erros.push('E-mail inválido.');                            setStatus('email', false); }
            else                               { setStatus('email', true); }

            if (!validarCPF(cpf))              { erros.push('CPF inválido.');                               setStatus('cpf', false); }
            else                               { setStatus('cpf', true); }

            if (reclamacao.trim().length < 20) { erros.push('Mensagem muito curta (mín. 20 caracteres).');  setStatus('reclamacao', false); }
            else                               { setStatus('reclamacao', true); }

            if (erros.length > 0) {
                mostrarErros(erros);
                return;
            }

            limparErros();
            setBtnLoading(true);

            const dados = new FormData();
            dados.append('nome', nome);
            dados.append('email', email);
            dados.append('cpf', cpf);
            dados.append('reclamacao', reclamacao);

            // ✅ AbortController para timeout de 20s no fetch
            const controller = new AbortController();
            const timeoutId  = setTimeout(function () { controller.abort(); }, 20000);

            fetch('enviar.php', {
                method: 'POST',
                body: dados,
                signal: controller.signal
            })
            .then(function (res) {
                clearTimeout(timeoutId);

                // ✅ Verifica status HTTP antes de tentar parsear JSON
                if (!res.ok) {
                    return res.text().then(function (txt) {
                        throw new Error('Servidor retornou erro ' + res.status + ': ' + txt.substring(0, 200));
                    });
                }

                return res.text().then(function (txt) {
                    try {
                        return JSON.parse(txt);
                    } catch (e) {
                        // ✅ Se o PHP retornou HTML de erro em vez de JSON, mostra mensagem útil
                        throw new Error('Resposta inesperada do servidor. Verifique os logs do PHP. Detalhe: ' + txt.substring(0, 300));
                    }
                });
            })
            .then(function (data) {
                setBtnLoading(false);
                if (data.sucesso) {
                    const modal         = document.getElementById('modalSucesso');
                    const spanProtocolo = document.getElementById('numeroProtocolo');
                    const btnFechar     = document.getElementById('btnFecharModal');

                    spanProtocolo.textContent = data.protocolo;
                    modal.style.display = 'flex';

                    btnFechar.onclick = function () {
                        modal.style.display = 'none';
                        formSuporte.reset();
                        ['name', 'email', 'cpf', 'reclamacao'].forEach(function (id) {
                            document.getElementById(id).style.outline = '';
                        });
                    };
                } else {
                    mostrarErros([data.erro || 'Erro ao enviar. Tente novamente.']);
                }
            })
            .catch(function (err) {
                clearTimeout(timeoutId);
                setBtnLoading(false);

                if (err.name === 'AbortError') {
                    mostrarErros(['Tempo limite excedido. O servidor demorou muito para responder.']);
                } else {
                    // ✅ Mostra o erro real em vez de mensagem genérica
                    mostrarErros([err.message || 'Erro de conexão com o servidor.']);
                }
            });
        });
    }

});