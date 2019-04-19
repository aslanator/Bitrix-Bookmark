<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)
{
	die();
}

$arComponentParameters = Array(
	'PARAMETERS' => array(
		"VARIABLE_ALIASES" => Array(
			"ELEMENT_ID" => Array("NAME" => GetMessage("BOOKMARK_ELEMENT_ID")),
		),
		"SEF_MODE" => Array(
			"list" => array(
				"NAME" => GetMessage("BOOKMARK_LIST"),
				"DEFAULT" => "",
				"VARIABLES" => array(),
			),
			"detail" => array(
				"NAME" => GetMessage("BOOKMARK_DETAIL"),
				"DEFAULT" => "",
				"VARIABLES" => array("SECTION_ID"),
			),
		),
		'ROWS_PER_PAGE' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('BOOKMARK_ROWS_PER_PAGE_PARAM'),
			'TYPE' => 'TEXT'
		),
	)
);
