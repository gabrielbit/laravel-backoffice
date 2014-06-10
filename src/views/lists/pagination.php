<?php
/** @var $paginator Illuminate\Pagination\Paginator */
$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
$paginator->setupPaginationContext();
?>
<div class="pagination-container">
	<span class="showing"><?=
		Lang::get('l4-backoffice::paginator.showing', [
			'from'  => $paginator->getFrom(),
			'to'    => $paginator->getTo(),
			'total' => $paginator->getTotal()
		]);
	?></span>
	<?php if ($paginator->getLastPage() > 1): ?>
		<ul class="pagination">
			<?= $presenter->render(); ?>
		</ul>
	<?php endif; ?>
</div>