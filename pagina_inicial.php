<?php
session_start();

// Verifica se o usuário está logado
if(!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true){
    // Se não estiver logado, redireciona para a página de login
    header("Location: index.html");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Netflix - Página Inicial</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="./css/style.css">
        <link rel="shortcut icon" href="./imagens/netflix-icon.jpg" type="image/x-icon">
       
    </head>
   

    <body class="inicial">

        <!-- Botão Hamburger -->
         <nav class="navbar">
            <div class="menu-icon">
                <i class="fas fa-bars"></i>
            </div>

         </nav>

        <!--Menu oculto que aparece ao clicar no botão -->
        <div class="menu-content">
            <ul>
                <li><a href="index.html">Sair</a></li>
            </ul>
        </div>

        <script src="./js/script.js"></script>

       

        
    </body>
</html>