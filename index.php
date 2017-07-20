<?php

function chargerClasse($classname) 
{
	require $classname. '.php';
}

spl_autoload_register('chargerClasse');

session_start();

if (isset($_GET['deconnexion']))
	{
		session_destroy();
		header('Location: .');
		exit();
	}
if (isset($_SESSION['perso']))
	{
		$perso = $_SESSION['perso'];
	}


$db = new PDO('mysql:host=localhost;dbname=war', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$manager = new PersonnagesManager($db);

if(isset($_POST['creer']) && isset($_POST['nom']))
{
	$perso = new Personnage(['nom' => $_POST['nom']]);

	if (!$perso->nomValide())
	{
		$message = 'Le nom choisi est invalide.';
		unset($perso);
	}
	elseif ($manager->exists($perso->nom()))
	{
		$message = 'Le nom du personnage est déjà pris.' ; 
		unset($perso);
	}
	else
	{
		$manager->add($perso);
	}
}

elseif (isset($_POST['utiliser']) && isset($_POST['nom']))
{
	if ($manager->exists($_POST['nom']))
	{
		$perso = $manager->get($_POST['nom']);
	}
	else
	{
		$message = 'Ce personnage n\'existe pas.'; 
	}
}


?>


<!DOCTYPE html>
<html>
<head>
	<title> Wars </title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

	<h2> Créer votre personnage </h2> 

	<p> Nombre de personnage crées : <?= $manager->count() ?> </p>
	<?php

	if (isset($message))	
		echo '<p>' ,$message, '</p>';

	if (isset($perso))
	{
		?>
			<p> <a href="?deconnexion=index.php"> Deconnexion </a> </p>
			<fieldset>
				<legend> Mes informations : </legend>
					<p>
						Nom : <?= htmlspecialchars($perso->nom()) ?> <br>
						Degats <?= $perso->degats() ?> <br>
					</p>	
			</fieldset>

			<fieldset>
				<legend> Qui attaquer ? : </legend>
				<p>
					<?php
						$perso = $manager->getList($perso->nom());
							if (empty($persos))
							{
								echo ' Il n\'y a personne à attaquer ';
							}
							else 
							{
								foreach ($persos as $unPerso ) 
								
									echo '<a href="?frapper=', $unPerso->id(),'">',
									htmlspecialchars($unPerso->nom()), '</a> (dégats : ', $unPerso->degats(), ') <br>' ;
							}	
					?>
				</p>				
			</fieldset>			
			<?php
		}
		else {
					

	
	?>

	<form id="" method="POST" action="">
		<p>
			Nom : <input type="text" name="nom" maxlength="40">
		<input type="submit" value="Créer un personnage" name="creer">
		<input type="submit" value="utiliser un personnage" name="utiliser">
		</p>
	</form>
	<?php
}

var_dump($perso);
?>



</body>
</html>
<?php
if (isset($perso))
{
	$_SESSION['perso'] = $perso;
}