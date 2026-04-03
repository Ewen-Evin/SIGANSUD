package com.sigansud.app.api;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import okhttp3.OkHttpClient;
import okhttp3.logging.HttpLoggingInterceptor;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;
import java.util.concurrent.TimeUnit;

public class RetrofitClient {

    // 1. SI EMULATEUR : utilisez "http://10.0.2.2:8000/api/"
    // 2. SI VRAI TELEPHONE : utilisez l'IP de votre PC (ex: "http://192.168.1.15:8000/api/")
    private static final String BASE_URL = "http://10.0.2.2:8000/api/";

    private static Retrofit instance = null;

    public static Retrofit getInstance() {
        if (instance == null) {
            HttpLoggingInterceptor logging = new HttpLoggingInterceptor();
            logging.setLevel(HttpLoggingInterceptor.Level.BODY);

            OkHttpClient client = new OkHttpClient.Builder()
                    .addInterceptor(logging)
                    .connectTimeout(30, TimeUnit.SECONDS)
                    .readTimeout(30, TimeUnit.SECONDS)
                    .writeTimeout(30, TimeUnit.SECONDS)
                    .build();

            Gson gson = new GsonBuilder().setLenient().create();

            instance = new Retrofit.Builder()
                    .baseUrl(BASE_URL)
                    .client(client)
                    .addConverterFactory(GsonConverterFactory.create(gson))
                    .build();
        }
        return instance;
    }

    public static ApiService getApiService() {
        return getInstance().create(ApiService.class);
    }
}
