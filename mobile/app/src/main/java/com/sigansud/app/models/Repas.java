package com.sigansud.app.models;

import com.google.gson.annotations.SerializedName;

public class Repas {

    @SerializedName("id_date_repas")
    private int idDateRepas;

    @SerializedName("id_espece")
    private int idEspece;

    @SerializedName("nomBapteme")
    private String nomBapteme;

    @SerializedName("quantite")
    private int qteRepas;

    @SerializedName("id_menu")
    private int idMenu;

    @SerializedName("date")
    private String date;

    @SerializedName("nom_espece")
    private String nomEspece;

    @SerializedName("aliment")
    private String alimentMenu;

    private Animal animal;
    private Soignant soignant;

    public Repas() {}

    public int getIdDateRepas() { return idDateRepas; }
    public void setIdDateRepas(int idDateRepas) { this.idDateRepas = idDateRepas; }

    public int getIdEspece() { return idEspece; }
    public void setIdEspece(int idEspece) { this.idEspece = idEspece; }

    public String getNomBapteme() { return nomBapteme; }
    public void setNomBapteme(String nomBapteme) { this.nomBapteme = nomBapteme; }

    public int getQteRepas() { return qteRepas; }
    public void setQteRepas(int qteRepas) { this.qteRepas = qteRepas; }

    public int getIdMenu() { return idMenu; }
    public void setIdMenu(int idMenu) { this.idMenu = idMenu; }

    public String getDate() { return date != null ? date : "N/A"; }
    public void setDate(String date) { this.date = date; }

    public Animal getAnimal() { return animal; }
    public void setAnimal(Animal animal) { this.animal = animal; }

    public Soignant getSoignant() { return soignant; }
    public void setSoignant(Soignant soignant) { this.soignant = soignant; }

    // Compatibilité avec les Adapters existants
    public int getId() { return idDateRepas; }
    public String getHeure() { return ""; }
    public float getQuantiteDonnee() { return qteRepas; }
    public String getObservation() { return ""; }
    public boolean isValide() { return true; }
    public String getNomAnimal() { return nomBapteme; }
}
