package com.sigansud.app.api;

import com.sigansud.app.models.Animal;
import com.sigansud.app.models.Espece;
import com.sigansud.app.models.LoginResponse;
import com.sigansud.app.models.Menu;
import com.sigansud.app.models.Repas;
import com.sigansud.app.models.Soignant;

import java.util.List;
import java.util.Map;

import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.Path;

public interface ApiService {

    // ─── AUTHENTIFICATION ────────────────────────────────────────────────────
    @POST("login")
    Call<LoginResponse> login(@Body Map<String, String> credentials);

    // ─── ESPÈCES ──────────────────────────────────────────────────────────────
    @GET("especes")
    Call<List<Espece>> getEspeces();

    // ─── ANIMAUX par espèce ───────────────────────────────────────────────────
    @GET("especes/{idEspece}/animaux")
    Call<List<Animal>> getAnimauxByEspece(@Path("idEspece") int idEspece);

    // ─── MENUS ────────────────────────────────────────────────────────────────
    @GET("menus")
    Call<List<Menu>> getMenus();

    @GET("especes/{idEspece}/menus")
    Call<List<Menu>> getMenusByEspece(@Path("idEspece") int idEspece);

    // ─── REPAS ────────────────────────────────────────────────────────────────
    @POST("repas")
    Call<Map<String, Object>> createRepas(@Body Map<String, Object> body);

    @GET("especes/{idEspece}/animaux/{nomBapteme}/repas")
    Call<List<Repas>> getRepasByAnimal(
            @Path("idEspece") int idEspece,
            @Path("nomBapteme") String nomBapteme
    );

    // ─── SOIGNANTS ────────────────────────────────────────────────────────────
    @GET("soignants/{matricule}")
    Call<Soignant> getSoignant(@Path("matricule") String matricule);

    @GET("soignants/{matricule}/especes")
    Call<List<Espece>> getEspecesSoignant(@Path("matricule") String matricule);
}
