<?php

session_start();
require 'config.php';

if(!empty($_POST['nome']) && !empty($_POST['email'])) { /*Verificando se o campo nome e email não estão vazios*/
    $nome = addslashes($_POST['nome']);    /*armazenando na variavel*/
    $email = addslashes($_POST['email']);
    $id_pai = $_SESSION['mmnlogin'];       /*nesse passo estamos dizendo quem vai ser id pai do novo cadastro*/
    $senha = md5($email);
    
    $sql = $pdo->prepare("SELECT * FROM tb_usuarios WHERE email= :email"); /*Verificando se existe cadastro*/
    $sql->bindValue(":email" ,$email);
    $sql->execute();
    
    if($sql->rowCount() == 0) { /*Verificando se ha algum email se for == 0 cadastrar*/
        
        $sql = $pdo->prepare("INSERT INTO tb_usuarios (id_pai, nome, email, senha) VALUES (:id_pai, :nome, :email, :senha)");
        $sql->bindValue(":id_pai", $id_pai);
        $sql->bindValue(":nome", $nome);
        $sql->bindValue(":email", $email);
        $sql->bindValue(":senha", $senha);
        $sql->execute();
        
        header("Location:index.php");
        exit;
        
    } else {
        
    echo "Já existe este usuário cadastrado !";
}

}
?>

<h1>Cadastrar novo usuário</h1>
<form method="POST">
    Nome:<br/>
    <input type="text" name="nome"/> <br/> <br/>
    E-mail:<br/>
    <input type="email" name="email" /><br/><br/>
    <input type="submit" value="Cadastrar" />
    
</form> <!-- Após os dados serem inseridos, serão enviados para a mesma página-->