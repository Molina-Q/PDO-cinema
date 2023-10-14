<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/style.css">
    <title><?= $title ?></title>
</head>
<body>
    <header>
        <nav>
            <div id="searchBar">
                <form action="" method="post">
                    <input type="search" name="" id="">
                </form>
            </div>
            <ul>
                <li><a href="index.php?action=homePage">Home</a></li>
                <li><a href="index.php?action=listFilms">List movies</a></li>
                <li><a href="index.php?action=listDirectors">Directors</a></li>
                <li><a href="index.php?action=listActors">All actors</a></li>
                <li><a href="index.php?action=everyGenres">Every genres</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <h2>mon footer</h2>

        <ul>
            <li>Contact</li>
            <li>Recruit</li>
            <li>Personal data</li>
            <li>Who are we</li>
        </ul>
        <div id="footerSocials">
            <i></i>
            <i></i>
            <i></i>
            <i></i>
        </div>
    </footer>
</body>
</html>