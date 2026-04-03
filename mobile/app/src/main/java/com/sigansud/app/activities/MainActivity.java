package com.sigansud.app.activities;

import android.content.Intent;
import android.os.Bundle;
import android.view.MenuItem;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.google.android.material.navigation.NavigationBarView;
import com.sigansud.app.R;
import com.sigansud.app.databinding.ActivityMainBinding;
import com.sigansud.app.utils.SessionManager;

public class MainActivity extends AppCompatActivity {

    private ActivityMainBinding binding;
    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        binding = ActivityMainBinding.inflate(getLayoutInflater());
        setContentView(binding.getRoot());

        sessionManager = new SessionManager(this);

        // Afficher le nom du soignant
        binding.tvBienvenue.setText("Bonjour, " + sessionManager.getNomComplet());

        // Boutons dashboard
        binding.cardAnimaux.setOnClickListener(v ->
                startActivity(new Intent(this, AnimauxActivity.class)));

        binding.cardMenus.setOnClickListener(v ->
                startActivity(new Intent(this, MenusActivity.class)));

        binding.cardSaisie.setOnClickListener(v ->
                startActivity(new Intent(this, SaisieRepasActivity.class)));

        binding.cardHistorique.setOnClickListener(v ->
                startActivity(new Intent(this, HistoriqueActivity.class)));

        // Déconnexion
        binding.btnLogout.setOnClickListener(v -> logout());
    }

    private void logout() {
        sessionManager.logout();
        Intent intent = new Intent(this, LoginActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
        startActivity(intent);
        finish();
    }
}