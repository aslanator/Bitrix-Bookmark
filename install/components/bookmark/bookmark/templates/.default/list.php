<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$APPLICATION->IncludeComponent(
	"bookmark:bookmark.list", 
	"", 
	array(
		"ROWS_PER_PAGE" => $arParams["ROWS_PER_PAGE"] ? $arParams["ROWS_PER_PAGE"] : "1"
	),
	$this->getComponent()
);