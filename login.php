<?php
session_start();
require 'config.php';

/*Apos enviar os dados do form, será direcionado para a mesma página.
Agora será feita a verificação do form*/

if(!empty($_POST['email'])) {
    $email = addslashes($_POST['email']); /*armazenando as informações dos campos digitados*/
    $senha = md5(addslashes($_POST['senha']));
    
    $sql = $pdo->prepare("SELECT * FROM tb_usuarios WHERE email = :email AND senha = :senha");
   
    $sql->bindValue(":email", $email); /*substituindo :email pela variavel*/
    $sql->bindValue(":senha", $senha); 
    $sql->execute();
    
    if($sql->rowCount() > 0) { /*Verificando se há dados no banco*/
        $sql = $sql->fetch(); /*armazenando os dados se estiverem corretos*/
        
        $_SESSION['mmnlogin'] = $sql['id']; /*salvando o id na sessão.
        E direcionar para pagina de index
        */
        
        header("Location: index.php"); 
        exit;
        
        
    } else{
        echo "Usuário e/ou Senha errados!";
    }
}

?>




<form method="POST">
    
    E-mail:<br/>
    <input type="email" name="email" /><br/><br/>
    
    Senha:<br/>
    <input type="password" name="senha" /><br/><br/>
    
    <input type="submit" value="Entrar"/>
    <a href="sair.php">Sair</a>
     
    
    
</form>



