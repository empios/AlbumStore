<!doctype html>
<html lang="en">

<head>
    <title>AlbumStore</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
        integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"
        integrity="sha512-aUhL2xOCrpLEuGD5f6tgHbLYEXRpYZ8G5yD+WlFrXrPy2IrWBlu6bih5C9H6qGsgqnU6mgx6KtU8TreHpASprw=="
        crossorigin="anonymous"></script>
    <script>
    $(function() {
        $("#datepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
    </script>
    <script>

    function confirmationDelete(anchor) {
        var conf = confirm('Czy chcesz usunąć rekord?');
        if (conf)
            window.location = anchor.attr("href");
    }
    </script>

</head>

<body>

    <?php  require_once 'dbController.php';?>
    <?php 
      $mysqlConnection = new mysqli('localhost', 'root', '', 'interview') or die(mysqli_error($mysqlConnection));
      $resultQuery = $mysqlConnection -> query("SELECT * from wykonawca") or die ($mysqlConnection -> error);
      $resultQuery2 = $mysqlConnection -> query("SELECT * from album") or die ($mysqlConnection -> error);
      $resultQuery3 = $mysqlConnection -> query("SELECT utwor.utwor_id as 'id', utwor.nazwa AS 'utwor',
        wykonawca.nazwa AS 'wykonawca', album.nazwa AS 'album', album.rok_wydania 
        FROM wykonawca inner JOIN album ON album.wykonawca_id = wykonawca.wykonawca_id 
        inner JOIN utwor ON utwor.album_id = album.album_id") or die ($mysqlConnection -> error);
      $counter = 0;
    ?>


    <div class="container">
        <div class="row justify-content-center">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Utwór</th>
                        <th>Wykonawca</th>
                        <th>Album</th>
                        <th>Rok Wydania</th>
                        <th colspan="2">Opcje</th>
                    </tr>
                </thead>
                <?php 
                while ($row = $resultQuery3->fetch_assoc()): ?>
                <tr>
                    <td><?php echo ($counter += 1)?></td>
                    <td><?php echo $row['utwor'];?></td>
                    <td><?php echo $row['wykonawca'];?></td>
                    <td><?php echo $row['album'];?></td>
                    <td><?php echo $row['rok_wydania'];?></td>
                    <td>
                        <input type="hidden" data-value="<?php echo $row['id'];?>" id="editId<?php echo $counter?>" />
                        <button value="<?php echo $counter?>" type="button"
                            class="editbutton btn btn-primary">Edytuj</button>
                        <a onclick='javascript:confirmationDelete($(this));return false;'
                            href="dbController.php?delete=<?php echo $row['id'];?>" class="btn btn-danger">Usuń</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
    </div>




    <div class="container">
        <form class="form-group" action="dbController.php" method="POST">
            <input type="text" class="form-control" name="albumName" placeholder="Nazwa albumu">
            <select name="artists" class="form-control">
                <?php 
                while ($artist = $resultQuery -> fetch_assoc()):
                ?>
                <option name="artist" value="<?php echo $artist['nazwa']?>"><?php echo $artist['nazwa']?></option>
                <?php endwhile; ?>
            </select>

            <input type="text" id="datepicker" name="year">

            <button type="submit" class="btn btn-primary" name="savealbum">Dodaj album</button>
        </form>
    </div>

    <div class="container">
        <form class="form-group" action="dbController.php" method="POST">
            <input type="text" class="form-control" name="songName" placeholder="Nazwa utworu">
            <select name="songs" class="form-control">
                <?php 
            while ($album = $resultQuery2 -> fetch_assoc()):
            ?>
                <option name="song" value="<?php echo $album['nazwa']?>"><?php echo $album['nazwa']?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit" class="btn btn-primary" name="savesong">Dodaj Utwór</button>
        </form>
    </div>

    <div class="container">
        <form class="form-group" action="dbController.php" method="POST">
            <input type="text" class="form-control" name="artistName" placeholder="Nazwa wykonawcy">
            <button type="submit" class="btn btn-primary" name="saveartist">Dodaj wykonawcę</button>
        </form>
    </div>




    <script>
    $(document).ready(function() {
        $('.editbutton').click(function() {
            location.reload();
            let clickedButton = $(this).attr('value');
            let idObj = document.querySelector('#editId' + clickedButton);
            let id = idObj.getAttribute("data-value");
            $.cookie('id', id);
            setTimeout(function(){
                $("#editModal").modal();
            }, 200);
               

        
        });
    });
    </script>
    

    <script>
        function reload(){
            location.reload();
        }
    </script>

    <div class="modal fade" id="editModal" role="dialog" tabindex="-1" role="dialog">
    <?php 
    $mysqlConnection = new mysqli('localhost', 'root', '', 'interview') or die(mysqli_error($mysqlConnection));
    $resultQuery = $mysqlConnection -> query("SELECT * from wykonawca") or die ($mysqlConnection -> error);
    $resultQuery2 = $mysqlConnection -> query("SELECT * from album") or die ($mysqlConnection -> error);
    $idCookie = $_COOKIE["id"];
    $resultQuery3 = $mysqlConnection->query("SELECT utwor.utwor_id as 'id', utwor.nazwa AS 'utwor',
    wykonawca.nazwa AS 'wykonawca', album.nazwa AS 'album', album.rok_wydania 
    FROM wykonawca inner JOIN album ON album.wykonawca_id = wykonawca.wykonawca_id 
    inner JOIN utwor ON utwor.album_id = album.album_id
    WHERE utwor.utwor_id = $idCookie") or die($mysqlConnection->error);
    $resultLast = $resultQuery3 -> fetch_assoc();
    ?>

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edytuj rekord</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="dbController.php" method="POST">
                        <input type="hidden" value="<?php echo $idCookie; ?>" name="id" />

                        <input type="text" class="form-control" name="songName" placeholder="Nazwa utworu"
                            value="<?php echo $resultLast['utwor'];?>">
                        <br>
                        <select name="albums" class="form-control" value="<?php echo $resultLast['album'];?>">
                            <?php 
                            while ($album = $resultQuery2 -> fetch_assoc()):
                            ?>
                            <option name="album" value="<?php echo $album['nazwa'];?>"><?php echo $album['nazwa'];?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                        <br>
                        <select name="artists" class="form-control" value="<?php echo $resultLast['wykonawca'];?>">
                            <?php 
                            while ($artist = $resultQuery -> fetch_assoc()):
                            ?>
                            <option name="artist" value="<?php echo $artist['nazwa'];?>">
                                <?php echo $artist['nazwa'];?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                        <br>
                        <link rel="stylesheet" href="css/datepicker.css">
                        <script src="js/datepicker.pl-PL"></script>
                        <script>
                        $(function() {
                            $('[data-toggle="datepicker"]').datepicker({
                                autoHide: true,
                                zIndex: 2048,
                            });
                        });
                        </script>

                        <input type="text" class="form-control" value="<?php echo $resultLast['rok_wydania']?>"
                            data-toggle="datepicker" name="year">

                        <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-dismiss="modal">Zamknij</button>
                    <button type="button" onclick="reload()" name="editSave"
                        class="btn btn-primary">Edytuj</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>

</body>

</html>