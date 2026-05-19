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
    <title>Clients — Plateforme ENEAM</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', Arial, sans-serif; min-height: 100vh; background: #0d0008; color: #fff; padding-bottom: 60px; }
        header { background: linear-gradient(135deg, #5d0030 0%, #880e4f 40%, #c2185b 75%, #e91e8c 100%); padding: 0 40px; height: 80px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 30px rgba(233,30,140,0.4); position: relative; overflow: hidden; }
        header::before { content: ''; position: absolute; width: 250px; height: 250px; background: rgba(255,255,255,0.05); border-radius: 50%; top: -120px; left: 35%; }
        .logo-boite { width: 54px; height: 54px; background: rgba(255,255,255,0.15); border-radius: 12px; border: 1.5px solid rgba(255,255,255,0.4); display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 8px; color: #fff; font-weight: 700; text-align: center; line-height: 1.3; position: relative; z-index: 1; }
        .header-centre { text-align: center; position: relative; z-index: 1; }
        .header-titre { font-size: 20px; font-weight: 700; color: #fff; }
        .header-sous { font-size: 11px; color: rgba(255,255,255,0.6); font-weight: 300; margin-top: 2px; }
        .page { max-width: 1100px; margin: 0 auto; padding: 40px 24px; }
        .page-entete { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
        .page-titre { font-size: 24px; font-weight: 700; color: #fff; }
        .page-titre span { background: linear-gradient(135deg, #f48fb1, #e91e8c); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .compteur { background: rgba(233,30,140,0.15); border: 1px solid rgba(233,30,140,0.4); border-radius: 20px; padding: 6px 16px; font-size: 13px; color: #f48fb1; font-weight: 500; }
        .tableau-boite { background: rgba(255,255,255,0.04); border: 1px solid rgba(233,30,140,0.2); border-radius: 20px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: linear-gradient(135deg, rgba(194,24,91,0.6), rgba(233,30,140,0.6)); color: #fff; padding: 16px 18px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; }
        table td { padding: 14px 18px; border-bottom: 1px solid rgba(233,30,140,0.1); font-size: 13px; color: rgba(255,255,255,0.8); }
        table tr:last-child td { border-bottom: none; }
        table tr:hover td { background: rgba(233,30,140,0.07); color: #fff; }
        .badge-id { display: inline-block; background: rgba(233,30,140,0.2); border: 1px solid rgba(233,30,140,0.4); border-radius: 8px; padding: 3px 10px; font-size: 12px; color: #f48fb1; font-weight: 600; }
        .ville-badge { display: inline-block; background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; padding: 3px 10px; font-size: 12px; color: rgba(255,255,255,0.7); }
        .mail-lien { color: #f48fb1; font-size: 12px; }
        .zone-retour { text-align: center; margin-top: 32px; }
        .btn-rose { display: inline-block; padding: 13px 40px; background: linear-gradient(135deg, #c2185b, #e91e8c); color: #fff; text-decoration: none; border-radius: 12px; font-size: 14px; font-weight: 600; font-family: 'Poppins', Arial, sans-serif; box-shadow: 0 8px 20px rgba(233,30,140,0.35), 0 4px 0 #880e4f; transition: all 0.15s; }
        .btn-rose:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(233,30,140,0.45), 0 4px 0 #880e4f; }
        .btn-rose:active { transform: translateY(3px); box-shadow: 0 3px 10px rgba(233,30,140,0.3), 0 1px 0 #880e4f; }
        .msg-vide { text-align: center; padding: 50px 20px; color: rgba(255,255,255,0.3); font-size: 15px; }
    </style>
</head>
<body>

<header>
    <div class="logo-boite">>UAC<br>logo</div>
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
    $requete = "SELECT * FROM client ORDER BY nom";
    $result  = $idcom->query($requete);

    if (!$result)
    {
        echo "<p style='color:#f48fb1;text-align:center;padding:30px;'>Erreur de lecture : " . $idcom->error . "</p>";
    }
    else
    {
        $nbclient = $result->num_rows;
    ?>

    <div class="page-entete">
        <div class="page-titre">Liste des <span>Clients</span></div>
        <div class="compteur"><?php echo $nbclient; ?> client(s)</div>
    </div>

    <?php if ($nbclient == 0) { ?>
        <div class="tableau-boite"><p class="msg-vide">Aucun client enregistré.</p></div>
    <?php } else { ?>

    <div class="tableau-boite">
        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Âge</th>
                <th>Adresse</th>
                <th>Ville</th>
                <th>Email</th>
            </tr>
            <?php
            while ($ligne = $result->fetch_array(MYSQLI_NUM))
            {
                echo "<tr>";
                echo "<td><span class='badge-id'>" . $ligne[0] . "</span></td>";
                echo "<td>" . $ligne[1] . "</td>";
                echo "<td>" . $ligne[2] . "</td>";
                echo "<td>" . $ligne[3] . " ans</td>";
                echo "<td>" . $ligne[4] . "</td>";
                echo "<td><span class='ville-badge'>" . $ligne[5] . "</span></td>";
                echo "<td><span class='mail-lien'>" . $ligne[6] . "</span></td>";
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

    <div class="zone-retour">
        <a href="acceuil.php" class="btn-rose">&#8592; Quitter</a>
    </div>

</div>

</body>
</html>
