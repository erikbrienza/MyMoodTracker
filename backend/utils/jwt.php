<?php
// jwt.php - libreria JWT leggera senza composer

function generateJWT($payload, $secret, $exp = 86400) {
  $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
  $payload['exp'] = time() + $exp;

  $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
  $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));

  $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
  $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

  return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

function verifyJWT($jwt, $secret) {
  $parts = explode('.', $jwt);
  if (count($parts) != 3) return false;

  [$header, $payload, $signature] = $parts;

  $validSignature = hash_hmac('sha256', "$header.$payload", $secret, true);
  $validBase64UrlSig = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($validSignature));

  if (!hash_equals($validBase64UrlSig, $signature)) return false;

  $decodedPayload = json_decode(base64_decode($payload), true);
  if ($decodedPayload['exp'] < time()) return false;

  return $decodedPayload;
}