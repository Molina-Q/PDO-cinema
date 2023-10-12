<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
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
                <li><a href="">Currently showing</a></li>
                <li><a href="">Coming soon</a></li>
                <li><a href="">Movies by director</a></li>
                <li><a href="">Best movies</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
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