# Gerenciador de Hábitos e Tarefas

## Sobre o Projeto
Este sistema foi desenvolvido como parte de um projeto interdisciplinar das disciplinas de **Autoria Web** e **Programação a Banco de Dados**. Seu objetivo é permitir que os usuários cadastrem, gerenciem e acompanhem seus hábitos e tarefas diárias de forma simples e eficiente.

## Tecnologias Utilizadas
- **Back-end:** PHP com PDO para conexão ao banco de dados
- **Front-end:** HTML, CSS, JavaScript e Bootstrap para estilização e responsividade
- **Banco de Dados:** PostgreSQL

## Funcionalidades
- Cadastro de hábitos
- Visualização dos hábitos cadastrados
- Exclusão de hábitos se necessário
- Marcar hábitos como concluídos ou não, registrando o dia
- Registro histórico das conclusões de hábitos, permitindo visualizar o progresso diário e se organizar melhor
- Criação de tarefas
- Edição de tarefas existentes
- Marcar tarefas como concluídas
- Cadastro de usuários
- Login

## Estrutura do Sistema
O sistema possui as seguintes telas:
- **Tela de Login**: Permite o acesso de usuários cadastrados.
- **Tela de Cadastro**: Permite novos registros no sistema.
- **Tela Principal (Index)**: Apresenta os hábitos cadastrados e possibilita novas adições ou remoções.
- **Tela de Tarefas**: Permite o gerenciamento de tarefas diárias, com opções para criar, editar e concluir tarefas.
- **Tela de Histórico**: Exibe o registro das conclusões de hábitos, permitindo ao usuário acompanhar seu progresso.

## Como Executar o Projeto
1. Clone este repositório em sua máquina local.
2. Configure o banco de dados PostgreSQL e importe as tabelas necessárias ( Dentro do diretório do projeto, você encontrará o arquivo `database.sql`. Este arquivo contém as tabelas necessárias para o funcionamento do programa.).
3. Inicie um servidor local (ex: XAMPP, WAMP ou diretamente com o PHP).
4. Acesse o sistema pelo navegador.
