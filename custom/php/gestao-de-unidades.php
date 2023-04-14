<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../custom/css/ag.css">
</head>

<body>
    <?php


require_once('common.php');//buscar funçoes do ficheiro php
global $link;

verificaLogIn();

$dados_validos = true;
$errors = array();

$query = "SELECT name FROM subitem_unit_type";
$result = mysqli_query($link,$query);

if(!isset($_POST["validade"]) || $_POST["validade"] == ""){
    $query3="SELECT subitem_unit_type.id AS id, subitem_unit_type.name AS unidade, GROUP_CONCAT(' ', subitem.name, '(', item.name, ')') AS subitens
FROM subitem JOIN subitem_unit_type ON subitem_unit_type.id = subitem.unit_type_id JOIN item ON subitem.item_id = item.id GROUP BY subitem_unit_type.id;";
    $result3 = mysqli_query($link, $query3);

    if(mysqli_num_rows($result) == 0){
        echo"<p>Não há tipos de unidades</p>";
    }
    else {
        echo '<table class="mytable" style="top: 8.203125vw;">
                <tbody>
      <tr>
         <th><b>Id</b></th>
         <th><b>Unidade</b></th>
         <th><b>Subitem</b></th>
      </tr>';
        while ($row = mysqli_fetch_assoc($result3)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['unidade'] . "</td>";
            echo "<td>" . $row['subitens'] . "</td>";
            echo "</tr>";
        }
        echo '</tbody>';
        echo '</table>';
    }

    if($dados_validos == true) {
        echo '<div class="cont">
    <h3>Gestão de unidades - Introdução</h3>
    <form action="" method="POST">
        <div class="contentbox">
            <input type="text" name="nome_subitem_unit">
            <span>Nome da unidade do subitem:</span>
        </div>
        <div class="contentbox">
            <input type="submit" value="Submit">
            <input type="hidden" name="validade" value="inserir">
        </div>
    </form>
</div>';
    }
}

if(isset($_POST["validade"]) && $_POST["validade"]=="inserir"){
    if($_SERVER["REQUEST_METHOD"]=="POST"){

        $nome_subitem_unit = "";
        $query2 = "SELECT name FROM subitem_unit_type WHERE name = '$nome_subitem_unit' ";
        $result2 = mysqli_query($link,$query2);

        if(empty($_POST["nome_subitem_unit"])){
            $errors["n"] = "* É necessario o preenchimento do nome";
            $dados_validos = false;
        }
        else if(!preg_match("/^[a-zA-Z ]*$/", $nome_subitem_unit)) {
            $errors["n"] = "* Só letras e espaços em branco";
            $dados_validos = false;
        }else{
            $nome_subitem_unit = text_Input($_POST['nome_subitem_unit']);
        }

        if($dados_validos == false) {
            echo '
            <div class="cont">
    <h3>Gestão de unidades - Inserçao</h3>
    <form action="" method="POST">
        <div class="contentbox">
            <input type="text" name="nome_subitem_unit">
            <span>Nome da unidade do subitem:</span>
        </div>
        <span class="error" style="position: relative !important; bottom: 5vh !important;">'. $errors["n"] . '</span>
        <div class="contentbox">
            <input type="submit" value="Submit">
            <input type="hidden" name="validade" value="inserir">
        </div>
    </form>
</div>';
voltarAtras();
        }
    }
        if ($dados_validos == true) {
            $query4 = "INSERT INTO subitem_unit_type(name) VALUES('$nome_subitem_unit');";
            if(mysqli_query($link,$query4)){
                echo "<script> alert('Inseriu os dados de novo tipo de unidade com sucesso.')</script>";
                voltarAtras();
            }
            else{
                echo "Error: " . $query4 . "<br>" . mysqli_error($link);
            }
        }
    
    if(!$link){
        die("Connection failed: ".mysqli_connect_error());
    }
    
    mysqli_close($link);
}
?>
</body>

</html>