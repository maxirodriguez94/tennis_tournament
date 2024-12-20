<?php

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR12' => true, // Usa el estándar PSR-12
        'array_syntax' => ['syntax' => 'short'], // Opcional: Para usar la sintaxis corta de array
        'trailing_comma_in_multiline' => true, // Opcional: Para agregar comas finales en arrays multilinea
        // Puedes agregar más reglas si lo deseas
    ])
    ->setRiskyAllowed(true)  // Permitir reglas arriesgadas (opcional)
    ->setUsingCache(false)  // Desactiva el uso de caché (opcional)
    ->setIndent("    "); // Usa cuatro espacios para la indentación (opcional)
