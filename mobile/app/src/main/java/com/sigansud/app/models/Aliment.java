package com.sigansud.app.models;

import com.google.gson.annotations.SerializedName;

public class Aliment {

    @SerializedName("id")
    private int id;

    @SerializedName("nom")
    private String nom;

    @SerializedName("unite")
    private String unite;

    @SerializedName("quantite")
    private float quantite;

    public Aliment() {}

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public String getNom() { return nom; }
    public void setNom(String nom) { this.nom = nom; }

    public String getUnite() { return unite; }
    public void setUnite(String unite) { this.unite = unite; }

    public float getQuantite() { return quantite; }
    public void setQuantite(float quantite) { this.quantite = quantite; }

    @Override
    public String toString() {
        return nom + " (" + quantite + " " + unite + ")";
    }
}