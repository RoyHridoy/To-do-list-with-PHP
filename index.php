<?php include_once("inc/templates/header.php"); ?>
<?php include_once("inc/templates/nav.php"); ?>
<?php include_once("inc/functions.php"); ?>

<?php

$task = isset($_GET['task']) ? $_GET['task'] : "toDoList";

if (isset($_POST['add'])) {
    $title       = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    if (isset($title) && isset($description) && !empty($title) && !empty($description)) {
        insertData($title, $description);
        $successMessage = "You have successfully added this at To do List";
    } else {
        $errorMessage = "Please fill up all fields";
    }
}

if (isset($_POST['taskComplete'])) {
    $id = $_POST['id'];
    completedThisTask($id);
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    deleteThisTask($id);
}


?>

<?php if ($task == "add") : ?>
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-6 offset-3">
                    <div class="content-box">
                        <h5 class="section-title">add to do list</h5>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="title">Title of To do list</label>
                                <input type="text" name="title" id="title" class="form-control">
                                <small class="text-muted">e.g Learn Basic Javascript</small>
                            </div>
                            <div class="form-group">
                                <label for="description">Description of To do list</label>
                                <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                                <small class="text-muted">e.g Complete Mock Udemy basic javascript course with
                                    parctise
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3" name="add">Add item</button>
                        </form>

                        <?php if (isset($successMessage)) : ?>
                            <div class="alert alert-success mt-3" role="alert">
                                <strong><?php echo $successMessage; ?></strong>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($errorMessage)) : ?>
                            <div class="alert alert-warning mt-3" role="alert">
                                <strong><?php echo $errorMessage; ?></strong>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($task == "toDoList") : ?>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="content-box">
                        <div class="pending lists">
                            <h5 class="section-title">Pending to Complete</h5>
                            <?php
                            $allToDoList = displayToDoLists();
                            if ($allToDoList) {
                                foreach ($allToDoList as $singleToDOList) { ?>
                                    <div class="single-item">
                                        <h6><?php printf("%s", ucwords($singleToDOList['title'])); ?></h6>
                                        <?php printf("%s", ucfirst($singleToDOList['description'])); ?>

                                        <form action="" method="POST">
                                            <input type="hidden" name="id" value="<?php echo $singleToDOList['id']; ?>">
                                            <button type="submit" name="taskComplete" class="btn btn-outline-dark mr-3">
                                                Mark
                                                as Completed
                                            </button>
                                            <button type="submit" name="delete" class="btn btn-outline-danger">Delete
                                            </button>
                                        </form>

                                    </div>
                                <?php }
                            } ?>

                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="content-box">
                        <div class="success lists">
                            <h5 class="section-title">Completed Lists</h5>
                            <?php
                            $completedList = displayCompletedList();
                            if ($completedList) {

                                foreach ($completedList as $singleList) { ?>

                                    <div class="single-item">
                                        <h6> <?php echo $singleList['title']; ?> </h6>
                                        <?php echo $singleList['description']; ?>
                                    </div>

                                <?php }
                            } else {
                                echo "You didn't complete any task yet.";
                            } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php include_once("inc/templates/footer.php"); ?>