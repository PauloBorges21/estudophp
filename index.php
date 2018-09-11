<?php 
    session_start();
    
    require 'config.php';
    require 'function.php';

if(empty($_SESSION['mmnlogin'])){ /*Se a sessão não existir ou tiver vazia redireciona para login.php*/
    
    header("Location: login.php");
    exit;
    
}

$id = $_SESSION['mmnlogin'];      /*essa parte vamos apresentar o nome do usuário logado.
                                Apartir do id do usuário vamos extrair essa informação */
//$sql = $pdo->prepare("SELECT * FROM tb_usuarios WHERE id = :id");

 $sql = $pdo->prepare("SELECT 
    tb_usuarios.id, tb_usuarios.id_pai, tb_usuarios.patente, tb_usuarios.nome, tb_patente.nome as p_nome
    FROM tb_usuarios 
    LEFT JOIN tb_patente ON tb_patente.id = tb_usuarios.patente
    WHERE tb_usuarios.id = :id");
$sql->bindValue(":id", $id);  /* o SELECT esta puxando nome através do id*/
$sql->execute();


/*Verificar se existe ou achou o usuário*/ 
if($sql->rowCount() > 0){ 
    $sql = $sql->fetch(); /*provavelmente o fetch armazena a consulta em um array */
    $nome = $sql['nome'];
    $p_nome =$sql['p_nome'];
    } else {
    header("Location: login.php");
    exit;
    
}

$lista = listar($id,$limite); //Para listagem dos usuários, criaremos uma variavel para armazenar um array.Vamos preencher o Array com os cadastros.



// todo usuário ele herda o id-pai de quem cadastrou, estamos trazendo os usuários relacionados.


    ?>
    
    <h1>Sistema de Marketing Multinivel</h1>
    <h2>Usuário Logado: <?php echo $nome.' ('.$p_nome.')'; ?></h2>
</br>
<a href="cadastro.php">Cadastrar Novo Usuário</a> </br><!-- Após logado, podemos inserir um novo usuário-->
<a href="sair.php">Sair</a>

<hr/>



<h4>Lista de Cadastros</h4>

<?php exibir($lista); ?> <!--chamando a função-->

