# DataLayer @Stonks

[![Maintainer](http://img.shields.io/badge/maintainer-@giovannialoliveira-blue.svg?style=flat-square)](https://www.facebook.com/giovannialoliveira)
[![Source Code](http://img.shields.io/badge/source-stonks/datalayer-blue.svg?style=flat-square)](https://github.com/giovannialo/datalayer)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/stonks/datalayer.svg?style=flat-square)](https://packagist.org/packages/stonks/datalayer)
[![Latest Version](https://img.shields.io/github/release/giovannialo/datalayer.svg?style=flat-square)](https://github.com/giovannialo/datalayer/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build](https://img.shields.io/scrutinizer/build/g/giovannialo/datalayer.svg?style=flat-square)](https://scrutinizer-ci.com/g/giovannialo/datalayer)
[![Quality Score](https://img.shields.io/scrutinizer/g/giovannialo/datalayer.svg?style=flat-square)](https://scrutinizer-ci.com/g/giovannialo/datalayer)
[![Total Downloads](https://img.shields.io/packagist/dt/stonks/datalayer.svg?style=flat-square)](https://packagist.org/packages/stonks/datalayer)

O DataLayer é um componente para abstração de persistência para banco de dados, que usa PDO com prepared statements para executar rotinas comuns como cadastrar, ler, editar e remover dados.

### Destaques

- Fácil de configurar
- Conecte-se com multiplos bancos de dados
- Abstração total do CRUD
- Crie modelos seguros
- Pronto para o composer
- Compatível com PSR-2

## Instalação

DataLayer está disponível via Composer:

```bash
"stonks/datalayer": "1.0.*"
```

ou execute

```bash
composer require stonks/datalayer
```

## Documentação

Para obter mais detalhes sobre como usar o DataLayer, consulte a pasta de amostra com detalhes no diretório do componente.

### Conexão

Para começar a usar o DataLayer, precisamos de uma conexão com o seu banco de dados. Para ver as conexões possíveis, acesse o [manual de conexões do PDO em PHP.net](https://www.php.net/manual/pt_BR/pdo.drivers.php)

Para conectar somente um banco de dados:

```php
define('DATA_LAYER_CONFIG', [
	'driver' => 'mysql',
	'host' => 'localhost',
	'port' => '3306',
	'dbname' => 'datalayer_example',
	'username' => 'root',
	'passwd' => '',
	'options' => [
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
		PDO::ATTR_CASE => PDO::CASE_NATURAL,
	],
]);
```

Para conectar dois ou mais bancos de dados:

```php
define('DATA_LAYER_CONFIG', [
	'db_1' => [ // Chave personalizada: faz referência ao datalayer_example_1
		'driver' => 'mysql',
		'host' => 'localhost',
		'port' => '3306',
		'dbname' => 'datalayer_example_1',
		'username' => 'root',
		'passwd' => '',
		'options' => [
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
			PDO::ATTR_CASE => PDO::CASE_NATURAL,
		],
	],
	0 => [ // Isto não é uma chave personalizada
		'driver' => 'mysql',
		'host' => 'localhost',
		'port' => '3306',
		'dbname' => 'datalayer_example_2', // Neste caso, a chave é o próprio nome do banco de dados
		'username' => 'root',
		'passwd' => '',
		'options' => [
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
			PDO::ATTR_CASE => PDO::CASE_NATURAL,
		],
	],
	// ...
]);
```

###### Chave personalizada _(int|string)_

Quando definida, deve ser utilizada nas classes de modelo para se conectar ao banco de dados definido em **dbname**.

### Classe Modelo

O DataLayer é baseado em uma estrutura MVC com os padrões de projeto Layer Super Type e Active Record. Logo, para consumir, é necessário criar o modelo de sua tabela e herdar o DataLayer.

```php
class User extends DataLayer
{
    public function __construct()
    {
        parent::__construct(string $entity, ?array $required = [], string $primary = 'id', mixed $timestamps = false, ?string $database = null);
    }
}
```

#### Parâmetros

###### entity _(obrigatório)_

Nome da tabela.

###### required _(opcional, padrão: [])_

Os nomes das colunas definidas como **NOT NULL**.

###### primary _(opcional, padrão: id)_

Chave primária. Este parâmetro não pode ser **null**.

###### timestamps _(opcional, padrão: false)_

Aceita os tipos booleano e string. É usado, especificamente, quando há colunas **created_at** e/ou **updated_at**.

_boolean_<br>
**False**, se nenhuma das colunas existirem. **True**, se ambas existirem.

_string_<br>
Se existir somente uma delas, especifique o seu nome.

###### database _(opcional, padrão: null)_

Nome do banco de dados ou chave personalizada(quando definida) ao qual este modelo se conectará.

Este parâmetro só deve ser especificado se **DATA_LAYER_CONFIG** for configurada para se conectar com dois ou mais bancos de dados.

#### find

Consulta no banco de dados.

```php
<?php
use Example\Models\User;
$model = new User();

// Encontre todos os usuários
$users = $model->find()->fetch(true);

// Encontre todos os usuários por grupo
$users = $model->find()->group('last_name')->fetch(true);

// Encontre todos os usuários e ordene por um campo
$users = $model->find()->order('email DESC')->fetch(true);

// Encontre todos os usuários com limite de 2
$users = $model->find()->limit(2)->fetch(true);

// Encontre todos os usuários com limite 2 e deslocamento 2
$users = $model->find()->limit(2)->offset(2)->fetch(true);

// Encontre um usuário por condição (apenas termos)
$user = $model->find('first_name = "Giovanni"')->fetch();

// Encontre um usuário por condição (termos e parâmetros)
$user = $model->find('last_name = :last_name', 'last_name=A. L. Oliveira')->fetch();

// Encontre um usuário por duas condições
$user = $model->find('first_name = :first_name AND last_name = :last_name', 'first_name=Giovanni&last_name=A. L. Oliveira')->fetch();
```

#### findByPrimaryKey

Consulta pela chave primária definida no parâmetro **$primary** da classe modelo.

```php
<?php
use Example\Models\User;

$model = new User();
$user = $model->findByPrimaryKey('giovanni.al.oliveira@gmail.com');
echo $user->first_name;
```

#### findById

Consulta pela coluna **id**, se definida.

```php
<?php
use Example\Models\User;

$model = new User();
$user = $model->findById(2);
echo $user->first_name;
```

#### Parâmetros seguros

Consulte o exemplo no arquivo **find_example.php** e classe modelo **User.php**.

```php
<?php
use Example\Models\User;

$params = http_build_query(['first_name' => 'Giovanni']);

$model = new User();
$user = $model->find('first_name = :first_name', $params);
var_dump($user, $user->fetch());
```

#### count

Conta o total de registros encontrados.

```php
<?php
use Example\Models\User;
$model = new User();
$count = $model->find()->count();
echo $count;
```

#### make save

Cria um registro no banco de dados.

```php
<?php
use Example\Models\User;
$user = new User();

$user->first_name = 'Giovanni';
$user->last_name = 'A. L. Oliveira';
$user->save(); // Ou $user->make()->save();
```

#### change save

Atualiza um registro no banco de dados.

```php
<?php
use Example\Models\User;

$user = (new User())->findById(2);
$user->last_name = 'Alves de Lima Oliveira';
$user->change()->save();
```

#### functionSql

Permite, ao criar ou atualizar um registro, utilizar funções SQL para tratar os dados. 

```php
<?php
use Example\Models\User;

$user = new User();
$user->first_name = 'Giovanni';
$user->last_name = 'A. L. Oliveira';

// $user->functionSql(string $column, string $function);
$user->functionSql('name', 'CONCAT(:first_name, " ", :last_name)');

$user->save(); 
```

#### destroy

Deleta um registro no banco de dados.

```php
<?php
use Example\Models\User;

$user = (new User())->findById(2);
$user->destroy();
```

#### fail

Retorna os erros, caso ocorra, na tentativa de executar uma operação no banco de dados.

```php
<?php
use Example\Models\User;

$user = (new User())->findById(2);

if($user->fail()){
    echo $user->fail()->getMessage();
}
```

#### Métodos de dados personalizados

Crie métodos para realizar determinados processamentos na obtenção de dados.

````php
class User{
    //...

    public function fullName(): string 
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    public function document(): string
    {
        return 'Restrito';
    }
}

echo $this->full_name; // Giovanni A. L. Oliveira
echo $this->document; // Restrito
```` 

## Contribuindo

Envie relatórios de bugs, sugestões e solicitações de pull para o rastreador de problemas do GitHub.

## Suporte

Se você descobrir algum problema relacionado à segurança, use o rastreador de problemas do GitHub.

Agradecido (õ.~)

## Licença

A Licença do MIT. Por favor, veja o [Arquivo de Licença](https://github.com/giovannialo/datalayer/blob/master/LICENSE) para maiores informações.
