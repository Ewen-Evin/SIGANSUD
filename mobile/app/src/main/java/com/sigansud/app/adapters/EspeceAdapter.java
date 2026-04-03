package com.sigansud.app.adapters;

import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.sigansud.app.R;
import com.sigansud.app.activities.AnimalDetailActivity;
import com.sigansud.app.models.Animal;
import com.sigansud.app.models.Espece;

import java.util.ArrayList;
import java.util.List;

public class EspeceAdapter extends RecyclerView.Adapter<EspeceAdapter.EspeceViewHolder> {

    private List<Espece> especes = new ArrayList<>();
    private final Context context;

    public EspeceAdapter(Context context) {
        this.context = context;
    }

    public void setEspeces(List<Espece> especes) {
        this.especes = especes;
        notifyDataSetChanged();
    }

    @NonNull
    @Override
    public EspeceViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_espece, parent, false);
        return new EspeceViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull EspeceViewHolder holder, int position) {
        Espece espece = especes.get(position);
        holder.tvNomEspece.setText(espece.getNom());

        int nb = espece.getNombreAnimaux();
        holder.tvNbAnimaux.setText(nb + " animal" + (nb > 1 ? "x" : ""));

        // Pas de description dans l'API
        holder.tvDescription.setVisibility(View.GONE);

        // Sous-liste des animaux
        if (espece.getAnimaux() != null && !espece.getAnimaux().isEmpty()) {
            holder.recyclerAnimaux.setVisibility(View.VISIBLE);
            AnimalMiniAdapter animalAdapter = new AnimalMiniAdapter(context, espece.getAnimaux(), espece);
            holder.recyclerAnimaux.setLayoutManager(
                    new LinearLayoutManager(context, LinearLayoutManager.VERTICAL, false));
            holder.recyclerAnimaux.setNestedScrollingEnabled(false);
            holder.recyclerAnimaux.setAdapter(animalAdapter);
        } else {
            holder.recyclerAnimaux.setVisibility(View.GONE);
        }

        // Expand / collapse au clic sur le header
        holder.itemView.setOnClickListener(v -> {
            boolean visible = holder.recyclerAnimaux.getVisibility() == View.VISIBLE;
            holder.recyclerAnimaux.setVisibility(visible ? View.GONE : View.VISIBLE);
            holder.tvChevron.setText(visible ? "▶" : "▼");
        });
    }

    @Override
    public int getItemCount() { return especes.size(); }

    static class EspeceViewHolder extends RecyclerView.ViewHolder {
        TextView tvNomEspece, tvNbAnimaux, tvDescription, tvChevron;
        RecyclerView recyclerAnimaux;

        EspeceViewHolder(@NonNull View itemView) {
            super(itemView);
            tvNomEspece    = itemView.findViewById(R.id.tvNomEspece);
            tvNbAnimaux    = itemView.findViewById(R.id.tvNbAnimaux);
            tvDescription  = itemView.findViewById(R.id.tvDescription);
            tvChevron      = itemView.findViewById(R.id.tvChevron);
            recyclerAnimaux = itemView.findViewById(R.id.recyclerAnimaux);
        }
    }

    // ─── Mini-adapter pour les animaux dans chaque espèce ───────────────────

    static class AnimalMiniAdapter extends RecyclerView.Adapter<AnimalMiniAdapter.AnimalViewHolder> {
        private final Context context;
        private final List<Animal> animaux;
        private final Espece espece;

        AnimalMiniAdapter(Context context, List<Animal> animaux, Espece espece) {
            this.context = context;
            this.animaux = animaux;
            this.espece  = espece;
        }

        @NonNull
        @Override
        public AnimalViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
            View view = LayoutInflater.from(context).inflate(R.layout.item_animal_mini, parent, false);
            return new AnimalViewHolder(view);
        }

        @Override
        public void onBindViewHolder(@NonNull AnimalViewHolder holder, int position) {
            Animal animal = animaux.get(position);

            String nom = animal.getNomBapteme();
            if (animal.isDecede()) nom += " ✝";
            holder.tvNom.setText(nom);
            holder.tvInfos.setText(animal.getGenre() != null ? animal.getGenre() : "Genre inconnu");

            holder.itemView.setOnClickListener(v -> {
                Intent intent = new Intent(context, AnimalDetailActivity.class);
                intent.putExtra(AnimalDetailActivity.EXTRA_NOM_BAPTEME, animal.getNomBapteme());
                intent.putExtra(AnimalDetailActivity.EXTRA_GENRE,       animal.getGenre());
                intent.putExtra(AnimalDetailActivity.EXTRA_NAISSANCE,   animal.getDateNaissance());
                intent.putExtra(AnimalDetailActivity.EXTRA_DECES,       animal.getDateDeces());
                intent.putExtra(AnimalDetailActivity.EXTRA_ESPECE_ID,   espece.getId());
                intent.putExtra(AnimalDetailActivity.EXTRA_ESPECE_NOM,  espece.getNom());
                context.startActivity(intent);
            });
        }

        @Override
        public int getItemCount() { return animaux.size(); }

        static class AnimalViewHolder extends RecyclerView.ViewHolder {
            TextView tvNom, tvInfos;

            AnimalViewHolder(@NonNull View itemView) {
                super(itemView);
                tvNom   = itemView.findViewById(R.id.tvNomAnimal);
                tvInfos = itemView.findViewById(R.id.tvInfosAnimal);
            }
        }
    }
}