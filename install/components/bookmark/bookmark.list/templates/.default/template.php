<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){die();}
$this->addExternalCss("/bitrix/css/main/bootstrap.css");
?>
<div class="cotainer">
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1">
			<?if($_GET['DELETED'] === 'Y'):?>
				<p><?=GetMessage('SUCCESS_DELETED')?></p>
			<?endif;?>
			<table class="table">
				<thead>
					<th scope="col" 
					class="bookmark-created-col-title 
					<?=$arResult['SORT_BY'] === 'CREATED' ? 'sorted-field-' . strtolower($arResult['SORT_DIRECTION']) : '' ?>">
						<a href="<?=$arResult['SORT_QUERY']['CREATED']?>"><?=GetMessage('CREATED')?></a></a>
					</th>
					<th scope="col"
					class="bookmark-favicon-col-title 
					<?=$arResult['SORT_BY'] === 'FAVICON' ? 'sorted-field-' . strtolower($arResult['SORT_DIRECTION']) : '' ?>">
						<?=GetMessage('FAVICON')?>
					</th>
					<th scope="col"
					class="bookmark-url-col-title 
					<?=$arResult['SORT_BY'] === 'URL' ? 'sorted-field-' . strtolower($arResult['SORT_DIRECTION']) : '' ?>">
						<a href="<?=$arResult['SORT_QUERY']['URL']?>"><?=GetMessage('URL')?></a>
					</th>
					<th scope="col"
					class="bookmark-title-col-title 
					<?=$arResult['SORT_BY'] === 'TITLE' ? 'sorted-field-' . strtolower($arResult['SORT_DIRECTION']) : '' ?>">
						<a href="<?=$arResult['SORT_QUERY']['TITLE']?>"><?=GetMessage('TITLE')?></a>
					</th>
					<tr></tr>
				</thead>
				<tbody>
				
					<?foreach($arResult['ITEMS'] as $arItem):?>
						<tr>
							<td><?=$arItem['CREATED']?></td>
							<td>
								<?if(isset($arItem['FAVICON'])):?>
									<img src="<?=$arItem['FAVICON']?>" alt="favicon">
								<?endif;?>
							</td>
							<td><a target="_blank" href="<?=$arItem['URL']?>"><?=$arItem['URL']?></a></td>
							<td><?=$arItem['TITLE']?></td>
							<td>
								<a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=GetMessage('DETAIL')?></a>
							</td>
						</tr>
					<?endforeach;?>
				</tbody>
			</table>
			<a href="add.php" class="btn btn-primary"><?=GetMessage('ADD')?></a>
		</div>
	</div>
</div>

<?php
if ($arParams['ROWS_PER_PAGE'] > 0):
	$APPLICATION->IncludeComponent(
		'bitrix:main.pagenavigation',
		'',
		array(
			'NAV_OBJECT' => $arResult['NAV_OBJECT'],
			'SEF_MODE' => 'N',
		),
		false
	);
endif;
