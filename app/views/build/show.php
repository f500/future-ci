<?php include __DIR__ . '/../layout/header.php' ?>

<div class="container">

    <h1 class="page-header">
        <?php
        switch ($result['statuses']['build']) {
            case \F500\CI\Build\Result::PASSED:
                echo '<span class="label label-success pull-right">Passed</span>';
                break;
            case \F500\CI\Build\Result::FAILED:
                echo '<span class="label label-warning pull-right">Failed</span>';
                break;
            case \F500\CI\Build\Result::BORKED:
                echo '<span class="label label-danger pull-right">Borked</span>';
                break;
        }
        ?>
        [<?= $result['metadata']['build']['cn'] ?>]
        <?= $result['metadata']['suite']['name'] ?>
        <small><?= $result['metadata']['build']['date'] ?></small>
    </h1>

    <div class="panel-group" id="accordion">
        <?php foreach ($result['statuses']['tasks'] as $taskCn => $taskStatus): ?>
            <?php
            switch ($taskStatus) {
                case \F500\CI\Build\Result::PASSED:
                    $panelColor = 'default';
                    $labelColor = 'success';
                    $label      = 'Passed';
                    break;
                case \F500\CI\Build\Result::FAILED:
                    $panelColor = 'warning';
                    $labelColor = 'warning';
                    $label      = 'Failed';
                    break;
                case \F500\CI\Build\Result::BORKED:
                    $panelColor = 'danger';
                    $labelColor = 'danger';
                    $label      = 'Borked';
                    break;
            }
            ?>

            <div class="panel panel-<?= $panelColor ?>">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <span class="label label-<?= $labelColor ?> pull-right"><?= $label ?></span>
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-task-<?= $taskCn ?>">
                            <?= $taskCn ?>
                        </a>
                    </h4>
                </div>
                <div id="collapse-task-<?= $taskCn ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php foreach ($result['results'][$taskCn]['commands'] as $commandResult): ?>
                            <pre><?= $commandResult['output']; ?></pre>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<?php include __DIR__ . '/../layout/footer.php' ?>
