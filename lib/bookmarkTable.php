<?php

namespace Bookmark;
 
use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
  

/**
 * class BookmarkTable
 *  */ 

class BookmarkTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'bookmark';
    }

    /**
     * Returns entity fields
     *
     * @return array
     */

    public function getFields():Array {
        $entity = BookmarkTable::getEntity();
        return $entity->getFields();
    }
 
    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('DATA_ENTITY_ID_FIELD'),
            ),
            'FAVICON' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('DATA_ENTITY_FAVICON_FIELD'),
            ),
            'URL' => array(
                'data_type' => 'text',
                'required' => true,
                'title' => Loc::getMessage('DATA_ENTITY_URL_FIELD'),
            ),
          'TITLE' => array(
                'data_type' => 'string',
                'required' => true,
                'title' => Loc::getMessage('DATA_ENTITY_TITLE_FIELD'),
            ),
          'META_DESCRIPTION' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('DATA_ENTITY_META_DESCRIPTION_FIELD'),
            ),
          'META_KEYWORDS' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('DATA_ENTITY_META_KEYWORDS_FIELD'),
            ),
            'PASSWORD' => array(
                'data_type' => 'string',
                'title' => Loc::getMessage('DATA_ENTITY_META_PASSWORD_FIELD'),
            ),
            'CREATED' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('DATA_ENTITY_CREATED_FIELD'),
            ),
        );
    }
}