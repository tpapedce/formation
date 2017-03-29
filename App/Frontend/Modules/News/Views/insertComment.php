<h3>Ajouter un commentaire</h3>
<form class="js-comment-form" action="<?= \App\Frontend\Modules\News\NewsController::getLinkToInsertComment( $news ) ?>" data-ajax-action="<?= \App\Frontend\Modules\News\NewsController::getLinkToInsertCommentAjax( $news ) ?>" method="post" id="commentPost">
	<p><?= $form ?> <input type="submit" value="Commenter" id="submitComment" /></p>
</form>
