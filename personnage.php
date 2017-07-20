<?php

class Personnage {


	private $_id,
	 		$_degats,
	 		$_nom;

	const PERSONNAGE_MOI = 1;
	const PERSONNAGE_TUE = 2;
	const PERSONNAGE_FRAPPE = 3;


	public function attaquer(Personnage $perso)
	{
		if($perso->id() == $this->_id)
		{
			return self::PERSONNAGE_MOI;
		}

			return $perso->defenceDegats();
	}

	public function defenceDegats()
	{
		$this->_degats += 5;
		if ($this->_degats >= 100)
		{
			return self::PERSONNAGE_TUE;
		}

			return self::PERSONNAGE_FRAPPE;

	}


	// CONSTRUCT

	public function __construct (array $donnees)
		{
			$this->hydrate($donnees);
		}

	// GETTERS

		public function id()
		{
			return $this->_id;
		}

		public function degats()
		{
			return $this->_degats;
		}

		public function nom()
		{
			return $this->_nom;
		}

	// SETTERS

		public function setId($id)
		{
			$id = (int) $id;
			if ($id > 0)
			{
				$this->_id = $id;
			}
		}

		public function setDegats($degats)
		{
			$degats = (int) $degats;
			if ($degats >= 0 && $degats <= 100 )
			{
				$this->_degats = $degats;
			}
		}

		public function setNom ($nom)
		{
			if (is_string($nom))
			{
				$this->_nom = $nom; 
			}
		}

		public function nomValide ()
		{
			return !empty($this->_nom);
		}

	// HYDRATION

		public function hydrate (array $donnees)
		{
			foreach ($donnees as $key => $value)
			 {
				$method ='set'.ucfirst($key);
				if (method_exists($this, $method))
				 {
					$this->$method($value);
				}
			}
		}

}






?>