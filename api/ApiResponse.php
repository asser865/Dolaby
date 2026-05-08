<?php
class ApiResponse {
    public static function send($success, $data = [], $message = '', $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }

    public static function success($data = [], $message = 'Success') {
        self::send(true, $data, $message, 200);
    }

    public static function error($message = 'Error', $statusCode = 400, $data = []) {
        self::send(false, $data, $message, $statusCode);
    }
}
