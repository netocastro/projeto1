<?php

namespace Source\Controllers\Api;

use Source\Models\User;

class UserController
{
    /**
     * Retorna todos os usuarios
     */
    public function index()
    {
        echo json_encode(objectToArray((new User())->find()->fetch(true)));
    }

    /**
     * Insere usuario no banco de dados
     */
    public function store($data)
    {
        /** verificando campos vazios */
        $findEmptyFields =  array_keys($data, '');

        /** retornando todos os campos vazios */
        if ($findEmptyFields) {
            echo json_encode(['emptyFields' => $findEmptyFields]);
            return;
        }

        /**Sanitizando todos os campos*/
        $data = filter_var_array($data, [
            "name" => FILTER_SANITIZE_STRIPPED,
            "nick" => FILTER_SANITIZE_STRIPPED,
            "cpf" => FILTER_SANITIZE_STRIPPED,
            "email" => FILTER_SANITIZE_EMAIL,
            "password" => FILTER_SANITIZE_STRIPPED,
            "phone" => FILTER_SANITIZE_STRIPPED,
            "address" => FILTER_SANITIZE_STRIPPED,
            "advertiser" => FILTER_SANITIZE_NUMBER_INT
        ]);

        /** variavel de erros */
        $validateFields = [];

        /** verificando se o email é válido */
        if (!validateEmail($data['email'])) {
            $validateFields['email'] = 'Formato de email inválido';
        }

        /** verificando se o email é válido */
        if (!validateName($data['name'])) {
            $validateFields['name'] = 'Formato do nome inválido';
        }

        /** verificando se Email existe */
        if ((new User())->find('Usu_email = :e', "e=" . trim($data['email']))->fetch()) {
            $validateFields['email'] = 'Email ja cadastrado';
        }

        /** verificando se Nick existe */
        if ((new User())->find('Usu_nick = :n', "n=" . trim($data['nick']))->fetch()) {
            $validateFields['nick'] = 'Nick ja cadastrado';
        }

        /** verificando se cpf existe */
        if ((new User())->find('Usu_cpf = :n', "n=" . trim($data['cpf']))->fetch()) {
            $validateFields['cpf'] = 'CPF ja cadastrado';
        }

        /** retorna validação de campos */
        if ($validateFields) {
            echo json_encode(['validateFields' => $validateFields]);
            return;
        }

        /** Instanciando o objeto que vai persistir as informaçõs no banco de dados */
        $user = new User();

        $user->Usu_nome = trim($data['name']);
        $user->Usu_nick = (!empty(trim($data['nick'])) ? trim($data['nick']) : NULL);
        $user->Usu_cpf = trim($data['cpf']);
        $user->Usu_email = trim($data['email']);
        $user->Usu_senha = hash('sha512', trim($data['password']));
        $user->Usu_telefone =  (!empty(trim($data['phone'])) ? trim($data['phone']) : NULL);
        $user->Usu_Endereco = (!empty(trim($data['address'])) ? trim($data['address']) : NULL);
        $user->Usu_anunciante = $data['advertiser'];

        $user->save();

        if ($user->fail()) {
            echo json_encode("Usuario: " . $user->fail()->getMessage());
            return;
        }
        echo json_encode(['success' => 'Registrado com sucesso']);
    }

    /**
     * Exibe um usuario especifico
     */
    public function show($data)
    {
        echo json_encode(objectToArray((new User())->findById($data['id'])));
    }

    /**
     * Atualiza um usuario
     */
    public function update($data)
    {
        $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
        $user = (new User())->findById($id);

        if (!$user) {
            echo json_encode(['userError' => 'Usuario nao cadastrado']);
            return;
        }

        /** verificando campos vazios */
        $findEmptyFields =  array_keys($data, '');

        /** retornando todos os campos vazios */
        if ($findEmptyFields) {
            echo json_encode(['emptyFields' => $findEmptyFields]);
            return;
        }

        /**Sanitizando todos os campos*/
        $data = filter_var_array($data, [
            "name" => FILTER_SANITIZE_STRIPPED,
            "nick" => FILTER_SANITIZE_STRIPPED,
            "cpf" => FILTER_SANITIZE_STRIPPED,
            "email" => FILTER_SANITIZE_EMAIL,
            "password" => FILTER_SANITIZE_STRIPPED,
            "phone" => FILTER_SANITIZE_STRIPPED,
            "address" => FILTER_SANITIZE_STRIPPED,
            "advertiser" => FILTER_SANITIZE_NUMBER_INT
        ]);

        $validateFields = [];

        /** verificando se o email é válido */
        if (!validateEmail($data['email'])) {
            $validateFields['email'] = 'Formato de email inválido';
        }

        /** verificando se o email é válido */
        if (!validateName($data['name'])) {
            $validateFields['name'] = 'Formato do nome inválido';
        }

        /** retorna validação de campos */
        if ($validateFields) {
            echo json_encode(['validateFields' => $validateFields]);
            return;
        }

        /** Atualizando informações no banco de dados */
        $user->Usu_nome = trim($data['name']);
        $user->Usu_nick = (!empty(trim($data['nick'])) ? trim($data['nick']) : NULL);
        $user->Usu_cpf = trim($data['cpf']);
        $user->Usu_email = trim($data['email']);
        $user->Usu_senha = hash('sha512', trim($data['password']));
        $user->Usu_telefone =  (!empty(trim($data['phone'])) ? trim($data['phone']) : NULL);
        $user->Usu_Endereco = (!empty(trim($data['address'])) ? trim($data['address']) : NULL);
        $user->Usu_anunciante = $data['advertiser'];

        $user->change()->save();

        if ($user->fail()) {
            echo json_encode("Usuario: " . $user->fail()->getMessage());
            return;
        }
        echo json_encode(['success' => 'Usuario atualizado com sucesso']);
    }

    /**
     * Deleta um usuario
     */
    public function delete($data)
    {
        $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
        $user = (new User())->findById($id);

        if ($user) {
            if ($user->destroy()) {
                echo json_encode(['deletedUser' => 'Cliente deletado com sucesso']);
                return;
            } else {
                echo json_encode($user->fail()->getMessage());
                return;
            }
        } else {
            echo json_encode(['userError' => 'Usuario nao cadastrado']);
            return;
        }
    }
}
