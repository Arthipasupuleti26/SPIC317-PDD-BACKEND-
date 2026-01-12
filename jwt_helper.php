<?php

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

function create_jwt($user_id) {
    $header = base64url_encode(json_encode(["alg" => "HS256", "typ" => "JWT"]));
    $payload = base64url_encode(json_encode([
        "user_id" => $user_id,
        "iat" => time(),
        "exp" => time() + (60 * 60 * 24)
    ]));

    $secret = "my_super_secret_key";

    $signature = base64url_encode(
        hash_hmac("sha256", "$header.$payload", $secret, true)
    );

    return "$header.$payload.$signature";
}

function decodeJWT($jwt) {
    $secret = "my_super_secret_key";

    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return false;

    [$header, $payload, $signature] = $parts;

    $valid_signature = base64url_encode(
        hash_hmac("sha256", "$header.$payload", $secret, true)
    );

    if ($signature !== $valid_signature) return false;

    $data = json_decode(base64url_decode($payload), true);

    if (!$data || $data["exp"] < time()) return false;

    return $data;
}
?>
