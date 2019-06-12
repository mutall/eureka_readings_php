<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>

    <body>
        <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

            <a class="navbar-brand mr-1" href="/<?php echo env('SUB_FOLDER'); ?>">HOME</a>
        </nav>
        <div class="container-fluid mt-5">
            <form class="form-inline" action="/<?php echo env('SUB_FOLDER'); ?>/insert/json" method="post">
                <div class="form-group mr-5">
                    <input type="text" class="form-control form-control-lg" id="input" name="filename" placeholder="Enter File">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="UPLOAD">
                </div>
            </form>

            <ul class="list-group list-group-flush mt-5">
                <h1>LIST OF BACKUPS</h1>
                <p>Backups are located in <i>public_html/<?php echo env('SUB_FOLDER'); ?>/backup</i></p>
                <?php foreach ($data as $key): ?>
                    <li class="list-group-item list-group-item-action"><?php echo $key; ?> </li>
                    <?php endforeach; ?>
            </ul>
        </div>
        <script>
            //get the input
            const input = document.querySelector("#input");
            //set onclick to list items
            const items = document.querySelectorAll(".list-group-item");

            items.forEach(item => {
                item.onclick = () => {
                    input.value = item.innerText;
                }

            });
        </script>
    </body>
</html>