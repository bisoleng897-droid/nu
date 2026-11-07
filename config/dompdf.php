<?php

return [
    'mode' => env('DOMPDF_MODE', 'utf-8'),
    'defines' => [
        'DOMPDF_MARGIN_TOP' => 0,
        'DOMPDF_MARGIN_RIGHT' => 0,
        'DOMPDF_MARGIN_BOTTOM' => 0,
        'DOMPDF_MARGIN_LEFT' => 0,
    ],
    'public_path' => null,
    'convert_entities' => true,
    'phi_replacement_char' => false,
    'positionable_tables' => false,
    'protect_memory' => true,
    'memory_limit' => '128M',
    'enable_font_subsetting' => false,
    'pdf_backend' => 'CPDF',
    'default_media_type' => 'screen',
    'default_paper_size' => 'a4',
    'default_font' => 'Segoe UI',
    'dpi' => 150,
    'enable_php' => false,
    'enable_javascript' => false,
    'enable_remote' => false,
    'font_dir' => storage_path('fonts/'),
    'font_cache' => storage_path('fonts/'),
    'chroot' => base_path(),
    'allowed_protocols' => [
        'file://' => [
            'rules' => [],
        ],
    ],
    'log_output_file' => storage_path('logs/dompdf.log'),
    'enable_html5_parser' => true,
];
