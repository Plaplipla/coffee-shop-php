<?php

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function findByEmail($email) {
        return $this->db->findOne('users', ['email' => $email]);
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function create($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = new MongoDB\BSON\UTCDateTime();
        $data['email_verified'] = false; // Por defecto no verificado
        $data['email_verification_token'] = null;
        return $this->db->insert('users', $data);
    }
    
    /**
     * Generar y guardar token de verificación de email
     */
    public function generateVerificationToken($email) {
        $token = bin2hex(random_bytes(32));
        $this->db->update('users', 
            ['email' => $email], 
            ['$set' => [
                'email_verification_token' => $token,
                'email_verification_token_expires' => new MongoDB\BSON\UTCDateTime((time() + 86400) * 1000) // 24 horas
            ]]
        );
        return $token;
    }
    
    /**
     * Verificar email con token
     */
    public function verifyEmailWithToken($token) {
        $user = $this->db->findOne('users', [
            'email_verification_token' => $token,
            'email_verification_token_expires' => ['$gt' => new MongoDB\BSON\UTCDateTime()]
        ]);
        
        if ($user) {
            $this->db->update('users',
                ['email' => $user->email],
                ['$set' => [
                    'email_verified' => true,
                    'email_verification_token' => null,
                    'email_verification_token_expires' => null
                ]]
            );
            return $user;
        }
        
        return null;
    }
    
    /**
     * Verificar si el email ya está verificado
     */
    public function isEmailVerified($email) {
        $user = $this->findByEmail($email);
        return $user && isset($user->email_verified) && $user->email_verified === true;
    }
}
