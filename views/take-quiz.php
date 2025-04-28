<?php
include '../config/db.php';
session_start();


if (!isset($_SESSION['quiz_started']) || !$_SESSION['quiz_started']) {
    // Fetch all questions
    $stmt = $pdo->query("SELECT * FROM questions");
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    shuffle($questions);

    // Store questions in session
    $_SESSION['questions'] = $questions;
    $_SESSION['current_question'] = 0;
    $_SESSION['user_answers'] = [];
    $_SESSION['quiz_started'] = true;
}

if (isset($_POST['submit_answer'])) {
    $questionId = $_POST['question_id'];
    $answerId = $_POST['answer_id'] ?? null;

    $_SESSION['user_answers'][$questionId] = $answerId;

    $_SESSION['current_question']++;

    if ($_SESSION['current_question'] >= count($_SESSION['questions'])) {
        calculateAndSaveResults($pdo);

        header('Location: result.php');
        exit();
    }
}

$currentQuestionIndex = $_SESSION['current_question'];
$currentQuestion = $_SESSION['questions'][$currentQuestionIndex];

$questionId = $currentQuestion['question_id'];
$stmt = $pdo->prepare("SELECT * FROM answers WHERE question_id = ?");
$stmt->execute([$questionId]);
$answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

shuffle($answers);

function calculateAndSaveResults($pdo)
{
    $totalMarks = 0;
    $userId = null; 

    foreach ($_SESSION['user_answers'] as $questionId => $answerId) {
        if ($answerId) {
            $stmt = $pdo->prepare("SELECT is_correct FROM answers WHERE answer_id = ?");
            $stmt->execute([$answerId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $marksObtained = ($row && $row['is_correct']) ? 1 : 0;
            $totalMarks += $marksObtained;

            $stmt = $pdo->prepare("INSERT INTO responses (question_id, user_id, marks_obtained, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$questionId, $userId, $marksObtained]);
        }
    }

    $_SESSION['total_score'] = $totalMarks;
    $_SESSION['total_questions'] = count($_SESSION['questions']);
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Take Quiz</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .list-group-item:hover {
                background-color: #f8f9fa;
            }

            .selected-answer {
                background-color: #e8f4fe !important;
                border-left: 4px solid #0d6efd;
            }
        </style>
    </head>

    <body>
        <div class="container mt-5">
          
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h2 class="text-center mb-0">Quiz in Progress</h2>
                        </div>
                        <div class="card-body p-4">
                            <p class="text-center mb-4"> Question <?php echo ($currentQuestionIndex + 1); ?> of
                                <?php echo count($_SESSION['questions']); ?>
                            </p>
                            <div class="question-container">
                                <h3 class="card-title mb-4">
                                    <?php echo htmlspecialchars($currentQuestion['question_text']); ?>
                                </h3>
                                <form method="POST" id="quiz-form">
                                    <input type="hidden" name="question_id" value="<?php echo $questionId; ?>">
                                    <div class="list-group mb-4">
                                        <?php if (count($answers) > 0): ?>
                                            <?php foreach ($answers as $answer): ?>
                                                <div class="list-group-item list-group-item-action answer-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="answer_id"
                                                               id="answer_<?php echo $answer['answer_id']; ?>"
                                                               value="<?php echo $answer['answer_id']; ?>" required>
                                                        <label class="form-check-label w-100"
                                                               for="answer_<?php echo $answer['answer_id']; ?>">
                                                            <?php echo htmlspecialchars($answer['answer_text']); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="alert alert-warning"> No answers found for this question. Please
                                                check your database. </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" name="submit_answer" class="btn btn-primary btn-lg">Submit
                                            Answer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.question-container').hide().fadeIn(500);

                $('.answer-item').click(function () {
                    $('.answer-item').removeClass('selected-answer');
                    $(this).addClass('selected-answer');
                    $(this).find('input[type="radio"]').prop('checked', true);
                });

                $('button[name="submit_answer"]').hover(
                    function () {
                        $(this).addClass('shadow-sm').css('opacity', '0.9');
                    },
                    function () {
                        $(this).removeClass('shadow-sm').css('opacity', '1');
                    }
                );
            });
        </script>
    </body>

</html>