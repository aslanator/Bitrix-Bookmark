<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){die();}
$this->addExternalCss("/bitrix/css/main/bootstrap.css");
?>
<div class="container">
    <div class="row">
        <?if(count($arResult['ERRORS']) > 0):?>
            <div class="col-xs-10 col-xs-offset-1">
            <?foreach($arResult['ERRORS'] as $arError):?>
                <div class="alert alert-danger" role="alert">
                    <?=$arError?>
                </div>
            <?endforeach;?>
            </div>
        <?endif;?>
        <div class="col-xs-10 col-xs-offset-1">
            <a href="<?=$arResult['BACK_URL']?>" class="btn btn-primary"><?=GetMessage('BACK')?></a>
            <?if($arResult['DELETE_WITH_PASSWORD'] !== 'Y'):?>
                <ul class="list-group">
                    <li class="list-group-item"><b><?=GetMessage('CREATED')?></b>: <?=$arResult['CREATED']?></li>
                    <?if(isset($arResult['FAVICON'])):?>
                        <li class="list-group-item"><b><?=GetMessage('FAVICON')?></b>: <img src="<?=$arResult['FAVICON']?>" alt="favicon"></li>
                    <?endif;?>
                    <li class="list-group-item"><b><?=GetMessage('URL')?></b>: <a target="_blank" href="<?=$arResult['URL']?>"><?=$arResult['URL']?></a></li>
                    <li class="list-group-item"><b><?=GetMessage('TITLE')?></b>: <?=$arResult['CREATED']?></li>
                    <li class="list-group-item"><b><?=GetMessage('META_DESCRIPTION')?></b>: <?=$arResult['META_DESCRIPTION']?></li>
                    <li class="list-group-item"><b><?=GetMessage('META_KEYWORDS')?></b>: <?=$arResult['META_KEYWORDS']?></li>
                </ul>
                <a href="?DELETE=Y"><?=GetMessage('DELETE')?></a>
            <?else:?>
                <form action="<?=POST_FORM_ACTION_URI?>" method="POST">
                    <?=bitrix_sessid_post()?>
                    <input type="hidden" name="DELETE" value="Y">
                    <p><?=GetMessage('INPUT_PASSWORD')?></p>
                    <p><input type="password" name="PASSWORD"></p>
                    <button class="btn btn-primary" type="submit"><?=GetMessage('DELETE')?></button>
                </form>
            <?endif;?>
        </div>
    </div>
</div>