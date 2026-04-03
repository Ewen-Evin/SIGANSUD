package com.sigansud.app.utils;

import android.content.Context;
import android.content.SharedPreferences;
import androidx.security.crypto.EncryptedSharedPreferences;
import androidx.security.crypto.MasterKeys;
import com.sigansud.app.models.Soignant;
import java.io.IOException;
import java.security.GeneralSecurityException;

public class SessionManager {
    private static final String PREF_NAME = "SigansudSession";
    private static final String KEY_TOKEN = "token";
    private static final String KEY_IS_LOGGED_IN = "isLoggedIn";
    private static final String KEY_MATRICULE = "matricule";
    private static final String KEY_NOM = "nom";
    private static final String KEY_PRENOM = "prenom";

    private SharedPreferences sharedPreferences;
    private SharedPreferences.Editor editor;

    public SessionManager(Context context) {
        try {
            String masterKeyAlias = MasterKeys.getOrCreate(MasterKeys.AES256_GCM_SPEC);
            sharedPreferences = EncryptedSharedPreferences.create(
                    PREF_NAME,
                    masterKeyAlias,
                    context,
                    EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
                    EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM
            );
            editor = sharedPreferences.edit();
        } catch (GeneralSecurityException | IOException e) {
            e.printStackTrace();
        }
    }

    public void createSession(Soignant soignant) {
        editor.putBoolean(KEY_IS_LOGGED_IN, true);
        editor.putString(KEY_TOKEN, soignant.getToken());
        editor.putString(KEY_MATRICULE, soignant.getMatricule());
        editor.putString(KEY_NOM, soignant.getNom());
        editor.putString(KEY_PRENOM, soignant.getPrenom());
        editor.apply();
    }

    public String fetchAuthToken() {
        return "Bearer " + sharedPreferences.getString(KEY_TOKEN, "");
    }

    public String getNomComplet() {
        String prenom = sharedPreferences.getString(KEY_PRENOM, "");
        String nom = sharedPreferences.getString(KEY_NOM, "");
        return prenom + " " + nom;
    }

    public boolean isLoggedIn() {
        return sharedPreferences.getBoolean(KEY_IS_LOGGED_IN, false);
    }

    public void logout() {
        editor.clear();
        editor.apply();
    }
}
