<?php
session_start();
if (!isset($_SESSION['login']))
{
    header("Location: connexion_form.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Accueil — Plateforme ENEAM</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            min-height: 100vh;
            background: #00050d;
            color: #ffffff;
            overflow-x: hidden;
        }

        /* ============ EN-TETE ============ */
        header {
            background: linear-gradient(135deg, #585d00 0%, #47880e 40%, #c2ae18 75%, #e9cb1e 100%);
            padding: 0 40px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            box-shadow: 0 4px 30px rgba(233, 30, 220, 0.4);
            overflow: hidden;
        }
        header::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            top: -150px; left: 30%;
        }
        header::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
            bottom: -100px; right: 20%;
        }

        .logo-boite {
            width: 62px; height: 62px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 14px;
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            font-size: 9px; color: #ffffff;
            font-weight: 700; text-align: center;
            line-height: 1.3;
            letter-spacing: 0.03em;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(4px);
        }
        .logo-boite img { width: 40px; height: 40px; object-fit: contain; }

        .header-centre {
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .header-titre {
            font-size: 26px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.02em;
            text-shadow: 0 2px 15px rgba(0,0,0,0.3);
        }
        .header-sous {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 300;
            margin-top: 3px;
            letter-spacing: 0.08em;
        }

        /* ============ HERO WELCOME ============ */
        .hero {
            text-align: center;
            padding: 70px 20px 50px;
            position: relative;
        }
        .hero::before {
            content: '';
            position: absolute;
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(30, 128, 233, 0.12) 0%, transparent 65%);
            top: -150px; left: 50%; transform: translateX(-50%);
            border-radius: 50%;
            pointer-events: none;
        }

        .badge-welcome {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(30, 206, 233, 0.15);
            border: 1px solid rgba(30, 105, 233, 0.4);
            border-radius: 30px;
            padding: 8px 20px;
            font-size: 12px;
            color: #8ff4b7;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 24px;
        }
        .badge-welcome .rond {
            width: 8px; height: 8px;
            background: #e91e8c;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.3); }
        }

        .hero h1 {
            font-size: 52px;
            font-weight: 800;
            line-height: 1.15;
            color: #ffffff;
            position: relative;
            z-index: 1;
        }
        .hero h1 .mot-rose {
            background: linear-gradient(135deg, #8ff4d1, #1ee998, #95c218);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-desc {
            font-size: 16px;
            color: rgba(255,255,255,0.5);
            margin-top: 16px;
            font-weight: 300;
            position: relative;
            z-index: 1;
        }

        /* ============ BARRE UTILISATEUR ============ */
        .user-barre {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin: 10px 0 50px;
        }
        .avatar {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #18a3c2, #1ee9e9);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; font-weight: 700; color: #fff;
            border: 2px solid rgba(30, 216, 233, 0.5);
        }
        .user-nom {
            font-size: 15px;
            color: #a0f0d7;
            font-weight: 500;
        }
        .btn-deconnexion {
            display: inline-block;
            padding: 8px 18px;
            background: transparent;
            border: 1px solid rgba(30, 199, 233, 0.5);
            border-radius: 20px;
            color: #8fedf4;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            font-family: 'Poppins', Arial, sans-serif;
            transition: all 0.2s;
        }
        .btn-deconnexion:hover {
            background: rgba(30, 233, 213, 0.15);
            border-color: #e91ee2;
            color: #ffffff;
        }

        /* ============ GRILLE DES LIENS ============ */
        .grille {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 30px 80px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .carte-lien {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(30, 233, 125, 0.2);
            border-radius: 20px;
            padding: 30px 20px 24px;
            text-align: center;
            text-decoration: none;
            color: #ffffff;
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
            display: block;
        }
        .carte-lien::before {
            content: '';
            position: absolute;
            width: 120px; height: 120px;
            background: radial-gradient(circle, rgba(30, 233, 152, 0.15), transparent);
            top: -40px; right: -30px;
            border-radius: 50%;
            transition: all 0.3s;
        }
        .carte-lien:hover {
            background: rgba(30, 233, 159, 0.12);
            border-color: rgba(30, 233, 98, 0.6);
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(135, 233, 30, 0.2);
        }
        .carte-lien:hover::before {
            width: 200px; height: 200px;
        }

        .carte-icone {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, rgba(24, 194, 30, 0.3), rgba(233,30,140,0.3));
            border: 1px solid rgba(111, 233, 30, 0.4);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            font-size: 26px;
            position: relative;
            z-index: 1;
        }
        .carte-nom {
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
            position: relative;
            z-index: 1;
        }
        .carte-desc {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.45);
            margin-top: 5px;
            font-weight: 300;
            position: relative;
            z-index: 1;
        }

        /* Grande carte pleine largeur */
        .carte-lien.pleine {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            padding: 22px 30px;
        }
        .carte-lien.pleine .carte-icone { margin: 0; }

        /* ============ PIED ============ */
        footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.2);
            border-top: 1px solid rgba(255,255,255,0.05);
        }
    </style>
</head>
<body>

<header>
    <div class="logo-boite">UAC<br>logo</div>

    <div class="header-centre">
        <div class="header-titre">Plateforme ENEAM</div>
        <div class="header-sous">Système de gestion commerciale &mdash; IG2 &mdash; 2025</div>
    </div>

    <div class="logo-boite">ENEAM<br>logo</div>
</header>

<div class="hero">
    <div class="badge-welcome">
        <span class="rond"></span>
        Bienvenue sur ma plateforme
    </div>
    <h1>Bonjour,<br><span class="mot-rose"><?php echo $_SESSION['prenom'] . " " . $_SESSION['nom']; ?></span> &#x1F338;</h1>
    <p class="hero-desc">Sélectionnez un module ci-dessous pour commencer</p>
</div>

<div class="user-barre">
    <div class="avatar"><?php echo strtoupper(substr($_SESSION['prenom'],0,1)); ?></div>
    <span class="user-nom">Connecté(e) en tant que <strong><?php echo $_SESSION['login']; ?></strong></span>
    <a href="deconnexion.php" class="btn-deconnexion">Se déconnecter</a>
</div>

<div class="grille">

    <a href="listuser.php" class="carte-lien">
        <div class="carte-icone">&#x1F465;</div>
        <div class="carte-nom">Liste utilisateurs</div>
        <div class="carte-desc">Voir tous les comptes</div>
    </a>

    <a href="voirarticle.php" class="carte-lien">
        <div class="carte-icone">&#x1F4E6;</div>
        <div class="carte-nom">Voir les articles</div>
        <div class="carte-desc">Consulter le catalogue</div>
    </a>

    <a href="ajouterarticle.php" class="carte-lien">
        <div class="carte-icone">&#x2795;</div>
        <div class="carte-nom">Ajouter un article</div>
        <div class="carte-desc">Enregistrer un nouveau produit</div>
    </a>

    <a href="voirclient.php" class="carte-lien">
        <div class="carte-icone">&#x1F9D1;</div>
        <div class="carte-nom">Voir les clients</div>
        <div class="carte-desc">Base de données clients</div>
    </a>

    <a href="effectuervente.php" class="carte-lien">
        <div class="carte-icone">&#x1F6D2;</div>
        <div class="carte-nom">Effectuer une vente</div>
        <div class="carte-desc">Nouvelle commande</div>
    </a>

    <a href="listevente.php" class="carte-lien">
        <div class="carte-icone">&#x1F4CB;</div>
        <div class="carte-nom">Liste des ventes</div>
        <div class="carte-desc">Historique complet</div>
    </a>

</div>

<footer>
    Plateforme ENEAM &mdash; IG2 &mdash; 2025 &nbsp;|&nbsp; Tous droits réservés
</footer>

</body>
</html>
