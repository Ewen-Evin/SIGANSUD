package com.sigansud.app.activities;

import android.os.Bundle;
import android.view.View;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;

import com.sigansud.app.adapters.EspeceAdapter;
import com.sigansud.app.api.ApiService;
import com.sigansud.app.api.RetrofitClient;
import com.sigansud.app.databinding.ActivityAnimauxBinding;
import com.sigansud.app.models.Animal;
import com.sigansud.app.models.Espece;
import com.sigansud.app.utils.ApiErrorHandler;
import com.sigansud.app.utils.SessionManager;

import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.atomic.AtomicInteger;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class AnimauxActivity extends AppCompatActivity {

    private ActivityAnimauxBinding binding;
    private SessionManager sessionManager;
    private ApiService apiService;
    private EspeceAdapter adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        binding = ActivityAnimauxBinding.inflate(getLayoutInflater());
        setContentView(binding.getRoot());

        setSupportActionBar(binding.toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setTitle("Animaux par espèce");
        }

        sessionManager = new SessionManager(this);
        apiService = RetrofitClient.getApiService();

        adapter = new EspeceAdapter(this);
        binding.recyclerEspeces.setLayoutManager(new LinearLayoutManager(this));
        binding.recyclerEspeces.setAdapter(adapter);

        binding.swipeRefresh.setOnRefreshListener(this::loadEspeces);
        loadEspeces();
    }

    private void loadEspeces() {
        binding.progressBar.setVisibility(View.VISIBLE);
        binding.tvEmpty.setVisibility(View.GONE);

        // Étape 1 : charger la liste des espèces
        apiService.getEspeces().enqueue(new Callback<List<Espece>>() {
            @Override
            public void onResponse(Call<List<Espece>> call, Response<List<Espece>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<Espece> especes = response.body();
                    if (especes.isEmpty()) {
                        binding.progressBar.setVisibility(View.GONE);
                        binding.swipeRefresh.setRefreshing(false);
                        binding.tvEmpty.setVisibility(View.VISIBLE);
                        binding.tvEmpty.setText("Aucune espèce trouvée");
                    } else {
                        // Étape 2 : charger les animaux de chaque espèce en parallèle
                        loadAnimauxForEspeces(especes);
                    }
                } else {
                    binding.progressBar.setVisibility(View.GONE);
                    binding.swipeRefresh.setRefreshing(false);
                    ApiErrorHandler.handle(AnimauxActivity.this, response, sessionManager);
                }
            }

            @Override
            public void onFailure(Call<List<Espece>> call, Throwable t) {
                binding.progressBar.setVisibility(View.GONE);
                binding.swipeRefresh.setRefreshing(false);
                ApiErrorHandler.handleNetworkError(AnimauxActivity.this, t);
            }
        });
    }

    /**
     * Pour chaque espèce, charge ses animaux via GET /api/especes/{id}/animaux.
     * Quand toutes les requêtes sont terminées, affiche la liste.
     */
    private void loadAnimauxForEspeces(List<Espece> especes) {
        AtomicInteger remaining = new AtomicInteger(especes.size());

        for (Espece espece : especes) {
            apiService.getAnimauxByEspece(espece.getId()).enqueue(new Callback<List<Animal>>() {
                @Override
                public void onResponse(Call<List<Animal>> call, Response<List<Animal>> response) {
                    if (response.isSuccessful() && response.body() != null) {
                        List<Animal> animaux = response.body();
                        // Rattacher l'espèce à chaque animal
                        for (Animal a : animaux) a.setEspece(espece);
                        espece.setAnimaux(animaux);
                    } else {
                        espece.setAnimaux(new ArrayList<>());
                    }
                    // Vérifier si toutes les requêtes sont terminées
                    if (remaining.decrementAndGet() == 0) {
                        runOnUiThread(() -> {
                            binding.progressBar.setVisibility(View.GONE);
                            binding.swipeRefresh.setRefreshing(false);
                            adapter.setEspeces(especes);
                        });
                    }
                }

                @Override
                public void onFailure(Call<List<Animal>> call, Throwable t) {
                    espece.setAnimaux(new ArrayList<>());
                    if (remaining.decrementAndGet() == 0) {
                        runOnUiThread(() -> {
                            binding.progressBar.setVisibility(View.GONE);
                            binding.swipeRefresh.setRefreshing(false);
                            adapter.setEspeces(especes);
                        });
                    }
                }
            });
        }
    }

    @Override
    public boolean onSupportNavigateUp() {
        onBackPressed();
        return true;
    }
}