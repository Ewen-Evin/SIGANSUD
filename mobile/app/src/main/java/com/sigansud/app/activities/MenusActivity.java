package com.sigansud.app.activities;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;

import com.sigansud.app.adapters.MenuAdapter;
import com.sigansud.app.api.ApiService;
import com.sigansud.app.api.RetrofitClient;
import com.sigansud.app.databinding.ActivityMenusBinding;
import com.sigansud.app.models.Menu;
import com.sigansud.app.utils.ApiErrorHandler;
import com.sigansud.app.utils.SessionManager;

import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MenusActivity extends AppCompatActivity implements MenuAdapter.OnMenuActionListener {

    private ActivityMenusBinding binding;
    private SessionManager sessionManager;
    private ApiService apiService;
    private MenuAdapter adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        binding = ActivityMenusBinding.inflate(getLayoutInflater());
        setContentView(binding.getRoot());

        setSupportActionBar(binding.toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setTitle("Menus disponibles");
        }

        sessionManager = new SessionManager(this);
        apiService = RetrofitClient.getApiService();

        binding.tvDateJour.setText("Tous les menus");

        adapter = new MenuAdapter(this, this);
        binding.recyclerMenus.setLayoutManager(new LinearLayoutManager(this));
        binding.recyclerMenus.setAdapter(adapter);

        binding.swipeRefresh.setOnRefreshListener(this::loadMenus);
        loadMenus();
    }

    private void loadMenus() {
        binding.progressBar.setVisibility(View.VISIBLE);
        binding.tvEmpty.setVisibility(View.GONE);

        apiService.getMenus().enqueue(new Callback<List<Menu>>() {
            @Override
            public void onResponse(Call<List<Menu>> call, Response<List<Menu>> response) {
                binding.progressBar.setVisibility(View.GONE);
                binding.swipeRefresh.setRefreshing(false);

                if (response.isSuccessful() && response.body() != null) {
                    List<Menu> menus = response.body();
                    if (menus.isEmpty()) {
                        binding.tvEmpty.setVisibility(View.VISIBLE);
                        binding.tvEmpty.setText("Aucun menu disponible");
                    } else {
                        adapter.setMenus(menus);
                    }
                } else {
                    ApiErrorHandler.handle(MenusActivity.this, response, sessionManager);
                }
            }

            @Override
            public void onFailure(Call<List<Menu>> call, Throwable t) {
                binding.progressBar.setVisibility(View.GONE);
                binding.swipeRefresh.setRefreshing(false);
                ApiErrorHandler.handleNetworkError(MenusActivity.this, t);
            }
        });
    }

    @Override
    public void onValiderMenu(Menu menu) {
        // Rediriger vers la saisie avec ce menu déjà sélectionné ?
        Intent intent = new Intent(this, SaisieRepasActivity.class);
        // On pourrait passer l'ID du menu en extra
        // intent.putExtra("MENU_ID", menu.getId());
        startActivity(intent);
    }

    @Override
    public boolean onSupportNavigateUp() {
        onBackPressed();
        return true;
    }
}
