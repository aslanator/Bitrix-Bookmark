<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/bookmark/public/bookmark.php');

Bitrix\Main\Loader::registerAutoloadClasses(
   "bookmark",
   array(
    "Bookmark\\D7\\Bookmark" => "lib/bookmark.php",
    "Bookmark\\D7\\BookmarkTable" => "lib/data.php",
   )
);