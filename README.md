# Sistema de Compras em CodeIgniter (CRUD de Produtos, Usuários e Unidades de Medida)

## Descrição
Este repositório contém um sistema de compras desenvolvido com o framework PHP CodeIgniter 3, como parte do 5º semestre do curso de Sistemas para Internet da FATEC São Roque, na matéria de Desenvolvimento para Servidores 2. O módulo de produtos, construído como um dos requisitos para aprovação na disciplina e desenvolvido em pair programming pelos alunos Luiz Bueno e Letícia Bueno.

O sistema oferece funcionalidades de cadastro, consulta, alteração e desativação de produtos, além do gerenciamento de usuários (com níveis de acesso) e unidades de medida. O código está estruturado seguindo boas práticas de MVC, tornando a manutenção e evolução mais simples. Há também um módulo de log integrado, registrando as operações executadas e garantindo a rastreabilidade das ações no banco de dados.

## Principais Recursos
- **Autenticação de Usuários:** Criação, consulta, alteração e desativação, com validação de permissões (admin/comum).  
- **Gestão de Produtos:** Inserção, consulta dinâmica por filtros, atualização parcial e desativação lógica de produtos.  
- **Unidades de Medida:** Cadastramento e validação de unidades para garantir a consistência dos dados.  
- **Logs de Operações:** Registro detalhado das transações no banco, fornecendo auditoria do que é alterado e por quem.  
- **API RESTful:** Integração e testes facilitados através de ferramentas como Insomnia ou Postman.

## Como Usar
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

## Métodos do Controller `Produto`

### inserir()
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
```

**Validações:** Todos os campos são obrigatórios e a unidade de medida deve existir.

**Exemplo de Sucesso (JSON):**
```json
{
  "codigo": 1,
  "msg": "Produto inserido com sucesso."
}
```

### consultar()
**Método HTTP:** POST  
**URL (sem .htaccess):** `http://localhost/Compras/index.php/produto/consultar`

**Entrada (JSON):**
```json
{
  "cod_produto": "",
  "descricao": "Produto Teste",
  "unid_medida": "",
  "usucria": "admin"
}
```

**Validações:** Parâmetros opcionais. Se fornecidos, filtram a busca.

**Exemplo de Sucesso (JSON):**
```json
{
  "codigo": 1,
  "msg": "Consulta realizada com sucesso.",
  "dados": [
    {
      "cod_produto": "1",
      "descricao": "Produto Teste",
      "unid_medida": "UN",
      "estoq_minimo": "10",
      "estoq_maximo": "100",
      "estatus": ""
    }
  ]
}
```

### alterar()
**Método HTTP:** POST  
**URL (sem .htaccess):** `http://localhost/Compras/index.php/produto/alterar`

**Entrada (JSON):**
```json
{
  "cod_produto": "1",
  "descricao": "Nova Descrição",
  "unid_medida": "LB",
  "estoq_minimo": "20",
  "estoq_maximo": "200",
  "usucria": "admin"
}
```

**Validações:**
- `cod_produto` é obrigatório.
- Se `unid_medida` for informada, deve existir.
- Pelo menos um campo, além de `cod_produto`, deve ser alterado.

**Exemplo de Sucesso (JSON):**
```json
{
  "codigo": 1,
  "msg": "Produto alterado com sucesso."
}
```

### desativar()
**Método HTTP:** POST  
**URL (sem .htaccess):** `http://localhost/Compras/index.php/produto/desativar`

**Entrada (JSON):**
```json
{
  "cod_produto": "1",
  "usucria": "admin"
}
```

**Validações:** `cod_produto` é obrigatório. Verifica se o produto existe e está ativo.

**Exemplo de Sucesso (JSON):**
```json
{
  "codigo": 1,
  "msg": "Produto desativado com sucesso."
}
```

## Métodos do Model `M_produto`

- **inserir($descricao, $unid_medida, $estoq_minimo, $estoq_maximo, $usucria):**  
  Insere um novo produto após validar a unidade de medida. Registra log da operação.

- **consultar($cod_produto, $descricao, $unid_medida, $usucria):**  
  Realiza pesquisa dinâmica com base nos filtros informados. Retorna dados ou mensagem de não encontrado.

- **alterar($cod_produto, $descricao, $unid_medida, $estoq_minimo, $estoq_maximo, $usucria):**  
  Verifica existência do produto, unidade de medida (se informada) e atualiza os campos desejados. Gera log da operação.

- **desativar($cod_produto, $usucria):**  
  Marca o produto como desativado (`estatus = 'D'`) e registra a operação em log.

## Log de Operações
Todas as inserções, alterações e desativações são registradas na tabela `log` por meio do model `M_log`, garantindo auditoria e histórico das modificações.

## Testando no Insomnia
1. Abrir o Insomnia.  
2. Criar nova requisição (POST).  
3. Inserir a URL adequada.  
4. Selecionar Body como JSON e inserir o payload conforme exemplos.  
5. Enviar a requisição e verificar a resposta.

## Possíveis Erros
- **404 Page Not Found:** Falta de `index.php` na URL ou configuração de `.htaccess`.
- **Código 6 (Dados não encontrados):** Produto não existe ou não atende aos filtros.
- **Código 4 (Unidade de medida não encontrada):** Ao inserir/alterar, a `unid_medida` informada não foi cadastrada.
- **Código 3 (Nenhum parâmetro de alteração informado):** Ao alterar, não foram fornecidos campos além do `cod_produto`.



