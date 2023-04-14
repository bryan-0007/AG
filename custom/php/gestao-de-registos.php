<!DOCTYPE html>
<html>

<head>

    <link rel="stylesheet" href="../custom/css/ag.css">

</head>

<body>
    <?php require_once('common.php'); //buscar funçoes do ficheiro php
    global $link;
    global $current_page;

    $capacidade="manage_records";
    verificaPermissoes($capacidade);

    verificaLogIn();

    $query="SELECT * FROM child";
    $result=mysqli_query($link, $query);
    $result2=mysqli_num_rows($result);

    $nome=$data_nas=$nome_enc=$email=$telefone_enc=$nomeRR=$data_nasRR=$nome_encRR=$emailRR=$telefone_encRR="";
    $dados_validos=true;

    if($result2==0) {
        //MELHORAR QUERY PARA NUMERO DE ROWS EM VEZ DE CONTAR OS NOMES
        echo"<h3> Nao há crianças</h3>";
    }

    if( !isset($_POST["validade"]) || $_POST["validade"]=="") {
        $query_crianças="SELECT DISTINCT child.id AS id, child.name AS nome, child.birth_date AS data, child.tutor_name AS tutorn, child.tutor_phone AS phone, child.tutor_email AS email 
FROM child,
        value,
        subitem,
        item WHERE child.id=value.child_id AND value.subitem_id=subitem.id AND subitem.item_id=item.id GROUP BY child.id,
        item.name ORDER BY child.id,
        item.name;
        ";
$result_crianças=mysqli_query($link, $query_crianças);

        $query_registos="SELECT child.id AS id, child.name AS nome, child.birth_date AS data, child.tutor_name AS tutorn, child.tutor_phone AS phone, child.tutor_email AS email, CONCAT(UPPER(item.name), ':', GROUP_CONCAT(' ', subitem.name, '(', value.value, ')' SEPARATOR ';')) as registos 
FROM child,
        value,
        subitem,
        item WHERE child.id=value.child_id AND value.subitem_id=subitem.id AND subitem.item_id=item.id GROUP BY child.id,
        item.name ORDER BY child.id,
        item.name;
        ";
$result_registos=mysqli_query($link, $query_registos);

        echo'<table class="mytable">
<tr><th>Nome</th><th>Data de Nascimento</th><th>Enc. de Educação</th><th>Telefone do Enc.</th><th>e-mail</th><th>registos</th></tr>';
$a="";

        while($row_crianças=mysqli_fetch_assoc($result_crianças)) {
            echo"<tr>";
            echo"<td>".$row_crianças['nome']."</td>";
            echo"<td>".$row_crianças['data']."</td>";
            echo"<td>".$row_crianças['tutorn']."</td>";
            echo"<td>".$row_crianças['phone']."</td>";
            echo"<td>".$row_crianças['email']."</td>";
            echo"<td>";

            while($row_registos=mysqli_fetch_assoc($result_registos)) {
                if($row_registos['id']==$row_crianças['id']) {
                    if( !empty($a)) {
                        echo $a;
                        echo "<br>";
                    }

                    echo $row_registos['registos'];
                    echo "<br>";
                }

                else {
                    $a=$row_registos['registos'];
                    break;
                }
            }

            echo"</td>";
            echo"</tr>";
        }

        echo'</table>';



        ////////////////////////////////////FORMULARIO AQUI ///////////////////////////////////
        echo "<div class='cont'>";
        echo"<h3>Dados de registo-Introdução</h3>";
        echo "Introduza os dados pessoais da criança";
        echo'<form method ="post">
<div class="contentbox"><input type="text"name="nome"><span>Nome: </span></div><div class="contentbox"><input type="text"name="data_de_nascimento"><span>Data De Nascimento: </span></div><div class="contentbox"><input type="text"name="nome_enc_de_educacao"><span>Enc de Educação:</span></div><div class="contentbox"><input type="number"name="telefone_enc_educacao"><span>Telefone do Enc: </span></div><div class="contentbox"><input type="email"name="email"><span>Email: </span></div><div class="contentbox"><input type="submit"value="Submit"><input type="hidden" name="validade"
        value="validar"></div></form></div>';

    }

    if(isset($_POST["validade"]) && $_POST["validade"]=="validar") {
        if($_SERVER["REQUEST_METHOD"]=="POST") {
            echo "<div style='width: 85vw;'></div>";
            echo "<div class='cont'>";
            echo"<h3>Dados de registo-Validação</h3>";

            if(empty($_POST["nome"])) {
                $nomeRR="É necessario o preenchimento do nome";
                $dados_validos=false;
            }

            else {
                $nome=test_input($_POST["nome"]);

            }

            if(empty($_POST["data_de_nascimento"])) {
                $data_nasRR="É necessario o preenchimento da data de nascimento";
                $dados_validos=false;
            }

            else {
                $data_nas=test_input($_POST["data_de_nascimento"]);
            }

            if(empty($_POST["nome_enc_de_educacao"])) {
                $nome_encRR="É necessario o preenchimento do nome do enc de edecucaçao";
                $dados_validos=false;
            }

            else {
                $nome_enc=test_input($_POST["nome_enc_de_educacao"]);
            }

            if(empty($_POST["telefone_enc_educacao"])) {
                $telefone_encRR="É necessario o preenchimento do numero de telefone do enc de educaçao";
                $dados_validos=false;
            }

            else {
                $telefone_enc=test_input($_POST["telefone_enc_educacao"]);
            }

            if( !empty($_POST["email"])) {
                if ( !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    //w3shchools URL/email form
                    $emailRR="Email invalido";
                    $dados_validos=false;
                }

                else {
                    $email=test_input($_POST["email"]);
                }
            }

            if($dados_validos==true) {
                echo'<form method="post">';
                echo "
Estamos prestes a inserir os dados abaixo na base de dados. Confirma que os dados estão correctos e pretende submeter os mesmos ? <br><br>";
echo '<div class="contentbox">
<strong>Nome: </strong>' . '' . $nome . '
                    </div> <div class="contentbox"> <strong>Data De Nascimento: </strong>' . '' . $data_nas . '
                    </div> <div class="contentbox"> <strong>Enc de Educação: </strong>' . '' . $nome_enc . '
                    </div> <div class="contentbox"> <strong>Telefone do Enc: </strong>' . '' . $telefone_enc . '

                    </div>';
if( !empty($email)) {
                    echo"<div class='contentbox'>
<strong>Email: </strong>" . ' ' . $email;
echo '</div>';

                }


                echo'
<input type="hidden"name="nome"value=';echo"$nome";echo'><input type="hidden"name="data_de_nascimento"value='.$data_nas.'><input type="hidden"name="nome_enc_de_educacao"value='.$nome_enc.'><input type="hidden"name="telefone_enc_educacao"value='.$telefone_enc.'><input type="hidden"name="email"value='.$email.'><div class="contentbox"><input type="submit"value="Submit"><input type="hidden"name="validade"value="inserir"></div></form></div>';
voltarAtras();
            }

            else {
                echo "<div class='error'>";

                if( !empty($nomeRR)) {
                    echo $nomeRR;
                }

                echo"<br>";

                if( !empty($data_nasRR)) {
                    echo $data_nasRR;
                }

                echo"<br>";

                if( !empty($nome_encRR)) {
                    echo $nome_encRR;
                }

                echo"<br>";

                if( !empty($telefone_encRR)) {
                    echo $telefone_encRR;
                }

                echo"<br>";

                if( !empty($emailRR)) {
                    echo $emailRR;
                }

                echo"</div>";
                voltarAtras();
            }
        }
    }

    if(isset($_POST["validade"]) && $_POST["validade"]=="inserir") {
        if($_SERVER["REQUEST_METHOD"]=="POST") {

            //se nao meter isto dá erro na inserção de valores
            $nome=test_input($_POST["nome"]);
            $data_nas=test_input($_POST["data_de_nascimento"]);
            $nome_enc=test_input($_POST["nome_enc_de_educacao"]);
            $telefone_enc=test_input($_POST["telefone_enc_educacao"]);
            $email=test_input($_POST["email"]);


            echo"<h3>Dados de registo-Inserçao </h3>";



            $query2="INSERT INTO child(name,birth_date,tutor_name,tutor_phone,tutor_email) VALUES('.$nome.','.$data_nas.','.$nome_enc.','.$telefone_enc.','.$email.')";

            if( !$link) {
                die("Connection failed: ".mysqli_connect_error());
            }

            if(mysqli_query($link, $query2)) {
                echo"Inseriu os dados de novo item com sucesso";
                echo"<a href=".$current_page."> Continuar</a>";
            }

            else {
                echo "Error: ". $query2 . "<br>". mysqli_error($link);
            }
        }

        mysqli_close($link);
    }

    ?></body>

</html>