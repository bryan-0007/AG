<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="javaScript.js"></script>

    <?php

global $current_page; $current_page = get_site_url().'/'.basename(get_permalink());

function test_input($data) {
    //w3schools form validation
    $data=trim($data);
    $data=stripslashes($data);
    $data=htmlspecialchars($data);
    return $data;
}


function voltarAtras(){   //possibilidade de voltar atrás se o componente nao estiver no estado inicial
    echo "<script type='text/javascript'>document.write(\"<a href='javascript:history.back()' class='backLink' title='Voltar atr&aacute;s'>Voltar atr&aacute;s</a>\");</script>
<noscript>
<a href='".$_SERVER['HTTP_REFERER']."‘ class='backLink' title='Voltar atr&aacute;s'>Voltar atr&aacute;s</a>
</noscript>";
}

function verificaLogIn(){  //verifica se utilizador fez log in
    if(!is_user_logged_in()){
        echo "<h3> Nao tem autorizaçao para ter acesso a esta pagina</h3>";
        die();
    }
}

function verificaPermissoes($capacidade){  // verifica se o utilizador tem acesso a uma certa capacidade
    if(!current_user_can($capacidade)){
        echo"<h3> Nao tem autoriçao para esta capability </h3>";
        die();
    }
}

function get_enum_values($connection, $table, $column )
{
    $query = " SHOW COLUMNS FROM $table LIKE '$column' ";
    $result = mysqli_query($connection, $query );
    $row = mysqli_fetch_array($result , MYSQLI_NUM );
    #extract the values
    #the values are enclosed in single quotes
    #and separated by commas
    $regex = "/'(.*?)'/";
    preg_match_all( $regex , $row[1], $enum_array );
    $enum_fields = $enum_array[1];
    return( $enum_fields );
}

global $link; $link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

function text_Input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>