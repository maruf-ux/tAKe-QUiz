<?php
include 'config/db.php';

if (isset($_POST['start_quiz'])) {
    header('Location: views/take-quiz.php');
    exit();
}

$stmt = $pdo->query("SELECT COUNT(*) as total FROM questions");
$row = $stmt->fetch();
$totalQuestions = $row['total'];
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quiz App</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h1 class="text-center mb-0">Welcome to the Quiz App</h1>
                        </div>
                        <div class="card-body text-center p-5">
                            <h4 class="card-title mb-4">Test your knowledge with our quiz!</h4>
                            <form method="POST">
                                <button type="submit" name="start_quiz" class="btn btn-primary btn-lg">Start
                                    Quiz</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.card').hide().fadeIn(1000);

                $('button[name="start_quiz"]').hover(
                    function () {
                        $(this).addClass('shadow-lg').css('transform', 'scale(1.05)');
                    },
                    function () {
                        $(this).removeClass('shadow-lg').css('transform', 'scale(1)');
                    }
                );
            });
        </script>
    </body>

</html>