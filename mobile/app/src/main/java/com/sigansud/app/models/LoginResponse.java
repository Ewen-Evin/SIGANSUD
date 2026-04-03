package com.sigansud.app.models;

import com.google.gson.annotations.SerializedName;

public class LoginResponse {

    @SerializedName("success")
    private boolean success;

    @SerializedName("message")
    private String message;

    @SerializedName("token")
    private String token;

    // Champs directs de la table soignant renvoyés lors du login
    @SerializedName("matricule")
    private String matricule;

    @SerializedName("nom")
    private String nom;

    @SerializedName("prenom")
    private String prenom;

    public LoginResponse() {}

    public boolean isSuccess() { return success; }
    public String getMessage() { return message; }
    public String getToken() { return token; }

    /**
     * Convertit la réponse en objet Soignant pour le SessionManager
     */
    public Soignant toSoignant() {
        Soignant s = new Soignant();
        s.setMatricule(matricule);
        s.setNom(nom);
        s.setPrenom(prenom);
        s.setToken(token);
        return s;
    }
}
