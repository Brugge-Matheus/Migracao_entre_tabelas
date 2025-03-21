# 📌 Projeto de Migração de Dados entre Bancos de Dados MySQL

Este repositório contém uma solução em PHP para migrar dados de uma tabela para outra, mesmo que os nomes dos campos sejam diferentes. O projeto utiliza PDO para gerenciar conexões com o banco de dados e garantir a segurança das operações.

## 📂 Estrutura do Projeto

```
src/
├── App/
│   ├── Database/
│   │   ├── Connection.php  # Classe de conexão e migração de dados
│   ├── config/
│   │   ├── config.php      # Configuração dos bancos de dados
├── vendor/                 # Dependências do Composer
├── composer.json           # Gerenciador de dependências
├── composer.lock
├── index.php               # Script de execução
```

## 🚀 Funcionalidades

- Conexão segura com o MySQL usando PDO.
- Extração de dados de uma tabela com mapeamento de campos.
- Migração de dados entre bancos de dados diferentes.

## 🛠️ Configuração

Antes de executar o projeto, configure os acessos aos bancos de dados no arquivo `config/config.php`:

```php
const MYSQL_HOST_OLD = 'seu_host_antigo';
const MYSQL_USER_OLD = 'seu_usuario_antigo';
const MYSQL_PASSWD_OLD = 'sua_senha_antiga';
const MYSQL_DATABASE_OLD = 'seu_banco_antigo';

const MYSQL_HOST_NEW = 'seu_host_novo';
const MYSQL_USER_NEW = 'seu_usuario_novo';
const MYSQL_PASSWD_NEW = 'sua_senha_nova';
const MYSQL_DATABASE_NEW = 'seu_banco_novo';
```

## ▶️ Como Usar

1. Instale as dependências do Composer (se houver):

   ```sh
   composer install
   ```

2. Execute o script principal `index.php`:

   ```sh
   php index.php
   ```

## 📝 Exemplo de Uso

```php
require __DIR__ . '/vendor/autoload.php';

use \App\Database\Connection;

$oldDatabase = new Connection(
    MYSQL_HOST_OLD,
    MYSQL_USER_OLD,
    MYSQL_PASSWD_OLD,
    MYSQL_DATABASE_OLD
);
$newDatabase = new Connection(
    MYSQL_HOST_NEW,
    MYSQL_USER_NEW,
    MYSQL_PASSWD_NEW,
    MYSQL_DATABASE_NEW
);
$newConnection = $newDatabase->connect();

$data = [
    'idproduto_categoria' => 'idcategoria',
    'nome' => 'nome',
    'tipocategoria' => 'tipocategoria',
    'idcategoria' => 'idcategoria_pai',
    'urlrewrite' => 'urlrewrite',
    'title' => 'seotitle',
    'description' => 'seodescription',
    'keywords' => 'seokeywords',
    'imagem' => 'imagem',
    'status' => 'status'
];

$dataOldDatabase = $oldDatabase->getFields('produto_categoria', $data);
$query = $oldDatabase->migrateData('categoria', $newConnection, $data, $dataOldDatabase);
```

## 🔗 Dependências

- PHP 7.4+
- MySQL
- Composer (para gerenciamento de dependências)

## 📌 Notas

- Certifique-se de que ambas as conexões aos bancos de dados estejam funcionando corretamente.
- Modifique a estrutura da tabela de destino caso necessário para acomodar os dados migrados.

---

📌 **Desenvolvido por [Matheus Brugge Milczwski]**

