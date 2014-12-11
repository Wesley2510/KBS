<!--
Sander de Wilde
-->
<html>
    <head>
        <title>Upload formulier</title>
    </head>
    <body>
        <h2>Bestand uploaden:</h2>
        Selecteer een bestand om te uploaden: <br />
    </body>
    <form action="bestanduploadscript.php" method="post"
          enctype="multipart/form-data">
        <input type="file" name="fileToUpload" size="50" />
        <br />
        <input type="submit" value="Upload Bestand" />
    </form>
</body>
</html>
