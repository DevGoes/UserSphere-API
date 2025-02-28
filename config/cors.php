<?php

return [
    'paths' => ['api/*', 'auth/*', 'sanctum/csrf-cookie'],  // Permite CORS nessas rotas
    'allowed_methods' => ['*'],  // Permite todos os métodos (GET, POST, PUT, DELETE, etc.)
    'allowed_origins' => ['*'],  // Permite requisições de qualquer origem
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],  // Permite qualquer cabeçalho
    'exposed_headers' => ['Authorization', 'X-Custom-Header'], // Exponha headers úteis
    'max_age' => 0,
    'supports_credentials' => false,  // ⚠️ Altere para "true" apenas se estiver usando cookies ou sessões

];
