<div class="panel <?= $class; ?>">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#<?= $parentId; ?>"
               href="#collapse<?= $parentId.$c; ?>"><?= $item['name']; ?></a>
        </h4>
    </div>
    <div id="collapse<?= $parentId.$c ?>" class="panel-collapse collapse">
        <div class="panel-body">
            <?= $item['text']; ?>
        </div>
    </div>
</div>