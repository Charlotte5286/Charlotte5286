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
    <title>Articles — Plateforme ENEAM</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', Arial, sans-serif; min-height: 100vh; background: #0a0d00; color: #fff; padding-bottom: 60px; }
        header { background: linear-gradient(135deg, #225d00 0%, #69880e 40%, #18c21b 75%, #d1e91e 100%); padding: 0 40px; height: 80px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 30px rgba(233,30,140,0.4); position: relative; overflow: hidden; }
        header::before { content: ''; position: absolute; width: 250px; height: 250px; background: rgba(255, 255, 255, 0.05); border-radius: 50%; top: -120px; left: 35%; }
        .logo-boite { width: 54px; height: 54px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; border: 1.5px solid rgba(255,255,255,0.4); display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 8px; color: #fff; font-weight: 700; text-align: center; line-height: 1.3; position: relative; z-index: 1; }
        .header-centre { text-align: center; position: relative; z-index: 1; }
        .header-titre { font-size: 20px; font-weight: 700; color: #ffffff; }
        .header-sous { font-size: 11px; color: rgba(255, 255, 255, 0.6); font-weight: 300; margin-top: 2px; }
        .page { max-width: 950px; margin: 0 auto; padding: 40px 24px; }
        .page-entete { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
        .page-titre { font-size: 24px; font-weight: 700; color: #ffffff; }
        .page-titre span { background: linear-gradient(135deg, #b4f48f, #b6e91e); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .compteur { background: rgba(94, 233, 30, 0.15); border: 1px solid rgba(125, 233, 30, 0.4); border-radius: 20px; padding: 6px 16px; font-size: 13px; color: #def48f; font-weight: 500; }
        .tableau-boite { background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(209, 233, 30, 0.2); border-radius: 20px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: linear-gradient(135deg, rgba(146, 194, 24, 0.6), rgba(169, 233, 30, 0.6)); color: #ffffff; padding: 16px 20px; text-align: left; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; }
        table td { padding: 15px 20px; border-bottom: 1px solid rgba(84, 233, 30, 0.1); font-size: 14px; color: rgba(255, 255, 255, 0.8); }
        table tr:last-child td { border-bottom: none; }
        table tr:hover td { background: rgba(142, 233, 30, 0.07); color: #ffffff; }
        .badge-id { display: inline-block; background: rgba(30, 233, 176, 0.2); border: 1px solid rgba(74, 233, 30, 0.4); border-radius: 8px; padding: 3px 10px; font-size: 12px; color: #e5f48f; font-weight: 600; }
        .prix { color: #c5f48f; font-weight: 600; }
        .cat-badge { display: inline-block; background: rgba(255, 255, 255, 0.07); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 8px; padding: 3px 10px; font-size: 12px; color: rgba(255, 255, 255, 0.7); }
        .zone-boutons { display: flex; align-items: center; justify-content: center; gap: 16px; margin-top: 32px; }
        .btn-rose { display: inline-block; padding: 13px 35px; background: linear-gradient(135deg, #bfc218, #b0e91e); color: #ffffff; text-decoration: none; border-radius: 12px; font-size: 14px; font-weight: 600; font-family: 'Poppins', Arial, sans-serif; box-shadow: 0 8px 20px rgba(220, 233, 30, 0.35), 0 4px 0 #6b880e; transition: all 0.15s; }
        .btn-rose:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(192, 233, 30, 0.45), 0 4px 0 #65880e; }
        .btn-rose:active { transform: translateY(3px); box-shadow: 0 3px 10px rgba(54, 233, 30, 0.3), 0 1px 0 #4d880e; }
        .btn-contour { display: inline-block; padding: 12px 30px; background: transparent; border: 1px solid rgba(155, 233, 30, 0.5); color: #f4ea8f; text-decoration: none; border-radius: 12px; font-size: 14px; font-weight: 500; font-family: 'Poppins', Arial, sans-serif; transition: all 0.2s; }
        .btn-contour:hover { background: rgba(169, 233, 30, 0.1); border-color: #cbe91e; color: #ffffff; }
        .msg-vide { text-align: center; padding: 50px 20px; color: rgba(255, 255, 255, 0.3); font-size: 15px; }
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

<div class="page">

    <?php
    include("connexion.php");
    $idcom   = connexobjet("essaiebase","myparam");
    $requete = "SELECT * FROM article ORDER BY design";
    $result  = $idcom->query($requete);

    if (!$result)
    {
        echo "<p style='color:#f48fb1;text-align:center;padding:30px;'>Erreur de lecture : " . $idcom->error . "</p>";
    }
    else
    {
        $nbart = $result->num_rows;
    ?>

    <div class="page-entete">
        <div class="page-titre">Liste des <span>Articles</span></div>
        <div class="compteur"><?php echo $nbart; ?> article(s)</div>
    </div>

    <?php if ($nbart == 0) { ?>
        <div class="tableau-boite"><p class="msg-vide">Aucun article enregistré.</p></div>
    <?php } else { ?>

    <div class="tableau-boite">
        <table>
            <tr>
                <th>ID</th>
                <th>Désignation</th>
                <th>Prix (FCFA)</th>
                <th>Catégorie</th>
            </tr>
            <?php
            while ($ligne = $result->fetch_array(MYSQLI_NUM))
            {
                echo "<tr>";
                echo "<td><span class='badge-id'>" . $ligne[0] . "</span></td>";
                echo "<td>" . $ligne[1] . "</td>";
                echo "<td><span class='prix'>" . number_format($ligne[2],0,',',' ') . "</span></td>";
                echo "<td><span class='cat-badge'>" . $ligne[3] . "</span></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <?php } ?>

    <?php
        $result->free();
        $idcom->close();
    }
    ?>

    <div class="zone-boutons">
        <a href="ajouterarticle.php" class="btn-contour">+ Ajouter un article</a>
        <a href="acceuil.php" class="btn-rose">&#8592; Quitter</a>
    </div>

</div>

</body>
</html>
