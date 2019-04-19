<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$APPLICATION->IncludeComponent(
	"bookmark:bookmark.element", 
	"", 
	array(
		'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
	),
	$this->getComponent()
);