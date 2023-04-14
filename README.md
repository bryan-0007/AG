# Aplicação de Gestão

## Objective
O objetivo deste projeto é implementar uma Aplicação de Gestão (AG). O AG visa automatizar e tornar mais consistentes diversas tarefas que lidam com informações importantes é desenvolvido de forma integrada com o Wordpress, que também é utilizado como sistema de gerenciamento de conteúdo online.

## Features

- Integrado com o Bootstrap 4 para design e estilo responsivos.
- Utiliza jQuery para validação e interações do lado do cliente.

## Estrutura de projeto

- common.php: este ficheiro inclui todas as funções PHP que serão reutilizadas entre diferentes componentes e/ou chamadas para bibliotecas JavaScript (por exemplo, funções de validação do lado do servidor, chamadas de biblioteca, etc.)
- gestao-de-itens: este ficheiro ao ser executado, faz primeiramente um teste se foi realizado um login no Wordpress e se o utilizador com sessão iniciada tem (independentemente do seu role) a capability Manage items.
- gestao-de-registos: este ficheiro ao ser executado, faz primeiramente um teste se foi realizado um login no Wordpress e se o utilizador com sessão iniciada tem (independentemente do seu role) a capability Manage records.
- gestao-de-subitens: ao ser executado, faz primeiramente um teste se foi realizado um login no Wordpress e se o utilizador com sessão iniciada tem a capability Manage records.
- gestao-de-unidades: este ficheiro ao ser executado, faz primeiramente um teste se foi realizado um login no Wordpress e se o utilizador com sessão iniciada tem (independentemente do seu role) a capability Manage unit types.
- ag.css: Arquivo CSS personalizado usado para a estilização das páginas.


## Instalação
1. Clone este repositório.
2. Instale o XAMPP e inicie os serviços Apache e MySQL.
3. Crie um novo banco de dados no phpMyAdmin e importe o arquivo SQL fornecido (se disponível).
4. Edite o arquivo wp-config.php para configurar a conexão com o banco de dados.
5. Instale e configure o WordPress.
6. Coloque os trechos de código PHP personalizados nas páginas apropriadas do WordPress ou nos arquivos de tema.
7. Instale as dependências Bootstrap e jQuery no tema ou usando um plugin.

## Uso
Navegue até o painel de administração do WordPress e crie novas páginas ou edite as existentes para incluir os trechos de código PHP personalizados fornecidos neste repositório. Certifique-se de que as dependências necessárias (Bootstrap e jQuery) sejam carregadas corretamente.

Ao usar os snippets de código PHP fornecidos, certifique-se de incluir o arquivo common.php para acessar as funções do utilitário para validação de formulário, sanitização de entrada e controle de acesso do usuário.

## Dependências
- Bootstrap 4.3.1
- jQuery 3.3.1 
- Popper.js 1.14.7