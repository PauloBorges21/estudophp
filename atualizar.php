<?php 
    session_start();
    
    require 'config.php';
    require 'function.php';

//precisamos pegar a lista de todas as patentes e listar usuários a usuários e fazer a "arvore" 

$sql = "SELECT id FROM tb_usuarios";
$sql = $pdo->query($sql);
$usuarios = array(); //por segurança estamos declarando fora.E Teremos um array com todos os usuarios e filhos de cada um

/* VErificação se houve resultado */

if($sql->rowCount() > 0) {
    $usuarios = $sql->fetchAll();
    foreach($usuarios as $chave => $usuario) {
        $usuarios[$chave]['filhos'] = calcular_cadastros($usuario['id'], $limite); /* Estamos fazendo um foreach em todos os usuários do sistema e calcular todos os cadastros do sistema */
        
    }
 
}

$sql ="SELECT * FROM tb_patente ORDER BY min DESC";
$sql = $pdo->query($sql);
$patentes = array();

if($sql->rowCount() > 0) {
    $patentes = $sql->fetchAll();
    
}
    
    foreach($usuarios as $usuario) {
        
        foreach($patentes as $patente) {
            if(intval($usuario['filhos']) >= intval($patente['min'])) {
               
                $sql = "UPDATE tb_usuarios SET patente = :patente WHERE id = :id";
                 
                $sql = $pdo->prepare($sql);
                $sql->bindValue(":patente", $patente['id']);
                $sql->bindValue(":id", $usuario['id']);
                $sql->execute();
                
                break;           
            }
        }

}

