package com.sigansud.app.activities;

import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;

import com.sigansud.app.adapters.RepasAdapter;
import com.sigansud.app.api.ApiService;
import com.sigansud.app.api.RetrofitClient;
import com.sigansud.app.databinding.ActivityHistoriqueBinding;
import com.sigansud.app.models.Animal;
import com.sigansud.app.models.Espece;
import com.sigansud.app.models.Repas;
import com.sigansud.app.utils.ApiErrorHandler;
import com.sigansud.app.utils.SessionManager;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class HistoriqueActivity extends AppCompatActivity {

    private ActivityHistoriqueBinding binding;
    private SessionManager sessionManager;
    private ApiService apiService;
    private RepasAdapter adapter;

    private List<Espece> especesList = new ArrayList<>();
    private List<Animal> animauxList = new ArrayList<>();

    private ArrayAdapter<String> especesAdapter;
    private ArrayAdapter<String> animauxAdapter;

    private Espece selectedEspece = null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        binding = ActivityHistoriqueBinding.inflate(getLayoutInflater());
        setContentView(binding.getRoot());

        setSupportActionBar(binding.toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setTitle("Historique des repas");
        }

        sessionManager = new SessionManager(this);
        apiService = RetrofitClient.getApiService();

        adapter = new RepasAdapter(this);
        binding.recyclerRepas.setLayoutManager(new LinearLayoutManager(this));
        binding.recyclerRepas.setAdapter(adapter);

        setupSpinners();
        loadEspeces();

        binding.swipeRefresh.setOnRefreshListener(() -> {
            int posAnimal = binding.spinnerAnimal.getSelectedItemPosition();
            if (posAnimal >= 0 && posAnimal < animauxList.size() && selectedEspece != null) {
                loadRepas(selectedEspece.getId(), animauxList.get(posAnimal).getNomBapteme());
            }
        });
    }

    private void setupSpinners() {
        especesAdapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, new ArrayList<>());
        especesAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        binding.spinnerEspece.setAdapter(especesAdapter);

        animauxAdapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, new ArrayList<>());
        animauxAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        binding.spinnerAnimal.setAdapter(animauxAdapter);

        binding.spinnerEspece.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if (position >= 0 && position < especesList.size()) {
                    selectedEspece = especesList.get(position);
                    loadAnimaux(selectedEspece.getId());
                }
            }
            @Override
            public void onNothingSelected(AdapterView<?> parent) {}
        });

        binding.spinnerAnimal.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if (position >= 0 && position < animauxList.size() && selectedEspece != null) {
                    loadRepas(selectedEspece.getId(), animauxList.get(position).getNomBapteme());
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

                    if (!especesList.isEmpty()) {
                        selectedEspece = especesList.get(0);
                        loadAnimaux(selectedEspece.getId());
                    }
                } else {
                    ApiErrorHandler.handle(HistoriqueActivity.this, response, sessionManager);
                }
            }
            @Override
            public void onFailure(Call<List<Espece>> call, Throwable t) {
                binding.progressBar.setVisibility(View.GONE);
                ApiErrorHandler.handleNetworkError(HistoriqueActivity.this, t);
            }
        });
    }

    private void loadAnimaux(int idEspece) {
        animauxList.clear();
        animauxAdapter.clear();
        animauxAdapter.notifyDataSetChanged();
        adapter.setRepasList(new ArrayList<>());

        apiService.getAnimauxByEspece(idEspece).enqueue(new Callback<List<Animal>>() {
            @Override
            public void onResponse(Call<List<Animal>> call, Response<List<Animal>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    animauxList = response.body();
                    List<String> noms = new ArrayList<>();
                    for (Animal a : animauxList) noms.add(a.getNomBapteme());
                    animauxAdapter.clear();
                    animauxAdapter.addAll(noms);
                    animauxAdapter.notifyDataSetChanged();
                    // Le listener spinnerAnimal va déclencher loadRepas automatiquement
                }
            }
            @Override
            public void onFailure(Call<List<Animal>> call, Throwable t) {}
        });
    }

    private void loadRepas(int idEspece, String nomBapteme) {
        binding.progressBar.setVisibility(View.VISIBLE);
        binding.tvEmpty.setVisibility(View.GONE);
        binding.tvDateSelectionnee.setText(nomBapteme);

        apiService.getRepasByAnimal(idEspece, nomBapteme).enqueue(new Callback<List<Repas>>() {
            @Override
            public void onResponse(Call<List<Repas>> call, Response<List<Repas>> response) {
                binding.progressBar.setVisibility(View.GONE);
                binding.swipeRefresh.setRefreshing(false);

                if (response.isSuccessful() && response.body() != null) {
                    List<Repas> repasList = response.body();
                    if (repasList.isEmpty()) {
                        binding.tvEmpty.setVisibility(View.VISIBLE);
                        binding.tvEmpty.setText("Aucun repas enregistré pour " + nomBapteme);
                    } else {
                        adapter.setRepasList(repasList);
                    }
                } else {
                    ApiErrorHandler.handle(HistoriqueActivity.this, response, sessionManager);
                }
            }
            @Override
            public void onFailure(Call<List<Repas>> call, Throwable t) {
                binding.progressBar.setVisibility(View.GONE);
                binding.swipeRefresh.setRefreshing(false);
                ApiErrorHandler.handleNetworkError(HistoriqueActivity.this, t);
            }
        });
    }

    @Override
    public boolean onSupportNavigateUp() {
        onBackPressed();
        return true;
    }
}