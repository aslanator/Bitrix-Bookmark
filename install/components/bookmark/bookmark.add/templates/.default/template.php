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
            <form action="<?=POST_FORM_ACTION_URI?>" method="POST">
                <?=bitrix_sessid_post()?>
                <input type="hidden" name="ACTION" value="addBookmark">
                <table class="table">
                    <thead>
                        <th><?=GetMessage("URL")?></th>
                        <th><?=GetMessage("PASSWORD")?></th>
                    </thead>
                    <tbody>
                        <td><input type="text" name="URL" size="40"></td>
                        <td><input type="password" name="PASSWORD" size="40"></td>
                    </tbody>
                </table>
                <button class="btn btn-primary" type="submit"><?=GetMessage("ADD")?></button>
            </form>
        </div>
    </div>
</div>
