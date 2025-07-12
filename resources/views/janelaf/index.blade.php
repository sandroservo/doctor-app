<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Janela Arrastável</title>
    <style>
        /* Estilo básico para a janela flutuante */
        .draggable {
            position: absolute;      /* importante para mover via JavaScript */
            width: 400px;
            top: 100px;             /* posição inicial */
            left: 100px;            /* posição inicial */
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f8f9fa;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            display: none;          /* inicial: não exibida (pode mudar se quiser aberta) */
        }

        /* Cabeçalho (“alça” para arrastar) */
        .draggable-header {
            background-color: #007bff;
            color: #fff;
            padding: 8px;
            cursor: move;           /* para indicar que é arrastável */
            border-radius: 4px 4px 0 0;
        }

        /* Corpo do conteúdo */
        .draggable-body {
            padding: 16px;
        }

        /* Botão para fechar no canto superior direito, se desejar */
        .close-btn {
            float: right;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 18px;
            margin-top: -2px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h1>Exemplo de Tela de Cadastro em Janela Arrastável</h1>

    <!-- Exibe mensagem de sucesso, se houver -->
    @if(session('success'))
        <div style="color: green; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Botão para abrir a janela arrastável -->
    <button id="openWindowBtn">Abrir Cadastro</button>

    <!-- A Janela Flutuante Arrastável -->
    <div id="draggableWindow" class="draggable">
        <div class="draggable-header">
            <strong>Cadastro de Usuário</strong>
            <button class="close-btn" id="closeWindowBtn" title="Fechar">&times;</button>
        </div>
        <div class="draggable-body">
            <!-- Formulário de cadastro -->
            <form action="{{ route('cadastro.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 10px;">
                    <label for="nome">Nome:</label><br>
                    <input type="text" name="nome" id="nome" placeholder="Seu nome" style="width: 100%;" required>
                </div>

                <div style="margin-bottom: 10px;">
                    <label for="email">E-mail:</label><br>
                    <input type="email" name="email" id="email" placeholder="Seu e-mail" style="width: 100%;" required>
                </div>

                <button type="submit">Cadastrar</button>
            </form>
        </div>
    </div>

    <!-- Script JavaScript para arrastar a janela e abrir/fechar -->
    <script>
        const draggableWindow = document.getElementById('draggableWindow');
        const header = draggableWindow.querySelector('.draggable-header');
        const openWindowBtn = document.getElementById('openWindowBtn');
        const closeWindowBtn = document.getElementById('closeWindowBtn');

        // Controle de arraste
        let isDragging = false;
        let offsetX = 0;
        let offsetY = 0;

        header.addEventListener('mousedown', (e) => {
            // Se o clique for no próprio botão de fechar, não arrastar.
            if (e.target === closeWindowBtn) return;

            isDragging = true;
            offsetX = e.clientX - draggableWindow.offsetLeft;
            offsetY = e.clientY - draggableWindow.offsetTop;
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            const left = e.clientX - offsetX;
            const top = e.clientY - offsetY;
            draggableWindow.style.left = left + 'px';
            draggableWindow.style.top = top + 'px';
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
        });

        // Abrir a janela
        openWindowBtn.addEventListener('click', () => {
            draggableWindow.style.display = 'block';
        });

        // Fechar a janela
        closeWindowBtn.addEventListener('click', () => {
            draggableWindow.style.display = 'none';
        });
    </script>
</body>
</html>
