package com.sigansud.app.activities;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import com.sigansud.app.api.ApiService;
import com.sigansud.app.api.RetrofitClient;
import com.sigansud.app.databinding.ActivityLoginBinding;
import com.sigansud.app.models.LoginResponse;
import com.sigansud.app.models.Soignant;
import com.sigansud.app.utils.ApiErrorHandler;
import com.sigansud.app.utils.SessionManager;
import java.util.HashMap;
import java.util.Map;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LoginActivity extends AppCompatActivity {
    private static final String TAG = "LoginActivity";
    private ActivityLoginBinding binding;
    private SessionManager sessionManager;
    private ApiService apiService;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        binding = ActivityLoginBinding.inflate(getLayoutInflater());
        setContentView(binding.getRoot());

        sessionManager = new SessionManager(this);
        apiService = RetrofitClient.getApiService();

        if (sessionManager.isLoggedIn()) {
            goToMain();
            return;
        }

        binding.btnLogin.setOnClickListener(v -> attemptLogin());
    }

    private void attemptLogin() {
        String identifiant = binding.etLogin.getText().toString().trim();
        String motDePasse = binding.etPassword.getText().toString().trim();

        if (identifiant.isEmpty() || motDePasse.isEmpty()) {
            Toast.makeText(this, "Identifiant et mot de passe requis", Toast.LENGTH_SHORT).show();
            return;
        }

        showLoading(true);

        // ESSAI 1 : Utilisation des clés standards (souvent utilisées par Symfony/Laravel)
        // Si ça échoue encore, essayez de remplacer "username" par "login"
        Map<String, String> credentials = new HashMap<>();
        credentials.put("matricule", identifiant);
        credentials.put("mot_de_passe", motDePasse);

        Log.d(TAG, "Tentative login avec : " + credentials.toString());

        apiService.login(credentials).enqueue(new Callback<LoginResponse>() {
            @Override
            public void onResponse(Call<LoginResponse> call, Response<LoginResponse> response) {
                showLoading(false);
                if (response.isSuccessful() && response.body() != null) {
                    LoginResponse loginResp = response.body();
                    if (loginResp.isSuccess() || loginResp.getToken() != null) {
                        Soignant user = loginResp.toSoignant();
                        sessionManager.createSession(user);
                        Toast.makeText(LoginActivity.this, "Connexion réussie !", Toast.LENGTH_SHORT).show();
                        goToMain();
                    } else {
                        Toast.makeText(LoginActivity.this, "Échec : " + loginResp.getMessage(), Toast.LENGTH_LONG).show();
                    }
                } else {
                    // Si on a une erreur 401, on affiche le message précis
                    ApiErrorHandler.handle(LoginActivity.this, response, null);
                }
            }

            @Override
            public void onFailure(Call<LoginResponse> call, Throwable t) {
                showLoading(false);
                ApiErrorHandler.handleNetworkError(LoginActivity.this, t);
            }
        });
    }

    private void showLoading(boolean show) {
        binding.progressBar.setVisibility(show ? View.VISIBLE : View.GONE);
        binding.btnLogin.setEnabled(!show);
    }

    private void goToMain() {
        startActivity(new Intent(this, MainActivity.class));
        finish();
    }
}
