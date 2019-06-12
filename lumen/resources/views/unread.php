<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <h2>TOTAL <?php echo $count; ?></h2>
            <table class="table table-striped table-sm">
                <caption>UNREAD CLIENTS</caption>
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">CODE</th>
                    <th scope="col">NAME</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($data as $key => $value) {?>
                    <tr>
                        <td><?php echo $value->code ?></td>
                        <td><?php echo $value->full_name ?></td>
                    </tr>
                    <?php }
                    ?>
                </tbody>
        </div>
    </body>
</html>