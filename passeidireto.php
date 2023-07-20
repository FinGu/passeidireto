<?php
if(isset($_POST['material'])){
    header('Location: ' . $_SERVER['PHP_SELF'] . '?material=' . $_POST['material']);
    exit;
}

if(!isset($_GET['material'])){
    die('<html>
    <head>
    </head>
    <body>
        <form method="POST">
            <label for="material">id do material: </label><br>
            <input type="text" id="material" name="material"><br>
            <button type="submit">pesquisar</button>
        </form> 
    </body> 
</html>');    
}

$id = filter_var($_GET['material'], FILTER_SANITIZE_NUMBER_INT);

if(!$id){
    die('id invalido');
}
    
$rawdata = file_get_contents("https://material-api.passeidireto.com/materials/" . $id);

if($rawdata === null){
    die('id invalido');
}

//assert($rawdata->code == 200);    
    
$data = json_decode($rawdata);

$filefingerprint = $data->SpecificDetails->FileFingerprint;

//if($filefingerprint->FileExtensionFormat->FileFormatName !== 'PDF'){
//   die('nao e um pdf');
//}

$fileurl = $filefingerprint->FileUrl;

$link_arquivos = 'https://files.passeidireto.com/' . $fileurl . '/';

$css = $link_arquivos . $fileurl . '.css';  // link do css

echo '<html><head>';

echo (include 'stub.html');

echo "<link href=\"$css\" rel=\"stylesheet\">";

echo '</head><body>
    <div class="v-content__wrap" style="flex-grow: 1; position: relative; min-width: 100%; ">
    <div style="width: 644px; height: 936.727px; padding-top: 6.88451px; padding-bottom: 6.88451px;">'; 

$npages = $filefingerprint->FileMetadata->PreviewPageCount;

for($i = 1; $i <= $npages; ++$i){ //contagem comeca no 1
    $nlink = $link_arquivos . $i . '.html';

    echo file_get_contents($nlink);
}

echo '</div></div></body></html>';
