# Sistema de Gestão e Geração de Certificados

Este é o sistema **Simplifica Certificados**, uma aplicação desenvolvida para facilitar a gestão e geração de certificados para cursos, permitindo o cadastro de cursos, usuários, e a emissão e controle de certificados. O sistema é construído com **PHP**, **MySQL**, e **Bootstrap** para garantir uma interface intuitiva e responsiva.

## Funcionalidades

### Usuários
- **Cadastro de Usuários:** Permite cadastrar novos usuários com perfis de acesso (admin ou usuário).
- **Login Seguro:** Autenticação com senhas hash (SHA256) e controle de acesso baseado em perfil.
- **Controle de Acessos:** Registra o número de acessos e o último login de cada usuário.

### Cursos
- **Cadastro de Cursos:** Adiciona cursos com nome, professor, carga horária e templates de certificados (frente e verso).
- **Listagem de Cursos:** Visualiza, edita e exclui cursos cadastrados.

### Certificados
- **Geração de Certificados:** Emissão de certificados personalizados com base nos templates cadastrados.
- **Listagem de Certificados:** Visualiza certificados gerados com opções para edição, exclusão e reemissão.

### Relatórios
- **Painel de Controle:** Exibe estatísticas como número de certificados gerados, cursos cadastrados, usuários ativos, e total de acessos.

### Layout e Interface
- **Design Responsivo:** Utiliza **Bootstrap** para garantir uma experiência visual consistente em diferentes dispositivos.
- **Mensagens de Sucesso e Erro:** Implementadas com modais do Bootstrap.

## Requisitos

- **Servidor Web:** Apache ou Nginx.
- **PHP:** Versão 7.4 ou superior.
- **MySQL:** Versão 5.7 ou superior.
- **Extensões PHP:** `mysqli`.

## Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-repositorio/simplifica-certificados.git
