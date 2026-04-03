package com.sigansud.app.models;

import com.google.gson.annotations.SerializedName;

public class Soignant {

    @SerializedName("matricule")
    private String matricule;

    @SerializedName("nom")
    private String nom;

    @SerializedName("prenom")
    private String prenom;

    @SerializedName("tel")
    private String tel;

    @SerializedName("adresse")
    private String adresse;

    @SerializedName("token")
    private String token;

    public Soignant() {}

    public String getMatricule() { return matricule; }
    public void setMatricule(String matricule) { this.matricule = matricule; }

    public String getNom() { return nom; }
    public void setNom(String nom) { this.nom = nom; }

    public String getPrenom() { return prenom; }
    public void setPrenom(String prenom) { this.prenom = prenom; }

    public String getTel() { return tel; }
    public void setTel(String tel) { this.tel = tel; }

    public String getAdresse() { return adresse; }
    public void setAdresse(String adresse) { this.adresse = adresse; }

    public String getToken() { return token; }
    public void setToken(String token) { this.token = token; }

    public String getNomComplet() {
        return (prenom != null ? prenom : "") + " " + (nom != null ? nom : "");
    }
}
