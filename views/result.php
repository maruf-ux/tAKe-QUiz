<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['total_score']) || !isset($_SESSION['total_questions'])) {
    header('Location: ../index.php');
    exit();
}

$totalScore = $_SESSION['total_score'];
$totalQuestions = $_SESSION['total_questions'];
$percentage = ($totalScore / $totalQuestions) * 100;

if (isset($_POST['take_new_quiz'])) {
    unset($_SESSION['quiz_started']);
    unset($_SESSION['questions']);
    unset($_SESSION['current_question']);
    unset($_SESSION['user_answers']);
    unset($_SESSION['total_score']);
    unset($_SESSION['total_questions']);
    
    header('Location: take-quiz.php');
    exit();
}

if (isset($_POST['go_home'])) {
    unset($_SESSION['quiz_started']);
    unset($_SESSION['questions']);
    unset($_SESSION['current_question']);
    unset($_SESSION['user_answers']);
    unset($_SESSION['total_score']);
    unset($_SESSION['total_questions']);
    
    header('Location: ../index.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h1 class="text-center mb-0">Quiz Results</h1>
                    </div>
                    <div class="card-body text-center p-5">
                        
                        
                        <h2 class="card-title mb-4" id="score-display">Your Score: <?php echo $totalScore; ?> / <?php echo $totalQuestions; ?></h2>
                        
                     
                       
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center" id="action-buttons">
                            <form method="POST">
                                <button type="submit" name="take_new_quiz" class="btn btn-primary btn-lg me-md-2">Take Another Quiz</button>
                                <button type="submit" name="go_home" class="btn btn-secondary btn-lg">Return to Home</button>
                            </form>
                        </div>
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
        $(document).ready(function() {
            
            
            // Add button hover effects
            $('button').hover(
                function() {
                    $(this).addClass('shadow-lg').css('transform', 'scale(1.05)');
                },
                function() {
                    $(this).removeClass('shadow-lg').css('transform', 'scale(1)');
                }
            );
        });
    </script>
</body>
</html>
