<?php

// don't call the file directly
if (! defined('ABSPATH')) {
    exit;
}

// this allow for using wordpress server-side translation
return [
    'sections' => [
        'general'   => __('Ogólne', \CreditCalc\Main::PREFIX),
        'advanced'  => __('Zaawansowane', \CreditCalc\Main::PREFIX),
        'debugging' => __('Debug', \CreditCalc\Main::PREFIX),
    ],
    'options'  => [
        'input'                          => [
            'name'        => __('Nagłówek tabeli', \CreditCalc\Main::PREFIX),
            'description' => __('Wprowadź tekst wyświetlany ponad tabelą kursów walut', \CreditCalc\Main::PREFIX),
            'section'     => 'general',
            'type'        => 'text',
            'default'     => 'Kursy walut',
        ],
        'color_table_border'                          => [
            'name'        => __('Kolor linii tabeli', \CreditCalc\Main::PREFIX),
            'description' => __('Wybierz z palety kolorów', \CreditCalc\Main::PREFIX),
            'section'     => 'general',
            'type'        => 'color',
            'default'     => '#018765', // empty text means #000 by default anyway so might as well set it
        ],
        'color_table_header'                          => [
	        'name'        => __('Kolor nagłówków tabli', \CreditCalc\Main::PREFIX),
	        'description' => __('Wybierz z palety kolorów', \CreditCalc\Main::PREFIX),
	        'section'     => 'general',
	        'type'        => 'color',
	        'default'     => '#7A7A7A', // empty text means #000 by default anyway so might as well set it
        ],
        'color_table_text'                          => [
	        'name'        => __('Kolor tekstu tabeli', \CreditCalc\Main::PREFIX),
	        'description' => __('Wybierz z palety kolorów', \CreditCalc\Main::PREFIX),
	        'section'     => 'general',
	        'type'        => 'color',
	        'default'     => '#018765', // empty text means #000 by default anyway so might as well set it
        ],
        'color_table_striped'                          => [
	        'name'        => __('Kolor tła parzystego wiersza tabeli', \CreditCalc\Main::PREFIX),
	        'description' => __('Wybierz z palety kolorów', \CreditCalc\Main::PREFIX),
	        'section'     => 'general',
	        'type'        => 'color',
	        'default'     => '#F8F8F8', // empty text means #000 by default anyway so might as well set it
        ],
        'enable_debug_messages'          => [
            'name'        => __('Włącz komunikaty debugowania', \CreditCalc\Main::PREFIX),
            'description' => __('Po włączeniu wtyczka wyświetli komunikaty debugowania w konsoli JavaScript.', \CreditCalc\Main::PREFIX),
            'section'     => 'debugging',
            'type'        => 'toggle',
            'default'     => false,
        ],
        'cleanup_db_on_plugin_uninstall' => [
            'name'        => __('Czyszczenie bazy danych po odinstalowaniu wtyczki', \CreditCalc\Main::PREFIX),
            'description' => __('Po włączeniu wtyczka usunie wszelkie dane z bazy danych po odinstalowaniu wtyczki.', \CreditCalc\Main::PREFIX),
            'section'     => 'advanced',
            'type'        => 'toggle',
            'default'     => false,
        ],
        'main_widget_rates'             => [
            'name'            => __('Wybierz waluty', \CreditCalc\Main::PREFIX),
            'description'     => __('Wybierz waluty wyświetlane w tabeli', \CreditCalc\Main::PREFIX),
            'section'         => 'general',
            'type'            => 'dropdownMultiselect',
            'optionsCallback' => function () {
	            $result = wp_remote_get( 'https://api.nbp.pl/api/exchangerates/tables/C?format=json' )['body'];
	            $rates_table = json_decode($result);

	            return $rates_table[0]->rates;
            },
            'default'         => [],
        ],
    ],
];
