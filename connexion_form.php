<?php
session_start();
if (isset($_SESSION['login']))
{
    header("Location: acceuil.php");
    exit();
}

$erreur = "";

if (isset($_POST['connexion']))
{
    include("connexion.php");
    $idcom = connexobjet("essaiebase","myparam");

    $login = $idcom->escape_string($_POST['login']);
    $pass  = $idcom->escape_string($_POST['password']);

    $requete = "SELECT * FROM user WHERE login='$login' AND password='$password'";
    $result  = $idcom->query($requete);

    if (!$result)
    {
        $erreur = "Erreur de lecture : " . $idcom->error;
    }
    else
    {
        if ($result->num_rows == 1)
        {
            $ligne = $result->fetch_array(MYSQLI_NUM);
            $_SESSION['login']  = $ligne[4];
            $_SESSION['nom']    = $ligne[1];
            $_SESSION['prenom'] = $ligne[2];
            header("Location: acceuil.php");
            exit();
        }
        else
        {
            $erreur = "Identifiant ou mot de passe incorrect.";
        }
    }
    $result->free();
    $idcom->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Connexion — Plateforme ENEAM</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            min-height: 100vh;
            background: #00121a;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Cercles décoratifs en arrière-plan */
        body::before {
            content: '';
            position: fixed;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(30, 233, 108, 0.18) 0%, transparent 70%);
            top: -200px; right: -200px;
            border-radius: 50%;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(24, 194, 89, 0.15) 0%, transparent 70%);
            bottom: -150px; left: -150px;
            border-radius: 50%;
            pointer-events: none;
        }

        .conteneur {
            display: flex;
            width: 860px;
            min-height: 520px;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 1;
        }

        /* Panneau gauche décoratif */
        .panneau-gauche {
            width: 42%;
            background: linear-gradient(145deg, #887a0e 0%, #c2185b 45%, #e91e8c 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .panneau-gauche::before {
            content: '';
            position: absolute;
            width: 280px; height: 280px;
            border: 40px solid rgba(255, 255, 255, 0.07);
            border-radius: 50%;
            top: -80px; left: -80px;
        }
        .panneau-gauche::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            border: 30px solid rgba(255, 255, 255, 0.07);
            border-radius: 50%;
            bottom: -60px; right: -60px;
        }
        .logo-zone {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }
        .logo-rond {
            width: 56px; height: 56px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.5);
            display: flex; align-items: center; justify-content: center;
            font-size: 9px; color: #fff; font-weight: 600;
            text-align: center; line-height: 1.3;
        }
        .separateur-logo {
            width: 2px; height: 40px;
            background: rgba(255,255,255,0.3);
            border-radius: 2px;
        }
        .titre-deco {
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.3;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        .sous-titre-deco {
            color: rgba(255,255,255,0.75);
            font-size: 13px;
            margin-top: 10px;
            position: relative;
            z-index: 1;
            font-weight: 300;
        }
        .deco-points {
            display: flex;
            gap: 8px;
            margin-top: 35px;
            position: relative;
            z-index: 1;
        }
        .point { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.4); }
        .point.actif { background: #fff; width: 24px; border-radius: 4px; }

        /* Panneau droit — formulaire */
        .panneau-droit {
            flex: 1;
            background: #fff;
            padding: 50px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-entete { margin-bottom: 32px; }
        .form-entete h2 {
            font-size: 26px;
            font-weight: 700;
            color: #0a1a00;
        }
        .form-entete p {
            color: #999;
            font-size: 14px;
            margin-top: 6px;
            font-weight: 300;
        }
        .accent { color: #e91e8c; }

        .champ-groupe { margin-bottom: 18px; }
        .champ-groupe label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .champ-groupe input[type="text"],
        .champ-groupe input[type="password"] {
            width: 100%;
            padding: 13px 18px;
            border: 1.5px solid #f0d0dc;
            border-radius: 12px;
            font-size: 14px;
            color: #333333;
            background: #fff8fb;
            outline: none;
            font-family: 'Poppins', Arial, sans-serif;
            transition: border-color 0.2s, background 0.2s;
        }
        .champ-groupe input:focus {
            border-color: #e91e8c;
            background: #fff;
        }

        .btn-connexion {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #c2185b 0%, #e91e8c 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', Arial, sans-serif;
            box-shadow: 0 8px 20px rgba(233,30,140,0.35), 0 4px 0 #880e4f;
            transition: all 0.15s;
            margin-top: 8px;
        }
        .btn-connexion:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(233,30,140,0.45), 0 4px 0 #880e4f; }
        .btn-connexion:active { transform: translateY(3px); box-shadow: 0 3px 10px rgba(233,30,140,0.3), 0 1px 0 #880e4f; }

        .lien-inscrire {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #999;
        }
        .lien-inscrire a {
            color: #e91e8c;
            text-decoration: none;
            font-weight: 600;
        }
        .lien-inscrire a:hover { text-decoration: underline; }

        .msg-erreur {
            background: #ffeef4;
            border-left: 4px solid #e91e8c;
            color: #880e4f;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="conteneur">

    <!-- Panneau gauche -->
    <div class="panneau-gauche">
        <div class="logo-zone">
            <div class="logo-rond">UAC<br>logo</div>
            <div class="separateur-logo"></div>
            <div class="logo-rond">ENEAM<br>logo</div>
        </div>
        <div class="titre-deco">Bienvenue sur<br>ma plateforme</div>
        <div class="sous-titre-deco">Système de gestion commerciale<br>ENEAM — IG2 — 2025</div>
        <div class="deco-points">
            <div class="point actif"></div>
            <div class="point"></div>
            <div class="point"></div>
        </div>
    </div>

    <!-- Panneau droit -->
    <div class="panneau-droit">
        <div class="form-entete">
            <h2>Connexion <span class="accent">&#x2665;</span></h2>
            <p>Entrez vos identifiants pour accéder à la plateforme</p>
        </div>

        <?php if ($erreur != "") echo "<div class='msg-erreur'>$erreur</div>"; ?>

        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="application/x-www-form-urlencoded">
            <div class="champ-groupe">
                <label>Login</label>
                <input type="text" name="login" placeholder="Votre identifiant" maxlength="50" required />
            </div>
            <div class="champ-groupe">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="••••••••" maxlength="50" required />
            </div>
            <input type="submit" name="connexion" value="Se connecter" class="btn-connexion" />
        </form>

        <p class="lien-inscrire">Pas encore de compte ? <a href="inscrire.php">Créer un compte</a></p>
    </div>

</div>

</body>
</html>
