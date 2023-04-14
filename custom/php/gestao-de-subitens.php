<!DOCTYPE html>
<HTML>
</head>

<body>
    <?php
require_once('common.php');// vai buscar a o ficheiro common
echo"<style>";
include"custom/css/ag.css";
echo"</style>";
global $link;
global $current_page;
$capability= "manage_subitems";
verificaLogIn();
verificaPermissoes($capability);

$queryVerSetemSubitems="SELECT * FROM subitem";
$ResultadoqueryVerSetemSubitems=mysqli_query($link,$queryVerSetemSubitems);
$NumeroResultadoqueryVerSetemSubitems=mysqli_num_rows($ResultadoqueryVerSetemSubitems);

$nomeDoSubitem1=$valorDoSubitem1=$Item=$TipoDeformulario1=$tipDeUnidade1=$ordemDoCampo1=$obrigatorio1="";
$ErrnomeDoSubitem1=$ErrvalorDoSubitem1=$ErrItem=$ErrTipoDeformulario1=$ErrtipDeUnidade1=$ErrordemDoCampo1=$Errobrigatorio1="";
$validacaoDoForm=false;

    if (!isset($_POST["validar"]) || $_POST["validar"] == '') {

        if ($NumeroResultadoqueryVerSetemSubitems == 0) {
            echo "Não há subitens especificados";
        } else {

            echo "<table class='mytable'>";
            echo '<thead class="thead-dark">';
            echo "<tr>";
            echo "<th> item </th>";
            echo "<th> id </th>";
            echo "<th> subitem </th>";
            echo "<th> tipo de valor </th>";
            echo "<th> nome do campo no formulário </th>";
            echo "<th> tipo do campo no formulário </th>";
            echo "<th> tipo de unidade </th>";
            echo "<th> ordem do campo no formulário </th>";
            echo "<th> obrigatório </th>";
            echo "<th> estado </th>";
            echo "<th> ação </th>";
            echo "</tr>";
            echo '</thead>';

            $VaiBuscarItems = "SELECT id, name FROM item ORDER BY name ASC";
            $resultadoVaiBuscarItems = mysqli_query($link, $VaiBuscarItems);
            while ($BuscarItemsLinha = mysqli_fetch_assoc($resultadoVaiBuscarItems)) {
                //ver se há subitems associados ao respetivo item
                $BuscarSubitems = "SELECT id FROM  subitem WHERE item_id='" . $BuscarItemsLinha['id'] . "'";
                $resultadoBuscarSUbitem = mysqli_query($link, $BuscarSubitems);
                $linhaItemSubitems = 0;
                $linhaItemSubitems = mysqli_num_rows($resultadoBuscarSUbitem);
                echo "<tbody>";
                echo "<tr>";
                if ($linhaItemSubitems == 0) {
                    //se nao tiver subitem associados aos items, irá criar uma linha mostrando
                    echo "<td>" . $BuscarItemsLinha['name'] . "</td>";
                    echo "<td style='width: 30px' colspan='10' class='central_'>este item não tem subitens</td>";

                } else {
                    $valoresSubitems = "SELECT id , name , value_type, form_field_name, form_field_type, form_field_order, mandatory, state, unit_type_id FROM subitem WHERE  item_id='" . $BuscarItemsLinha['id'] . "' ORDER BY name ASC";
                    $resultadoValoresSubitems = mysqli_query($link, $valoresSubitems);
                    echo "<td rowspan='" . $linhaItemSubitems . "' >" . $BuscarItemsLinha['name'] . "</td>";
                    while ($subitemsParaLinha = mysqli_fetch_assoc($resultadoValoresSubitems)) {
                        echo "
                <td class='table-center'>" . $subitemsParaLinha['id'] . "</td>
                <td>" . $subitemsParaLinha['name'] . "</td>
                <td>" . $subitemsParaLinha['value_type'] . "</td>
                <td>" . $subitemsParaLinha['form_field_name'] . "</td>
                <td>" . $subitemsParaLinha['form_field_type'] . "</td>";
                        if ($subitemsParaLinha['unit_type_id'] == NULL) {
                            echo "<td class='table-center'>-</td>";
                        } else {
                            $BuscarTipoUnit = "SELECT name FROM subitem_unit_type WHERE subitem_unit_type.id= '" . $subitemsParaLinha['unit_type_id'] . "' ";
                            $ResultadoBuscarUnit = mysqli_query($link, $BuscarTipoUnit);
                            $tipoUnitLinha = mysqli_fetch_assoc($ResultadoBuscarUnit);
                            echo "<td class='table-center'>" . $tipoUnitLinha['name'] . "</td>";
                        }
                        echo "<td class='table-center'>" . $subitemsParaLinha['form_field_order'] . "</td>";
                        if ($subitemsParaLinha['mandatory'] == 1) {
                            echo "<td>sim</td>";
                        }
                        if ($subitemsParaLinha['mandatory'] == 0) {
                            echo "<td>não</td>";
                        }
                        echo "<td>" . $subitemsParaLinha['state'] . "</td>";
                        if ($subitemsParaLinha['state'] == "active") {
                            echo "<td><a href='edicao-de-dados?validacao=editarSub&tipo=subEditar&id=" . $subitemsParaLinha['id'] . "'>[editar]<a/><a href='edicao-de-dados?validacao=desativarSub&tipo=subEditar&id=" . $subitemsParaLinha['id'] . "'>[desativar]<a/><a href='edicao-de-dados?validacao=apagarSub&tipo=subEditar&id=" . $subitemsParaLinha['id'] . "'>[apagar]<a/></td>";
                        }
                        if ($subitemsParaLinha['state'] == "inactive") {
                            echo "<td><a href='edicao-de-dados?validacao=editarSub&tipo=subEditar&id=" . $subitemsParaLinha['id'] . "'>[editar]<a/><a href='edicao-de-dados?validacao=ativarSub&tipo=subEditar&id=" . $subitemsParaLinha['id'] . "'>[ativar]<a/><a href='edicao-de-dados?validacao=apagarSub&tipo=subEditar&id=" . $subitemsParaLinha['id'] . "'>[apagar]<a/></td>";
                        }
                        echo "</tr>";
                    }
                }
            }
            echo "</tbody>";
            echo "</table><br>";
            echo "<div class='cont'><h3>Gestão de subitens - Introdução</h3>";
            echo "<form method=post action=''>";
            echo "<div class='contentbox' >";
            //Campo de texto do nome do subitem
            echo "<input type='text' name='nome'> <span>*Nome do subitem: </span> <span class=error></span> ";
            //Campo de radio do tipo de valor
            echo "</div>";
            $table = 'subitem';
            $field = 'value_type';
            $vt = get_enum_values($link, $table, $field);
            $incremento = 0;
            echo "<span style='font-size: 0.96vw'>*Tipo de valor: </span> <span class=error> </span>";
            echo "<div class='contentbox radio-wrapper'>";
            while ($incremento < sizeof($vt)) {
                echo "<input type=radio id='$incremento' name='value_type' value='$vt[$incremento]'><label for='$incremento'>$vt[$incremento]</label>";
                $incremento = $incremento + 1;
            }
            echo "</div>";
            //Campo de seleção do item
            echo "<span style='font-size: 0.96vw;'>*Item: </span><span class=error> </span>
    <div class='contentbox'> <select name='item'>";
            $query_itens = "SELECT name, id FROM item";
            $itens = mysqli_query($link, $query_itens);
            echo "<option value=''> </option>";
            while ($linha1 = mysqli_fetch_assoc($itens)) {
                echo "<option value=" . $linha1['id'] . "> " . $linha1['name'] . "  </option>";
            }
            echo "</select>";
            echo "<div class='select-arrow'>";
            echo "</div>";
            echo "</div>";
            //Campo de radio do tipo do campo do formulário
            $field = 'form_field_type';
            $fft = get_enum_values($link, $table, $field);
            $incremento = 0;
            echo "<span style='font-size:0.96vw;'>*Tipo do campo do formulário: </span> <span class=error>  </span>";
            echo "<div class='contentbox radio-wrapper'>";
            while ($incremento < sizeof($fft)) {
                echo "<input type=radio id='$fft[$incremento]' name='form_field_type' value='$fft[$incremento]'><label for='$fft[$incremento]'>$fft[$incremento]</label>";
                $incremento = $incremento + 1;
            }
            echo "</div>";
            //Campo de seleção do tipo de unidade
            echo "<span style='font-size:0.96vw;'>Tipo de unidade: </span> <div class='contentbox'> <select name='unit_type'>";
            $query_subitem_unit_type = "SELECT id, name FROM subitem_unit_type";
            $subitem_unit_types = mysqli_query($link, $query_subitem_unit_type);
            echo "<option value=''> </option>";
            while ($linha2 = mysqli_fetch_assoc($subitem_unit_types)) {
                echo "
        <option value=" . $linha2['id'] . "> " . $linha2['name'] . "  </option>";
            }
            echo "</select>";
            echo "<div class='select-arrow'>";
            echo "</div>";
            echo "</div>";
            //Campo de texto da ordem do campo no formulário
            echo "<span class=error> </span> <div class='contentbox' > <input type=text name='form_field_order'> <span>*Ordem do campo no formulário: </span> </div>";
            //Campo de radio do obrigatório
            echo "<span style='font-size: 0.96vw'>*Obrigatório: </span> <span class=error> </span> <div class='contentbox radio-wrapper'>
        <input type=radio id='Sim' name='mandatory' value='1'><label for='Sim'>Sim</label>
        <input type=radio id='Nao' name='mandatory' value='0'><label for='Nao'>Não</label> ";
            echo "</div>";
            echo '<div class="contentbox">
    <input id="submeter"  type="submit" value="Submit">
    <input type="hidden" name="validade" value="inserir">
</div>';
            echo "</div>";
            echo "</form>";
            echo " <script type='text/JavaScript'>
        function myfunction(){
            let html= document.getElementById('ordemForm').value;
            let submit= document.getElementById('submeter');
            if(html == '0)'{
                alert('O valor da ordem do campo do formulário não pode ser 0');
                submit.removeAttribute('name');
                submit.removeAttribute('value');
            }
            if(isNaN(html)){ // verifica se o valor nao converte para número 
                alert('Erro Ordem do Campo no formulário: É necessario inserir números e não letras.');
                submit.removeAttribute('name');
                submit.removeAttribute('value');
            }
        }
 </script>";

        }
        if (isset($_POST["validade"]) && $_POST["validade"] == "inserir") {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                echo "<h3>Gestão de subitens - Inserção</h3>";
                echo "<br>";

                if (empty($_POST["nome"])) {
                    $ErrnomeDoSubitem1 = "É necessário preencher o nome do subitem";
                    $validacaoDoForm = true;
                } else {
                    $nomeDoSubitem1 = test_input($_POST["nome"]);
                }

                if (empty($_POST["value_type"])) {
                    $ErrvalorDoSubitem1 = "É necessário preencher o valor do subitem";
                    $validacaoDoForm = true;
                } else {
                    $valorDoSubitem1 = test_input($_POST["value_type"]);
                }

                if (empty($_POST["item"])) {
                    $ErrItem = "É necessário preencher o item";
                    $validacaoDoForm = true;
                } else {
                    $Item = test_input($_POST["item"]);
                }

                if (empty($_POST["form_field_type"])) {
                    $ErrTipoDeformulario1 = "É necessário preencher o tipo do campo do formulário";
                    $validacaoDoForm = true;
                } else {
                    $TipoDeformulario1 = test_input($_POST["form_field_type"]);
                }

                if (empty($_POST["form_field_order"])) {
                    $ErrordemDoCampo1 = "É necessário preencher a ordem do campo no formulario";
                    $validacaoDoForm = true;
                } else {
                    $ordemDoCampo1 = test_input($_POST["form_field_order"]);
                }

                if (empty($_POST["mandatory"])) {
                    $Errobrigatorio1 = "É necessário preencher o campo obrigatorio";
                    $validacaoDoForm = true;
                } else {
                    $obrigatorio1 = test_input($_POST["mandatory"]);
                }
                $tipDeUnidade1 = test_input($_POST["unit_type"]);


                if ($validacaoDoForm == true) {
                    echo '<div class="card">';
                    echo '<h5 class="card-header">Erros ao inserir os dados</h5>';
                    echo '<div class="card-body">';
                    if (!empty($ErrnomeDoSubitem1)) {
                        echo '<h5 class="card-title">Erro em nome do subitem:</h5>
                            <p class="card-text">' . $ErrnomeDoSubitem1 . '</p>';
                        echo "<hr class='style-seven'>";
                    }
                    if (!empty($ErrvalorDoSubitem1)) {
                        echo '<h5 class="card-title">Erro no valor do subitem:</h5>
                            <p class="card-text">' . $ErrvalorDoSubitem1 . '</p>';
                        echo "<hr class='style-seven'>";
                    }
                    if (!empty($ErrItem)) {
                        echo '<h5 class="card-title">Erro em item:</h5>
                            <p class="card-text">' . $ErrItem . '</p>';
                        echo "<hr class='style-seven'>";
                    }
                    if (!empty($ErrTipoDeformulario1)) {
                        echo '<h5 class="card-title">Erro em tipo do formulário:</h5>
                            <p class="card-text">' . $ErrTipoDeformulario1 . '</p>';
                        echo "<hr class='style-seven'>";
                    }
                    if (!empty($ErrordemDoCampo1)) {
                        echo '<h5 class="card-title">Erro em ordem do campo:</h5>
                            <p class="card-text">' . $ErrordemDoCampo1 . '</p>';
                        echo "<hr class='style-seven'>";
                    }
                    if (!empty($Errobrigatorio1)) {
                        echo '<h5 class="card-title">Erro em obrigatorio:</h5>
                            <p class="card-text">' . $Errobrigatorio1 . '</p>';
                        echo "<hr class='style-seven'>";
                    }
                    echo "</div>";
                    echo '</div>';

                    echo "<br>";
                    echo "<br>";
                    voltarAtras();
                }
                if (empty($ErrnomeDoSubitem1) && empty($ErrvalorDoSubitem1) && empty($ErrItem) && empty($ErrTipoDeformulario1) && empty($ErrordemDoCampo1) && empty($Errobrigatorio1)) {

                    $state = 'active';
    
                    $queryBuscarSubString = "SELECT SUBSTRING(name,1,3) AS nome FROM item WHERE id='" . $Item . "'";
                    $queryResultadoSubstring = mysqli_query($link, $queryBuscarSubString);
                    $arraySubstring = mysqli_fetch_assoc($queryResultadoSubstring);

                    $estaConcatenado = $arraySubstring["nome"]; // tenho aqui o item em substring ja
    

                    $queryBuscarUltimoID = "SELECT AUTO_INCREMENT AS auto FROM information_schema.tables WHERE table_name = 'subitem' and table_schema = 'bitnami_wordpress';";
                    $resultadoUltimoID = mysqli_query($link, $queryBuscarUltimoID);
                    $arrayUltimoID = mysqli_fetch_assoc($resultadoUltimoID);

                    $ultimoID = $arrayUltimoID["auto"]; // tenho aqui o id com auto incremente
    

                    $variavelComTudo = $estaConcatenado . "-" . $ultimoID . "-" . $nomeDoSubitem1; // tenho aqui o form_field_name
    
                    $queryInsereSubitem = "INSERT INTO subitem(name,item_id,value_type,form_field_name,form_field_type,unit_type_id,form_field_order,mandatory,state) VALUES('" . $nomeDoSubitem1 . "','" . $Item . "','" . $valorDoSubitem1 . "','" . $variavelComTudo . "','" . $TipoDeformulario1 . "','" . $tipDeUnidade1 . "','" . $ordemDoCampo1 . "','" . $obrigatorio1 . "','" . $state . "')";

                    if (!$link) {
                        die("Connection failed: " . mysqli_connect_error());
                    }
                    if (mysqli_query($link, $queryInsereSubitem)) {
                        echo '<div class="alert alert-primary" role="alert">
                            Inseriu os dados com sucesso.
                            </div>';
                        echo "<br>";
                        echo "<div class='contentbox'><a href='" . $current_page . "'><input type='button' value='Continuar'></a></div>";
                    } else {
                        echo "Error: " . $queryInsereSubitem . "<br>" . mysqli_error($link);
                    }

                }

            }
        }
    }
        mysqli_close($link);
        ?>
</body>

</HTML>