package com.sigansud.app.activities;

import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.sigansud.app.api.ApiService;
import com.sigansud.app.api.RetrofitClient;
import com.sigansud.app.databinding.ActivitySaisieRepasBinding;
import com.sigansud.app.models.Animal;
import com.sigansud.app.models.Espece;
import com.sigansud.app.models.Menu;
import com.sigansud.app.utils.ApiErrorHandler;
import com.sigansud.app.utils.SessionManager;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class SaisieRepasActivity extends AppCompatActivity {

    private ActivitySaisieRepasBinding binding;
    private SessionManager sessionManager;
    private ApiService apiService;

    // Données chargées
    private List<Espece> especesList = new ArrayList<>();
    private List<Animal> animauxList = new ArrayList<>();
    private List<Menu> menusList = new ArrayList<>();

    // Adapters
    private ArrayAdapter<String> especesAdapter;
    private ArrayAdapter<String> animauxAdapter;
    private ArrayAdapter<String> menusAdapter;

    // Sélections courantes
    private Espece selectedEspece = null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        binding = ActivitySaisieRepasBinding.inflate(getLayoutInflater());
        setContentView(binding.getRoot());

        setSupportActionBar(binding.toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setTitle("Saisie d'un repas");
        }

        sessionManager = new SessionManager(this);
        apiService = RetrofitClient.getApiService();

        setupSpinners();
        loadEspeces();

        binding.btnSauvegarder.setOnClickListener(v -> sauvegarderRepas());
    }

    private void setupSpinners() {
        // Spinner espèces
        especesAdapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, new ArrayList<>());
        especesAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        binding.spinnerEspece.setAdapter(especesAdapter);

        // Spinner animaux (chargé après sélection d'espèce)
        animauxAdapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, new ArrayList<>());
        animauxAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        binding.spinnerAnimal.setAdapter(animauxAdapter);

        // Spinner menus (chargé après sélection d'espèce)
        menusAdapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, new ArrayList<>());
        menusAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        binding.spinnerMenu.setAdapter(menusAdapter);

        // Quand l'espèce change → recharger animaux + menus
        binding.spinnerEspece.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if (position >= 0 && position < especesList.size()) {
                    selectedEspece = especesList.get(position);
                    loadAnimauxEtMenus(selectedEspece.getId());
                }
            }
            @Override
            public void onNothingSelected(AdapterView<?> parent) {}
        });
    }

    private void loadEspeces() {
        binding.progressBar.setVisibility(View.VISIBLE);

        apiService.getEspeces().enqueue(new Callback<List<Espece>>() {
            @Override
            public void onResponse(Call<List<Espece>> call, Response<List<Espece>> response) {
                binding.progressBar.setVisibility(View.GONE);
                if (response.isSuccessful() && response.body() != null) {
                    especesList = response.body();
                    List<String> noms = new ArrayList<>();
                    for (Espece e : especesList) noms.add(e.getNom());
                    especesAdapter.clear();
                    especesAdapter.addAll(noms);
                    especesAdapter.notifyDataSetChanged();

                    // Déclencher le chargement de la première espèce
                    if (!especesList.isEmpty()) {
                        selectedEspece = especesList.get(0);
                        loadAnimauxEtMenus(selectedEspece.getId());
                    }
                } else {
                    Toast.makeText(SaisieRepasActivity.this, "Erreur chargement espèces", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<List<Espece>> call, Throwable t) {
                binding.progressBar.setVisibility(View.GONE);
                ApiErrorHandler.handleNetworkError(SaisieRepasActivity.this, t);
            }
        });
    }

    private void loadAnimauxEtMenus(int idEspece) {
        // Charger animaux
        apiService.getAnimauxByEspece(idEspece).enqueue(new Callback<List<Animal>>() {
            @Override
            public void onResponse(Call<List<Animal>> call, Response<List<Animal>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    animauxList = response.body();
                    List<String> noms = new ArrayList<>();
                    for (Animal a : animauxList) {
                        String label = a.getNomBapteme();
                        if (a.isDecede()) label += " ✝";
                        noms.add(label);
                    }
                    animauxAdapter.clear();
                    animauxAdapter.addAll(noms);
                    animauxAdapter.notifyDataSetChanged();
                }
            }
            @Override
            public void onFailure(Call<List<Animal>> call, Throwable t) {}
        });

        // Charger menus recommandés pour cette espèce
        apiService.getMenusByEspece(idEspece).enqueue(new Callback<List<Menu>>() {
            @Override
            public void onResponse(Call<List<Menu>> call, Response<List<Menu>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    menusList = response.body();
                    List<String> labels = new ArrayList<>();
                    for (Menu m : menusList) labels.add(m.toString());
                    menusAdapter.clear();
                    menusAdapter.addAll(labels);
                    menusAdapter.notifyDataSetChanged();
                }
            }
            @Override
            public void onFailure(Call<List<Menu>> call, Throwable t) {}
        });
    }

    private void sauvegarderRepas() {
        if (selectedEspece == null) {
            Toast.makeText(this, "Sélectionnez une espèce", Toast.LENGTH_SHORT).show();
            return;
        }

        int posAnimal = binding.spinnerAnimal.getSelectedItemPosition();
        if (posAnimal < 0 || posAnimal >= animauxList.size()) {
            Toast.makeText(this, "Sélectionnez un animal", Toast.LENGTH_SHORT).show();
            return;
        }

        int posMenu = binding.spinnerMenu.getSelectedItemPosition();
        if (posMenu < 0 || posMenu >= menusList.size()) {
            Toast.makeText(this, "Sélectionnez un menu", Toast.LENGTH_SHORT).show();
            return;
        }

        String quantiteStr = binding.etQuantite.getText().toString().trim();
        if (quantiteStr.isEmpty()) {
            binding.etQuantite.setError("Quantité requise");
            return;
        }
        int quantite;
        try {
            quantite = Integer.parseInt(quantiteStr);
        } catch (NumberFormatException e) {
            binding.etQuantite.setError("Nombre entier requis");
            return;
        }

        Animal animal = animauxList.get(posAnimal);
        Menu menu = menusList.get(posMenu);

        // Corps de la requête POST /api/repas
        Map<String, Object> body = new HashMap<>();
        body.put("id_espece",  selectedEspece.getId());
        body.put("nomBapteme", animal.getNomBapteme());
        body.put("id_menu",    menu.getId());
        body.put("quantite",   quantite);

        binding.btnSauvegarder.setEnabled(false);
        binding.progressBar.setVisibility(View.VISIBLE);

        apiService.createRepas(body).enqueue(new Callback<Map<String, Object>>() {
            @Override
            public void onResponse(Call<Map<String, Object>> call, Response<Map<String, Object>> response) {
                binding.btnSauvegarder.setEnabled(true);
                binding.progressBar.setVisibility(View.GONE);
                if (response.isSuccessful()) {
                    Toast.makeText(SaisieRepasActivity.this,
                            "✓ Repas enregistré pour " + animal.getNomBapteme(), Toast.LENGTH_SHORT).show();
                    finish();
                } else {
                    ApiErrorHandler.handle(SaisieRepasActivity.this, response, sessionManager);
                }
            }

            @Override
            public void onFailure(Call<Map<String, Object>> call, Throwable t) {
                binding.btnSauvegarder.setEnabled(true);
                binding.progressBar.setVisibility(View.GONE);
                ApiErrorHandler.handleNetworkError(SaisieRepasActivity.this, t);
            }
        });
    }

    @Override
    public boolean onSupportNavigateUp() {
        onBackPressed();
        return true;
    }
}