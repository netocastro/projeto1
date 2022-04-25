

### POST `/api/user`
Esse endpoint insere novo usuário no banco de dados para ser consumido pela própria API.
```json
{
   "name":"Paulo das neves",
   "nick": "Paulo",
   "cpf": "00000000003",
   "email": "paulo@paulo.com",
   "password": "123",
   "phone": "85999999999",
   "address":"rua das arvores",
   "advertiser": 1
}
```
| Campo       | Tipo   |
|-------------|--------|
| name        | String |
| nick        | String |
| cpf         | String |
| email       | String |
| password    | String |
| phone       | String |
| address     | String |
| advertiser  | Boolean |

### GET `/api/user`
Esse endpoint retorna todos os usuários no formato JSON.
```json
[
    {
        "id": "1",
        "Usu_nome": "Joao da Silva",
        "Usu_nick": "Joao",
        "Usu_cpf": "00000000001",
        "Usu_email": "joao@joao.com",
        "Usu_senha": "3043aa4a83b0934982956a90828140cb834869135b5f294938865de12e036de440a330e1e8529bec15ddd59f18d1161a97bfec110d2622680f2c714a737d7061",
        "Usu_telefone": null,
        "Usu_Endereco": null,
        "Usu_anunciante": null,
        "created_at": "2022-04-25 14:45:49",
        "updated_at": "2022-04-25 15:07:36",
    },
     {
        "id": "2",
        "Usu_nome": "Maria dos Santos",
        "Usu_nick": "maria",
        "Usu_cpf": "00000000002",
        "Usu_email": "maria@maria.com",
        "Usu_senha": "3043aa4a83b0934982956a90828140cb834869135b5f294938865de12e036de440a330e1e8529bec15ddd59f18d1161a97bfec110d2622680f2c714a737d7061",
        "Usu_telefone": "81999999999",
        "Usu_Endereco": null,
        "Usu_anunciante": null,
        "created_at": "2022-04-25 14:45:49",
        "updated_at": "2022-04-25 15:07:36",
    },
    {
        "id": "3",
        "Usu_nome": "pedro costa",
        "Usu_nick": null,
        "Usu_cpf": "00000000004",
        "Usu_email": "paulo@paulo.com",
        "Usu_senha": "9f85350673981a94ceab70841196660927504ae729073a828c446da737089361fcab9d831cdf87e49b87a93d300ff7def7ab226010a7803a6801e16fdd4a4d48",
        "Usu_telefone": "",
        "Usu_Endereco": null,
        "Usu_anunciante": null,
        "created_at": "2022-04-25 14:47:31",
        "updated_at": "2022-04-25 14:47:31",
    }
]
```

| Campo            | Tipo   |
|------------------|--------|
| id               | integer |
| Usu_nome         | String |
| Usu_nick         | String |
| Usu_cpf          | String |
| Usu_email        | String |
| Usu_senha        | String |
| Usu_telefone     | String |
| Usu_Endereco     | String |
| Usu_anunciante   | Boolean |
| created_at       | timestamp |
| updated_at       | timestamp |

### get `/api/user/id`
Esse endpoint retorna um usuário atravez do id.
```json
{
    "id": "1",
    "Usu_nome": "Joao da Silva",
    "Usu_nick": "Joao",
    "Usu_cpf": "00000000001",
    "Usu_email": "joao@joao.com",
    "Usu_senha": "3043aa4a83b0934982956a90828140cb834869135b5f294938865de12e036de440a330e1e8529bec15ddd59f18d1161a97bfec110d2622680f2c714a737d7061",
    "Usu_telefone": null,
    "Usu_Endereco": null,
    "Usu_anunciante": null,
    "created_at": "2022-04-25 14:45:49",
    "updated_at": "2022-04-25 15:07:36",
}
```
| Campo            | Tipo   |
|------------------|--------|
| id               | integer |
| Usu_nome         | String |
| Usu_nick         | String |
| Usu_cpf          | String |
| Usu_email        | String |
| Usu_senha        | String |
| Usu_telefone     | String |
| Usu_Endereco     | String |
| Usu_anunciante   | Boolean |
| created_at       | timestamp |
| updated_at       | timestamp |


### PUT `/api/user/id`
Esse endpoint atualiza um usuario especifico atraves do ID.
```json
{
   "id": "1",
   "name": "Paulo das neves",
   "nick": "Paulo",
   "cpf": "00000000003",
   "email": "paulo@paulo.com",
   "password": "123",
   "phone": "85999999999",
   "address": "rua das arvores",
   "advertiser": 1
}
```

| Campo       | Tipo   |
|-------------|--------|
| name        | String |
| nick        | String |
| cpf         | String |
| email       | String |
| password    | String |
| phone       | String |
| address     | String |
| advertiser  | Boolean |


### DELETE `/user/id`
Esse endpoint deleta um usuario especifico atraves do ID.
