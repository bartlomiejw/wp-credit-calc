<?php

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// this allow for using wordpress server-side translation
return [
	'sections' => [
		'general'   => __( 'Ogólne', \CreditCalc\Main::PREFIX ),
		'settings'  => __( 'Ustawienia kalkulatora', \CreditCalc\Main::PREFIX ),
		'advanced'  => __( 'Zaawansowane', \CreditCalc\Main::PREFIX ),
		'debugging' => __( 'Debug', \CreditCalc\Main::PREFIX ),
	],
	'options'  => [
		'header'                     => [
			'name'        => __( 'Nagłówek kalkulatora', \CreditCalc\Main::PREFIX ),
			'description' => __( 'Wprowadź tekst wyświetlany jako nagłówek', \CreditCalc\Main::PREFIX ),
			'section'     => 'general',
			'type'        => 'text',
			'default'     => 'Kalkulator',
		],
		'header_color'               => [
			'name'        => __( 'Kolor nagłówka', \CreditCalc\Main::PREFIX ),
			'description' => __( 'Wybierz z palety kolorów', \CreditCalc\Main::PREFIX ),
			'section'     => 'general',
			'type'        => 'color',
			'default'     => '#018765', // empty text means #000 by default anyway so might as well set it
		],
		'body_color'                     => [
			'name'        => __( 'Kolor fontu', \CreditCalc\Main::PREFIX ),
			'description' => __( 'Wybierz z palety kolorów', \CreditCalc\Main::PREFIX ),
			'section'     => 'general',
			'type'        => 'color',
			'default'     => '#7A7A7A', // empty text means #000 by default anyway so might as well set it
		],
		'num_color'                 => [
			'name'        => __( 'Kolor liczb klienta', \CreditCalc\Main::PREFIX ),
			'description' => __( 'Wybierz z palety kolorów', \CreditCalc\Main::PREFIX ),
			'section'     => 'general',
			'type'        => 'color',
			'default'     => '#018765', // empty text means #000 by default anyway so might as well set it
		],
		'background_color'               => [
			'name'        => __( 'Kolor tła kalkulatora', \CreditCalc\Main::PREFIX ),
			'description' => __( 'Wybierz z palety kolorów', \CreditCalc\Main::PREFIX ),
			'section'     => 'general',
			'type'        => 'color',
			'default'     => '#F8F8F8', // empty text means #000 by default anyway so might as well set it
		],
		'credit_calc_settings'           => [
			'name'        => __( 'Kalkulator', \CreditCalc\Main::PREFIX ),
			'description' => __( 'Dodaj nowy kalkulator', \CreditCalc\Main::PREFIX ),
			'section'     => 'settings',
			'type'        => 'repeater',
			'default'     => '',
			'repeater'      => [
				'id'         => [
					'name'        => __( 'Nazwa kalkulatora', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź tekst', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'id',
					'default'     => '',
				],
				'credit_name'         => [
					'name'        => __( 'Nazwa produktu', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź tekst', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'text',
					'default'     => '',
				],
				'interest_rate'         => [
					'name'        => __( 'Stopa procentowa', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź liczbę', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'number',
					'default'     => '',
				],
				'provision'         => [
					'name'        => __( 'Prowizja banku', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź liczbę', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'number',
					'default'     => '',
				],
				'other_costs'           => [
					'name'        => __( 'Wysokość innych kosztów', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź liczbę', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'number',
					'default'     => '',
				],
				'rrso'           => [
					'name'        => __( 'RRSO', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź liczbę', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'number',
					'default'     => '',
				],
				'sale_switch'           => [
					'name'        => __( 'Promocja prowizji', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Włącz możliwość obniżenia prowizji bankowej', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'toggle',
					'default'     => false,
				],
				'sale_number'           => [
					'name'        => __( 'Wysokość promocyjnej prowizji', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź liczbę', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'number',
					'default'     => '',
				],
				'credit_amount_min'     => [
					'name'        => __( 'Minimalna kwota kredytu', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź liczbę', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'number',
					'default'     => '',
				],
				'credit_amount_max'     => [
					'name'        => __( 'Maksymalna kwota kredytu', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź liczbę', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'number',
					'default'     => '',
				],
				'credit_amount_default' => [
					'name'        => __( 'Domyślna kwota kredytu', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź liczbę', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'number',
					'default'     => '',
				],
				'credit_amount_step'    => [
					'name'        => __( 'Krok kwoty kredytu', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź liczbę', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'number',
					'default'     => '',
				],
				'credit_period_min'     => [
					'name'        => __( 'Minimalny okres kredytowania', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź liczbę', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'number',
					'default'     => '',
				],
				'credit_period_max'     => [
					'name'        => __( 'Maksymalny okres kredytowania', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź liczbę', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'number',
					'default'     => '',
				],
				'credit_period_default' => [
					'name'        => __( 'Domyślny okres kredytowania', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź liczbę', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'number',
					'default'     => '',
				],
				'credit_period_step'    => [
					'name'        => __( 'Krok okresu kredytowania', \CreditCalc\Main::PREFIX ),
					'description' => __( 'Wprowadź liczbę', \CreditCalc\Main::PREFIX ),
					'section'     => 'settings',
					'type'        => 'number',
					'default'     => '',
				],
			]
		],
		'enable_debug_messages'          => [
			'name'        => __( 'Włącz komunikaty debugowania', \CreditCalc\Main::PREFIX ),
			'description' => __( 'Po włączeniu wtyczka wyświetli komunikaty debugowania w konsoli JavaScript.',
				\CreditCalc\Main::PREFIX ),
			'section'     => 'debugging',
			'type'        => 'toggle',
			'default'     => false,
		],
		'cleanup_db_on_plugin_uninstall' => [
			'name'        => __( 'Czyszczenie bazy danych po odinstalowaniu wtyczki', \CreditCalc\Main::PREFIX ),
			'description' => __( 'Po włączeniu wtyczka usunie wszelkie dane z bazy danych po odinstalowaniu wtyczki.',
				\CreditCalc\Main::PREFIX ),
			'section'     => 'advanced',
			'type'        => 'toggle',
			'default'     => false,
		],
	],
];
