<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title><?= $title ?></title>
</head>
<body>
    <header>
        <nav>
            <?php require_once "./view/searchBar.php" ?>
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
        <script src="./public/app/app.js"></script>
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