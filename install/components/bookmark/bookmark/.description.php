<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)
{
	die();
}

$arComponentDescription = array(
	'NAME' => getMessage('BOOKMARK_NAME'),
	'DESCRIPTION' => getMessage('BOOKMARK_DESCRIPTION'),
	'SORT' => 1,
	'PATH' => array(
		'ID' => 'bookmark',
		'NAME' => getMessage('BOOKMARK_NAME'),
	)
);