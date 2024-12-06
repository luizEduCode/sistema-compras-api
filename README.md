# Sistema de Compras em CodeIgniter (CRUD de Produtos, Usuários e Unidades de Medida)

**Descrição:**  
Este repositório contém um sistema de compras desenvolvido com o framework PHP CodeIgniter 3, como parte do 5º semestre do curso de Sistemas para Internet da FATEC São Roque, na matéria de Desenvolvimento para Servidores 2. O módulo de produtos, construído como um dos requisitos para aprovação na disciplina e desenvolvido em pair programming pelos alunos Luiz Bueno e Letícia Bueno.

O sistema oferece funcionalidades de cadastro, consulta, alteração e desativação de produtos, além do gerenciamento de usuários (com níveis de acesso) e unidades de medida. O código está estruturado seguindo boas práticas de MVC, tornando a manutenção e evolução mais simples. Há também um módulo de log integrado, registrando as operações executadas e garantindo a rastreabilidade das ações no banco de dados.

**Principais Recursos:**
- **Autenticação de Usuários:** Criação, consulta, alteração e desativação, com validação de permissões (admin/comum).  
- **Gestão de Produtos:** Inserção, consulta dinâmica por filtros, atualização parcial e desativação lógica de produtos.  
- **Unidades de Medida:** Cadastramento e validação de unidades para garantir a consistência dos dados.  
- **Logs de Operações:** Registro detalhado das transações no banco, fornecendo auditoria do que é alterado e por quem.  
- **API RESTful:** Integração e testes facilitados através de ferramentas como Insomnia ou Postman.

**Como Usar:**
1. Configure o ambiente PHP/MySQL e ajuste o arquivo `config.php` do CodeIgniter para corresponder à sua URL base.  
2. Importe o schema do banco de dados e crie as tabelas `usuarios`, `produtos`, `unid_medida` e `log`.  
3. Utilize ferramentas de testes (Insomnia/Postman) para chamar os endpoints `inserir`, `consultar`, `alterar` e `desativar` conforme a documentação abaixo.

Este repositório é ideal para quem deseja compreender a arquitetura MVC em PHP com CodeIgniter, gerando um sistema completo de CRUDs, com validações, logs e autenticação simples.

---

## Documentação do Módulo Produto

### Contexto e Objetivo
O módulo de produto foi desenvolvido para gerenciar informações relacionadas aos produtos da aplicação, incluindo inserção, consulta, alteração e desativação de registros. O objetivo principal é fornecer uma interface bem definida para a manipulação de produtos, garantindo integridade dos dados, reutilização de código e manutenção simplificada.

### Estrutura do Módulo
O módulo segue a estrutura padrão do CodeIgniter 3, separando a lógica em Controller e Model:

- **Controller:** `application/controllers/Produto.php`  
- **Model:** `application/models/M_produto.php`

A interação ocorre principalmente via JSON, disponibilizando endpoints RESTful.

### Tecnologias e Ferramentas
- **Linguagem:** PHP 7+  
- **Framework:** CodeIgniter 3  
- **Banco de Dados:** MySQL (ou MariaDB)  
- **Ferramenta de Teste:** Insomnia (para simular chamadas HTTP)

### Requisitos Funcionais

**Inserir Produto:**  
- Parâmetros: `descricao`, `unid_medida`, `estoq_minimo`, `estoq_maximo`, `usucria`.  
- Verifica se a unidade de medida existe antes da inserção.

**Consultar Produto:**  
- Parâmetros: `cod_produto`, `descricao`, `unid_medida`, `usucria` (opcionais).  
- Filtra por esses parâmetros e retorna produtos ativos (`estatus = ''`).

**Alterar Produto:**  
- Parâmetros: `cod_produto` (obrigatório) e opcionalmente `descricao`, `unid_medida`, `estoq_minimo`, `estoq_maximo`.  
- Atualiza apenas os campos informados.  
- Caso `unid_medida` seja enviada, verifica se existe.

**Desativar Produto:**  
- Parâmetros: `cod_produto` (obrigatório), `usucria`.  
- Atualiza o campo `estatus` para `'D'`.

---

### Métodos do Controller `Produto`

#### inserir()
**Método HTTP:** POST  
**URL (sem .htaccess):** `http://localhost/Compras/index.php/produto/inserir`

**Entrada (JSON):**
```json
{
  "descricao": "Produto Teste",
  "unid_medida": "UN",
  "estoq_minimo": 10,
  "estoq_maximo": 100,
  "usucria": "admin"
}
