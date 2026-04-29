<?php

declare(strict_types=1);

use App\QnA;

session_start();

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/QnA.php';

$qna = new QnA(__DIR__ . '/data/qna.json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? (string) $_POST['action'] : '';

    if ($action === 'create') {
        $question = isset($_POST['question']) ? trim((string) $_POST['question']) : '';
        $answer = isset($_POST['answer']) ? trim((string) $_POST['answer']) : '';

        if ($question === '' || $answer === '') {
            $_SESSION['flash_message'] = 'Otazka aj odpoved su povinne.';
        } else {
            $created = $qna->createItem($question, $answer);
            $_SESSION['flash_message'] = $created ? 'Otazka bola pridana.' : 'Pridanie sa nepodarilo.';
        }
    }

    if ($action === 'update') {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $question = isset($_POST['question']) ? trim((string) $_POST['question']) : '';
        $answer = isset($_POST['answer']) ? trim((string) $_POST['answer']) : '';

        if ($id <= 0 || $question === '' || $answer === '') {
            $_SESSION['flash_message'] = 'Neplatne udaje pre upravu.';
        } else {
            $updated = $qna->updateItem($id, $question, $answer);
            $_SESSION['flash_message'] = $updated ? 'Otazka bola upravena.' : 'Uprava sa nepodarila.';
        }
    }

    if ($action === 'delete') {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

        if ($id <= 0) {
            $_SESSION['flash_message'] = 'Neplatne ID pre vymazanie.';
        } else {
            $deleted = $qna->deleteItem($id);
            $_SESSION['flash_message'] = $deleted ? 'Otazka bola vymazana.' : 'Vymazanie sa nepodarilo.';
        }
    }

    header('Location: qna.php');
    exit;
}

$qnaItems = $qna->getAllItems();
$flashMessage = isset($_SESSION['flash_message']) ? (string) $_SESSION['flash_message'] : '';
unset($_SESSION['flash_message']);
?>
<!DOCTYPE html>
<html lang="sk">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Moja stranka</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/accordion.css">
  <link rel="stylesheet" href="css/banner.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
  <header class="container main-header">
    <div class="logo-holder">
      <a href="index.php"><img src="img/logo.png" height="40" alt="Logo"></a>
    </div>
    <nav class="main-nav">
      <ul class="main-menu" id="main-menu">
        <li><a href="index.php">Domov</a></li>
        <li><a href="portfolio.html">Portfolio</a></li>
        <li><a href="qna.php">Q&amp;A</a></li>
        <li><a href="kontakt.html">Kontakt</a></li>
      </ul>
      <a class="hamburger" id="hamburger">
        <i class="fa fa-bars"></i>
      </a>
    </nav>
  </header>
  <main>
    <section class="banner">
      <div class="container text-white">
        <h1>Q&amp;A</h1>
      </div>
    </section>
    <section class="container">
      <div class="row">
        <div class="col-100 text-center">
          <p><strong><em>Elit culpa id mollit irure sit. Ex ut et ea esse culpa officia ea incididunt elit velit veniam qui. Mollit deserunt culpa incididunt laborum commodo in culpa.</em></strong></p>
        </div>
      </div>
    </section>
    <section class="container">
      <?php renderAccordionItems($qnaItems); ?>
      <?php if ($flashMessage !== ''): ?>
        <p><strong><?= escapeHtml($flashMessage); ?></strong></p>
      <?php endif; ?>

      <h3>Pridat otazku</h3>
      <form method="post">
        <input type="hidden" name="action" value="create">
        <p>
          <label for="create-question">Otazka</label><br>
          <input id="create-question" type="text" name="question" required>
        </p>
        <p>
          <label for="create-answer">Odpoved</label><br>
          <textarea id="create-answer" name="answer" rows="3" required></textarea>
        </p>
        <button type="submit">Pridat</button>
      </form>

      <h3>Upravit / Zmazat</h3>
      <?php foreach ($qnaItems as $item): ?>
        <?php if (!isset($item['id'])): ?>
          <?php continue; ?>
        <?php endif; ?>
        <form method="post">
          <input type="hidden" name="id" value="<?= (int) $item['id']; ?>">
          <p>
            <label>Otazka</label><br>
            <input type="text" name="question" value="<?= escapeHtml((string) ($item['question'] ?? '')); ?>" required>
          </p>
          <p>
            <label>Odpoved</label><br>
            <textarea name="answer" rows="3" required><?= escapeHtml((string) ($item['answer'] ?? '')); ?></textarea>
          </p>
          <button type="submit" name="action" value="update">Upravit</button>
          <button type="submit" name="action" value="delete">Zmazat</button>
        </form>
        <hr>
      <?php endforeach; ?>
    </section>
  </main>
  <footer class="container bg-dark text-white">
    <div class="row">
      <div class="col-25">
        <h4>Kto sme</h4>
        <p>Laboris duis ut est fugiat et reprehenderit magna labore aute.</p>
        <p>Laboris duis ut est fugiat et reprehenderit magna labore aute.</p>
        <p>Laboris duis ut est fugiat et reprehenderit magna labore aute.</p>
      </div>
      <div class="col-25 text-left">
        <h4>Kontaktujte nas</h4>
        <p><i class="fa fa-envelope" aria-hidden="true"><a href="mailto:livia.kelebercova@gmail.com"> livia.kelebercova@gmail.com</a></i></p>
        <p><i class="fa fa-phone" aria-hidden="true"><a href="tel:0909500600"> 0909500600</a></i></p>
      </div>
      <div class="col-25">
        <h4>Rychle odkazy</h4>
        <p><a href="index.php">Domov</a></p>
        <p><a href="qna.php">Q&amp;A</a></p>
        <p><a href="kontakt.html">Kontakt</a></p>
      </div>
      <div class="col-25">
        <h4>Najdete nas</h4>
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d10614.839764656655!2d18.0910518!3d48.3084298!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xba2bad032d96b960!2sFakulta%20pr%C3%ADrodn%C3%BDch%20vied%20a%20informatiky!5e0!3m2!1ssk!2ssk!4v1669307792855!5m2!1ssk!2ssk" width="300" height="150" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
    <div class="row">
      Created and designed by Livia
    </div>
  </footer>
  <script src="js/accordion.js"></script>
  <script src="js/menu.js"></script>
</body>

</html>
