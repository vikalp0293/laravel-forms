<?php

return [
	'ROLES' => [
		'CUSTOMER'  => 'customer',
		'SUPERUSER'  => 'superuser'
	],
	'PAGE' => [
		'PER_PAGE'  => '10'
	],
	'DATE' => [
		'DATE_FORMAT' => 'd M, Y',
		'TIME_FORMAT' => 'h:i A',
		'DATE_FORMAT_FULL' => 'd M, Y h:i A',
	],
	
	'REGEX' => [
		'PASSWORD_WEIGHT_REGX' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\d)(?=.*[@`~#^_$!%*?&])[A-Za-z\S]{6,}$',
		'VALIDATE_GSTIN' => '^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$',
		'VALIDATE_EMAIL' => '^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$',
		'VALIDATE_OTP_LENGTH' => '^[0-9]{6}$',
		'VALIDATE_ZIP_CODE' => '^[0-9]{6}$',
		'VALIDATE_CHEQUE_NUMBER' => '^[0-9]{6}$',
		'VALIDATE_NUMBER' => '^[0-9]*$',
		'VALIDATE_FLOAT_NUMBER' => '^(\d*\.)?\d+$',
		'VALIDATE_MOBILE_NUMBER_LENGTH' => '^[0-9]{10}$',
		'VALIDATE_ADHAR_CARD' => '^\d{4}\d{4}\d{4}$',
		'VALIDATE_IFSC_CODE' => '[A-Z|a-z]{4}[0][\d]{6}$',
		'VALIATE_PAN_CARD' => '^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$',
		'VALIDATE_VEHICLE_NUMBER' => '^(([A-Za-z]){2,3}(|-)(?:[0-9]){1,2}(|-)(?:[A-Za-z]){2}(|-)([0-9]){1,4})|(([A-Za-z]){2,3}(|-)([0-9]){1,4})',
	],
    'SLUG_CHARACTER_LIMIT' => 255,
    'allowedImportExtensions' => ['xlsx','csv','xls']
];

