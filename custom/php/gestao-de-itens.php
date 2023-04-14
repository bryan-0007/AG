<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../custom/css/ag.css">

</head>

<body>
    <?php
require_once('common.php');
global $link;
global $current_page;

$capacidade="manage_items";
verificaLogIn();
verificaPermissoes($capacidade);

$dados_validos=true;
$nome_do_item=$tipo_de_item=$estado=$nome_do_itemR=$tipo_de_itemR=$estadoR="";

if( !isset($_POST["validade"]) || $_POST["validade"]==""){
    $query_ver_items="SELECT * FROM item";
    $result_query_ver_items=mysqli_query($link,$query_ver_items);
    $numero_de_rows=mysqli_num_rows($result_query_ver_items);
    if($numero_de_rows==0){
        echo"Não há itens";
    }
    else{
        //fazer aqui a tabela fazer ao final do dia sem falta para nao ficar atrasado
        echo"<table class='mytable'>";
                echo"<tr>";
                    echo"<th>tipo de item</th>";
                    echo"<th>id</th>";
                    echo"<th>nome do item</th>";
                    echo"<th>estado</th>";
                    echo"<th>ação</th>";
                echo"<tr>";
        $query_item_type="SELECT * FROM item_type ORDER BY name ASC";
        $result_query_item_type=mysqli_query($link,$query_item_type);
        while($cada_linha_item_type=mysqli_fetch_assoc($result_query_item_type)){
            $query_item="SELECT * FROM item WHERE item_type_id=".$cada_linha_item_type['id']." ORDER BY name ASC";
            $resul_query_item=mysqli_query($link,$query_item);
            $query_n_l_item=mysqli_num_rows($resul_query_item);
            echo"<tr>";
            echo"<td rowspan='$query_n_l_item'>".$cada_linha_item_type['name']." </td>";
            while($cada_linha_item=mysqli_fetch_assoc($resul_query_item)){
                    echo"<td>".$cada_linha_item['id']." </td>";
                    echo"<td>".$cada_linha_item['name']." </td>";
                    echo"<td>".$cada_linha_item['state']." </td>";
                    if($cada_linha_item['state']=="inactive"){
                        echo"<td>[editar] [ativar] </td>";
                    }
                    else{
                        echo"<td>[editar] [desativar] </td>";
                    }
                echo"</tr>";
            }

        }

        echo"</table>";

///////////////////////////////////////////FORMULARIO//////////////////////////////////////////////////////////////
        $query_ver_item_type="SELECT id,name FROM item_type";
        $result_query=mysqli_query($link,$query_ver_item_type);
        echo "<div class='cont'>";
        
        echo"<h3> Gestão de itens - introdução </h3>";

        echo"<form method='post'>";
            echo "<div class='contentbox'>
                <input type='text' name='nome'>
                <span>Nome:</span>
                </div>";
                echo"<span style='font-size: 0.96vw'>Tipo:</span><div class='contentbox radio-wrapper'>";
                while($ver_row_item_type=mysqli_fetch_assoc($result_query)){
                    echo"<input type='radio' id='".$ver_row_item_type['name']."' name='tipo_de_item' value=".$ver_row_item_type['id'].">"."<label for='".$ver_row_item_type['name']."'>".$ver_row_item_type['name']."</label>";
                }
            echo "</div>";
            echo"<span style='font-size: 0.96vw'>Estado:</span><div class='contentbox radio-wrapper'>";
            echo"<input type='radio' id='ativo' name='state' value='active'><label for='ativo'>ativo</label>";
            echo"<input type='radio' id='inativo' name='state' value='inactive'><label for='inativo'>inativo</label>";
            echo "</div>";
                echo"<div class='contentbox'><input type='submit'value='Submit'><input type='hidden'name='validade'value='inserir'></div></form></div>";
    }
}

if( isset($_POST["validade"]) && $_POST["validade"]=="inserir"){
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        echo"<h3>Gestão de itens - inserção</h3>";
        if(empty($_POST["nome"])){//FALTA MAIS ERROS POSSIVEIS AQUI NESTE FORMULARIO
            $nome_do_itemR="É obrigatorio o preenchimento do nome";
            $dados_validos=false;
        }
        else{
            $nome_do_item=test_input($_POST["nome"]);

        }

        if(empty($_POST["tipo_de_item"])){
            $tipo_de_itemR="É obrigatorio o preenchimento do tipo de item";
            $dados_validos=false;
        }
        else{
            $tipo_de_item=test_input($_POST["tipo_de_item"]);

        }
        if(empty($_POST["state"])){
            $estadoR="É obrigatorio o preenchimento do estado";
            $dados_validos=false;
        }
        else{
            $estado=test_input($_POST["state"]);

        }


        if($dados_validos==false){
            echo "<div class='error'>";
            if(!empty($nome_do_itemR)){
                echo $nome_do_itemR;
            }
            echo"<br>";
            if(!empty($tipo_de_itemR)){
                echo $tipo_de_itemR;
            }
            echo"<br>";
            if(!empty($estadoR)){
                echo $estadoR;
            }
            echo"</div>";
            voltarAtras();

        }

        else{

            $query_de_inserçao="INSERT INTO item(name,item_type_id,state) VALUES('".$nome_do_item."','".$tipo_de_item."','".$estado."')";
            if(!$link){
                die("Connection failed: ".mysqli_connect_error());
            }
            if(mysqli_query($link,$query_de_inserçao)){
                echo"Inseriu os dados de registo com sucesso";
                echo"<a href=".$current_page."> Continuar</a>";
            }
            else{
                echo "Error: " . $query_de_inserçao . "<br>" . mysqli_error($link);
            }

        }
    }
    mysqli_close($link);
}
?>

</body>

</html>