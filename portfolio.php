<?php
$pageTitle = 'Portfólio';
$extraCss  = ['css/portfolio.css'];

$projekty = [
    ['nazov' => 'Web stránka 1', 'img' => 'portfolio1.jpg'],
    ['nazov' => 'Web stránka 2', 'img' => 'portfolio2.jpg'],
    ['nazov' => 'Web stránka 3', 'img' => 'portfolio3.jpg'],
    ['nazov' => 'Web stránka 4', 'img' => 'portfolio4.jpg'],
    ['nazov' => 'Web stránka 5', 'img' => 'portfolio5.jpg'],
    ['nazov' => 'Web stránka 6', 'img' => 'portfolio6.jpg'],
    ['nazov' => 'Web stránka 7', 'img' => 'portfolio7.jpg'],
    ['nazov' => 'Web stránka 8', 'img' => 'portfolio8.jpg'],
];

$stlpceVRadku = 4;

include 'header.php';
?>

        <main>
            <section class="banner">
                <div class="container text-white">
                    <h1>Portfólio</h1>
                </div>
            </section>
            <section class="container">
                <?php foreach (array_chunk($projekty, $stlpceVRadku) as $riadok): ?>
                <div class="row">
                    <?php foreach ($riadok as $i => $projekt): ?>
                    <?php $id = array_search($projekt, $projekty) + 1; ?>
                    <div class="col-25 portfolio text-white text-center"
                         id="portfolio-<?php echo $id; ?>"
                         style="background-image: url('img/<?php echo htmlspecialchars($projekt['img']); ?>');">
                        <?php echo htmlspecialchars($projekt['nazov']); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </section>
        </main>

<?php include 'footer.php'; ?>
