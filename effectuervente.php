<?php
session_start();
if (!isset($_SESSION['login']))
{
    header("Location: connexion_form.php");
    exit();
}

include("connexion.php");
$idcom = connexobjet("essaiebase","myparam");

$erreur = "";
$bon    = false;
$etape  = 1;

// ============================================================
// ETAPE 3 : ENREGISTREMENT FINAL + BON DE COMMANDE
// ============================================================
if (isset($_POST['enregistrer_vente']))
{
    $id_client  = $idcom->escape_string($_POST['id_client']);
    $id_article = $idcom->escape_string($_POST['id_article']);
    $qte_comm   = $idcom->escape_string($_POST['qte_comm']);
    $date       = date("Y-m-d");

    $req_prix = "SELECT prix, design FROM article WHERE id_article='$id_article'";
    $res_prix = $idcom->query($req_prix);
    $art      = $res_prix->fetch_array(MYSQLI_ASSOC);
    $prix_unit = $art['prix'];
    $design    = $art['design'];
    $montant   = $prix_unit * $qte_comm;

    $id_cmd  = "\N";
    $req_cmd = "INSERT INTO commande VALUES('$id_cmd','$date','$id_client','$montant')";
    $res_cmd = $idcom->query($req_cmd);

    if (!$res_cmd)
    {
        $erreur = "Erreur commande : " . $idcom->error;
        $etape  = 2;
        $id_client_nouveau = $id_client;
    }
    else
    {
        $id_commande = $idcom->insert_id;

        $req_cont = "INSERT INTO contenir VALUES('$id_commande','$id_article','$qte_comm')";
        $res_cont = $idcom->query($req_cont);

        if (!$res_cont)
        {
            $erreur = "Erreur détail vente : " . $idcom->error;
            $etape  = 2;
            $id_client_nouveau = $id_client;
        }
        else
        {
            $req_cl = "SELECT * FROM client WHERE id_client='$id_client'";
            $res_cl = $idcom->query($req_cl);
            $client = $res_cl->fetch_array(MYSQLI_ASSOC);
            $bon    = true;
        }
    }
}

// ============================================================
// ETAPE 2 : ENREGISTREMENT CLIENT + FORMULAIRE ARTICLE
// ============================================================
elseif (isset($_POST['etape2']))
{
    $nom_client    = $idcom->escape_string($_POST['nom_client']);
    $prenom_client = $idcom->escape_string($_POST['prenom_client']);
    $age           = $idcom->escape_string($_POST['age']);
    $adresse       = $idcom->escape_string($_POST['adresse']);
    $ville         = $idcom->escape_string($_POST['ville']);
    $mail          = $idcom->escape_string($_POST['mail']);

    $id_cl  = "\N";
    $req_cl = "INSERT INTO client VALUES('$id_cl','$nom_client','$prenom_client','$age','$adresse','$ville','$mail')";
    $res_cl = $idcom->query($req_cl);

    if (!$res_cl)
    {
        $erreur = "Erreur client : " . $idcom->error;
    }
    else
    {
        $id_client_nouveau = $idcom->insert_id;
        $etape = 2;
    }
}

// Chargement des articles pour le select (utile à l'étape 2)
$res_articles = $idcom->query("SELECT Id_article, design, prix FROM article ORDER BY design");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Effectuer une vente — Plateforme ENEAM</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            min-height: 100vh;
            background: #0d0008;
            color: #fff;
            display: flex;
            flex-direction: column;
        }

        header {
            background: linear-gradient(135deg, #5d0030 0%, #880e4f 40%, #c2185b 75%, #e91e8c 100%);
            padding: 0 40px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 30px rgba(233,30,140,0.4);
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }
        header::before {
            content: '';
            position: absolute;
            width: 250px; height: 250px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            top: -120px; left: 35%;
        }
        .logo-boite {
            width: 54px; height: 54px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            border: 1.5px solid rgba(255,255,255,0.4);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            font-size: 8px; color: #fff; font-weight: 700;
            text-align: center; line-height: 1.3;
            position: relative; z-index: 1;
        }
        .header-centre { text-align: center; position: relative; z-index: 1; }
        .header-titre { font-size: 20px; font-weight: 700; color: #fff; }
        .header-sous { font-size: 11px; color: rgba(255,255,255,0.6); font-weight: 300; margin-top: 2px; }

        .centre {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
        }

        /* ====== BARRE DE PROGRESSION ====== */
        .etapes-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 30px;
        }
        .etape-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }
        .etape-cercle {
            width: 36px; height: 36px;
            border-radius: 50%;
            border: 2px solid rgba(233,30,140,0.3);
            background: rgba(233,30,140,0.08);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: rgba(255,255,255,0.35);
            transition: all 0.3s;
        }
        .etape-cercle.actif {
            background: linear-gradient(135deg, #c2185b, #e91e8c);
            border-color: #e91e8c;
            color: #fff;
            box-shadow: 0 0 20px rgba(233,30,140,0.5);
        }
        .etape-cercle.fait {
            background: linear-gradient(135deg, #880e4f, #c2185b);
            border-color: #c2185b;
            color: #fff;
        }
        .etape-label { font-size: 10px; color: rgba(255,255,255,0.4); font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; }
        .etape-label.actif { color: #f48fb1; }
        .trait-etape { width: 60px; height: 2px; background: rgba(233,30,140,0.2); margin: 0 8px; margin-bottom: 22px; }
        .trait-etape.actif { background: linear-gradient(90deg, #c2185b, #e91e8c); }

        /* ====== BOITE FORMULAIRE ====== */
        .boite {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(233,30,140,0.25);
            border-radius: 24px;
            padding: 42px 48px;
            width: 500px;
            position: relative;
            overflow: hidden;
        }
        .boite::before {
            content: '';
            position: absolute;
            width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(233,30,140,0.08), transparent);
            top: -80px; right: -60px;
            border-radius: 50%;
        }

        .boite-titre {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
        }
        .boite-titre span {
            background: linear-gradient(135deg, #f48fb1, #e91e8c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .boite-sous {
            font-size: 13px;
            color: rgba(255,255,255,0.35);
            font-weight: 300;
            margin-bottom: 28px;
            position: relative;
            z-index: 1;
        }

        .champ-groupe { margin-bottom: 14px; position: relative; z-index: 1; }
        .champ-groupe label { display: block; font-size: 11px; font-weight: 600; color: rgba(255,255,255,0.45); margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.07em; }
        .champ-groupe input[type="text"],
        .champ-groupe input[type="email"],
        .champ-groupe input[type="number"],
        .champ-groupe select {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid rgba(233,30,140,0.22);
            border-radius: 11px;
            font-size: 14px;
            color: #fff;
            background: rgba(255,255,255,0.05);
            outline: none;
            font-family: 'Poppins', Arial, sans-serif;
            transition: border-color 0.2s, background 0.2s;
        }
        .champ-groupe input:focus,
        .champ-groupe select:focus { border-color: #e91e8c; background: rgba(233,30,140,0.1); }
        .champ-groupe input::placeholder { color: rgba(255,255,255,0.2); }
        .champ-groupe select option { background: #2a0018; color: #fff; }

        .grille-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        .btn-suivant {
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
        .btn-suivant:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(233,30,140,0.5), 0 4px 0 #880e4f; }
        .btn-suivant:active { transform: translateY(3px); box-shadow: 0 3px 10px rgba(233,30,140,0.3), 0 1px 0 #880e4f; }

        .btn-quitter {
            display: block;
            text-align: center;
            margin-top: 12px;
            padding: 11px;
            background: transparent;
            border: 1px solid rgba(233,30,140,0.35);
            border-radius: 11px;
            color: #f48fb1;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            font-family: 'Poppins', Arial, sans-serif;
            transition: all 0.2s;
            position: relative;
            z-index: 1;
        }
        .btn-quitter:hover { background: rgba(233,30,140,0.1); border-color: #e91e8c; color: #fff; }

        .msg-erreur {
            background: rgba(233,30,140,0.1);
            border-left: 4px solid #e91e8c;
            color: #f48fb1;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 13px;
            position: relative;
            z-index: 1;
        }

        /* ====== BON DE COMMANDE ====== */
        .bon {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(233,30,140,0.25);
            border-radius: 24px;
            padding: 48px;
            width: 560px;
            position: relative;
            overflow: hidden;
        }
        .bon::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(233,30,140,0.08), transparent);
            top: -100px; right: -80px;
            border-radius: 50%;
        }
        .bon-entete {
            text-align: center;
            margin-bottom: 28px;
            padding-bottom: 24px;
            border-bottom: 1px dashed rgba(233,30,140,0.3);
            position: relative;
            z-index: 1;
        }
        .bon-badge {
            display: inline-block;
            background: rgba(233,30,140,0.15);
            border: 1px solid rgba(233,30,140,0.4);
            border-radius: 20px;
            padding: 5px 18px;
            font-size: 11px;
            color: #f48fb1;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 12px;
        }
        .bon-entete h2 {
            font-size: 26px;
            font-weight: 800;
            color: #fff;
        }
        .bon-entete h2 span {
            background: linear-gradient(135deg, #f48fb1, #e91e8c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .bon-entete p { font-size: 13px; color: rgba(255,255,255,0.35); margin-top: 6px; }

        .bon-section { margin-bottom: 22px; position: relative; z-index: 1; }
        .bon-section h3 {
            font-size: 11px;
            font-weight: 700;
            color: #e91e8c;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .bon-section h3::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(233,30,140,0.2);
        }
        .bon-ligne {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .bon-ligne:last-child { border-bottom: none; }
        .bon-ligne .etiquette { font-size: 13px; color: rgba(255,255,255,0.45); }
        .bon-ligne .valeur { font-size: 13px; color: rgba(255,255,255,0.85); font-weight: 500; }

        .bon-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 22px;
            background: linear-gradient(135deg, rgba(194,24,91,0.2), rgba(233,30,140,0.2));
            border: 1px solid rgba(233,30,140,0.4);
            border-radius: 14px;
            margin: 22px 0;
            position: relative;
            z-index: 1;
        }
        .bon-total .label { font-size: 14px; font-weight: 700; color: #f48fb1; }
        .bon-total .montant { font-size: 24px; font-weight: 800; color: #fff; }

        .bon-vendeur {
            text-align: center;
            font-size: 12px;
            color: rgba(255,255,255,0.3);
            padding-top: 16px;
            border-top: 1px dashed rgba(233,30,140,0.2);
            margin-bottom: 22px;
            position: relative;
            z-index: 1;
        }

        .zone-actions { display: flex; gap: 12px; position: relative; z-index: 1; }
        .btn-imprimer {
            flex: 1;
            padding: 13px;
            background: linear-gradient(135deg, #c2185b, #e91e8c);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', Arial, sans-serif;
            box-shadow: 0 8px 20px rgba(233,30,140,0.4), 0 4px 0 #880e4f;
            transition: all 0.15s;
        }
        .btn-imprimer:hover { transform: translateY(-2px); }
        .btn-imprimer:active { transform: translateY(3px); }
        .btn-accueil {
            flex: 1;
            display: block;
            text-align: center;
            padding: 13px;
            background: transparent;
            border: 1px solid rgba(233,30,140,0.4);
            border-radius: 12px;
            color: #f48fb1;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            font-family: 'Poppins', Arial, sans-serif;
            transition: all 0.2s;
        }
        .btn-accueil:hover { background: rgba(233,30,140,0.1); color: #fff; }

        @media print {
            header, .zone-actions { display: none !important; }
            body { background: #fff !important; color: #000 !important; }
            .bon { border: 2px solid #e91e8c; box-shadow: none; background: #fff !important; }
            .bon-entete h2, .bon-section h3 { color: #c2185b !important; }
            .bon-ligne .etiquette { color: #666 !important; }
            .bon-ligne .valeur, .bon-entete p, .bon-vendeur { color: #333 !important; }
            .bon-total { background: #fce4ec !important; }
            .bon-total .label { color: #c2185b !important; }
            .bon-total .montant { color: #880e4f !important; }
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

<?php
// ============================================================
// AFFICHAGE BON DE COMMANDE
// ============================================================
if ($bon)
{
?>
<div class="bon">
    <div class="bon-entete">
        <div class="bon-badge">Bon de commande</div>
        <h2>&#x1F9FE; Commande <span>#<?php echo $id_commande; ?></span></h2>
        <p>Émis le <?php echo $date; ?> &nbsp;&bull;&nbsp; Vendeur : <?php echo $_SESSION['prenom'] . " " . $_SESSION['nom']; ?></p>
    </div>

    <div class="bon-section">
        <h3>&#x1F464; Informations client</h3>
        <div class="bon-ligne"><span class="etiquette">Nom complet</span><span class="valeur"><?php echo $client['nom'] . " " . $client['prenom']; ?></span></div>
        <div class="bon-ligne"><span class="etiquette">Âge</span><span class="valeur"><?php echo $client['age']; ?> ans</span></div>
        <div class="bon-ligne"><span class="etiquette">Adresse</span><span class="valeur"><?php echo $client['adresse']; ?></span></div>
        <div class="bon-ligne"><span class="etiquette">Ville</span><span class="valeur"><?php echo $client['ville']; ?></span></div>
        <div class="bon-ligne"><span class="etiquette">Email</span><span class="valeur"><?php echo $client['mail']; ?></span></div>
    </div>

    <div class="bon-section">
        <h3>&#x1F6CD; Article commandé</h3>
        <div class="bon-ligne"><span class="etiquette">Désignation</span><span class="valeur"><?php echo $design; ?></span></div>
        <div class="bon-ligne"><span class="etiquette">Prix unitaire</span><span class="valeur"><?php echo number_format($prix_unit,0,',',' '); ?> FCFA</span></div>
        <div class="bon-ligne"><span class="etiquette">Quantité</span><span class="valeur"><?php echo $qte_comm; ?></span></div>
    </div>

    <div class="bon-total">
        <span class="label">MONTANT TOTAL</span>
        <span class="montant"><?php echo number_format($montant,0,',',' '); ?> FCFA</span>
    </div>

    <div class="bon-vendeur">
        Merci pour votre confiance &#x1F338; &nbsp;&bull;&nbsp; Plateforme ENEAM &mdash; 2025
    </div>

    <div class="zone-actions">
        <button class="btn-imprimer" onclick="window.print()">&#x1F5A8; Imprimer</button>
        <a href="acceuil.php" class="btn-accueil">&#8592; Retour à l'accueil</a>
    </div>
</div>

<?php
}

// ============================================================
// FORMULAIRE ETAPE 2 — Choix article + quantité
// ============================================================
elseif ($etape == 2)
{
?>
<div class="boite">
    <div class="etapes-bar">
        <div class="etape-item">
            <div class="etape-cercle fait">&#10003;</div>
            <div class="etape-label">Client</div>
        </div>
        <div class="trait-etape actif"></div>
        <div class="etape-item">
            <div class="etape-cercle actif">2</div>
            <div class="etape-label actif">Article</div>
        </div>
        <div class="trait-etape"></div>
        <div class="etape-item">
            <div class="etape-cercle">3</div>
            <div class="etape-label">Bon</div>
        </div>
    </div>

    <div class="boite-titre">Choisir un <span>Article</span></div>
    <div class="boite-sous">Étape 2 — Sélectionnez l'article et la quantité</div>

    <?php if ($erreur != "") echo "<div class='msg-erreur'>$erreur</div>"; ?>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="id_client" value="<?php echo $id_client_nouveau; ?>" />

        <div class="champ-groupe">
            <label>Article</label>
            <select name="id_article" required>
                <option value="">-- Choisir un article --</option>
                <?php
                while ($ar = $res_articles->fetch_array(MYSQLI_NUM))
                {
                    echo "<option value='" . $ar[0] . "'>" . $ar[1] . "  —  " . number_format($ar[2],0,',',' ') . " FCFA</option>";
                }
                ?>
            </select>
        </div>

        <div class="champ-groupe">
            <label>Quantité</label>
            <input type="number" name="qte_comm" placeholder="Ex : 3" min="1" required />
        </div>

        <input type="submit" name="enregistrer_vente" value="&#x2713; Enregistrer la commande" class="btn-suivant" />
    </form>
    <a href="acceuil.php" class="btn-quitter">&#8592; Quitter</a>
</div>

<?php
}

// ============================================================
// FORMULAIRE ETAPE 1 — Infos client
// ============================================================
else
{
?>
<div class="boite">
    <div class="etapes-bar">
        <div class="etape-item">
            <div class="etape-cercle actif">1</div>
            <div class="etape-label actif">Client</div>
        </div>
        <div class="trait-etape"></div>
        <div class="etape-item">
            <div class="etape-cercle">2</div>
            <div class="etape-label">Article</div>
        </div>
        <div class="trait-etape"></div>
        <div class="etape-item">
            <div class="etape-cercle">3</div>
            <div class="etape-label">Bon</div>
        </div>
    </div>

    <div class="boite-titre">Nouvelle <span>Vente</span></div>
    <div class="boite-sous">Étape 1 — Informations du client</div>

    <?php if ($erreur != "") echo "<div class='msg-erreur'>$erreur</div>"; ?>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="application/x-www-form-urlencoded">
        <div class="grille-2">
            <div class="champ-groupe">
                <label>Nom</label>
                <input type="text" name="nom_client" placeholder="Nom du client" maxlength="50" required />
            </div>
            <div class="champ-groupe">
                <label>Prénom</label>
                <input type="text" name="prenom_client" placeholder="Prénom" maxlength="50" required />
            </div>
        </div>
        <div class="grille-2">
            <div class="champ-groupe">
                <label>Âge</label>
                <input type="number" name="age" placeholder="Ex : 25" min="1" max="120" required />
            </div>
            <div class="champ-groupe">
                <label>Ville</label>
                <input type="text" name="ville" placeholder="Ex : Cotonou" maxlength="40" required />
            </div>
        </div>
        <div class="champ-groupe">
            <label>Adresse</label>
            <input type="text" name="adresse" placeholder="Adresse complète" maxlength="60" required />
        </div>
        <div class="champ-groupe">
            <label>Email</label>
            <input type="email" name="mail" placeholder="email@exemple.com" maxlength="50" required />
        </div>

        <input type="submit" name="etape2" value="Suivant &#10145;" class="btn-suivant" />
    </form>
    <a href="acceuil.php" class="btn-quitter">&#8592; Quitter</a>
</div>

<?php
}
$idcom->close();
?>

</div>
</body>
</html>
