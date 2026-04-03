package com.sigansud.app.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class Espece {

    @SerializedName("id")
    private int id;

    @SerializedName("nom")
    private String nom;

    // Champs locaux
    private List<Animal> animaux;
    private String description; // Optionnel si l'API en renvoie une

    public Espece() {}

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public String getNom() { return nom; }
    public void setNom(String nom) { this.nom = nom; }

    public List<Animal> getAnimaux() { return animaux; }
    public void setAnimaux(List<Animal> animaux) { this.animaux = animaux; }

    public int getNombreAnimaux() {
        return animaux != null ? animaux.size() : 0;
    }

    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }
}
