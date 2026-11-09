<?php

class EncryptionService {

    private static $encKey = null;
    private static $bidxKey = null;

    private static function initKeys() {
        if (self::$encKey === null) {
            self::$encKey = defined('APP_ENC_KEY') ? APP_ENC_KEY : hash('sha256', 'FINAL_PROJECT_TEMPLATE_SECRET_KEY_2026', true);
        }
        if (self::$bidxKey === null) {
            self::$bidxKey = defined('APP_BIDX_KEY') ? APP_BIDX_KEY : hash('sha256', 'FINAL_PROJECT_BLIND_INDEX_SALT_KEY_2026', true);
        }
    }

    public static function encrypt($plaintext) {
        if ($plaintext === null || $plaintext === '') {
            return '';
        }
        self::initKeys();
        $iv = random_bytes(12);
        $tag = '';
        $ciphertext = openssl_encrypt((string)$plaintext, 'aes-256-gcm', self::$encKey, OPENSSL_RAW_DATA, $iv, $tag);
        return 'ENC:' . base64_encode($iv . $tag . $ciphertext);
    }

    public static function decrypt($ciphertext) {
        if ($ciphertext === null || $ciphertext === '') {
            return '';
        }
        if (strpos((string)$ciphertext, 'ENC:') !== 0) {
            return (string)$ciphertext;
        }
        self::initKeys();
        $raw = base64_decode(substr((string)$ciphertext, 4));
        if (strlen($raw) < 28) {
            return (string)$ciphertext;
        }
        $iv = substr($raw, 0, 12);
        $tag = substr($raw, 12, 16);
        $data = substr($raw, 28);
        $decrypted = openssl_decrypt($data, 'aes-256-gcm', self::$encKey, OPENSSL_RAW_DATA, $iv, $tag);
        return $decrypted !== false ? $decrypted : (string)$ciphertext;
    }

    public static function blind_index($plaintext) {
        if ($plaintext === null || $plaintext === '') {
            return '';
        }
        self::initKeys();
        return 'BIDX:' . hash_hmac('sha256', strtolower(trim((string)$plaintext)), self::$bidxKey);
    }

    public static function hash_password($password) {
        if (defined('PASSWORD_ARGON2ID')) {
            return password_hash($password, PASSWORD_ARGON2ID);
        }
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);
    }

    public static function verify_password($password, $hash) {
        if (password_verify($password, $hash)) {
            return true;
        }
        return ($password === $hash);
    }

    public static function mask($string, $visibleStart = 3, $visibleEnd = 2) {
        $string = trim((string)$string);
        $len = strlen($string);
        if ($len <= ($visibleStart + $visibleEnd)) {
            return $string;
        }
        $start = substr($string, 0, $visibleStart);
        $end = substr($string, -$visibleEnd);
        $masked = str_repeat('•', max(3, $len - $visibleStart - $visibleEnd));
        return $start . $masked . $end;
    }

    public static function decrypt_student_record(&$row) {
        if (!is_array($row)) return;
        if (isset($row['nisn_enc'])) $row['nisn'] = self::decrypt($row['nisn_enc']);
        if (isset($row['nik_enc'])) $row['nik'] = self::decrypt($row['nik_enc']);
        if (isset($row['parent_name_enc'])) $row['parent_name'] = self::decrypt($row['parent_name_enc']);
        if (isset($row['parent_phone_enc'])) $row['parent_phone'] = self::decrypt($row['parent_phone_enc']);
        if (isset($row['address_enc'])) $row['address'] = self::decrypt($row['address_enc']);
        if (isset($row['health_info_enc'])) $row['health_info'] = self::decrypt($row['health_info_enc']);
    }

    public static function decrypt_teacher_record(&$row) {
        if (!is_array($row)) return;
        if (isset($row['nik_enc'])) $row['nik'] = self::decrypt($row['nik_enc']);
        if (isset($row['phone_enc'])) $row['phone'] = self::decrypt($row['phone_enc']);
        if (isset($row['address_enc'])) $row['address'] = self::decrypt($row['address_enc']);
    }

    public static function decrypt_staff_record(&$row) {
        if (!is_array($row)) return;
        if (isset($row['nik_enc'])) $row['nik'] = self::decrypt($row['nik_enc']);
        if (isset($row['phone_enc'])) $row['phone'] = self::decrypt($row['phone_enc']);
        if (isset($row['address_enc'])) $row['address'] = self::decrypt($row['address_enc']);
    }
}
