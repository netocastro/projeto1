<?php

/**
 * função de validação de nomes com expressões regulares
 */

function validateName($name): bool
{
    if (preg_match('/^[a-zA-Z ]+$/', $name)) {
        return true;
    } else {
        return false;
    }
}

/**
 * função de validação de cpf com expressões regulares
 */

function validateCpf($cpf): bool
{
    if (preg_match('/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}\-[0-9]{2}$/', $cpf)) {
        return true;
    } else {
        return false;
    }
}

/**
 * função de validação de email
 */

function validateEmail($email): bool
{
    if (preg_match('/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/', $email)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Transforma objetos do tipo DataLayer em arrays
 */

function objectToArray($object): array
{
    $newArray = [];
    if ($object == null) {
        return (array)$newArray;
    }

    if (is_array($object)) {

        foreach ($object as $item => $value) {
            $newArray[] = (array)$value->data();
        }
        return  (array) $newArray;
    } else {
        $newArray = [];
        $newArray[] = (array)$object->data();
        return (array)$newArray;
    }
}
