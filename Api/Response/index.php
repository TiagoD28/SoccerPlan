<?php

function sendErrorResponse($statusCode, $message) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $statusCode,
        'message' => $message,
    ]);
    exit;
}

function sendSuccessResponse($statusCode, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $statusCode,
        'message' => $message,
        'data' => $data,
    ]);
    exit;
}