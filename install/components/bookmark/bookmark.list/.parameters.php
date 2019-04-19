<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)
{
	die();
}

$arComponentParameters = Array(
	'PARAMETERS' => array(
		'ROWS_PER_PAGE' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('BOOKMARK_LIST_ROWS_PER_PAGE_PARAM'),
			'TYPE' => 'TEXT'
		),
	)
);
