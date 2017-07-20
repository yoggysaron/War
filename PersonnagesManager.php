<?php

class PersonnagesManager {

	private $_db; 

	// CONSTRUCT

	public function __construct($db)
	{
		$this->_db = $db;
	}

	// GETTERS

	public function add(Personnage $perso)
	{
		$req = $this->_db->prepare('INSERT INTO personnages(nom) VALUES(:nom)');
		$req->bindValue(':nom', $perso->nom());
		$req->execute();

		$perso->hydrate([
			'id' => $this->_db->lastInsertId(),
			'degats' => 0, ]); 
	}

	public function delete(Personnage $perso)
	{
		$this->_db->exec('DELETE FROM personnages WHERE id ='.$perso->id()); 
	}

	public function update (Personnage $perso)
	{
		$req = $this->_db->prepare('UPDATE personnages SET degats = :degats WHERE id = :id');
		$req->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
		$req->bindValue(':id', $perso->id(), PDO::PARAM_INT);
		$req->execute();
	}

	public function exists ($info)
	{
		if (is_int($info))
		{
			return(bool) $this->_db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
		}

		$req = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
		$req->execute([':nom' => $info]);

		return (bool) $req->fetchColumn();
	}

	public function count()
	{
		return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
	}

	public function get($info)
	{
		if(is_int($info))
		{
			$req = $this->_db->query('SELECT id, nom, degats FROM personnages WHERE id ='.$info);
			$donnees = $req->fetch(PDO::FETCH_ASSOC);
			return new Personnage($donnees);
		}
		else
		{
			$req = $this->_db->prepare('SELECT id, nom, degats FROM personnages WHERE nom = :nom');
			$req->execute([':nom' => $info]);
			return new Personnage($req->fetch(PDO::FETCH_ASSOC));
		}
	}

	public function getList($nom)
	{
		$persos = [];

		$req = $this->_db->prepare('SELECT id, nom, degats FROM personnages WHERE nom <> :nom ORDER BY nom');
		$req->execute([':nom' => $nom]);

		while ($donnees = $req->fetch(PDO::FETCH_ASSOC))
		{
			$persos[] = new Personnage($donnees);
		}

		return $persos;
	}

	// SETTERS 

	public function setDb (PDO $db)
	{
		return $this->_db = $bd;
	}
}

?>