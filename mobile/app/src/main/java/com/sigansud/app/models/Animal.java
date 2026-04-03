package com.sigansud.app.models;

import com.google.gson.annotations.SerializedName;

public class Animal {

    @SerializedName("idEspece")
    private int idEspece;

    @SerializedName("nomBapteme")
    private String nomBapteme;

    @SerializedName("dateNaissance")
    private String dateNaissance;

    @SerializedName("dateDeces")
    private String dateDeces;

    @SerializedName("genre")
    private String genre;

    private Espece espece;

    public Animal() {}

    public int getIdEspece() { return idEspece; }
    public void setIdEspece(int idEspece) { this.idEspece = idEspece; }

    public String getNomBapteme() { return nomBapteme; }
    public void setNomBapteme(String nomBapteme) { this.nomBapteme = nomBapteme; }

    public String getNom() { return nomBapteme; }

    public String getDateNaissance() { return dateNaissance; }
    public void setDateNaissance(String dateNaissance) { this.dateNaissance = dateNaissance; }

    public String getDateDeces() { return dateDeces; }
    public void setDateDeces(String dateDeces) { this.dateDeces = dateDeces; }

    public String getGenre() { return genre; }
    public void setGenre(String genre) { this.genre = genre; }

    public Espece getEspece() { return espece; }
    public void setEspece(Espece espece) { this.espece = espece; }

    public boolean isDecede() {
        return dateDeces != null && !dateDeces.isEmpty();
    }
}
