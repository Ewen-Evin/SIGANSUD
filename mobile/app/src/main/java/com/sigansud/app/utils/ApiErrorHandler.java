package com.sigansud.app.utils;

import android.content.Context;
import android.content.Intent;
import android.util.Log;
import android.widget.Toast;

import com.sigansud.app.activities.LoginActivity;

import org.json.JSONObject;

import java.io.IOException;

import retrofit2.Response;

public class ApiErrorHandler {

    private static final String TAG = "ApiErrorHandler";

    public static void handle(Context context, Response<?> response, SessionManager sessionManager) {
        if (response.code() == 401) {
            if (sessionManager != null) sessionManager.logout();
            Toast.makeText(context, "Session expirée ou identifiants incorrects.", Toast.LENGTH_LONG).show();
            return;
        }

        String errorMessage = "Erreur serveur (" + response.code() + ")";
        
        try {
            if (response.errorBody() != null) {
                String errorBody = response.errorBody().string();
                // Si c'est du JSON, on extrait le message
                if (errorBody.trim().startsWith("{")) {
                    JSONObject jsonObject = new JSONObject(errorBody);
                    errorMessage = jsonObject.has("error")
                            ? jsonObject.optString("error", errorMessage)
                            : jsonObject.optString("message", errorMessage);
                } else {
                    // Si c'est du HTML (erreur PHP), on prend les 100 premiers caractères
                    errorMessage = "Erreur PHP : " + (errorBody.length() > 100 ? errorBody.substring(0, 100) + "..." : errorBody);
                }
            }
        } catch (Exception e) {
            Log.e(TAG, "Error parsing error body", e);
        }

        Toast.makeText(context, errorMessage, Toast.LENGTH_LONG).show();
    }

    public static void handleNetworkError(Context context, Throwable t) {
        Log.e(TAG, "Network error", t);
        String msg = t.getMessage();
        if (msg != null && msg.contains("MalformedJsonException")) {
            Toast.makeText(context, "Le serveur a renvoyé une réponse invalide (HTML au lieu de JSON). Vérifiez vos logs PHP.", Toast.LENGTH_LONG).show();
        } else {
            Toast.makeText(context, "Erreur réseau : " + t.getLocalizedMessage(), Toast.LENGTH_LONG).show();
        }
    }
}
