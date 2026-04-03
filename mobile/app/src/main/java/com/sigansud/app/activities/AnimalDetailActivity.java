package com.sigansud.app.activities;

import android.content.Intent;
import android.os.Bundle;

import androidx.appcompat.app.AppCompatActivity;

import com.sigansud.app.databinding.ActivityAnimalDetailBinding;
import com.sigansud.app.utils.DateUtils;

public class AnimalDetailActivity extends AppCompatActivity {

    public static final String EXTRA_NOM_BAPTEME = "nomBapteme";
    public static final String EXTRA_GENRE       = "genre";
    public static final String EXTRA_NAISSANCE   = "dateNaissance";
    public static final String EXTRA_DECES       = "dateDeces";
    public static final String EXTRA_ESPECE_ID   = "especeId";
    public static final String EXTRA_ESPECE_NOM  = "especeNom";

    private ActivityAnimalDetailBinding binding;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        binding = ActivityAnimalDetailBinding.inflate(getLayoutInflater());
        setContentView(binding.getRoot());

        String nomBapteme = getIntent().getStringExtra(EXTRA_NOM_BAPTEME);
        String genre      = getIntent().getStringExtra(EXTRA_GENRE);
        String naissance  = getIntent().getStringExtra(EXTRA_NAISSANCE);
        String deces      = getIntent().getStringExtra(EXTRA_DECES);
        int    especeId   = getIntent().getIntExtra(EXTRA_ESPECE_ID, -1);
        String especeNom  = getIntent().getStringExtra(EXTRA_ESPECE_NOM);

        setSupportActionBar(binding.toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setTitle(nomBapteme != null ? nomBapteme : "Animal");
        }

        // Afficher les infos
        binding.contentLayout.setVisibility(android.view.View.VISIBLE);
        binding.progressBar.setVisibility(android.view.View.GONE);

        binding.tvNom.setText(nomBapteme != null ? nomBapteme : "—");
        binding.tvEspece.setText("Espèce : " + (especeNom != null ? especeNom : "—"));
        binding.tvSexe.setText("Genre : " + (genre != null ? genre : "—"));
        binding.tvNaissance.setText("Naissance : " +
                (naissance != null ? DateUtils.apiToDisplay(naissance) : "Inconnue"));

        if (deces != null && !deces.isEmpty()) {
            binding.tvPoids.setVisibility(android.view.View.VISIBLE);
            binding.tvPoids.setText("✝ Décédé le : " + DateUtils.apiToDisplay(deces));
        } else {
            binding.tvPoids.setVisibility(android.view.View.GONE);
        }

        // Bouton saisir un repas → pré-sélectionner cet animal
        binding.btnSaisirRepas.setOnClickListener(v -> {
            Intent intent = new Intent(this, SaisieRepasActivity.class);
            intent.putExtra("preselect_espece_id", especeId);
            intent.putExtra("preselect_nom_bapteme", nomBapteme);
            startActivity(intent);
        });
    }

    @Override
    public boolean onSupportNavigateUp() {
        onBackPressed();
        return true;
    }
}