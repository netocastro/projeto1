<?php

namespace Source\Models;

use Stonks\DataLayer\DataLayer;

class User extends DataLayer
{
    public function __construct()
    {
        parent::__construct('usuarios', ['Usu_nome', 'Usu_cpf', 'Usu_email', 'Usu_senha',], 'id', true);
    }
}
