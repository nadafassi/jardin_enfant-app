<?php


namespace RaedBundle\models;


class jardinmodels
{
    private $id;
    private $name;
    private $numtel;
    private $adresse;
   private $Longitude;
   private $Latitude;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getNumtel()
    {
        return $this->numtel;
    }

    /**
     * @param mixed $numtel
     */
    public function setNumtel($numtel)
    {
        $this->numtel = $numtel;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->Longitude;
    }

    /**
     * @param mixed $Longitude
     */
    public function setLongitude($Longitude)
    {
        $this->Longitude = $Longitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->Latitude;
    }

    /**
     * @param mixed $Latitude
     */
    public function setLatitude($Latitude)
    {
        $this->Latitude = $Latitude;
    }


}