<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/bookmark/public/bookmark.php');

Bitrix\Main\Loader::registerAutoloadClasses(
   "bookmark",
   array(
    "Bookmark\\Bookmark" => "lib/bookmark.php",
    "Bookmark\\BookmarkTable" => "lib/bookmarkTable.php",
    "Bookmark\\pageDownloader" => "lib/pageDownloader.php",
   )
);