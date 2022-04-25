<?php

namespace Source\Controllers\Site;

use Source\Models\User;

class Requests
{
    /** 
     * Executa o login do usuario 
     * */
    public function login($data)
    {
        $findEmptyFields =  array_keys($data, '');

        if ($findEmptyFields) {
            echo json_encode(['emptyFields' => $findEmptyFields]);
            return;
        }

        /** Filtrando as variaveis de entrada */
        $data = filter_var_array($data, [
            "email" => FILTER_SANITIZE_STRIPPED,
            "password" => FILTER_SANITIZE_STRIPPED
        ]);

        /** Variavel de erros */
        $validateFields = [];

        /** Consultando se o usuario existe */
        $user = (new User())->find('Usu_email = :ue', "ue=" . trim($data['email']))->fetch();

        /** verificando se informações estão corretas */
        if (!$user || $user->Usu_senha != hash('sha512', trim($data['password']))) {
            $validateFields['password'] = 'invalid informations';
        }

        /** Enviando resultado da validação do campos */
        if ($validateFields) {
            echo json_encode(['validateFields' => $validateFields]);
            return;
        }
        
        /** Gerando a sessão do usuario*/
        $_SESSION['user_id'] = $user->id;
        echo json_encode(['success' => 'LOGADO com sucesso']);
    }

    /**
     * Executa logout do usuario
     */
    public function logout()
    {
        /**desloga o usuario */
        unset($_SESSION['user_id']);
        echo json_encode(['success' => 'DESLOGADO com sucesso']);
    }
}
