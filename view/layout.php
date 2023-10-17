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
                <form action="something.php" method="post">
                    <input type="search" name="search" placeholder="Rechercher un film, un acteur, etc...">
                </form>
            </div>
            <ul>
                <li><a href="index.php?action=homePage">Home</a></li>
                <li><a href="index.php?action=listFilms">Movies</a></li>
                <li><a href="index.php?action=listDirectors">Directors</a></li>
                <li><a href="index.php?action=listActors">Actors</a></li>
                <li><a href="index.php?action=listRoles">Roles</a></li>
                <li><a href="index.php?action=listGenres">Genres</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>

        <ul>
            <li>Contact</li>
            <li>|</li>
            <li>Recruit</li>
            <li>|</li>
            <li>Personal data</li>
            <li>|</li>
            <li>Who are we</li>
        </ul>
        <div id="footerSocials">
            <i>FB</i>
            <i>Tweet</i>
            <i>Linkd</i>
            <i>Insta</i>
        </div>
    </footer>
</body>
</html>