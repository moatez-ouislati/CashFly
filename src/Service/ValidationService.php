<?php

namespace App\Service;

class ValidationService
{
    private array $errors = [];

    public function validateEmail(string $email): bool
    {
        $pattern = '/^[A-Za-z0-9+_.-]+@[A-Za-z0-9.-]+$/';
        if (!preg_match($pattern, $email)) {
            $this->errors['email'] = 'Format d\'email invalide';
            return false;
        }
        return true;
    }

    public function validateCin(string $cin): bool
    {
        $pattern = '/^[01][0-9]{7}$/';
        if (!preg_match($pattern, $cin)) {
            $this->errors['cin'] = 'CIN invalide (8 chiffres, commence par 0 ou 1)';
            return false;
        }
        return true;
    }

    public function validatePhone(string $phone): bool
    {
        $cleaned = preg_replace('/[\s\-\(\)]/', '', $phone);
        $pattern = '/^[0-9]{8}$/';
        if (!preg_match($pattern, $cleaned)) {
            $this->errors['phone'] = 'Numero de telephone invalide (8 chiffres)';
            return false;
        }
        return true;
    }

    public function validatePassword(string $password): bool
    {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caracteres';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins une lettre majuscule';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins une lettre minuscule';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins un chiffre';
        }
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins un symbole';
        }
        
        if (!empty($errors)) {
            $this->errors['password'] = $errors;
            return false;
        }
        
        return true;
    }

    public function validateRequired(string $value, string $fieldName): bool
    {
        if (empty(trim($value))) {
            $this->errors[$fieldName] = "Le champ {$fieldName} est requis";
            return false;
        }
        return true;
    }

    public function validatePositiveNumber(mixed $value, string $fieldName): bool
    {
        if (!is_numeric($value) || floatval($value) < 0) {
            $this->errors[$fieldName] = "Le champ {$fieldName} doit etre un nombre positif";
            return false;
        }
        return true;
    }

    public function validateRole(string $role): bool
    {
        $validRoles = ['administrateur', 'proprietaire', 'investisseur'];
        if (!in_array($role, $validRoles)) {
            $this->errors['role'] = 'Role invalide';
            return false;
        }
        return true;
    }

    public function validateStatut(string $statut): bool
    {
        $validStatuts = ['EN_ATTENTE', 'ACTIF', 'TERMINE', 'ANNULE'];
        if (!in_array($statut, $validStatuts)) {
            $this->errors['statut'] = 'Statut invalide';
            return false;
        }
        return true;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function clearErrors(): void
    {
        $this->errors = [];
    }
}
