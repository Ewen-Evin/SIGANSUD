package com.sigansud.app.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class Menu {

    @SerializedName("id")
    private int id;

    @SerializedName("aliment")
    private String aliment;

    @SerializedName("quantite")
    private int quantite;

    public Menu() {}

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }

    public String getAliment() { return aliment; }
    public void setAliment(String aliment) { this.aliment = aliment; }

    public int getQuantite() { return quantite; }
    public void setQuantite(int quantite) { this.quantite = quantite; }

    // Pour l'affichage dans l'adapter ou spinner
    @Override
    public String toString() {
        return (aliment != null ? aliment : "Inconnu") + " (" + quantite + " g)";
    }
    
    // Alias pour la compatibilité avec le code existant
    public String getNomEspece() { return "Espece"; }
    public String getDate() { return "Aujourd'hui"; }
    public List<Aliment> getAliments() { return null; }
    public float getQuantiteTotale() { return quantite; }
}
