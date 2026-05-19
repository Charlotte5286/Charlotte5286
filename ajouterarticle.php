<?php
session_start();
if (!isset($_SESSION['login']))
{
    header("Location: connexion_form.php");
    exit();
}

$erreur  = "";
$message = "";

if (isset($_POST['ajouter']))
{
    include("connexion.php");
    $idcom = connexobjet("essaiebase","myparam");

    $design    = $idcom->escape_string($_POST['design']);
    $prix      = $idcom->escape_string($_POST['prix']);
    $categorie = $idcom->escape_string($_POST['categorie']);

    $id_art  = "\N";
    $requete = "INSERT INTO article VALUES('$id_art','$design','$prix','$categorie')";
    $result  = $idcom->query($requete);

    if (!$result)
    {
        $erreur = "Erreur lors de l'ajout : " . $idcom->error;
    }
    else
    {
        $message = "Article ajouté avec succès !";
    }
    $idcom->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Ajouter un article — Plateforme ENEAM</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', Arial, sans-serif; min-height: 100vh; background: #03000d; color: #ffffff; display: flex; flex-direction: column; }
        header { background: linear-gradient(135deg, #305d00 0%, #0e8859 40%, #1873c2 75%, #1ec0e9 100%); padding: 0 40px; height: 80px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 30px rgba(233,30,140,0.4); position: relative; overflow: hidden; flex-shrink: 0; }
        header::before { content: ''; position: absolute; width: 250px; height: 250px; background: rgba(255,255,255,0.05); border-radius: 50%; top: -120px; left: 35%; }
        .logo-boite { width: 54px; height: 54px; background: rgba(255,255,255,0.15); border-radius: 12px; border: 1.5px solid rgba(255,255,255,0.4); display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 8px; color: #fff; font-weight: 700; text-align: center; line-height: 1.3; position: relative; z-index: 1; }
        .header-centre { text-align: center; position: relative; z-index: 1; }
        .header-titre { font-size: 20px; font-weight: 700; color: #fff; }
        .header-sous { font-size: 11px; color: rgba(255, 255, 255, 0.6); font-weight: 300; margin-top: 2px; }

        .centre {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
        }

        .boite {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(132, 233, 30, 0.25);
            border-radius: 24px;
            padding: 48px 50px;
            width: 480px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .boite::before {
            content: '';
            position: absolute;
            width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(128, 233, 30, 0.1), transparent);
            top: -80px; right: -60px;
            border-radius: 50%;
        }

        .boite-titre {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }
        .boite-titre span {
            background: linear-gradient(135deg, #f4ca8f, #e91e8c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .boite-sous {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 300;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .champ-groupe {
            margin-bottom: 16px;
            text-align: left;
            position: relative;
            z-index: 1;
        }
        .champ-groupe label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }
        .champ-groupe input[type="text"],
        .champ-groupe input[type="number"] {
            width: 100%;
            padding: 13px 18px;
            border: 1.5px solid rgba(233,30,140,0.25);
            border-radius: 12px;
            font-size: 14px;
            color: #fff;
            background: rgba(255,255,255,0.06);
            outline: none;
            font-family: 'Poppins', Arial, sans-serif;
            transition: border-color 0.2s, background 0.2s;
        }
        .champ-groupe input:focus {
            border-color: #e91e8c;
            background: rgba(233,30,140,0.1);
        }
        .champ-groupe input::placeholder { color: rgba(255,255,255,0.25); }

        .btn-ajouter {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #c2185b, #e91e8c);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', Arial, sans-serif;
            box-shadow: 0 8px 24px rgba(233,30,140,0.4), 0 4px 0 #880e4f;
            transition: all 0.15s;
            margin-top: 8px;
            position: relative;
            z-index: 1;
        }
        .btn-ajouter:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(233,30,140,0.5), 0 4px 0 #880e4f; }
        .btn-ajouter:active { transform: translateY(3px); box-shadow: 0 3px 10px rgba(233,30,140,0.3), 0 1px 0 #880e4f; }

        .zone-liens {
            display: flex;
            gap: 10px;
            margin-top: 14px;
            position: relative;
            z-index: 1;
        }
        .btn-contour {
            flex: 1;
            display: block;
            padding: 11px;
            background: transparent;
            border: 1px solid rgba(233,30,140,0.35);
            border-radius: 10px;
            color: #f48fb1;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            font-family: 'Poppins', Arial, sans-serif;
            text-align: center;
            transition: all 0.2s;
        }
        .btn-contour:hover { background: rgba(233,30,140,0.1); border-color: #e91e8c; color: #fff; }

        .msg-succes {
            background: rgba(46,213,115,0.15);
            border: 1px solid rgba(46,213,115,0.4);
            color: #2ed573;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 13px;
            position: relative;
            z-index: 1;
        }
        .msg-erreur {
            background: rgba(233,30,140,0.1);
            border-left: 4px solid #e91e8c;
            color: #f48fb1;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 13px;
            text-align: left;
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>

<header>
    <div class="logo-boite">UAC<br>logo</div>
    <div class="header-centre">
        <div class="header-titre">Plateforme ENEAM</div>
        <div class="header-sous">Système de gestion commerciale</div>
    </div>
    <div class="logo-boite">ENEAM<br>logo</div>
</header>

<div class="centre">
    <div class="boite">
        <div class="boite-titre">Ajouter un <span>Article</span></div>
        <div class="boite-sous">Remplissez les informations du nouvel article</div>

        <?php if ($message != "") echo "<div class='msg-succes'>&#10003; $message</div>"; ?>
        <?php if ($erreur  != "") echo "<div class='msg-erreur'>$erreur</div>"; ?>

        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="application/x-www-form-urlencoded">
            <div class="champ-groupe">
                <label>Désignation</label>
                <input type="text" name="design" placeholder="Nom de l'article" maxlength="100" required />
            </div>
            <div class="champ-groupe">
                <label>Prix (FCFA)</label>
                <input type="number" name="prix" placeholder="Ex : 5000" min="0" required />
            </div>
            <div class="champ-groupe">
                <label>Catégorie</label>
                <input type="text" name="categorie" placeholder="Ex : Électronique" maxlength="60" required />
            </div>
            <input type="submit" name="ajouter" value="Enregistrer l'article" class="btn-ajouter" />
        </form>

        <div class="zone-liens">
            <a href="voirarticle.php" class="btn-contour">Voir les articles</a>
            <a href="acceuil.php" class="btn-contour">&#8592; Accueil</a>
        </div>
    </div>
</div>

</body>
</html>
