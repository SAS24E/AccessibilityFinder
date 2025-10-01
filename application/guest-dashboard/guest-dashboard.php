<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../styles.css">
        <title>Accessiblity Finder</title>
    </head>
    <body>
    <header class ="site-header">
        <h1>Accessiblity Finder</h1>
        <nav>
            <a class="site-navigation-button" href="../../index.php">Home</a>
        </nav>
    </header>
        <main>
            <h2>Guest</h2>
            <form method="post">
                Search for restaurant: <input name="restaurant">
        
                Radius: <input type="number" name="radius">
        
                <fieldset>
                    <legend>Filter by Type:</legend>
        
                    <label>
                        <input type="checkbox" name="category[]" value="italian">
                        Italian
                    </label>
        
                    <label>
                        <input type="checkbox" name="category[]" value="chinese">
                        Chinese
                    </label>
        
                    <label>
                        <input type="checkbox" name="category[]" value="mexican">
                        Mexican
                    </label>
                </fieldset>
        
                <br>
                <button type="submit">Search</button>
        
            </form>
            <h3>Here is where all the posts will show </h3>
        </main>
        <footer class="site-footer">
            <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
        </footer>          
    </body>
</html>